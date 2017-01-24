<?php
/*
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */


  /**
   * The "userinfo" collection of methods.
   * Typical usage is:
   *  <code>
   *   $oauth2Service = new Google_Oauth2Service(...);
   *   $userinfo = $oauth2Service->userinfo;
   *  </code>
   */
  class Google_UserinfoServiceResource extends Google_ServiceResource {


    /**
     * (userinfo.get)
     *
     * @param array $optParams Optional parameters.
     * @return Google_Userinfo
     */
    public function get($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Userinfo($data);
      } else {
        return $data;
      }
    }
  }

  /**
   * The "v2" collection of methods.
   * Typical usage is:
   *  <code>
   *   $oauth2Service = new Google_Oauth2Service(...);
   *   $v2 = $oauth2Service->v2;
   *  </code>
   */
  class Google_UserinfoV2ServiceResource extends Google_ServiceResource {


  }

  /**
   * The "me" collection of methods.
   * Typical usage is:
   *  <code>
   *   $oauth2Service = new Google_Oauth2Service(...);
   *   $me = $oauth2Service->me;
   *  </code>
   */
  class Google_UserinfoV2MeServiceResource extends Google_ServiceResource {


    /**
     * (me.get)
     *
     * @param array $optParams Optional parameters.
     * @return Google_Userinfo
     */
    public function get($optParams = array()) {
      $params = array();
      $params = array_merge($params, $optParams);
      $data = $this->__call('get', array($params));
      if ($this->useObjects()) {
        return new Google_Userinfo($data);
      } else {
        return $data;
      }
    }
  }

/**
 * Service definition for Google_Oauth2 (v2).
 *
 * <p>
 * OAuth2 API
 * </p>
 *
 * <p>
 * For more information about this service, see the
 * <a href="" target="_blank">API Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Google_Oauth2Service extends Google_Service {
  public $userinfo;
  public $userinfo_v2_me;
  /**
   * Constructs the internal representation of the Oauth2 service.
   *
   * @param Google_Client $client
   */
  public function __construct(Google_Client $client) {
    $this->servicePath = '';
    $this->version = 'v2';
    $this->serviceName = 'oauth2';

    $client->addService($this->serviceName, $this->version);
    $this->userinfo = new Google_UserinfoServiceResource($this, $this->serviceName, 'userinfo', json_decode('{"methods": {"get": {"path": "oauth2/v2/userinfo", "scopes": ["https://www.googleapis.com/auth/userinfo.email", "https://www.googleapis.com/auth/userinfo.profile"], "id": "oauth2.userinfo.get", "httpMethod": "GET", "response": {"$ref": "Userinfo"}}}}', true));
    $this->userinfo_v2_me = new Google_UserinfoV2MeServiceResource($this, $this->serviceName, 'me', json_decode('{"methods": {"get": {"path": "userinfo/v2/me", "scopes": ["https://www.googleapis.com/auth/userinfo.email", "https://www.googleapis.com/auth/userinfo.profile"], "id": "oauth2.userinfo.v2.me.get", "httpMethod": "GET", "response": {"$ref": "Userinfo"}}}}', true));
  }
}



