<?php
    require_once 'src/Patron.php';

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    $server = 'mysql:host=localhost:8889;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
        }

        function test_getName()
        {
            //Arrange
            $name = "F. Scott Fitzgerald";
            $test_patron = new Patron ($name);
            $test_patron->save();
            //Act
            $result = $test_patron->getName();
            //Assert
            $this->assertEquals("F. Scott Fitzgerald", $result);
        }

        function test_getId()
        {
            $name = "F. Scott Fitzgerald";
            $test_patron = new Patron ($name);
            $test_patron->save();

            $result = $test_patron->getId();

            $this->assertEquals(true, is_numeric($result));
        }

        function test_save()
        {
            $name = "F. Scott Fitzgerald";
            $test_patron = new Patron ($name);
            $test_patron->save();

            $result = Patron::getAll();

            $this->assertEquals($test_patron, $result[0]);
        }

        function test_getAll()
        {
            $name1 = "F. Scott Fitzgerald";
            $test_patron1 = new Patron ($name1);
            $test_patron1->save();

            $name2 = "Earnest Hemingway";
            $test_patron2 = new Patron ($name2);
            $test_patron2->save();

            $result = Patron::getAll();

            $this->assertEquals([$test_patron1, $test_patron2], $result);
        }

        function test_find()
        {
            $name1 = "F. Scott Fitzgerald";
            $test_patron1 = new Patron ($name1);
            $test_patron1->save();

            $name2 = "Earnest Hemingway";
            $test_patron2 = new Patron ($name2);
            $test_patron2->save();

            $result = Patron::find($test_patron1->getId());

            $this->assertEquals($test_patron1, $result);
        }

        function test_updateName()
        {
            $name = "F. Scott Fitzgerald";
            $test_patron = new Patron ($name);
            $test_patron->save();

            $new_name = "F. Tott Fitzgerald";
            $test_patron->updateName($new_name);
            $result = Patron::getAll();

            $this->assertEquals($new_name, $result[0]->getName());
        }

        function test_delete()
        {
            $name1 = "F. Scott Fitzgerald";
            $test_patron1 = new Patron ($name1);
            $test_patron1->save();

            $name2 = "Earnest Hemingway";
            $test_patron2 = new Patron ($name2);
            $test_patron2->save();

            $test_patron1->delete();

            $result = Patron::getAll();

            $this->assertEquals([$test_patron2], $result);
        }

        function test_getBooks()
        {
            $name = "F. Scott Fitzgerald";
            $test_patron = new Patron ($name);
            $test_patron->save();

            $title = "How to Fight Earnest Hemingway";
            $test_book = new Book ($title);
            $test_book->save();

            $title2 = "Ruining Women's Lives: An Instructional Guide";
            $test_book2 = new Book ($title2);
            $test_book2->save();

            $test_patron->addBook($test_book);
            $test_patron->addBook($test_book2);
            $result = $test_patron->getBooks();

            $this->assertEquals([$test_book, $test_book2], $result);


        }
    }
?>
