<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    echo 'Cannot connect to database: '. $e->getMessage(). PHP_EOL;
    exit(1);
}

$pdo->exec('
    CREATE TABLE IF NOT EXISTS `migrations` (
        `name` VARCHAR(255) NOT NULL PRIMARY KEY,
        `applied_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
');

$applied = $pdo->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN);

$files = glob(__DIR__ . '/../db/migrations/*.sql');
sort($files);

if ($files === []) {
    echo 'No migrations found.'. PHP_EOL;
    exit(0);
}

$newCount = 0;

foreach ($files as $file) {
    $name = basename($file);

    if (in_array($name, $applied, true)) {
        continue;
    }

    echo "Applying {$name}... ";

    $sql = file_get_contents($file);

    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare("INSERT INTO migrations (name) VALUES (?)");
        $stmt->execute([$name]);
        echo 'OK'. PHP_EOL;
        $newCount++;
    } catch (PDOException $e) {
        echo 'FAILED'. PHP_EOL;
        echo 'Error in {$name}: '. $e->getMessage(). PHP_EOL;
        exit(1);
    }
}

echo $newCount === 0
    ? 'Nothing to apply, database is up to date.'. PHP_EOL
    : "Applied {$newCount} migration(s).". PHP_EOL;