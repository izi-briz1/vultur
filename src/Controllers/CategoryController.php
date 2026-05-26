<?php
declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Repositories\CategoryRepository;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categoryRepository,
    ) {}

    public function index(): string
    {
        $categories = $this->categoryRepository->findWithLatestArticles(3);

        return $this->view->render('index.tpl', [
            'categories' => $categories,
        ]);
    }

    public function show($id): string
    {
        $category = $this->categoryRepository->findById($id);

        if(empty($category)){
            http_response_code(404);
            return '404 Not Found';
        }

        $data = $this->categoryRepository->findCategoryArticles($category,
            $_GET['sort'] ?? '',
            $_GET['order'] ?? '',
            $_GET['page'] ?? '',
            10
        );
        $data['category'] = $category;

        return $this->view->render('category.tpl', $data);
    }
}