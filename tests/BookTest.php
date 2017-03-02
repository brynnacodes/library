<?php
    require_once 'src/Book.php';

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange
            $title = "F. Scott Fitzgerald";
            $test_books = new Book ($title);
            $test_books->save();
            //Act
            $result = $test_books->getTitle();
            //Assert
            $this->assertEquals("F. Scott Fitzgerald", $result);
        }

        function test_getId()
        {
            $title = "F. Scott Fitzgerald";
            $test_books = new Book ($title);
            $test_books->save();

            $result = $test_books->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $title = "F. Scott Fitzgerald";
            $test_books = new Book ($title);
            $test_books->save();

            $result = Book::getAll();

            $this->assertEquals($test_books, $result[0]);
        }

        function test_getAll()
        {
            $title1 = "F. Scott Fitzgerald";
            $test_books1 = new Book ($title1);
            $test_books1->save();

            $title2 = "Earnest Hemingway";
            $test_books2 = new Book ($title2);
            $test_books2->save();

            $result = Book::getAll();

            $this->assertEquals([$test_books1, $test_books2], $result);
        }

        function test_find()
        {
            $title1 = "F. Scott Fitzgerald";
            $test_books1 = new Book ($title1);
            $test_books1->save();

            $title2 = "Earnest Hemingway";
            $test_books2 = new Book ($title2);
            $test_books2->save();

            $result = Book::find($test_books1->getId());

            $this->assertEquals($test_books1, $result);
        }

        function test_updateTitle()
        {
            $title = "F. Scott Fitzgerald";
            $test_books = new Book ($title);
            $test_books->save();

            $new_title = "F. Tott Fitzgerald";
            $test_books->updateTitle($new_title);
            $result = Book::getAll();

            $this->assertEquals($new_title, $result[0]->getTitle());

        }

        function test_getAuthors()
        {
            $name = "F. Scott Fitzgerald";
            $test_author = new Author ($name);
            $test_author->save();

            $name2 = "Ernest Hemmingway";
            $test_author2 = new Author ($name);
            $test_author2->save();

            $title = "Ruining Women's Lives: An Instructional Guide";
            $test_book = new Book ($title);
            $test_book->save();

            $test_book->addAuthor($test_author);
            $test_book->addAuthor($test_author2);
            $result = $test_book->getAuthors();

            $this->assertEquals([$test_author, $test_author2], $result);
        }
    }



?>
