<?php declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';

use App\Database;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\View;

$config = require __DIR__ . '/../config/db.php';

try {
    $pdo = Database::create($config);
} catch (Throwable $t) {
    echo 'Cannot connect to database: '. $t->getMessage(). PHP_EOL;
    exit(1);
}

$View = new View(
    __DIR__ . '/../templates/',
    __DIR__ . '/../runtime/compile/',
    __DIR__ . '/../runtime/cache/'
);

$Router = new App\Router();

$ArticleRepository = new ArticleRepository($pdo);
$CategoryRepository = new CategoryRepository($pdo);

$CategoryController = new CategoryController($View, $CategoryRepository);
$ArticleController = new ArticleController($View, $ArticleRepository);

$Router->addRoute('/', static function() use ($CategoryController){
    return $CategoryController->index();
});

$Router->addRoute('/category/{id}', static function(string $id) use ($CategoryController){
    return $CategoryController->show($id);
});

$Router->addRoute('/article/{id}', static function(string $id) use ($ArticleController){
    return $ArticleController->show($id);
});

echo $Router->dispatch($_SERVER['REQUEST_URI']);
