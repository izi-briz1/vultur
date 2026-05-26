<?php

namespace App\Models;

class Category{
    public int $id;
    public string $title;
    public string $description;
    public string $created_at;
    public ?string $updated_at;
    public ?array $articles = null;
}