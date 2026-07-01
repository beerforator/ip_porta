<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */

// Функция для красивого вывода размера файла
function formatBytes($bytes) {
    $units = array('Б', 'КБ', 'МБ', 'ГБ');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    return round($bytes / pow(1024, $pow), 2) . ' ' . $units[$pow];
}
?>

<div class="ib-accordion-list">
    <?php foreach($arResult["SECTIONS_WITH_ITEMS"] as $arSection): ?>
        
        <div class="accordion-item glass-panel">
            <!-- Заголовок аккордеона -->
            <div class="accordion-header">
                <h3 class="accordion-title"><?=$arSection["NAME"]?></h3>
                <button class="accordion-toggle-btn">
                    <span class="icon-plus"></span>
                </button>
            </div>
            
            <!-- Содержимое (файлы) -->
            <div class="accordion-content-wrapper">
                <div class="accordion-content">
                    <div class="docs-list">
                        <?php foreach($arSection["ITEMS"] as $arItem): ?>
                            <?php
                            $fileName = $arItem["NAME"];
                            $date = $arItem["ACTIVE_FROM"] ? (new \Bitrix\Main\Type\DateTime($arItem["ACTIVE_FROM"]))->format("d.m.Y") : "";
                            
                            // Получаем данные прикрепленного файла
                            $fileUrl = "";
                            $fileSize = "0 КБ";
                            if(!empty($arItem["PROPERTIES"]["FILE"]["VALUE"])) {
                                $fileData = CFile::GetFileArray($arItem["PROPERTIES"]["FILE"]["VALUE"]);
                                if($fileData) {
                                    $fileUrl = $fileData["SRC"];
                                    $fileSize = formatBytes($fileData["FILE_SIZE"]);
                                }
                            }
                            ?>
                            
                            <div class="doc-row">
                                <div class="doc-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8b8d98" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                </div>
                                
                                <div class="doc-info">
                                    <a href="<?=$fileUrl?>" target="_blank" class="doc-name"><?=$fileName?></a>
                                    <div class="doc-meta"><?=$date?> &bull; <?=$fileSize?></div>
                                </div>
                                
                                <a href="<?=$fileUrl?>" download class="doc-download" title="Скачать">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4facfe" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>