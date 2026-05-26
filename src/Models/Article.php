<?php

namespace App\Models;

class Article{
    public int $id;
    public string $title;
    public string $description;
    public string $body;
    public string $image;
    public int $views;
    public string $created_at;
    public string $published_at;
    public ?string $updated_at;
    public ?array $categories = null;
}