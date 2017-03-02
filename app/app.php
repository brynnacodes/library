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
        $patrons = Patron::getAll();
        return $app["twig"]->render("root.html.twig", ['patrons' => $patrons]);
    });

    $app->post('/add_patron', function() use($app) {
        $name = $_POST['name'];
        $new_patron = new Patron($name);
        $new_patron->save();
        return $app->redirect('/');
    });

    $app->get('/librarian', function() use($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        return $app['twig']->render("librarian.html.twig", ['books' => $books, 'authors' => $authors]);
    });

    $app->get('/patrons/{id}', function($id) use($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        $patron = Patron::find($id);
        $patron_books = $patron->getBooks();
        return $app['twig']->render("patron.html.twig", ['books' => $books, 'patron_books' => $patron_books, 'authors' => $authors, 'patron' => $patron]);
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

    $app->post("/checkout_book/{id}", function($id) use($app) {
        $patron = Patron::find($id);
        $books = Book::getAll();

        $book_id = $_POST['book'];
        $found_book = Book::find($book_id);
        $patron->addBook($found_book);

        $patron_books = $patron->getBooks();

        return $app['twig']->render("patron.html.twig", ['patron_books'=>$patron_books,'books'=>$books, 'patron' => $patron]);
    });

    return $app;
?>
