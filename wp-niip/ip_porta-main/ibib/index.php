<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Отдел Информационной Безопасности");
?>

<style>
/* Стили для кнопок-ссылок на главной */
.hero-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}
.hero-link-card {
    display: flex; align-items: center; gap: 16px;
    padding: var(--space-md) var(--space-lg);
    text-decoration: none; color: var(--text-main);
    transition: all 0.3s ease;
}
.hero-link-card:hover {
    transform: translateY(-4px);
    background: rgba(43, 116, 255, 0.1);
    border-color: #4facfe;
}
.hero-link-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: rgba(255,255,255,0.05); display: flex; justify-content: center; align-items: center;
    color: #4facfe; font-size: 24px;
}
</style>

<!-- Блок приветствия -->
<div class="glass-panel" style="padding: var(--space-xl) var(--space-lg); text-align: center; margin-top: var(--space-lg);">
    <h1 style="margin-top: 0; font-size: 36px; background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">На страже ваших данных</h1>
    <p style="color: var(--text-muted); font-size: 18px; max-width: 600px; margin: 0 auto; line-height: 1.6;">
        Добро пожаловать на корпоративный портал отдела ИБ. Здесь вы найдете актуальные регламенты, обучающие материалы и инструменты для безопасной работы в сети компании.
    </p>
</div>

<!-- Быстрые ссылки -->
<div class="hero-links">
    <a href="/ib-test/password/" class="hero-link-card glass-panel">
        <div class="hero-link-icon">🔑</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Генератор паролей</div>
            <div style="font-size: 12px; color: var(--text-muted);">Создать надежный пароль</div>
        </div>
    </a>
    <a href="/ib-test/memo/" class="hero-link-card glass-panel">
        <div class="hero-link-icon">🛡️</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Памятка ИБ</div>
            <div style="font-size: 12px; color: var(--text-muted);">Базовые правила защиты</div>
        </div>
    </a>
    <a href="/ib-test/polls/" class="hero-link-card glass-panel">
        <div class="hero-link-icon">📝</div>
        <div>
            <div style="font-weight: bold; font-size: 16px;">Тестирование</div>
            <div style="font-size: 12px; color: var(--text-muted);">Проверка знаний</div>
        </div>
    </a>
</div>

<!-- Блок со слайдером новостей -->
<div style="margin-top: var(--space-xl);">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-md);">
        <h2 style="margin: 0;">Важное в фокусе</h2>
        <a href="/ib-test/news/" style="color: #4facfe; text-decoration: none; font-size: 14px;">Все новости &rarr;</a>
    </div>
    
    <?php
    // Вызываем новости из того же ИБ 15, но с новым шаблоном ib_slider
    $APPLICATION->IncludeComponent(
        "bitrix:news.list", 
        "ib_slider", 
        array(
            "IBLOCK_TYPE" => "news",
            "IBLOCK_ID" => "15", // Тот же ID новостей!
            "NEWS_COUNT" => "6", // Берем последние 6
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "FIELD_CODE" => array("NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM"),
            "SET_TITLE" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
        ),
        false
    );
    ?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>