<?php
/**
 * Copyright 2010 Google Inc.
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

/**
 * HTTP Request to be executed by apiIO classes. Upon execution, the
 * responseHttpCode, responseHeaders and responseBody will be filled in.
 *
 * @author Chris Chabot <chabotc@google.com>
 * @author Chirag Shah <chirags@google.com>
 *
 */
class Google_HttpRequest {
  const USER_AGENT_SUFFIX = "google-api-php-client/0.6.0";
  private $batchHeaders = array(
    'Content-Type' => 'application/http',
    'Content-Transfer-Encoding' => 'binary',
    'MIME-Version' => '1.0',
    'Content-Length' => ''
  );

  protected $url;
  protected $requestMethod;
  protected $requestHeaders;
  protected $postBody;
  protected $userAgent;

  protected $responseHttpCode;
  protected $responseHeaders;
  protected $responseBody;
  
  public $accessKey;

  public function __construct($url, $method = 'GET', $headers = array(), $postBody = null) {
    $this->setUrl($url);
    $this->setRequestMethod($method);
    $this->setRequestHeaders($headers);
    $this->setPostBody($postBody);

    global $apiConfig;
    if (empty($apiConfig['application_name'])) {
      $this->userAgent = self::USER_AGENT_SUFFIX;
    } else {
      $this->userAgent = $apiConfig['application_name'] . " " . self::USER_AGENT_SUFFIX;
    }
  }

  /**
   * Misc function that returns the base url component of the $url
   * used by the OAuth signing class to calculate the base string
   * @return string The base url component of the $url.
   * @see http://oauth.net/core/1.0a/#anchor13
   */
  public function getBaseUrl() {
    if ($pos = strpos($this->url, '?')) {
      return substr($this->url, 0, $pos);
    }
    return $this->url;
  }

  /**
   * Misc function that returns an array of the query parameters of the current
   * url used by the OAuth signing class to calculate the signature
   * @return array Query parameters in the query string.
   */
  public function getQueryParams() {
    if ($pos = strpos($this->url, '?')) {
      $queryStr = substr($this->url, $pos + 1);
      $params = array();
      parse_str($queryStr, $params);
      return $params;
    }
    return array();
  }

  /**
   * @return string HTTP Response Code.
   */
  public function getResponseHttpCode() {
    return (int) $this->responseHttpCode;
  }

  /**
   * @param int $responseHttpCode HTTP Response Code.
   */
  public function setResponseHttpCode($responseHttpCode) {
    $this->responseHttpCode = $responseHttpCode;
  }

  /**
   * @return $responseHeaders (array) HTTP Response Headers.
   */
  public function getResponseHeaders() {
    return $this->responseHeaders;
  }

  /**
   * @return string HTTP Response Body
   */
  public function getResponseBody() {
    return $this->responseBody;
  }

  /**
   * @param array $headers The HTTP response headers
   * to be normalized.
   */
  public function setResponseHeaders($headers) {
    $headers = Google_Utils::normalize($headers);
    if ($this->responseHeaders) {
      $headers = array_merge($this->responseHeaders, $headers);
    }

    $this->responseHeaders = $headers;
  }

  /**
   * @param string $key
   * @return array|boolean Returns the requested HTTP header or
   * false if unavailable.
   */
  public function getResponseHeader($key) {
    return isset($this->responseHeaders[$key])
        ? $this->responseHeaders[$key]
        : false;
  }

  /**
   * @param string $responseBody The HTTP response body.
   */
  public function setResponseBody($responseBody) {
    $this->responseBody = $responseBody;
  }

  /**
   * @return string $url The request URL.
   */

  public function getUrl() {
    return $this->url;
  }

  /**
   * @return string $method HTTP Request Method.
   */
  public function getRequestMethod() {
    return $this->requestMethod;
  }

  /**
   * @return array $headers HTTP Request Headers.
   */
  public function getRequestHeaders() {
    return $this->requestHeaders;
  }

  /**
   * @param string $key
   * @return array|boolean Returns the requested HTTP header or
   * false if unavailable.
   */
  public function getRequestHeader($key) {
    return isset($this->requestHeaders[$key])
        ? $this->requestHeaders[$key]
        : false;
  }

  /**
   * @return string $postBody HTTP Request Body.
   */
  public function getPostBody() {
    return $this->postBody;
  }

