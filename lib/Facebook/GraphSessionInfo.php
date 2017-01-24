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
namespace Facebook;

/**
 * Class GraphSessionInfo
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphSessionInfo extends GraphObject
{

  /**
   * Returns the application id the token was issued for.
   *
   * @return string|null
   */
  public function getAppId()
  {
    return $this->getProperty('app_id');
  }

  /**
   * Returns the application name the token was issued for.
   *
   * @return string|null
   */
  public function getApplication()
  {
    return $this->getProperty('application');
  }

  /**
   * Returns the date & time that the token expires.
   *
   * @return \DateTime|null
   */
  public function getExpiresAt()
  {
    $stamp = $this->getProperty('expires_at');
    if ($stamp) {
      return (new \DateTime())->setTimestamp($stamp);
    } else {
      return null;
    }
  }

  /**
   * Returns whether the token is valid.
   *
   * @return boolean
   */
  public function isValid()
  {
    return $this->getProperty('is_valid');
  }

  /**
   * Returns the date & time the token was issued at.
   *
   * @return \DateTime|null
   */
  public function getIssuedAt()
  {
    $stamp = $this->getProperty('issued_at');
    if ($stamp) {
      return (new \DateTime())->setTimestamp($stamp);
    } else {
      return null;
    }
  }

  /**
   * Returns the scope permissions associated with the token.
   *
   * @return array
   */
  public function getScopes()
  {
    return $this->getPropertyAsArray('scopes');
  }

  /**
   * Returns the login id of the user associated with the token.
   *
   * @return string|null
   */
  public function getId()
  {
    return $this->getProperty('user_id');
  }

}