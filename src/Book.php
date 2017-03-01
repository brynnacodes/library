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

    function getTitle()
    {
        return $this->title;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO books (title) VALUES (:title)");
        $exec->execute([':title' => $this->getTitle()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }
}
?>
