<?php
/**
 * Copyright 2014 Facebook, Inc.
 *
 * You are hereby granted a non-exclusive, worldwide, royalty-free license to
 * use, copy, modify, and distribute this software in source code or binary
 * form for use in connection with the web services and APIs provided by
 * Facebook.
 *
 * As with any software that integrates with the Facebook platform, your use
 * of this software is subject to the Facebook Developer Principles and
 * Policies [http://developers.facebook.com/policy/]. This copyright notice
 * shall be included in all copies or substantial portions of the software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
namespace Facebook\HttpClients;

/**
 * Class FacebookCurl
 * Abstraction for the procedural curl elements so that curl can be mocked
 * and the implementation can be tested.
 * @package Facebook
 */
class FacebookCurl
{

  /**
   * @var resource Curl resource instance
   */
  protected $curl;

  /**
   * Make a new curl reference instance
   */
  public function init()
  {
    $this->curl = curl_init();
  }

  /**
   * Set a curl option
   *
   * @param $key
   * @param $value
   */
  public function setopt($key, $value)
  {
    curl_setopt($this->curl, $key, $value);
  }

  /**
   * Set an array of options to a curl resource
   *
   * @param array $options
   */
  public function setopt_array(array $options)
  {
    curl_setopt_array($this->curl, $options);
  }

  /**
   * Send a curl request
   *
   * @return mixed
   */
  public function exec()
  {
    return curl_exec($this->curl);
  }

  /**
   * Return the curl error number
   *
   * @return int
   */
  public function errno()
  {
    return curl_errno($this->curl);
  }

  /**
   * Return the curl error message
   *
   * @return string
   */
  public function error()
  {
    return curl_error($this->curl);
  }

  /**
   * Get info from a curl reference
   *
   * @param $type
   *
   * @return mixed
   */
  public function getinfo($type)
  {
    return curl_getinfo($this->curl, $type);
  }

  /**
   * Get the currently installed curl version
   *
   * @return array
   */
  public function version()
  {
    return curl_version();
  }

  /**
   * Close the resource connection to curl
   */
  public function close()
  {
    curl_close($this->curl);
  }

}
