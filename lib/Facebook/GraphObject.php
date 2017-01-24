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
 * Class GraphObject
 * @package Facebook
 * @author Fosco Marotto <fjm@fb.com>
 * @author David Poll <depoll@fb.com>
 */
class GraphObject
{

  /**
   * @var array - Holds the raw associative data for this object
   */
  protected $backingData;

  /**
   * Creates a GraphObject using the data provided.
   *
   * @param array $raw
   */
  public function __construct($raw)
  {
    if ($raw instanceof \stdClass) {
      $raw = get_object_vars($raw);
    }
    $this->backingData = $raw;

    if (isset($this->backingData['data']) && count($this->backingData) === 1) {
      if ($this->backingData['data'] instanceof \stdClass) {
        $this->backingData = get_object_vars($this->backingData['data']);
      } else {
        $this->backingData = $this->backingData['data'];
      }
    }
  }

  /**
   * cast - Return a new instance of a FacebookGraphObject subclass for this
   *   objects underlying data.
   *
   * @param string $type The GraphObject subclass to cast to
   *
   * @return GraphObject
   *
   * @throws FacebookSDKException
   */
  public function cast($type)
  {
    if ($this instanceof $type) {
      return $this;
    }
    if (is_subclass_of($type, GraphObject::className())) {
      return new $type($this->backingData);
    } else {
      throw new FacebookSDKException(
        'Cannot cast to an object that is not a GraphObject subclass', 620
      );
    }
  }

  /**
   * asArray - Return a key-value associative array for the given graph object.
   *
   * @return array
   */
  public function asArray()
  {
    return $this->backingData;
  }

  /**
   * getProperty - Gets the value of the named property for this graph object,
   *   cast to the appropriate subclass type if provided.
   *
   * @param string $name The property to retrieve
   * @param string $type The subclass of GraphObject, optionally
   *
   * @return mixed
   */
  public function getProperty($name, $type = 'Facebook\GraphObject')
  {
    if (isset($this->backingData[$name])) {
      $value = $this->backingData[$name];
      if (is_scalar($value)) {
        return $value;
      } else {
        return (new GraphObject($value))->cast($type);
      }
    } else {
      return null;
    }
  }

  /**
   * getPropertyAsArray - Get the list value of a named property for this graph
   *   object, where each item has been cast to the appropriate subclass type
   *   if provided.
   *
   * Calling this for a property that is not an array, the behavior
   *   is undefined, so don’t do this.
   *
   * @param string $name The property to retrieve
   * @param string $type The subclass of GraphObject, optionally
   *
   * @return array
   */
  public function getPropertyAsArray($name, $type = 'Facebook\GraphObject')
  {
    $target = array();
    if (isset($this->backingData[$name]['data'])) {
      $target = $this->backingData[$name]['data'];
    } else if (isset($this->backingData[$name])
      && !is_scalar($this->backingData[$name])) {
      $target = $this->backingData[$name];
    }
    $out = array();
    foreach ($target as $key => $value) {
      if (is_scalar($value)) {
        $out[$key] = $value;
      } else {
        $out[$key] = (new GraphObject($value))->cast($type);
      }
    }
    return $out;
  }

  /**
   * getPropertyNames - Returns a list of all properties set on the object.
   *
   * @return array
   */
  public function getPropertyNames()
  {
    return array_keys($this->backingData);
  }

  /**
   * Returns the string class name of the GraphObject or subclass.
   *
   * @return string
   */
  public static function className()
  {
    return get_called_class();
  }

}