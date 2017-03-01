<?php
    require_once 'src/Author.php';

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "F. Scott Fitzgerald";
            $test_author = new Author ($name);
            $test_author->save();
            //Act
            $result = $test_author->getName();
            //Assert
            $this->assertEquals("F. Scott Fitzgerald", $result);
        }

        function test_getId()
        {
            $name = "F. Scott Fitzgerald";
            $test_author = new Author ($name);
            $test_author->save();

            $result = $test_author->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $name = "F. Scott Fitzgerald";
            $test_author = new Author ($name);
            $test_author->save();

            $result = Author::getAll();

            $this->assertEquals($test_author, $result[0]);
        }

        function test_getAll()
        {
            $name1 = "F. Scott Fitzgerald";
            $test_author1 = new Author ($name1);
            $test_author1->save();

            $name2 = "Earnest Hemingway";
            $test_author2 = new Author ($name2);
            $test_author2->save();

            $result = Author::getAll();

            $this->assertEquals([$test_author1, $test_author2], $result);
        }

        function test_find()
        {
            $name1 = "F. Scott Fitzgerald";
            $test_author1 = new Author ($name1);
            $test_author1->save();

            $name2 = "Earnest Hemingway";
            $test_author2 = new Author ($name2);
            $test_author2->save();

            $result = Author::find($test_author1->getId());

            $this->assertEquals($test_author1, $result);
        }

        function test_updateName()
        {
            $name = "F. Scott Fitzgerald";
            $test_author = new Author ($name);
            $test_author->save();

            $new_name = "F. Tott Fitzgerald";
            $test_author->updateName($new_name);
            $result = Author::getAll();

            $this->assertEquals($new_name, $result[0]->getName());

        }

        function test_getBooks()
        {
            $title = "Ruining Women's Lives: An Instructional Guide pt. 1";
            $test_book = new Book ($title);
            $test_book->save();

            $title2 = "Ruining Your Own Life: An Instructional Guide pt. 2";
            $test_book2 = new Book ($title);
            $test_book2->save();

            $name = "Jack Kerouac";
            $test_author = new Author ($name);
            $test_author->save();

            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);
            $result = $test_author->getBooks();

            $this->assertEquals([$test_book, $test_book2], $result);
        }
    }
?>
