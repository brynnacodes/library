<?php

    require_once __DIR__."/../src/Book.php";

    class Patron
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
        $exec = $GLOBALS['DB']->prepare("INSERT INTO patrons (name) VALUES (:name)");
        $exec->execute([':name' => $this->getName()]);
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function updateName($new_name)
    {
        $exec = $GLOBALS['DB']->prepare("UPDATE patrons SET name = :name WHERE id = :id;");
        $exec->execute([':name' => $new_name, 'id' => $this->getId()]);
        $this->setName($new_name);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getid()};");
    }

    function addBook($book)
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO checkouts (patron_id, book_id) VALUES (:patron_id, :book_id);");
        $exec->execute([':book_id'=>$book->getId(), ':patron_id'=>$this->getId()]);
    }

    function getBooks()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM patrons JOIN checkouts ON (checkouts.patron_id = patrons.id) JOIN books ON (books.id = checkouts.book_id) WHERE patrons.id = {$this->getId()};");
        $books = [];
        foreach($returned_books as $book) {
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }

    function checkoutBook($new_status)
    {
        $exec = $GLOBALS['DB']->prepare("UPDATE checkout SET active_status = :active_status WHERE id = :id;");
        $exec->execute([':'])

    }

    function returnBook()
    {

    }

    function dropBooks()
    {
        $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE book_id = {$this->getId()};");
    }

    static function find($id)
    {
        $found_patron;
        $patrons = Patron::getAll();
        foreach ($patrons as $patron) {
            if ($patron->getId() == $id) {
                $found_patron = $patron;
            }
        }
        return $found_patron;
    }

    static function getAll()
    {
        $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
        $patrons = [];
        foreach ($returned_patrons as $patron) {
            $name = $patron['name'];
            $id = $patron['id'];
            $new_patron = new Patron ($name, $id);
            array_push($patrons, $new_patron);
        }
        return $patrons;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM patrons;");
    }
}
?>
