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
 * Class GraphUser
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphUser extends GraphObject
{

  /**
   * Returns the ID for the user as a string if present.
   *
   * @return string|null
   */
  public function getId()
  {
    return $this->getProperty('id');
  }

  /**
   * Returns the name for the user as a string if present.
   *
   * @return string|null
   */
  public function getName()
  {
    return $this->getProperty('name');
  }
  
  public function getEmail()
  {
    return $this->getProperty('email');
  }

  /**
   * Returns the first name for the user as a string if present.
   *
   * @return string|null
   */
  public function getFirstName()
  {
    return $this->getProperty('first_name');
  }

  /**
   * Returns the middle name for the user as a string if present.
   *
   * @return string|null
   */
  public function getMiddleName()
  {
    return $this->getProperty('middle_name');
  }

  /**
   * Returns the last name for the user as a string if present.
   *
   * @return string|null
   */
  public function getLastName()
  {
    return $this->getProperty('last_name');
  }
  
  /**
   * Returns the gender for the user as a string if present.
   *
   * @return string|null
   */
  public function getGender()
  {
    return $this->getProperty('gender');
  }

  /**
   * Returns the Facebook URL for the user as a string if available.
   *
   * @return string|null
   */
  public function getLink()
  {
    return $this->getProperty('link');
  }

  /**
   * Returns the users birthday, if available.
   *
   * @return \DateTime|null
   */
  public function getBirthday()
  {
    $value = $this->getProperty('birthday');
    if ($value) {
      return new \DateTime($value);
    }
    return null;
  }

  /**
   * Returns the current location of the user as a FacebookGraphLocation
   *   if available.
   *
   * @return GraphLocation|null
   */
  public function getLocation()
  {
    return $this->getProperty('location', GraphLocation::className());
  }

}
