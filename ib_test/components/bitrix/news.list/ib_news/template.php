<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
?>

<div class="ib-news-grid">
    <?php foreach($arResult["ITEMS"] as $arItem): ?>
        <?php
        // 1. Добавляем поддержку редактирования Битрикса (всплывающие кнопки над карточкой)
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

        // 2. ИЩЕМ И ПАРСИМ ТВОИ ДАННЫЕ
        $name = $arItem["NAME"];
        $description = $arItem["PREVIEW_TEXT"];
        
        // Достаем картинку. Если её нет - ставим заглушку (можно нарисовать заглушку на CSS, мы сделаем градиентную)
        $photoSrc = "";
        if(is_array($arItem["PREVIEW_PICTURE"])) {
            $photoSrc = $arItem["PREVIEW_PICTURE"]["SRC"];
        }

        // Парсим дату и время (Битрикс отдает строку вида "26.06.2026 14:30:00")
        $date = "Неизвестно";
        $time = "";
        if(!empty($arItem["ACTIVE_FROM"])) {
            $objDate = new \Bitrix\Main\Type\DateTime($arItem["ACTIVE_FROM"]);
            $date = $objDate->format("d / m / Y"); // Как на скриншоте!
            $time = $objDate->format("H:i");
        }
        ?>

        <!-- Рисуем карточку -->
        <div class="ib-news-card glass-panel" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <!-- Блок с фото -->
            <div class="card-image-wrap">
                <?php if($photoSrc): ?>
                    <div class="card-image" style="background-image: url('<?=$photoSrc?>');"></div>
                <?php else: ?>
                    <div class="card-image fallback-image">
                        <span>ИБ</span>
                    </div>
                <?php endif; ?>
                
                <!-- Тег рубрики поверх картинки (как на макете) -->
                <div class="card-tag">НОВОСТИ</div>
            </div>

            <!-- Блок с контентом -->
            <div class="card-content">
                <div class="card-meta">
                    <span class="meta-date"><?=$date?></span>
                    <?php if($time): ?>
                        <span class="meta-separator">•</span>
                        <span class="meta-time"><?=$time?></span>
                    <?php endif; ?>
                </div>
                
                <h3 class="card-title"><?=$name?></h3>
                
                <?php if($description): ?>
                    <div class="card-desc">
                        <!-- Ограничиваем описание по длине, если оно слишком длинное -->
                        <?=mb_strimwidth(strip_tags($description), 0, 120, "...");?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Если новостей нет (инфоблок пустой) -->
<?php if(empty($arResult["ITEMS"])): ?>
    <div class="glass-panel" style="padding: var(--space-lg); text-align: center;">
        <p>Новостей пока нет. Добавьте их в инфоблок ID 15.</p>
    </div>
<?php endif; ?>