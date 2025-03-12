<?php

class PhotoSkeleton {
    private $id;
    private $user_id;
    private $title;
    private $description;
    private $tags;
    private $url;


    public function __construct($id, $user_id, $title, $description, $tags,$url) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->description = $description;
        $this->tags = $tags;
        $this->url = $url;

    }

    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getTags()
    {
        return $this->tags;
    }
    public function getUrl()
    {
        return $this->url;
    }


    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
    public function setUrl($url)
    {
        $this->url = $url;
    }
}

?>