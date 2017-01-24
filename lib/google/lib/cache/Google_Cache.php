<?php
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Google_FileCache extends Google_Cache {
  private $path;

  public function __construct() {
    global $apiConfig;
    $this->path = $apiConfig['ioFileCache_directory'];
  }

  private function isLocked($storageFile) {
    // our lock file convention is simple: /the/file/path.lock
    return file_exists($storageFile . '.lock');
  }

  private function createLock($storageFile) {
    $storageDir = dirname($storageFile);
    if (! is_dir($storageDir)) {
      // @codeCoverageIgnoreStart
      if (! @mkdir($storageDir, 0755, true)) {
        // make sure the failure isn't because of a concurrency issue
        if (! is_dir($storageDir)) {
          throw new Google_CacheException("Could not create storage directory: $storageDir");
        }
      }
      // @codeCoverageIgnoreEnd
    }
    @touch($storageFile . '.lock');
  }

  private function removeLock($storageFile) {
    // suppress all warnings, if some other process removed it that's ok too
    @unlink($storageFile . '.lock');
  }

  private function waitForLock($storageFile) {
    // 20 x 250 = 5 seconds
    $tries = 20;
    $cnt = 0;
    do {
      // make sure PHP picks up on file changes. This is an expensive action but really can't be avoided
      clearstatcache();
      // 250 ms is a long time to sleep, but it does stop the server from burning all resources on polling locks..
      usleep(250);
      $cnt ++;
    } while ($cnt <= $tries && $this->isLocked($storageFile));
    if ($this->isLocked($storageFile)) {
      // 5 seconds passed, assume the owning process died off and remove it
      $this->removeLock($storageFile);
    }
  }

  private function getCacheDir($hash) {
    // use the first 2 characters of the hash as a directory prefix
    // this should prevent slowdowns due to huge directory listings
    // and thus give some basic amount of scalability
    return $this->path . '/' . substr($hash, 0, 2);
  }

  private function getCacheFile($hash) {
    return $this->getCacheDir($hash) . '/' . $hash;
  }

  public function get($key, $expiration = false) {
    $storageFile = $this->getCacheFile(md5($key));
    // See if this storage file is locked, if so we wait upto 5 seconds for the lock owning process to
    // complete it's work. If the lock is not released within that time frame, it's cleaned up.
    // This should give us a fair amount of 'Cache Stampeding' protection
    if ($this->isLocked($storageFile)) {
      $this->waitForLock($storageFile);
    }
    if (file_exists($storageFile) && is_readable($storageFile)) {
      $now = time();
      if (! $expiration || (($mtime = @filemtime($storageFile)) !== false && ($now - $mtime) < $expiration)) {
        if (($data = @file_get_contents($storageFile)) !== false) {
          $data = unserialize($data);
          return $data;
        }
      }
    }
    return false;
  }

  public function set($key, $value) {
    $storageDir = $this->getCacheDir(md5($key));
    $storageFile = $this->getCacheFile(md5($key));
    if ($this->isLocked($storageFile)) {
      // some other process is writing to this file too, wait until it's done to prevent hickups
      $this->waitForLock($storageFile);
    }
    if (! is_dir($storageDir)) {
      if (! @mkdir($storageDir, 0755, true)) {
        throw new Google_CacheException("Could not create storage directory: $storageDir");
      }
    }
    // we serialize the whole request object, since we don't only want the
    // responseContent but also the postBody used, headers, size, etc
    $data = serialize($value);
    $this->createLock($storageFile);
    if (! @file_put_contents($storageFile, $data)) {
      $this->removeLock($storageFile);
      throw new Google_CacheException("Could not store data in the file");
    }
    $this->removeLock($storageFile);
  }

  public function delete($key) {
    $file = $this->getCacheFile(md5($key));
    if (! @unlink($file)) {
      throw new Google_CacheException("Cache file could not be deleted");
    }
  }
}


class Google_MemcacheCache extends Google_Cache {
  private $connection = false;

  public function __construct() {
    global $apiConfig;
    if (! function_exists('memcache_connect')) {
      throw new Google_CacheException("Memcache functions not available");
    }
    $this->host = $apiConfig['ioMemCacheCache_host'];
    $this->port = $apiConfig['ioMemCacheCache_port'];
    if (empty($this->host) || empty($this->port)) {
      throw new Google_CacheException("You need to supply a valid memcache host and port");
    }
  }

  private function isLocked($key) {
    $this->check();
    if ((@memcache_get($this->connection, $key . '.lock')) === false) {
      return false;
    }
    return true;
  }

