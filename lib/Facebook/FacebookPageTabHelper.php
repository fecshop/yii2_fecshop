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
 * Class FacebookPageTabHelper
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 */
class FacebookPageTabHelper extends FacebookCanvasLoginHelper
{

  /**
   * @var array|null
   */
  protected $pageData;

  /**
   * Initialize the helper and process available signed request data.
   *
   * @param string|null $appId
   * @param string|null $appSecret
   */
  public function __construct($appId = null, $appSecret = null)
  {
    parent::__construct($appId, $appSecret);

    if (!$this->signedRequest) {
      return;
    }

    $this->pageData = $this->signedRequest->get('page');
  }

  /**
   * Returns a value from the page data.
   *
   * @param string $key
   * @param mixed|null $default
   *
   * @return mixed|null
   */
  public function getPageData($key, $default = null)
  {
    if (isset($this->pageData[$key])) {
      return $this->pageData[$key];
    }
    return $default;
  }

  /**
   * Returns true if the page is liked by the user.
   *
   * @return boolean
   */
  public function isLiked()
  {
    return $this->getPageData('liked') === true;
  }

  /**
   * Returns true if the user is an admin.
   *
   * @return boolean
   */
  public function isAdmin()
  {
    return $this->getPageData('admin') === true;
  }

  /**
   * Returns the page id if available.
   *
   * @return string|null
   */
  public function getPageId()
  {
    return $this->getPageData('id');
  }

}
