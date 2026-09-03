<?php
// Подключаем минимальное ядро Битрикса для AJAX (без HTML-шаблона)
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');

$RESULTS_IBLOCK_ID = 21; // ЗАМЕНИШЬ ПОТОМ НА ID ИНФОБЛОКА "РЕЗУЛЬТАТЫ"

// Проверяем, что пришли данные
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    
    $name = htmlspecialchars($_POST['name']);
    $testName = htmlspecialchars($_POST['testName']);
    $score = htmlspecialchars($_POST['score']);
    
    // Создаем новый элемент в инфоблоке результатов
    $el = new CIBlockElement;
    
    $PROP = array();
    $PROP['TEST_NAME'] = $testName;
    $PROP['SCORE'] = $score;
    
    $arLoadProductArray = Array(
        "IBLOCK_ID"      => $RESULTS_IBLOCK_ID,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $name . ' (' . date('d.m.Y H:i') . ')',
        "ACTIVE"         => "Y",
    );
    
    // Если IBLOCK_ID валидный (больше нуля) - сохраняем
    if ($RESULTS_IBLOCK_ID > 0) {
        if ($el->Add($arLoadProductArray)) {
            echo "OK";
        } else {
            echo "Error: " . $el->LAST_ERROR;
        }
    } else {
        echo "IBLOCK_ID not configured yet (Demo mode)";
    }
}
?>