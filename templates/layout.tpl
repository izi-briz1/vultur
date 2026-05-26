<!DOCTYPE html>
<html>
<head>
    <title>
        {block name="title"}
            Сайт
        {/block}
    </title>
    {block name="head"}
        <link rel="stylesheet" href="/style.css">
    {/block}
</head>
<body>
<header>
    {block name="header"}
        <h1>Мой сайт</h1>
        <nav><a href="/">Главная</a> | <a href="/about">О нас</a></nav>
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