  /**
   * @param string $url the url to set
   */
  public function setUrl($url) {
    if (substr($url, 0, 4) == 'http') {
      $this->url = $url;
    } else {
      // Force the path become relative.
      if (substr($url, 0, 1) !== '/') {
        $url = '/' . $url;
      }
      global $apiConfig;
      $this->url = $apiConfig['basePath'] . $url;
    }
  }

  /**
   * @param string $method Set he HTTP Method and normalize
   * it to upper-case, as required by HTTP.
   *
   */
  public function setRequestMethod($method) {
    $this->requestMethod = strtoupper($method);
  }

  /**
   * @param array $headers The HTTP request headers
   * to be set and normalized.
   */
  public function setRequestHeaders($headers) {
    $headers = Google_Utils::normalize($headers);
    if ($this->requestHeaders) {
      $headers = array_merge($this->requestHeaders, $headers);
    }
    $this->requestHeaders = $headers;
  }

  /**
   * @param string $postBody the postBody to set
   */
  public function setPostBody($postBody) {
    $this->postBody = $postBody;
  }

  /**
   * Set the User-Agent Header.
   * @param string $userAgent The User-Agent.
   */
  public function setUserAgent($userAgent) {
    $this->userAgent = $userAgent;
  }

  /**
   * @return string The User-Agent.
   */
  public function getUserAgent() {
    return $this->userAgent;
  }

  /**
   * Returns a cache key depending on if this was an OAuth signed request
   * in which case it will use the non-signed url and access key to make this
   * cache key unique per authenticated user, else use the plain request url
   * @return string The md5 hash of the request cache key.
   */
  public function getCacheKey() {
    $key = $this->getUrl();

    if (isset($this->accessKey)) {
      $key .= $this->accessKey;
    }

    if (isset($this->requestHeaders['authorization'])) {
      $key .= $this->requestHeaders['authorization'];
    }

    return md5($key);
  }

  public function getParsedCacheControl() {
    $parsed = array();
    $rawCacheControl = $this->getResponseHeader('cache-control');
    if ($rawCacheControl) {
      $rawCacheControl = str_replace(', ', '&', $rawCacheControl);
      parse_str($rawCacheControl, $parsed);
    }

    return $parsed;
  }

  /**
   * @param string $id
   * @return string A string representation of the HTTP Request.
   */
  public function toBatchString($id) {
    $str = '';
    foreach($this->batchHeaders as $key => $val) {
      $str .= $key . ': ' . $val . "\n";
    }

    $str .= "Content-ID: $id\n";
    $str .= "\n";

    $path = parse_url($this->getUrl(), PHP_URL_PATH);
    $str .= $this->getRequestMethod() . ' ' . $path . " HTTP/1.1\n";
    foreach($this->getRequestHeaders() as $key => $val) {
      $str .= $key . ': ' . $val . "\n";
    }

    if ($this->getPostBody()) {
      $str .= "\n";
      $str .= $this->getPostBody();
    }

    return $str;
  }
}



/**
 * Implement the caching directives specified in rfc2616. This
 * implementation is guided by the guidance offered in rfc2616-sec13.
 * @author Chirag Shah <chirags@google.com>
 */
class Google_CacheParser {
  public static $CACHEABLE_HTTP_METHODS = array('GET', 'HEAD');
  public static $CACHEABLE_STATUS_CODES = array('200', '203', '300', '301');

  private function __construct() {}

  /**
   * Check if an HTTP request can be cached by a private local cache.
   *
   * @static
   * @param Google_HttpRequest $resp
   * @return bool True if the request is cacheable.
   * False if the request is uncacheable.
   */
  public static function isRequestCacheable (Google_HttpRequest $resp) {
    $method = $resp->getRequestMethod();
    if (! in_array($method, self::$CACHEABLE_HTTP_METHODS)) {
      return false;
    }

    // Don't cache authorized requests/responses.
    // [rfc2616-14.8] When a shared cache receives a request containing an
    // Authorization field, it MUST NOT return the corresponding response
    // as a reply to any other request...
    if ($resp->getRequestHeader("authorization")) {
      return false;
    }

    return true;
  }

