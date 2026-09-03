<?php
/**
 * Template Name: Генератор паролей
 */

get_header();
echo '<h1>' . get_the_title() . '</h1>';
?>

<div class="glass-panel generator-container">
    <div class="result-box">
        <input type="text" id="pw-result" readonly value="Нажмите кнопку ниже">
        <button class="copy-btn" id="pw-copy">Копировать</button>
    </div>

    <div class="range-group">
        <div class="range-header">
            <span>Длина пароля</span>
            <span id="pw-length-val" style="color: #4facfe; font-weight: bold;">16</span>
        </div>
        <input type="range" id="pw-length" min="8" max="64" value="16">
    </div>

    <div class="options-group">
        <label class="toggle-row">
            <span>Заглавные буквы (A-Z)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-upper" checked>
                <span class="slider-toggle"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Строчные буквы (a-z)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-lower" checked>
                <span class="slider-toggle"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Цифры (0-9)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-numbers" checked>
                <span class="slider-toggle"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Спецсимволы (!@#$%)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-symbols">
                <span class="slider-toggle"></span>
            </div>
        </label>
    </div>

    <button class="generate-btn" id="pw-generate">Сгенерировать пароль</button>
</div>

<?php get_footer(); ?>