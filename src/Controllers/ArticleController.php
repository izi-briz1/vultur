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
        $article = $this->articleRepository->findById($id);

        if(empty($article)){
            http_response_code(404);
            exit(1);
        }

        $similar = $this->articleRepository->findSimilarArticles($article);

        return $this->view->render('article.tpl', [
            'article' => $article,
            'similar' => $similar,
        ]);
    }
}