  /**
   * Check if an HTTP response can be cached by a private local cache.
   *
   * @static
   * @param Google_HttpRequest $resp
   * @return bool True if the response is cacheable.
   * False if the response is un-cacheable.
   */
  public static function isResponseCacheable (Google_HttpRequest $resp) {
    // First, check if the HTTP request was cacheable before inspecting the
    // HTTP response.
    if (false == self::isRequestCacheable($resp)) {
      return false;
    }

    $code = $resp->getResponseHttpCode();
    if (! in_array($code, self::$CACHEABLE_STATUS_CODES)) {
      return false;
    }

    // The resource is uncacheable if the resource is already expired and
    // the resource doesn't have an ETag for revalidation.
    $etag = $resp->getResponseHeader("etag");
    if (self::isExpired($resp) && $etag == false) {
      return false;
    }

    // [rfc2616-14.9.2]  If [no-store is] sent in a response, a cache MUST NOT
    // store any part of either this response or the request that elicited it.
    $cacheControl = $resp->getParsedCacheControl();
    if (isset($cacheControl['no-store'])) {
      return false;
    }

    // Pragma: no-cache is an http request directive, but is occasionally
    // used as a response header incorrectly.
    $pragma = $resp->getResponseHeader('pragma');
    if ($pragma == 'no-cache' || strpos($pragma, 'no-cache') !== false) {
      return false;
    }

    // [rfc2616-14.44] Vary: * is extremely difficult to cache. "It implies that
    // a cache cannot determine from the request headers of a subsequent request
    // whether this response is the appropriate representation."
    // Given this, we deem responses with the Vary header as uncacheable.
    $vary = $resp->getResponseHeader('vary');
    if ($vary) {
      return false;
    }

    return true;
  }

  /**
   * @static
   * @param Google_HttpRequest $resp
   * @return bool True if the HTTP response is considered to be expired.
   * False if it is considered to be fresh.
   */
  public static function isExpired(Google_HttpRequest $resp) {
    // HTTP/1.1 clients and caches MUST treat other invalid date formats,
    // especially including the value “0”, as in the past.
    $parsedExpires = false;
    $responseHeaders = $resp->getResponseHeaders();
    if (isset($responseHeaders['expires'])) {
      $rawExpires = $responseHeaders['expires'];
      // Check for a malformed expires header first.
      if (empty($rawExpires) || (is_numeric($rawExpires) && $rawExpires <= 0)) {
        return true;
      }

      // See if we can parse the expires header.
      $parsedExpires = strtotime($rawExpires);
      if (false == $parsedExpires || $parsedExpires <= 0) {
        return true;
      }
    }

    // Calculate the freshness of an http response.
    $freshnessLifetime = false;
    $cacheControl = $resp->getParsedCacheControl();
    if (isset($cacheControl['max-age'])) {
      $freshnessLifetime = $cacheControl['max-age'];
    }

    $rawDate = $resp->getResponseHeader('date');
    $parsedDate = strtotime($rawDate);

    if (empty($rawDate) || false == $parsedDate) {
      $parsedDate = time();
    }
    if (false == $freshnessLifetime && isset($responseHeaders['expires'])) {
      $freshnessLifetime = $parsedExpires - $parsedDate;
    }

    if (false == $freshnessLifetime) {
      return true;
    }

    // Calculate the age of an http response.
    $age = max(0, time() - $parsedDate);
    if (isset($responseHeaders['age'])) {
      $age = max($age, strtotime($responseHeaders['age']));
    }

    return $freshnessLifetime <= $age;
  }

  /**
   * Determine if a cache entry should be revalidated with by the origin.
   *
   * @param Google_HttpRequest $response
   * @return bool True if the entry is expired, else return false.
   */
  public static function mustRevalidate(Google_HttpRequest $response) {
    // [13.3] When a cache has a stale entry that it would like to use as a
    // response to a client's request, it first has to check with the origin
    // server to see if its cached entry is still usable.
    return self::isExpired($response);
  }
}

/**
 * Curl based implementation of apiIO.
 *
 * @author Chris Chabot <chabotc@google.com>
 * @author Chirag Shah <chirags@google.com>
 */
class Google_CurlIO implements Google_IO {
  const CONNECTION_ESTABLISHED = "HTTP/1.0 200 Connection established\r\n\r\n";
  const FORM_URLENCODED = 'application/x-www-form-urlencoded';

