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

    function updateTitle($new_title)
    {
        $exec = $GLOBALS['DB']->prepare("UPDATE books SET title = :title WHERE id = :id;");
        $exec->execute([':title' => $new_title, 'id' => $this->getId()]);
        $this->setTitle($new_title);
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getid()};");
    }

    function addAuthor($author)
    {
        $exec = $GLOBALS['DB']->prepare("INSERT INTO authorship (author_id, book_id) VALUES (:author_id, :book_id);");
        $exec->execute([':book_id'=>$this->getId(), ':author_id'=>$author->getId()]);
    }

    function getAuthors()
    {
        $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books JOIN authorship ON (authorship.book_id = books.id) JOIN authors ON (authors.id = authorship.author_id) WHERE books.id = {$this->getId()};");
        $authors = [];
        foreach($returned_authors as $author) {
            $name = $author['name'];
            $id = $author['id'];
            $new_author = new Author($name, $id);
            array_push($authors, $new_author);
        }
        return $authors;
    }

    static function find($id)
    {
        $found_book;
        $books = Book::getAll();
        foreach ($books as $book) {
            if ($book->getId() == $id) {
                $found_book = $book;
            }
        }
        return $found_book;
    }

    static function getAll()
    {
        $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
        $books = [];
        foreach ($returned_books as $book) {
            $title = $book['title'];
            $id = $book['id'];
            $new_book = new Book ($title, $id);
            array_push($books, $new_book);
        }
        return $books;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM books;");
    }
}
?>
