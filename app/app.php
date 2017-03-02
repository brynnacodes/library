<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Patron.php";


    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), ["twig.path" => __DIR__."/../views"]);

    $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $app['debug'] = true;

    $app->get('/', function() use($app) {
        $result = 'hello';
        return $app["twig"]->render("root.html.twig", ['result' => $result]);
    });

    $app->get('/librarian', function() use($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("librarian.html.twig", ['books' => $books, 'authors' => $authors]);
    });

    $app->get('/patron', function() use($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("patron.html.twig", ['books' => $books, 'authors' => $authors]);
    });

    $app->post('/add_book', function() use($app) {
        $title = $_POST['title'];
        $book = new Book($title);
        $book->save();
        return $app->redirect("/librarian");
    });

    $app->post('/add_author', function() use($app){
        $name = $_POST['name'];
        $author = new Author($name);
        $author->save();
        return $app->redirect('/librarian');
    });

    $app->get('/books/{id}', function($id) use($app) {
        $book = Book::find($id);
        $authors = Author::getAll();
        $author = $book->getAuthors();
        return $app['twig']->render('book.html.twig', ['book' => $book, 'author' => $author, 'authors' => $authors]);
    });

    $app->post('/add_book_author/{id}', function($id) use ($app) {
        $author_id = $_POST['author'];
        $author = Author::find($author_id);
        var_dump($author_id);
        $book = Book::find($id);
        var_dump($book);
        $book->addAuthor($author);
        return $app->redirect('/books/'.$id);
    });

    $app->post("/checkout_book", function() use($app) {

    });

    return $app;
?>
