<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Документы и политики ИБ");
?>

<div class="documents-page" style="margin-top: var(--space-lg);">
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:news.list", 
        "ib_docs", // Наш новый шаблон
        array(
            "IBLOCK_TYPE" => "news",
            "IBLOCK_ID" => "16", // Укажи здесь ID твоего инфоблока с документами
            "NEWS_COUNT" => "100", // Выводим много, чтобы попали все
            "SORT_BY1" => "SORT",
            "SORT_ORDER1" => "ASC",
            "FIELD_CODE" => array("NAME", "DATE_ACTIVE_FROM"),
            "PROPERTY_CODE" => array("FILE"), // Вытягиваем свойство с файлом
            "SET_TITLE" => "N",
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