  private function createLock($key) {
    $this->check();
    // the interesting thing is that this could fail if the lock was created in the meantime..
    // but we'll ignore that out of convenience
    @memcache_add($this->connection, $key . '.lock', '', 0, 5);
  }

  private function removeLock($key) {
    $this->check();
    // suppress all warnings, if some other process removed it that's ok too
    @memcache_delete($this->connection, $key . '.lock');
  }

  private function waitForLock($key) {
    $this->check();
    // 20 x 250 = 5 seconds
    $tries = 20;
    $cnt = 0;
    do {
      // 250 ms is a long time to sleep, but it does stop the server from burning all resources on polling locks..
      usleep(250);
      $cnt ++;
    } while ($cnt <= $tries && $this->isLocked($key));
    if ($this->isLocked($key)) {
      // 5 seconds passed, assume the owning process died off and remove it
      $this->removeLock($key);
    }
  }

  // I prefer lazy initialization since the cache isn't used every request
  // so this potentially saves a lot of overhead
  private function connect() {
    if (! $this->connection = @memcache_pconnect($this->host, $this->port)) {
      throw new Google_CacheException("Couldn't connect to memcache server");
    }
  }

  private function check() {
    if (! $this->connection) {
      $this->connect();
    }
  }

  /**
   * @inheritDoc
   */
  public function get($key, $expiration = false) {
    $this->check();
    if (($ret = @memcache_get($this->connection, $key)) === false) {
      return false;
    }
    if (! $expiration || (time() - $ret['time'] > $expiration)) {
      $this->delete($key);
      return false;
    }
    return $ret['data'];
  }

  /**
   * @inheritDoc
   * @param string $key
   * @param string $value
   * @throws Google_CacheException
   */
  public function set($key, $value) {
    $this->check();
    // we store it with the cache_time default expiration so objects will at least get cleaned eventually.
    if (@memcache_set($this->connection, $key, array('time' => time(),
        'data' => $value), false) == false) {
      throw new Google_CacheException("Couldn't store data in cache");
    }
  }

  /**
   * @inheritDoc
   * @param String $key
   */
  public function delete($key) {
    $this->check();
    @memcache_delete($this->connection, $key);
  }
}



class googleApcCache extends Google_Cache {

  public function __construct() {
    if (! function_exists('apc_add')) {
      throw new Google_CacheException("Apc functions not available");
    }
  }

  private function isLocked($key) {
    if ((@apc_fetch($key . '.lock')) === false) {
      return false;
    }
    return true;
  }

  private function createLock($key) {
    // the interesting thing is that this could fail if the lock was created in the meantime..
    // but we'll ignore that out of convenience
    @apc_add($key . '.lock', '', 5);
  }

  private function removeLock($key) {
    // suppress all warnings, if some other process removed it that's ok too
    @apc_delete($key . '.lock');
  }

  private function waitForLock($key) {
    // 20 x 250 = 5 seconds
    $tries = 20;
    $cnt = 0;
    do {
      // 250 ms is a long time to sleep, but it does stop the server from burning all resources on polling locks..
      usleep(250);
      $cnt ++;
    } while ($cnt <= $tries && $this->isLocked($key));
    if ($this->isLocked($key)) {
      // 5 seconds passed, assume the owning process died off and remove it
      $this->removeLock($key);
    }
  }

   /**
   * @inheritDoc
   */
  public function get($key, $expiration = false) {

    if (($ret = @apc_fetch($key)) === false) {
      return false;
    }
    if (!$expiration || (time() - $ret['time'] > $expiration)) {
      $this->delete($key);
      return false;
    }
    return unserialize($ret['data']);
  }

  /**
   * @inheritDoc
   */
  public function set($key, $value) {
    if (@apc_store($key, array('time' => time(), 'data' => serialize($value))) == false) {
      throw new Google_CacheException("Couldn't store data");
    }
  }

  /**
   * @inheritDoc
   * @param String $key
   */
  public function delete($key) {
    @apc_delete($key);
  }
}



/**
 * Abstract storage class
 *
 * @author Chris Chabot <chabotc@google.com>
 */
abstract class Google_Cache {

  /**
   * Retrieves the data for the given key, or false if they
   * key is unknown or expired
   *
   * @param String $key The key who's data to retrieve
   * @param boolean|int $expiration Expiration time in seconds
   *
   */
  abstract function get($key, $expiration = false);

  /**
   * Store the key => $value set. The $value is serialized
   * by this function so can be of any type
   *
   * @param string $key Key of the data
   * @param string $value data
   */
  abstract function set($key, $value);

  /**
   * Removes the key/data pair for the given $key
   *
   * @param String $key
   */
  abstract function delete($key);
}


