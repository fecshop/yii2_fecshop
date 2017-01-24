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

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookStreamHttpClient;

/**
 * Class FacebookRequest
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class FacebookRequest
{

  /**
   * @const string Version number of the Facebook PHP SDK.
   */
  const VERSION = '4.0.15';

  /**
   * @const string Default Graph API version for requests
   */
  const GRAPH_API_VERSION = 'v2.2';

  /**
   * @const string Graph API URL
   */
  const BASE_GRAPH_URL = 'https://graph.facebook.com';

  /**
   * @var FacebookSession The session used for this request
   */
  private $session;

  /**
   * @var string The HTTP method for the request
   */
  private $method;

  /**
   * @var string The path for the request
   */
  private $path;

  /**
   * @var array The parameters for the request
   */
  private $params;

  /**
   * @var string The Graph API version for the request
   */
  private $version;

  /**
   * @var string ETag sent with the request
   */
  private $etag;

  /**
   * @var FacebookHttpable HTTP client handler
   */
  private static $httpClientHandler;

  /**
   * @var int The number of calls that have been made to Graph.
   */
  public static $requestCount = 0;

  /**
   * getSession - Returns the associated FacebookSession.
   *
   * @return FacebookSession
   */
  public function getSession()
  {
    return $this->session;
  }

  /**
   * getPath - Returns the associated path.
   *
   * @return string
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * getParameters - Returns the associated parameters.
   *
   * @return array
   */
  public function getParameters()
  {
    return $this->params;
  }

  /**
   * getMethod - Returns the associated method.
   *
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * getETag - Returns the ETag sent with the request.
   *
   * @return string
   */
  public function getETag()
  {
    return $this->etag;
  }

  /**
   * setHttpClientHandler - Returns an instance of the HTTP client
   * handler
   *
   * @param \Facebook\HttpClients\FacebookHttpable
   */
  public static function setHttpClientHandler(FacebookHttpable $handler)
  {
    static::$httpClientHandler = $handler;
  }

  /**
   * getHttpClientHandler - Returns an instance of the HTTP client
   * data handler
   *
   * @return FacebookHttpable
   */
  public static function getHttpClientHandler()
  {
    if (static::$httpClientHandler) {
      return static::$httpClientHandler;
    }
    return function_exists('curl_init') ? new FacebookCurlHttpClient() : new FacebookStreamHttpClient();
  }

  /**
   * FacebookRequest - Returns a new request using the given session.  optional
   *   parameters hash will be sent with the request.  This object is
   *   immutable.
   *
   * @param FacebookSession $session
   * @param string $method
   * @param string $path
   * @param array|null $parameters
   * @param string|null $version
   * @param string|null $etag
   */
  public function __construct(
    FacebookSession $session, $method, $path, $parameters = null, $version = null, $etag = null
  )
  {
    $this->session = $session;
    $this->method = $method;
    $this->path = $path;
    if ($version) {
      $this->version = $version;
    } else {
      $this->version = static::GRAPH_API_VERSION;
    }
    $this->etag = $etag;

    $params = ($parameters ?: array());
    if ($session
      && !isset($params["access_token"])) {
      $params["access_token"] = $session->getToken();
    }
    if (FacebookSession::useAppSecretProof()
      && !isset($params["appsecret_proof"])) {
      $params["appsecret_proof"] = $this->getAppSecretProof(
        $params["access_token"]
      );
    }
    $this->params = $params;
  }

  /**
   * Returns the base Graph URL.
   *
   * @return string
   */
  protected function getRequestURL()
  {
    return static::BASE_GRAPH_URL . '/' . $this->version . $this->path;
  }

  /**
   * execute - Makes the request to Facebook and returns the result.
   *
   * @return FacebookResponse
   *
   * @throws FacebookSDKException
   * @throws FacebookRequestException
   */
  public function execute()
  {
    $url = $this->getRequestURL();
    $params = $this->getParameters();

    if ($this->method === "GET") {
      $url = self::appendParamsToUrl($url, $params);
      $params = array();
    }

    $connection = self::getHttpClientHandler();
    $connection->addRequestHeader('User-Agent', 'fb-php-' . self::VERSION);
    $connection->addRequestHeader('Accept-Encoding', '*'); // Support all available encodings.

    // ETag
    if (isset($this->etag)) {
      $connection->addRequestHeader('If-None-Match', $this->etag);
    }

    // Should throw `FacebookSDKException` exception on HTTP client error.
    // Don't catch to allow it to bubble up.
    $result = $connection->send($url, $this->method, $params);

    static::$requestCount++;

    $etagHit = 304 == $connection->getResponseHttpStatusCode();

    $headers = $connection->getResponseHeaders();
    $etagReceived = isset($headers['ETag']) ? $headers['ETag'] : null;

    $decodedResult = json_decode($result);
    if ($decodedResult === null) {
      $out = array();
      parse_str($result, $out);
      return new FacebookResponse($this, $out, $result, $etagHit, $etagReceived);
    }
    if (isset($decodedResult->error)) {
      throw FacebookRequestException::create(
        $result,
        $decodedResult->error,
        $connection->getResponseHttpStatusCode()
      );
    }

    return new FacebookResponse($this, $decodedResult, $result, $etagHit, $etagReceived);
  }

  /**
   * Generate and return the appsecret_proof value for an access_token
   *
   * @param string $token
   *
   * @return string
   */
  public function getAppSecretProof($token)
  {
    return hash_hmac('sha256', $token, FacebookSession::_getTargetAppSecret());
  }

  /**
   * appendParamsToUrl - Gracefully appends params to the URL.
   *
   * @param string $url
   * @param array $params
   *
   * @return string
   */
  public static function appendParamsToUrl($url, $params = array())
  {
    if (!$params) {
      return $url;
    }

    if (strpos($url, '?') === false) {
      return $url . '?' . http_build_query($params, null, '&');
    }

    list($path, $query_string) = explode('?', $url, 2);
    parse_str($query_string, $query_array);

    // Favor params from the original URL over $params
    $params = array_merge($params, $query_array);

    return $path . '?' . http_build_query($params, null, '&');
  }

}
