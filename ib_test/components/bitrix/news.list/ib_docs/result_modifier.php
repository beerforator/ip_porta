<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// Получаем все разделы этого инфоблока
$arSections = [];
$rsSections = CIBlockSection::GetList(["SORT"=>"ASC"], ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"]);
while($arSection = $rsSections->GetNext()) {
    $arSection["ITEMS"] = []; // Создаем пустой массив для файлов
    $arSections[$arSection["ID"]] = $arSection;
}
// Раздел для файлов, лежащих в корне
$arSections[0] = ["NAME" => "Прочие документы", "ITEMS" => []];

// Раскидываем файлы по разделам
foreach($arResult["ITEMS"] as $arItem) {
    $sectId = (int)$arItem["IBLOCK_SECTION_ID"];
    if(isset($arSections[$sectId])) {
        $arSections[$sectId]["ITEMS"][] = $arItem;
    } else {
        $arSections[0]["ITEMS"][] = $arItem;
    }
}

// Удаляем пустые разделы, чтобы не выводить пустые аккордеоны
foreach($arSections as $k => $v) {
    if(empty($v["ITEMS"])) unset($arSections[$k]);
}

// Передаем сгруппированные данные в шаблон
$arResult["SECTIONS_WITH_ITEMS"] = $arSections;
?>