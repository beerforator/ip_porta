<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Генератор паролей");
?>

<style>
/* --- СТИЛИ КОНКРЕТНО ДЛЯ ГЕНЕРАТОРА --- */
.generator-container {
    max-width: 600px;
    margin: 0 auto;
    padding: var(--space-lg);
    display: flex;
    flex-direction: column;
    gap: var(--space-lg);
}

/* Поле с результатом */
.result-box {
    display: flex;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: var(--space-sm) var(--space-md);
    align-items: center;
    border: 1px solid var(--glass-border);
    box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);
}

.result-box input {
    flex-grow: 1;
    background: transparent;
    border: none;
    color: var(--text-main);
    font-size: 22px;
    font-family: 'Courier New', Courier, monospace; /* Моноширинный для паролей */
    outline: none;
}

.copy-btn {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-main);
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.copy-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Ползунок длины (Range Slider) */
.range-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-sm);
}

.range-header {
    display: flex;
    justify-content: space-between;
    font-weight: 500;
}

input[type=range] {
    -webkit-appearance: none;
    width: 100%;
    background: transparent;
    margin: 10px 0;
}

input[type=range]::-webkit-slider-runnable-track {
    width: 100%;
    height: 6px;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

input[type=range]::-webkit-slider-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #4facfe; /* Акцентный синий цвет */
    cursor: pointer;
    -webkit-appearance: none;
    margin-top: -7px;
    box-shadow: 0 0 12px rgba(79, 172, 254, 0.6); /* Свечение */
    transition: transform 0.1s;
}

input[type=range]::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

/* Тумблеры (Apple Style Switches) */
.options-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-md);
}

.toggle-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 28px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(255, 255, 255, 0.1);
    transition: .3s;
    border-radius: 34px;
    border: 1px solid var(--glass-border);
}

.slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: var(--text-main);
    transition: .3s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #4facfe;
    border-color: #4facfe;
}

input:checked + .slider:before {
    transform: translateX(22px);
    background-color: #fff;
}

/* Главная кнопка */
.generate-btn {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    padding: 16px;
    border-radius: 12px;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.generate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
}

.generate-btn:active {
    transform: translateY(0);
}
</style>

<!-- --- HTML КАРКАС --- -->
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
                <span class="slider"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Строчные буквы (a-z)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-lower" checked>
                <span class="slider"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Цифры (0-9)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-numbers" checked>
                <span class="slider"></span>
            </div>
        </label>
        <label class="toggle-row">
            <span>Спецсимволы (!@#$%)</span>
            <div class="toggle-switch">
                <input type="checkbox" id="pw-symbols">
                <span class="slider"></span>
            </div>
        </label>
    </div>

    <button class="generate-btn" id="pw-generate">Сгенерировать пароль</button>
</div>

<!-- --- VANILLA JS ЛОГИКА --- -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Находим все нужные элементы
    const resultEl = document.getElementById('pw-result');
    const lengthEl = document.getElementById('pw-length');
    const lengthVal = document.getElementById('pw-length-val');
    const uppercaseEl = document.getElementById('pw-upper');
    const lowercaseEl = document.getElementById('pw-lower');
    const numbersEl = document.getElementById('pw-numbers');
    const symbolsEl = document.getElementById('pw-symbols');
    const generateBtn = document.getElementById('pw-generate');
    const copyBtn = document.getElementById('pw-copy');

    // Обновляем цифру длины при движении ползунка
    lengthEl.addEventListener('input', (e) => {
        lengthVal.innerText = e.target.value;
    });

    // Словари символов
    const chars = {
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        lower: 'abcdefghijklmnopqrstuvwxyz',
        numbers: '0123456789',
        symbols: '!@#$%^&*()_+~|{}[]:;?><,./-='
    };

    // Функция безопасной генерации
    function generatePassword(length, upper, lower, numbers, symbols) {
        let availableChars = '';
        if (upper) availableChars += chars.upper;
        if (lower) availableChars += chars.lower;
        if (numbers) availableChars += chars.numbers;
        if (symbols) availableChars += chars.symbols;

        if (availableChars.length === 0) {
            return 'Выберите хотя бы одну опцию!';
        }

        let generatedPassword = '';
        // Используем криптографически стойкий генератор случайных чисел (Crypto API)
        const randomValues = new Uint32Array(length);
        window.crypto.getRandomValues(randomValues);

        for (let i = 0; i < length; i++) {
            // Выбираем символ из доступных, используя случайное число
            generatedPassword += availableChars[randomValues[i] % availableChars.length];
        }

        return generatedPassword;
    }

    // Слушатель клика на кнопку генерации
    generateBtn.addEventListener('click', () => {
        const length = +lengthEl.value; // Конвертируем строку в число
        resultEl.value = generatePassword(
            length, 
            uppercaseEl.checked, 
            lowercaseEl.checked, 
            numbersEl.checked, 
            symbolsEl.checked
        );
    });

    // Слушатель для кнопки "Копировать" (используем Clipboard API)
    copyBtn.addEventListener('click', () => {
        if (!resultEl.value || resultEl.value.includes('Выберите') || resultEl.value.includes('Нажмите')) {
            return;
        }
        navigator.clipboard.writeText(resultEl.value).then(() => {
            const originalText = copyBtn.innerText;
            copyBtn.innerText = 'Скопировано!';
            copyBtn.style.color = '#00f2fe';
            copyBtn.style.borderColor = '#00f2fe';
            
            // Возвращаем кнопку в исходное состояние через 2 секунды
            setTimeout(() => {
                copyBtn.innerText = originalText;
                copyBtn.style.color = 'var(--text-main)';
                copyBtn.style.borderColor = 'var(--glass-border)';
            }, 2000);
        });
    });
    
    // Генерируем пароль сразу при загрузке страницы, чтобы поле не было пустым
    generateBtn.click();
});
</script>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>