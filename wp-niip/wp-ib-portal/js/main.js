document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('documents-list');
    if (!list) return;

    const sections = [...list.querySelectorAll('.accordion-item')];
    const viewButtons = [...document.querySelectorAll('.view-switch button')];
    let savedView = 'grid';
    try { savedView = localStorage.getItem('ib-documents-view') || 'grid'; } catch {}
    function setView(view) {
        list.dataset.view = view === 'list' ? 'list' : 'grid';
        viewButtons.forEach(button => button.setAttribute('aria-pressed', String(button.dataset.view === list.dataset.view)));
        try { localStorage.setItem('ib-documents-view', list.dataset.view); } catch {}
    }
    setView(savedView);
    viewButtons.forEach(button => button.addEventListener('click', () => setView(button.dataset.view)));

    function setOpen(section, open) {
        section.classList.toggle('active', open);
        section.querySelector('.accordion-header').setAttribute('aria-expanded', String(open));
    }
    sections.forEach(section => {
        const header = section.querySelector('.accordion-header');
        header.addEventListener('click', () => {
            setOpen(section, !section.classList.contains('active'));
        });
    });

    const search = document.getElementById('documents-search');
    const empty = document.getElementById('documents-empty');
    search.addEventListener('input', () => {
        const term = search.value.trim().toLocaleLowerCase('ru');
        let matches = 0;
        sections.forEach(section => {
            const sectionMatch = section.dataset.sectionSearch.toLocaleLowerCase('ru').includes(term);
            let sectionCount = 0;
            section.querySelectorAll('.doc-row').forEach(row => {
                const visible = !term || sectionMatch || row.dataset.search.toLocaleLowerCase('ru').includes(term);
                row.hidden = !visible;
                if (visible) sectionCount++;
            });
            section.hidden = sectionCount === 0;
            matches += sectionCount;
            if (term && sectionCount) setOpen(section, true);
        });
        empty.hidden = matches !== 0;
    });

    const targetId = new URLSearchParams(location.search).get('doc');
    const target = targetId && /^\d+$/.test(targetId) ? document.getElementById(`document-${targetId}`) : null;
    if (target) {
        const section = target.closest('.accordion-item');
        setOpen(section, true);
        target.classList.add('doc-target');
        const fileUrl = target.dataset.fileUrl;
        if (fileUrl) {
            const preview = document.getElementById('document-preview');
            const link = document.getElementById('document-preview-link');
            const frame = document.getElementById('document-preview-frame');
            const note = document.getElementById('document-preview-note');
            preview.hidden = false;
            document.getElementById('document-preview-title').textContent = target.querySelector('.doc-name').textContent;
            link.href = fileUrl;
            const isPdf = target.dataset.fileMime === 'application/pdf' || /\.pdf($|[?#])/i.test(fileUrl);
            frame.hidden = !isPdf;
            note.hidden = isPdf;
            if (isPdf) frame.src = fileUrl;
        }
        setTimeout(() => target.scrollIntoView({ behavior: 'smooth', block: 'center' }), 180);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('news-search');
    if (!search) return;
    const cards = [...document.querySelectorAll('#news-list .ib-news-card')];
    const empty = document.getElementById('news-empty');
    search.addEventListener('input', () => {
        const term = search.value.trim().toLocaleLowerCase('ru');
        let found = 0;
        cards.forEach(card => {
            card.hidden = !card.dataset.search.toLocaleLowerCase('ru').includes(term);
            if (!card.hidden) found++;
        });
        empty.hidden = found !== 0;
    });
});

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

document.addEventListener('DOMContentLoaded', () => {
    if (typeof testsData === 'undefined' || !testsData) return;

    const container = document.getElementById('polls-container');
    const overlay = document.getElementById('quiz-overlay');
    const content = document.getElementById('quiz-content');
    const closeBtn = document.getElementById('quiz-close');

    if (!container || !overlay || !content) return;

    let currentTest = null;
    let userName = '';
    let currentStep = 0;
    let selectedAnswers = [];

    if (testsData.length === 0) {
        container.textContent = 'Нет доступных тестов.';
    }
    testsData.forEach(test => {
        const card = document.createElement('article');
        card.className = 'poll-card glass-panel';
        card.dataset.search = `${test.name} ${test.description}`.toLocaleLowerCase('ru');
        const title = document.createElement('h2');
        title.className = 'poll-title';
        title.textContent = test.name;
        const description = document.createElement('p');
        description.className = 'poll-desc';
        description.textContent = test.description.replace(/<[^>]*>/g, '');
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'poll-btn';
        button.textContent = 'Пройти тест';
        button.addEventListener('click', () => startTest(test));
        card.append(title, description, button);
        container.appendChild(card);
    });

    const search = document.getElementById('polls-search');
    search.addEventListener('input', () => {
        const term = search.value.trim().toLocaleLowerCase('ru');
        let found = 0;
        container.querySelectorAll('.poll-card').forEach(card => {
            card.hidden = !card.dataset.search.includes(term);
            if (!card.hidden) found++;
        });
        document.getElementById('polls-empty').hidden = found !== 0;
    });

    function startTest(test) {
        currentTest = test;
        userName = '';
        currentStep = 0;
        selectedAnswers = [];
        overlay.classList.add('active');
        renderStep();
    }

    closeBtn.addEventListener('click', () => overlay.classList.remove('active'));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') overlay.classList.remove('active');
    });

    function renderStep() {
        if (currentStep === 0) {
            content.innerHTML = '<div class="quiz-step active"><h2>Представьтесь</h2><p>Укажите ФИО для сохранения результата</p><input type="text" id="quiz-name" class="quiz-input" placeholder="Иванов Иван" maxlength="120"><button type="button" class="poll-btn" id="quiz-start-btn">Начать тест</button></div>';
            document.getElementById('quiz-start-btn').addEventListener('click', saveName);
            document.getElementById('quiz-name').addEventListener('keydown', event => { if (event.key === 'Enter') saveName(); });
            return;
        }

        const qIndex = currentStep - 1;
        if (qIndex < currentTest.questions.length) {
            const question = currentTest.questions[qIndex];
            content.innerHTML = '';
            const step = document.createElement('div');
            step.className = 'quiz-step active';
            const progress = document.createElement('div');
            progress.className = 'quiz-progress';
            progress.textContent = `Вопрос ${qIndex + 1} из ${currentTest.questions.length}`;
            const heading = document.createElement('h3');
            heading.textContent = question.question;
            step.append(progress, heading);
            question.answers.forEach((answer, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'answer-btn';
                button.textContent = answer;
                button.addEventListener('click', () => {
                    selectedAnswers.push(index);
                    currentStep++;
                    renderStep();
                });
                step.appendChild(button);
            });
            content.appendChild(step);
            return;
        }

        content.innerHTML = '<div class="quiz-step active"><h2>Сохраняем результат…</h2></div>';
        sendResult().then(result => {
            content.innerHTML = '<div class="quiz-step active quiz-finished"><h2>Тест завершён</h2><div class="result-circle"></div><p>Результат сохранён.</p><button type="button" class="poll-btn" id="quiz-finish-btn">Закрыть</button></div>';
            content.querySelector('.result-circle').textContent = `${result.score} / ${result.total}`;
            document.getElementById('quiz-finish-btn').addEventListener('click', () => overlay.classList.remove('active'));
        }).catch(error => {
            content.innerHTML = '<div class="quiz-step active"><h2>Не удалось сохранить результат</h2><p class="quiz-error"></p><button type="button" class="poll-btn" id="quiz-retry-btn">Повторить</button></div>';
            content.querySelector('.quiz-error').textContent = error.message;
            document.getElementById('quiz-retry-btn').addEventListener('click', renderStep);
        });
    }

    function saveName() {
        const nameVal = document.getElementById('quiz-name').value.trim();
        if (!nameVal) { alert('Пожалуйста, введите имя'); return; }
        userName = nameVal;
        currentStep = 1;
        renderStep();
    }

    async function sendResult() {
        const formData = new FormData();
        formData.append('action', 'ib_save_poll_result');
        formData.append('nonce', ibAjax.nonce);
        formData.append('name', userName);
        formData.append('testId', currentTest.id);
        formData.append('answers', JSON.stringify(selectedAnswers));
        const response = await fetch(ibAjax.ajaxurl, { method: 'POST', body: formData, credentials: 'same-origin' });
        let data;
        try { data = await response.json(); }
        catch (error) { throw new Error('Не удалось проверить запрос. Обновите страницу и повторите тест.'); }
        if (!response.ok || !data.success) throw new Error(data.data?.message || 'Проверьте соединение и попробуйте снова.');
        return data.data;
    }
});
