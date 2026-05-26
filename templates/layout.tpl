<!DOCTYPE html>
<html lang="ru">
<head>
    <title>
        {block name="title"}
            Сайт
        {/block}
    </title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<header>
    {block name="header"}
        <h1><a href="/">Блог</a></h1>
    {/block}
</header>

<main>
    {block name="content"}{/block}
</main>

<footer>
    {block name="footer"}
        <p>&copy; 2026</p>
    {/block}
</footer>
</body>
</html>