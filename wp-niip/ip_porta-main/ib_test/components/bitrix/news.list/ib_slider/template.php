<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<div class="ib-slider-wrapper">
    <!-- Кнопка Назад -->
    <button class="slider-btn prev-btn" id="slider-prev">&larr;</button>
    
    <!-- Контейнер слайдов -->
    <div class="ib-slider-track" id="slider-track">
        <?php foreach($arResult["ITEMS"] as $arItem): ?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $photoSrc = is_array($arItem["PREVIEW_PICTURE"]) ? $arItem["PREVIEW_PICTURE"]["SRC"] : "";
            $date = $arItem["ACTIVE_FROM"] ? (new \Bitrix\Main\Type\DateTime($arItem["ACTIVE_FROM"]))->format("d.m.Y") : "";
            ?>
            <a href="/ib-test/news/" class="ib-slide glass-panel" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <?php if($photoSrc): ?>
                    <div class="slide-img" style="background-image: url('<?=$photoSrc?>');"></div>
                <?php else: ?>
                    <div class="slide-img fallback-image">ИБ</div>
                <?php endif; ?>
                <div class="slide-content">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;"><?=$date?></div>
                    <div class="slide-title"><?=$arItem["NAME"]?></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Кнопка Вперед -->
    <button class="slider-btn next-btn" id="slider-next">&rarr;</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('slider-track');
    const btnPrev = document.getElementById('slider-prev');
    const btnNext = document.getElementById('slider-next');

    // Шаг прокрутки = ширина одной карточки + gap (примерно 340px)
    const scrollAmount = 340; 

    btnNext.addEventListener('click', () => {
        track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });

    btnPrev.addEventListener('click', () => {
        track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });
});
</script>