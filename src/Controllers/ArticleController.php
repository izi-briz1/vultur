<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ArticleRepository;
use App\View;

final class ArticleController
{
    public function __construct(
        private readonly View $view,
        private readonly ArticleRepository $articleRepository,
    ) {}

    public function show($id): string
    {
        return __METHOD__;
    }
}