  private static $ENTITY_HTTP_METHODS = array("POST" => null, "PUT" => null);
  private static $HOP_BY_HOP = array(
      'connection', 'keep-alive', 'proxy-authenticate', 'proxy-authorization',
      'te', 'trailers', 'transfer-encoding', 'upgrade');

  private $curlParams = array (
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => 0,
      CURLOPT_FAILONERROR => false,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLOPT_HEADER => true,
      CURLOPT_VERBOSE => false,
  );

  /**
   * Perform an authenticated / signed apiHttpRequest.
   * This function takes the apiHttpRequest, calls apiAuth->sign on it
   * (which can modify the request in what ever way fits the auth mechanism)
   * and then calls apiCurlIO::makeRequest on the signed request
   *
   * @param Google_HttpRequest $request
   * @return Google_HttpRequest The resulting HTTP response including the
   * responseHttpCode, responseHeaders and responseBody.
   */
  public function authenticatedRequest(Google_HttpRequest $request) {
    $request = Google_Client::$auth->sign($request);
    return $this->makeRequest($request);
  }

  /**
   * Execute a apiHttpRequest
   *
   * @param Google_HttpRequest $request the http request to be executed
   * @return Google_HttpRequest http request with the response http code, response
   * headers and response body filled in
   * @throws Google_IOException on curl or IO error
   */
  public function makeRequest(Google_HttpRequest $request) {
    // First, check to see if we have a valid cached version.
    $cached = $this->getCachedRequest($request);
    if ($cached !== false) {
      if (Google_CacheParser::mustRevalidate($cached)) {
        $addHeaders = array();
        if ($cached->getResponseHeader('etag')) {
          // [13.3.4] If an entity tag has been provided by the origin server,
          // we must use that entity tag in any cache-conditional request.
          $addHeaders['If-None-Match'] = $cached->getResponseHeader('etag');
        } elseif ($cached->getResponseHeader('date')) {
          $addHeaders['If-Modified-Since'] = $cached->getResponseHeader('date');
        }

        $request->setRequestHeaders($addHeaders);
      } else {
        // No need to revalidate the request, return it directly
        return $cached;
      }
    }

    if (array_key_exists($request->getRequestMethod(),
          self::$ENTITY_HTTP_METHODS)) {
      $request = $this->processEntityRequest($request);
    }

    $ch = curl_init();
    curl_setopt_array($ch, $this->curlParams);
    curl_setopt($ch, CURLOPT_URL, $request->getUrl());
    if ($request->getPostBody()) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPostBody());
    }

    $requestHeaders = $request->getRequestHeaders();
    if ($requestHeaders && is_array($requestHeaders)) {
      $parsed = array();
      foreach ($requestHeaders as $k => $v) {
        $parsed[] = "$k: $v";
      }
      curl_setopt($ch, CURLOPT_HTTPHEADER, $parsed);
    }

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod());
    curl_setopt($ch, CURLOPT_USERAGENT, $request->getUserAgent());
    $respData = curl_exec($ch);

    // Retry if certificates are missing.
    if (curl_errno($ch) == CURLE_SSL_CACERT) {
      error_log('SSL certificate problem, verify that the CA cert is OK.'
        . ' Retrying with the CA cert bundle from google-api-php-client.');
      curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacerts.pem');
      $respData = curl_exec($ch);
    }

    $respHeaderSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $respHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErrorNum = curl_errno($ch);
    $curlError = curl_error($ch);
    curl_close($ch);
    if ($curlErrorNum != CURLE_OK) {
      throw new Google_IOException("HTTP Error: ($respHttpCode) $curlError");
    }

    // Parse out the raw response into usable bits
    list($responseHeaders, $responseBody) =
          self::parseHttpResponse($respData, $respHeaderSize);

    if ($respHttpCode == 304 && $cached) {
      // If the server responded NOT_MODIFIED, return the cached request.
      if (isset($responseHeaders['connection'])) {
        $hopByHop = array_merge(
          self::$HOP_BY_HOP,
          explode(',', $responseHeaders['connection'])
        );

        $endToEnd = array();
        foreach($hopByHop as $key) {
          if (isset($responseHeaders[$key])) {
            $endToEnd[$key] = $responseHeaders[$key];
          }
        }
        $cached->setResponseHeaders($endToEnd);
      }
      return $cached;
    }

    // Fill in the apiHttpRequest with the response values
    $request->setResponseHttpCode($respHttpCode);
    $request->setResponseHeaders($responseHeaders);
    $request->setResponseBody($responseBody);
    // Store the request in cache (the function checks to see if the request
    // can actually be cached)
    $this->setCachedRequest($request);
    // And finally return it
    return $request;
  }

  /**
   * @visible for testing.
   * Cache the response to an HTTP request if it is cacheable.
   * @param Google_HttpRequest $request
   * @return bool Returns true if the insertion was successful.
   * Otherwise, return false.
   */
  public function setCachedRequest(Google_HttpRequest $request) {
    // Determine if the request is cacheable.
    if (Google_CacheParser::isResponseCacheable($request)) {
      Google_Client::$cache->set($request->getCacheKey(), $request);
      return true;
    }

    return false;
  }

  /**
   * @visible for testing.
   * @param Google_HttpRequest $request
   * @return Google_HttpRequest|bool Returns the cached object or
   * false if the operation was unsuccessful.
   */
  public function getCachedRequest(Google_HttpRequest $request) {
    if (false == Google_CacheParser::isRequestCacheable($request)) {
      false;
    }

    return Google_Client::$cache->get($request->getCacheKey());
  }

  /**
   * @param $respData
   * @param $headerSize
   * @return array
   */
  public static function parseHttpResponse($respData, $headerSize) {
    if (stripos($respData, self::CONNECTION_ESTABLISHED) !== false) {
      $respData = str_ireplace(self::CONNECTION_ESTABLISHED, '', $respData);
    }

    if ($headerSize) {
      $responseBody = substr($respData, $headerSize);
      $responseHeaders = substr($respData, 0, $headerSize);
    } else {
      list($responseHeaders, $responseBody) = explode("\r\n\r\n", $respData, 2);
    }

    $responseHeaders = self::parseResponseHeaders($responseHeaders);
    return array($responseHeaders, $responseBody);
  }

  public static function parseResponseHeaders($rawHeaders) {
    $responseHeaders = array();

    $responseHeaderLines = explode("\r\n", $rawHeaders);
    foreach ($responseHeaderLines as $headerLine) {
      if ($headerLine && strpos($headerLine, ':') !== false) {
        list($header, $value) = explode(': ', $headerLine, 2);
        $header = strtolower($header);
        if (isset($responseHeaders[$header])) {
          $responseHeaders[$header] .= "\n" . $value;
        } else {
          $responseHeaders[$header] = $value;
        }
      }
    }
    return $responseHeaders;
  }

  /**
   * @visible for testing
   * Process an http request that contains an enclosed entity.
   * @param Google_HttpRequest $request
   * @return Google_HttpRequest Processed request with the enclosed entity.
   */
  public function processEntityRequest(Google_HttpRequest $request) {
    $postBody = $request->getPostBody();
    $contentType = $request->getRequestHeader("content-type");

    // Set the default content-type as application/x-www-form-urlencoded.
    if (false == $contentType) {
      $contentType = self::FORM_URLENCODED;
      $request->setRequestHeaders(array('content-type' => $contentType));
    }

    // Force the payload to match the content-type asserted in the header.
    if ($contentType == self::FORM_URLENCODED && is_array($postBody)) {
      $postBody = http_build_query($postBody, '', '&');
      $request->setPostBody($postBody);
    }

    // Make sure the content-length header is set.
    if (!$postBody || is_string($postBody)) {
      $postsLength = strlen($postBody);
      $request->setRequestHeaders(array('content-length' => $postsLength));
    }

    return $request;
  }

  /**
   * Set options that update cURL's default behavior.
   * The list of accepted options are:
   * {@link http://php.net/manual/en/function.curl-setopt.php]
   *
   * @param array $optCurlParams Multiple options used by a cURL session.
   */
  public function setOptions($optCurlParams) {
    foreach ($optCurlParams as $key => $val) {
      $this->curlParams[$key] = $val;
    }
  }
}
/**
 * This class implements the RESTful transport of apiServiceRequest()'s
 *
 * @author Chris Chabot <chabotc@google.com>
 * @author Chirag Shah <chirags@google.com>
 */
