<?php
require_once 'includes/db-connect.php';
require_once 'includes/functions.php';

// SQL-Statement für die 6 neusten Artikel
$sql = "SELECT a.id, a.title, a.summary, a.category_id, a.user_id,
        c.name AS category,
        CONCAT(u.forename, ' ', u.surname) AS author,
        i.filename AS image_file,
        i.alttext AS image_alt
    FROM articles AS a
    JOIN category AS c ON a.category_id = c.id
    JOIN user AS u ON a.user_id = u.id
    LEFT JOIN images AS i ON a.images_id = i.id
    WHERE a.published = 1
    ORDER BY a.id DESC
    LIMIT 6;";
$articles = pdo_execute( $pdo, $sql )->fetchAll( PDO::FETCH_ASSOC );

// SQL Abfrage um alle Kategorien zu erhalten
$sql = "SELECT id, name FROM category WHERE navigation = 1;";
// Führt die SQL-ANweisung aus und speichert das Ergebnis in der Variable $navigation
$navigation = pdo_execute( $pdo, $sql )->fetchAll();

// Header-Variablen setzten
$title = 'IT-News';
$description = 'All about IT and New from Software Development and Hardware';
$section = 1;
// var_dump($articles);
?>

<?php include './includes/header.php'; ?>
<main class="flex flex-wrap p-8" id="content">
    <?php foreach ( $articles as $article ) : ?>
        <article class="w-full p-4 flex justify-between flex-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-4">
            <a href="article.php?id=<?= $article['id'] ?>">
                <img src="uploads/<?= e( $article['image_file'] ?? 'blank.png' ) ?>" alt="<?= e( $article['image_alt'] ) ?>">
                <h2 class="text-blue-500 text-2xl pt-3 pb-1.5"><?= e( $article['title'] ) ?></h2>
                <p class="text-gray-500 pb-2.5"><?= e( $article['summary'] ) ?></p> 
            </a>
            <p class="credit text-xs">
                Posted in <a href="category.php?id=<?= $article['category_id'] ?>" class="text-pink-400">
                    <?= e( $article['category'] ) ?></a>
                by <a href="user.php?id=<?= $article['user_id'] ?>" class="text-pink-400">
                    <?= e( $article['author'] ) ?></a>
            </p>
        </article>
    <?php endforeach; ?>
</main>
<?php include './includes/footer.php'; ?>