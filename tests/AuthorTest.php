<?php
    require_once 'src/Author.php';

    $server = 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=library_test';
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
    }
?>