class Google_REST {
  /**
   * Executes a apiServiceRequest using a RESTful call by transforming it into
   * an apiHttpRequest, and executed via apiIO::authenticatedRequest().
   *
   * @param Google_HttpRequest $req
   * @return array decoded result
   * @throws Google_ServiceException on server side error (ie: not authenticated,
   *  invalid or malformed post body, invalid url)
   */
  static public function execute(Google_HttpRequest $req) {
    $httpRequest = Google_Client::$io->makeRequest($req);
    $decodedResponse = self::decodeHttpResponse($httpRequest);
    $ret = isset($decodedResponse['data'])
        ? $decodedResponse['data'] : $decodedResponse;
    return $ret;
  }

  
  /**
   * Decode an HTTP Response.
   * @static
   * @throws Google_ServiceException
   * @param Google_HttpRequest $response The http response to be decoded.
   * @return mixed|null
   */
  public static function decodeHttpResponse($response) {
    $code = $response->getResponseHttpCode();
    $body = $response->getResponseBody();
    $decoded = null;
    
    if ($code != '200' && $code != '201' && $code != '204') {
      $decoded = json_decode($body, true);
      $err = 'Error calling ' . $response->getRequestMethod() . ' ' . $response->getUrl();
      if ($decoded != null && isset($decoded['error']['message'])  && isset($decoded['error']['code'])) {
        // if we're getting a json encoded error definition, use that instead of the raw response
        // body for improved readability
        $err .= ": ({$decoded['error']['code']}) {$decoded['error']['message']}";
      } else {
        $err .= ": ($code) $body";
      }

      throw new Google_ServiceException($err, $code, null, $decoded['error']['errors']);
    }
    
    // Only attempt to decode the response, if the response code wasn't (204) 'no content'
    if ($code != '204') {
      $decoded = json_decode($body, true);
      if ($decoded === null || $decoded === "") {
        throw new Google_ServiceException("Invalid json in service response: $body");
      }
    }
    return $decoded;
  }

