<?php declare(strict_types=1);

use App\Database;

require __DIR__ . '/../bootstrap.php';

$config = require __DIR__ . '/../config/db.php';

try {
    $pdo = Database::create($config);
} catch (Throwable $t) {
    echo 'Cannot connect to database: '. $t->getMessage(). PHP_EOL;
    exit(1);
}

$faker = Faker\Factory::create('ru_RU');

echo "Clearing existing data... ";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE article_category");
$pdo->exec("TRUNCATE TABLE articles");
$pdo->exec("TRUNCATE TABLE categories");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
echo "ok\n";

echo "Seeding categories... ";
$categoryIds = [];
$categoryTitles = [];
$stmt = $pdo->prepare("
    INSERT INTO categories
        (title, description)
    VALUES
        (?, ?)
");

for ($i = 0; $i < 6; $i++) {
    do {
        $title = ucfirst($faker->unique()->words(2, true));
    } while (in_array($title, $categoryTitles, true));

    $categoryTitles[] = $title;
    $stmt->execute([$title, $faker->sentence(10)]);
    $categoryIds[] = (int) $pdo->lastInsertId();
}
echo count($categoryIds) . " created\n";

echo "Seeding articles... ";
$articleStmt = $pdo->prepare("
    INSERT INTO articles 
        (title, description, body, image, views, published_at)
    VALUES 
        (?, ?, ?, ?, ?, ?)
");
$linkStmt = $pdo->prepare("
    INSERT INTO article_category 
        (article_id, category_id)
    VALUES 
        (?, ?)
");

$articleCount = 40;
$pdo->beginTransaction();

try {
    for ($i = 0; $i < $articleCount; $i++) {
        $publishedAt = $faker->dateTimeBetween('-6 months', 'now');

        $articleStmt->execute([
            ucfirst($faker->sentence(6)),
            $faker->paragraph(3),
            implode("\n\n", $faker->paragraphs(8)),
            sprintf('https://picsum.photos/seed/%d/800/400', $i + 1),
            $faker->numberBetween(0, 5000),
            $publishedAt->format('Y-m-d H:i:s'),
        ]);

        $articleId = (int) $pdo->lastInsertId();

        $categoryCount = $faker->numberBetween(1, 3);
        $pickedCategories = $faker->randomElements($categoryIds, $categoryCount);

        foreach ($pickedCategories as $categoryId) {
            $linkStmt->execute([$articleId, $categoryId]);
        }
    }

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, "Seeding failed: {$e->getMessage()}\n");
    exit(1);
}

echo "$articleCount created\n";
echo "Done.\n";