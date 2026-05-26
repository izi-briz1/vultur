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
        return __METHOD__;
    }

    public function show($id): string
    {
        return __METHOD__;
    }
}