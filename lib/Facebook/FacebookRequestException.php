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
 * Class FacebookRequestException
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookRequestException extends FacebookSDKException
{

  /**
   * @var int Status code for the response causing the exception
   */
  private $statusCode;

  /**
   * @var string Raw response
   */
  private $rawResponse;

  /**
   * @var array Decoded response
   */
  private $responseData;

  /**
   * Creates a FacebookRequestException.
   *
   * @param string $rawResponse The raw response from the Graph API
   * @param array $responseData The decoded response from the Graph API
   * @param int $statusCode
   */
  public function __construct($rawResponse, $responseData, $statusCode)
  {
    $this->rawResponse = $rawResponse;
    $this->statusCode = $statusCode;
    $this->responseData = self::convertToArray($responseData);
    parent::__construct(
      $this->get('message', 'Unknown Exception'), $this->get('code', -1), null
    );
  }

  /**
   * Process an error payload from the Graph API and return the appropriate
   *   exception subclass.
   *
   * @param string $raw the raw response from the Graph API
   * @param array $data the decoded response from the Graph API
   * @param int $statusCode the HTTP response code
   *
   * @return FacebookRequestException
   */
  public static function create($raw, $data, $statusCode)
  {
    $data = self::convertToArray($data);
    if (!isset($data['error']['code']) && isset($data['code'])) {
      $data = array('error' => $data);
    }
    $code = (isset($data['error']['code']) ? $data['error']['code'] : null);

    if (isset($data['error']['error_subcode'])) {
      switch ($data['error']['error_subcode']) {
        // Other authentication issues
        case 458:
        case 459:
        case 460:
        case 463:
        case 464:
        case 467:
          return new FacebookAuthorizationException($raw, $data, $statusCode);
          break;
      }
    }

    switch ($code) {
      // Login status or token expired, revoked, or invalid
      case 100:
      case 102:
      case 190:
        return new FacebookAuthorizationException($raw, $data, $statusCode);
        break;

      // Server issue, possible downtime
      case 1:
      case 2:
        return new FacebookServerException($raw, $data, $statusCode);
        break;

      // API Throttling
      case 4:
      case 17:
      case 341:
        return new FacebookThrottleException($raw, $data, $statusCode);
        break;

      // Duplicate Post
      case 506:
        return new FacebookClientException($raw, $data, $statusCode);
        break;
    }

    // Missing Permissions
    if ($code == 10 || ($code >= 200 && $code <= 299)) {
      return new FacebookPermissionException($raw, $data, $statusCode);
    }

    // OAuth authentication error
    if (isset($data['error']['type'])
      and $data['error']['type'] === 'OAuthException') {
      return new FacebookAuthorizationException($raw, $data, $statusCode);
    }

    // All others
    return new FacebookOtherException($raw, $data, $statusCode);
  }

  /**
   * Checks isset and returns that or a default value.
   *
   * @param string $key
   * @param mixed $default
   *
   * @return mixed
   */
  private function get($key, $default = null)
  {
    if (isset($this->responseData['error'][$key])) {
      return $this->responseData['error'][$key];
    }
    return $default;
  }

  /**
   * Returns the HTTP status code
   *
   * @return int
   */
  public function getHttpStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * Returns the sub-error code
   *
   * @return int
   */
  public function getSubErrorCode()
  {
    return $this->get('error_subcode', -1);
  }

  /**
   * Returns the error type
   *
   * @return string
   */
  public function getErrorType()
  {
    return $this->get('type', '');
  }

  /**
   * Returns the raw response used to create the exception.
   *
   * @return string
   */
  public function getRawResponse()
  {
    return $this->rawResponse;
  }

  /**
   * Returns the decoded response used to create the exception.
   *
   * @return array
   */
  public function getResponse()
  {
    return $this->responseData;
  }

  /**
   * Converts a stdClass object to an array
   *
   * @param mixed $object
   *
   * @return array
   */
  private static function convertToArray($object)
  {
    if ($object instanceof \stdClass) {
      return get_object_vars($object);
    }
    return $object;
  }

}