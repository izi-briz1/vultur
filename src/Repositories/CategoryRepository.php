<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Category;
use PDO;

class CategoryRepository
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id){
        $statement = $this->pdo->prepare('
            SELECT 
                * 
            FROM 
                categories 
            WHERE
                id = ?
        ');
        $statement->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $statement->execute([
            $id
        ]);

        return $statement->fetch();
    }

    public function findCategoryArticles(Category $category, string $sort, string $order, string $page, int $limit = 10){
        static $SORTS = ["views", "published_at"];
        static $ORDERS = ["asc", "desc"];

        $statement = $this->pdo->prepare('
            SELECT 
                COUNT(*)
            FROM 
                articles a
            INNER JOIN
                article_category a2c
            ON 
                a.id = a2c.article_id
                    AND 
                a.published_at IS NOT NULL                    
                    AND
                a2c.category_id IN (?)
        ');
        $statement->execute([
            $category->id
        ]);
        $count = $statement->fetchColumn();

        if(empty(in_array($sort, $SORTS, true))){
            $sort = $SORTS[0];
        }

        $order = strtolower($order);

        if(empty(in_array($order, $ORDERS, true))){
            $order = $ORDERS[0];
        }

        $page = max(1, (int)$page);

        $statement = $this->pdo->prepare(sprintf('
            SELECT 
                a.* 
            FROM 
                articles a
            INNER JOIN
                article_category a2c
            ON 
                a.id = a2c.article_id
                    AND 
                a.published_at IS NOT NULL                    
                    AND
                a2c.category_id IN (%d)
            ORDER BY 
                %s %s
            LIMIT
                %d
            OFFSET
                %d
        ',
            $category->id,
            $sort,
            $order,
            $limit,
            $limit * ($page - 1),
        ));
        $statement->setFetchMode(PDO::FETCH_CLASS, Article::class);
        $statement->execute();

        return [
            'sort' => $sort,
            'page' => $page,
            'order' => $order,
            'limit' => $limit,
            'pages' => ceil($count / $limit),
            'articles' => $statement->fetchAll()
        ];
    }

    public function findWithLatestArticles(int $count = 3){
        $statement = $this->pdo->prepare('
            SELECT 
                *
            FROM (
                SELECT 
                    a2c.category_id,
                    a.*,
                    ROW_NUMBER() OVER (
                        PARTITION BY a2c.category_id 
                        ORDER BY a.published_at DESC
                    ) AS rn
                FROM 
                    article_category a2c
                INNER JOIN 
                    articles a 
                ON 
                    a.id = a2c.article_id
            ) as _
            WHERE 
                rn <= ?
        ');
        $statement->setFetchMode(PDO::FETCH_CLASS, Article::class);
        $statement->execute([
            $count
        ]);

        $articles = $statement->fetchAll(PDO::FETCH_GROUP);

        if(empty($articles)){
            return [];
        }

        $statement = $this->pdo->prepare(sprintf('
            SELECT
                *
            FROM
                categories
            WHERE
                id IN (%s)
        ', join(',', array_keys($articles))));
        $statement->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $statement->execute();
        $categories = $statement->fetchAll();

        foreach ($categories as $category) {
            $category->articles = $articles[$category->id];
        }

        return $categories;
    }
}