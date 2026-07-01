<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости по информационной безопасности");
?>

<div class="news-page-container" style="margin-top: var(--space-lg);">
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:news.list", 
        "ib_news", // Указываем название нашего будущего шаблона
        array(
            "IBLOCK_TYPE" => "news", // Тип твоего инфоблока (поменяй, если другой)
            "IBLOCK_ID" => "15",     // Тот самый ID 15
            "NEWS_COUNT" => "9",     // Сколько новостей выводить (сделаем кратно 3 для красивой сетки)
            "SORT_BY1" => "ACTIVE_FROM",
            "SORT_ORDER1" => "DESC",
            "FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_TEXT",
                2 => "PREVIEW_PICTURE",
                3 => "DATE_ACTIVE_FROM",
            ),
            "SET_TITLE" => "N", // Не перезаписывать заголовок страницы
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
        ),
        false
    );
    ?>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>