<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Category;
use PDO;

class ArticleRepository
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM articles WHERE id = ?');
        $statement->setFetchMode(PDO::FETCH_CLASS, Article::class);
        $statement->execute([
            $id
        ]);

        $article = $statement->fetch();

        if(empty($article)){
            return null;
        }

        return $this->findCategories([$article])[0];
    }

    public function findSimilarArticles(Article $article, int $count = 3, bool $withCategories = false)
    {
        $statement = $this->pdo->prepare(sprintf('
            SELECT
                a2с.article_id,
                a.*
            FROM 
                article_category a2с
            INNER JOIN 
                articles a 
            ON 
                a2с.article_id = a.id
            WHERE 
                a.id NOT IN (?)
                    AND
                a2с.category_id IN (
                    SELECT 
                        category_id 
                    FROM 
                        article_category 
                    WHERE 
                        article_id IN (?)
                )
            GROUP BY
                a2с.article_id
            ORDER BY
                COUNT(a2с.category_id) DESC,
                a.published_at DESC
            LIMIT
                %d
        ', $count));
        $statement->setFetchMode(PDO::FETCH_CLASS, Article::class);
        $statement->execute([
            $article->id, $article->id
        ]);

        $similar = $statement->fetchAll(PDO::FETCH_UNIQUE);

        if(empty($similar)){
            return [];
        }

        if($withCategories){
            $similar = $this->findCategories($similar);
        }

        return $similar;
    }

    private function findCategories(array $articles): array{
        if(empty($articles)){
            return [];
        }

        $articlesId = [];

        foreach ($articles as $article) {
            $articlesId[] = $article->id;
        }

        $statement = $this->pdo->prepare(sprintf('
            SELECT 
                article_category.article_id as article_id,
                categories.*
            FROM 
                categories
            INNER JOIN
                article_category
            ON 
                categories.id = article_category.category_id
            WHERE
                article_category.article_id IN (%s)
        ', join(', ', array_fill(0, count($articlesId), '?'))));
        $statement->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $statement->execute($articlesId);
        $categories = $statement->fetchAll(PDO::FETCH_GROUP);

        if($categories){
            foreach ($articles as $article) {
                if(isset($categories[$article->id])){
                    $article->categories = $categories[$article->id];
                }
            }
        }

        return $articles;
    }
}