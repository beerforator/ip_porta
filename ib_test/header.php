<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?$APPLICATION->ShowTitle()?></title>
    <?$APPLICATION->ShowHead(); // Системный вывод CSS/JS Битрикса ?>
</head>
<body>
    <?$APPLICATION->ShowPanel(); // Панель администратора сверху ?>
    
    <div class="animated-background"></div>

    <header class="main-header glass-panel">
        <div class="container header-content">
            <div class="logo">
                <span style="color: #4facfe;">ИБ</span> ПОРТАЛ
            </div>
            <nav class="main-nav">
                <a href="/ib-test/">Главная</a>
                <a href="/ib-test/news/">Новости</a>
                <a href="/ib-test/documents/">Документы</a>
                <a href="/ib-test/password/">Генератор паролей</a>
            </nav>
        </div>
    </header>

    <main class="page-content">
        <div class="container">
            <!-- Здесь будет начинаться контент конкретной страницы -->
            <h1><?$APPLICATION->ShowTitle(false)?></h1>