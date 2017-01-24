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
 * Class GraphAlbum
 * @package Facebook
 * @author Daniele Grosso <daniele.grosso@gmail.com>
 */

class GraphAlbum extends GraphObject
{
    /**
     * Returns the ID for the album.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getProperty('id');
    }

    /**
     * Returns whether the viewer can upload photos to this album.
     *
     * @return boolean|null
     */
    public function canUpload()
    {
        return $this->getProperty('can_upload');
    }

    /**
     * Returns the number of photos in this album.
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->getProperty('count');
    }

    /**
     * Returns the ID of the album's cover photo.
     *
     * @return string|null
     */
    public function getCoverPhoto()
    {
        return $this->getProperty('cover_photo');
    }

    /**
     * Returns the time the album was initially created.
     *
     * @return \DateTime|null
     */
    public function getCreatedTime()
    {
        $value = $this->getProperty('created_time');
        if ($value) {
            return new \DateTime($value);
        }
        return null;
    }

    /**
     * Returns the time the album was updated.
     *
     * @return \DateTime|null
     */
    public function getUpdatedTime()
    {
        $value = $this->getProperty('updated_time');
        if ($value) {
            return new \DateTime($value);
        }
        return null;
    }

    /**
     * Returns the description of the album.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getProperty('description');
    }

    /**
     * Returns profile that created the album.
     *
     * @return GraphUser|null
     */
    public function getFrom()
    {
      return  $this->getProperty('from', GraphUser::className());
    }

    /**
     * Returns a link to this album on Facebook.
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->getProperty('link');
    }

    /**
     * Returns the textual location of the album.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->getProperty('location');
    }

    /**
     * Returns the title of the album.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getProperty('name');
    }

    /**
     * Returns the privacy settings for the album.
     *
     * @return string|null
     */
    public function getPrivacy()
    {
        return $this->getProperty('privacy');
    }

    /**
     * Returns the type of the album. enum{profile, mobile, wall, normal, album}
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->getProperty('type');
    }

    //TODO: public function getPlace() that should return GraphPage
}
