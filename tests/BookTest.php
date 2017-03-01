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
    }



?>
