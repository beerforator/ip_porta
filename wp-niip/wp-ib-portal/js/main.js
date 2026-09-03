// ============================================================
// Слайдер новостей
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('slider-track');
    const btnPrev = document.getElementById('slider-prev');
    const btnNext = document.getElementById('slider-next');

    if (track && btnPrev && btnNext) {
        const scrollAmount = 340;
        btnNext.addEventListener('click', () => {
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
        btnPrev.addEventListener('click', () => {
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
    }
});

// ============================================================
// Аккордеон для документов
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const headers = document.querySelectorAll('.accordion-header');
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const currentItem = header.closest('.accordion-item');
            if (currentItem) {
                currentItem.classList.toggle('active');
            }
        });
    });
});

// ============================================================
// Генератор паролей
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    const resultEl = document.getElementById('pw-result');
    const lengthEl = document.getElementById('pw-length');
    const lengthVal = document.getElementById('pw-length-val');
    const uppercaseEl = document.getElementById('pw-upper');
    const lowercaseEl = document.getElementById('pw-lower');
    const numbersEl = document.getElementById('pw-numbers');
    const symbolsEl = document.getElementById('pw-symbols');
    const generateBtn = document.getElementById('pw-generate');
    const copyBtn = document.getElementById('pw-copy');

    if (!resultEl) return;

    lengthEl.addEventListener('input', (e) => {
        lengthVal.innerText = e.target.value;
    });

    const chars = {
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        lower: 'abcdefghijklmnopqrstuvwxyz',
        numbers: '0123456789',
        symbols: '!@#$%^&*()_+~|{}[]:;?><,./-='
    };

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
        const randomValues = new Uint32Array(length);
        window.crypto.getRandomValues(randomValues);

        for (let i = 0; i < length; i++) {
            generatedPassword += availableChars[randomValues[i] % availableChars.length];
        }

        return generatedPassword;
    }

    generateBtn.addEventListener('click', () => {
        const length = +lengthEl.value;
        resultEl.value = generatePassword(
            length,
            uppercaseEl.checked,
            lowercaseEl.checked,
            numbersEl.checked,
            symbolsEl.checked
        );
    });

    copyBtn.addEventListener('click', () => {
        if (!resultEl.value || resultEl.value.includes('Выберите') || resultEl.value.includes('Нажмите')) {
            return;
        }
        navigator.clipboard.writeText(resultEl.value).then(() => {
            const originalText = copyBtn.innerText;
            copyBtn.innerText = 'Скопировано!';
            copyBtn.style.color = '#00f2fe';
            copyBtn.style.borderColor = '#00f2fe';
            setTimeout(() => {
                copyBtn.innerText = originalText;
                copyBtn.style.color = 'var(--text-main)';
                copyBtn.style.borderColor = 'var(--glass-border)';
            }, 2000);
        });
    });

    if (generateBtn) {
        generateBtn.click();
    }
});

// ============================================================
// Тестирование и опросы
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    if (typeof testsData === 'undefined' || !testsData) return;

    const container = document.getElementById('polls-container');
    const overlay = document.getElementById('quiz-overlay');
    const content = document.getElementById('quiz-content');
    const closeBtn = document.getElementById('quiz-close');

    if (!container || !overlay) return;

    let currentTest = null;
    let userName = '';
    let currentStep = 0;
    let score = 0;

    if (testsData.length === 0) {
        container.innerHTML = '<p style="color: var(--text-muted)">Нет доступных опросов.</p>';
    } else {
        testsData.forEach(test => {
            const card = document.createElement('div');
            card.className = 'poll-card glass-panel';
            card.innerHTML = `
                <div>
                    <div class="poll-title">${test.name}</div>
                    <div class="poll-desc">${test.description}</div>
                </div>
                <button class="poll-btn" data-test-id="${test.id}">Пройти опрос</button>
            `;
            card.querySelector('.poll-btn').addEventListener('click', () => startTest(test.id));
            container.appendChild(card);
        });
    }

    function startTest(testId) {
        currentTest = testsData.find(t => t.id == testId);
        if (!currentTest) return;
        userName = '';
        currentStep = 0;
        score = 0;
        renderStep();
        overlay.classList.add('active');
    }

    closeBtn.addEventListener('click', () => overlay.classList.remove('active'));

    function renderStep() {
        if (currentStep === 0) {
            content.innerHTML = `
                <div class="quiz-step active">
                    <h2 style="margin-top:0">Представьтесь</h2>
                    <p style="color: var(--text-muted)">Укажите ФИО для сохранения результата</p>
                    <input type="text" id="quiz-name" class="quiz-input" placeholder="Иванов Иван">
                    <button class="poll-btn" style="width:100%" id="quiz-start-btn">Начать тест</button>
                </div>
            `;
            document.getElementById('quiz-start-btn').addEventListener('click', saveName);
            return;
        }

        const qIndex = currentStep - 1;

        if (qIndex < currentTest.questions.length) {
            const q = currentTest.questions[qIndex];
            let answersHtml = q.answers.map((ans, idx) =>
                `<button class="answer-btn" data-idx="${idx}" data-correct="${q.correct}">${ans}</button>`
            ).join('');

            content.innerHTML = `
                <div class="quiz-step active">
                    <div style="font-size:12px; color:#4facfe; margin-bottom:8px;">Вопрос ${qIndex + 1} из ${currentTest.questions.length}</div>
                    <h3 style="margin-top:0">${q.question}</h3>
                    <div style="margin-top: 20px;">${answersHtml}</div>
                </div>
            `;
            content.querySelectorAll('.answer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    checkAnswer(parseInt(this.dataset.idx), parseInt(this.dataset.correct));
                });
            });
        } else {
            const total = currentTest.questions.length;
            content.innerHTML = `
                <div class="quiz-step active" style="text-align:center;">
                    <h2 style="margin-top:0">Тест завершен</h2>
                    <div class="result-circle">${score} / ${total}</div>
                    <p style="color: var(--text-muted); margin-bottom:24px;">Ваш результат успешно сохранен.</p>
                    <button class="poll-btn" id="quiz-finish-btn">Закрыть</button>
                </div>
            `;
            document.getElementById('quiz-finish-btn').addEventListener('click', () => overlay.classList.remove('active'));
            sendResult(userName, currentTest.id, currentTest.name, score, total);
        }
    }

    window.saveName = saveName;
    function saveName() {
        const nameInput = document.getElementById('quiz-name');
        if (!nameInput) return;
        const nameVal = nameInput.value.trim();
        if (nameVal === '') { alert('Пожалуйста, введите имя'); return; }
        userName = nameVal;
        currentStep++;
        renderStep();
    }

    window.checkAnswer = checkAnswer;
    function checkAnswer(selected, correct) {
        if (selected === correct) score++;
        currentStep++;
        renderStep();
    }

    function sendResult(name, testId, testName, score, total) {
        const formData = new FormData();
        formData.append('action', 'ib_save_poll_result');
        formData.append('nonce', ibAjax.nonce);
        formData.append('name', name);
        formData.append('testName', testName);
        formData.append('score', score + ' из ' + total);

        fetch(ibAjax.ajaxurl, {
            method: 'POST',
            body: formData
        }).then(res => res.text()).then(data => console.log('Результат сохранен:', data));
    }
});