  /**
   * Parse/expand request parameters and create a fully qualified
   * request uri.
   * @static
   * @param string $servicePath
   * @param string $restPath
   * @param array $params
   * @return string $requestUrl
   */
  static function createRequestUri($servicePath, $restPath, $params) {
    $requestUrl = $servicePath . $restPath;
    $uriTemplateVars = array();
    $queryVars = array();
    foreach ($params as $paramName => $paramSpec) {
      // Discovery v1.0 puts the canonical location under the 'location' field.
      if (! isset($paramSpec['location'])) {
        $paramSpec['location'] = $paramSpec['restParameterType'];
      }

      if ($paramSpec['type'] == 'boolean') {
        $paramSpec['value'] = ($paramSpec['value']) ? 'true' : 'false';
      }
      if ($paramSpec['location'] == 'path') {
        $uriTemplateVars[$paramName] = $paramSpec['value'];
      } else {
        if (isset($paramSpec['repeated']) && is_array($paramSpec['value'])) {
          foreach ($paramSpec['value'] as $value) {
            $queryVars[] = $paramName . '=' . rawurlencode($value);
          }
        } else {
          $queryVars[] = $paramName . '=' . rawurlencode($paramSpec['value']);
        }
      }
    }

    if (count($uriTemplateVars)) {
      $uriTemplateParser = new URI_Template_Parser($requestUrl);
      $requestUrl = $uriTemplateParser->expand($uriTemplateVars);
    }
    //FIXME work around for the the uri template lib which url encodes
    // the @'s & confuses our servers.
    $requestUrl = str_replace('%40', '@', $requestUrl);

    if (count($queryVars)) {
      $requestUrl .= '?' . implode($queryVars, '&');
    }

    return $requestUrl;
  }
}

/**
 * Abstract IO class
 *
 * @author Chris Chabot <chabotc@google.com>
 */
interface Google_IO {
  /**
   * An utility function that first calls $this->auth->sign($request) and then executes makeRequest()
   * on that signed request. Used for when a request should be authenticated
   * @param Google_HttpRequest $request
   * @return Google_HttpRequest $request
   */
  public function authenticatedRequest(Google_HttpRequest $request);

  /**
   * Executes a apIHttpRequest and returns the resulting populated httpRequest
   * @param Google_HttpRequest $request
   * @return Google_HttpRequest $request
   */
  public function makeRequest(Google_HttpRequest $request);

  /**
   * Set options that update the transport implementation's behavior.
   * @param $options
   */
  public function setOptions($options);

}
