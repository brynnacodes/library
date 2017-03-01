<?php
    class Book
    {
        private $title;
        private $id;

    function __construct($title, $id = null)
    {
        $this->title = $title;
        $this->id = $id;
    }

    function setTitle($new_title)
    {
        $this->title = $new_title;
    }

    function getName()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }


}
?>
