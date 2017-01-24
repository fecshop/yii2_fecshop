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

use Facebook\FacebookSDKException;

class FacebookStreamHttpClient implements FacebookHttpable {

  /**
   * @var array The headers to be sent with the request
   */
  protected $requestHeaders = array();

  /**
   * @var array The headers received from the response
   */
  protected $responseHeaders = array();

  /**
   * @var int The HTTP status code returned from the server
   */
  protected $responseHttpStatusCode = 0;

  /**
   * @var FacebookStream Procedural stream wrapper as object
   */
  protected static $facebookStream;

  /**
   * @param FacebookStream|null Procedural stream wrapper as object
   */
  public function __construct(FacebookStream $facebookStream = null)
  {
    self::$facebookStream = $facebookStream ?: new FacebookStream();
  }

  /**
   * The headers we want to send with the request
   *
   * @param string $key
   * @param string $value
   */
  public function addRequestHeader($key, $value)
  {
    $this->requestHeaders[$key] = $value;
  }

  /**
   * The headers returned in the response
   *
   * @return array
   */
  public function getResponseHeaders()
  {
    return $this->responseHeaders;
  }

  /**
   * The HTTP status response code
   *
   * @return int
   */
  public function getResponseHttpStatusCode()
  {
    return $this->responseHttpStatusCode;
  }

  /**
   * Sends a request to the server
   *
   * @param string $url The endpoint to send the request to
   * @param string $method The request method
   * @param array  $parameters The key value pairs to be sent in the body
   *
   * @return string Raw response from the server
   *
   * @throws \Facebook\FacebookSDKException
   */
  public function send($url, $method = 'GET', $parameters = array())
  {
    $options = array(
      'http' => array(
        'method' => $method,
        'timeout' => 60,
        'ignore_errors' => true
      ),
      'ssl' => array(
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => true, // All root certificates are self-signed
        'cafile' => __DIR__ . '/certs/DigiCertHighAssuranceEVRootCA.pem',
      ),
    );

    if ($parameters) {
      $options['http']['content'] = http_build_query($parameters, null, '&');

      $this->addRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }

    $options['http']['header'] = $this->compileHeader();

    self::$facebookStream->streamContextCreate($options);
    $rawResponse = self::$facebookStream->fileGetContents($url);
    $rawHeaders = self::$facebookStream->getResponseHeaders();

    if ($rawResponse === false || !$rawHeaders) {
      throw new FacebookSDKException('Stream returned an empty response', 660);
    }

    $this->responseHeaders = self::formatHeadersToArray($rawHeaders);
    $this->responseHttpStatusCode = self::getStatusCodeFromHeader($this->responseHeaders['http_code']);

    return $rawResponse;
  }

  /**
   * Formats the headers for use in the stream wrapper
   *
   * @return string
   */
  public function compileHeader()
  {
    $header = [];
    foreach($this->requestHeaders as $k => $v) {
      $header[] = $k . ': ' . $v;
    }

    return implode("\r\n", $header);
  }

  /**
   * Converts array of headers returned from the wrapper into
   * something standard
   *
   * @param array $rawHeaders
   *
   * @return array
   */
  public static function formatHeadersToArray(array $rawHeaders)
  {
    $headers = array();

    foreach ($rawHeaders as $line) {
      if (strpos($line, ':') === false) {
        $headers['http_code'] = $line;
      } else {
        list ($key, $value) = explode(': ', $line);
        $headers[$key] = $value;
      }
    }

    return $headers;
  }

  /**
   * Pulls out the HTTP status code from a response header
   *
   * @param string $header
   *
   * @return int
   */
  public static function getStatusCodeFromHeader($header)
  {
    preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $header, $match);
    return (int) $match[1];
  }

}
