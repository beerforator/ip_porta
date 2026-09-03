<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <div class="animated-background"></div>

    <header class="main-header glass-panel">
        <div class="container header-content">
            <div class="logo">
                <span style="color: #4facfe;">ИБ</span> ПОРТАЛ
            </div>
            <nav class="main-nav">
                <a href="<?php echo home_url('/'); ?>">Главная</a>
                <a href="<?php echo home_url('/news/'); ?>">Новости</a>
                <a href="<?php echo home_url('/documents/'); ?>">Документы</a>
                <a href="<?php echo home_url('/password/'); ?>">Генератор паролей</a>
            </nav>
        </div>
    </header>

    <main class="page-content">
        <div class="container">