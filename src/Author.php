<?php
    class Author
    {
        private $name;
        private $id;

    function __construct($name, $id = null)
    {
        $this->name = $name;
        $this->id = $id;
    }

    function setName($new_name)
    {
        $this->name = $new_name;
    }

    function getName()
    {
        return $this->name;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO authors (name) VALUES (:name)");
        $exec->execute([':name' => $this->getName()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function updateName($new_name)
    {
        $exec = $GLOBALS['DB']->prepare("UPDATE authors SET name = :name WHERE id = :id;");
        $exec->execute([':name' => $new_name, 'id' => $this->getId()]);
        $this->setName($new_name);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getid()};");
    }

    static function find($id)
    {
        $found_author;
        $authors = Author::getAll();
        foreach ($authors as $author) {
            if ($author->getId() == $id) {
                $found_author = $author;
            }
        }
        return $found_author;
    }

    static function getAll()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
        $authors = [];
        foreach ($returned_authors as $author) {
            $name = $author['name'];
            $id = $author['id'];
            $new_author = new Author ($name, $id);
            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM authors;");
    }
}

?>
