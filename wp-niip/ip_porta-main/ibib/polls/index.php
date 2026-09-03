<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование и опросы");
\Bitrix\Main\Loader::includeModule('iblock');

$POLLS_IBLOCK_ID = 20; // ЗАМЕНИШЬ ПОТОМ НА РЕАЛЬНЫЙ ID
$testsData = [];

// 1. Получаем Тесты (Разделы)
if ($POLLS_IBLOCK_ID > 0) {
    $rsSections = CIBlockSection::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $POLLS_IBLOCK_ID, 'ACTIVE' => 'Y']);
    while ($arSection = $rsSections->Fetch()) {
        $testsData[$arSection['ID']] = [
            'id' => $arSection['ID'],
            'name' => $arSection['NAME'],
            'description' => $arSection['DESCRIPTION'] ?: 'Пройдите тестирование для проверки знаний.',
            'questions' => []
        ];
    }

    // 2. Получаем Вопросы (Элементы)
    $rsElements = CIBlockElement::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $POLLS_IBLOCK_ID, 'ACTIVE' => 'Y']);
    while ($ob = $rsElements->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $sectionId = $arFields['IBLOCK_SECTION_ID'];
        
        if (isset($testsData[$sectionId])) {
            $testsData[$sectionId]['questions'][] = [
                'question' => $arFields['NAME'],
                'answers' => is_array($arProps['ANSWERS']['VALUE']) ? $arProps['ANSWERS']['VALUE'] : [$arProps['ANSWERS']['VALUE']],
                'correct' => (int)$arProps['CORRECT_INDEX']['VALUE']
            ];
        }
    }
}
// Убираем тесты без вопросов
$testsData = array_values(array_filter($testsData, function($t) { return count($t['questions']) > 0; }));
?>

<style>
/* Сетка карточек */
.polls-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}
.poll-card {
    padding: var(--space-lg);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    transition: transform 0.3s;
}
.poll-card:hover { transform: translateY(-5px); border-color: #4facfe; }
.poll-title { font-size: 20px; font-weight: bold; margin-bottom: var(--space-sm); color: var(--text-main); }
.poll-desc { color: var(--text-muted); font-size: 14px; margin-bottom: var(--space-lg); line-height: 1.5; }
.poll-btn {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none; padding: 10px 24px; border-radius: 8px; color: #fff; font-weight: bold; cursor: pointer; transition: 0.3s;
}
.poll-btn:hover { box-shadow: 0 0 15px rgba(79,172,254,0.5); }

/* Модальное окно (Glassmorphism) */
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(8px);
    display: flex; justify-content: center; align-items: center;
    opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
    z-index: 1000;
}
.modal-overlay.active { opacity: 1; pointer-events: all; }
.poll-modal {
    width: 100%; max-width: 500px; padding: var(--space-lg);
    position: relative; transform: translateY(20px); transition: transform 0.3s ease;
}
.modal-overlay.active .poll-modal { transform: translateY(0); }
.modal-close {
    position: absolute; top: 16px; right: 16px; background: transparent; border: none;
    color: var(--text-muted); font-size: 24px; cursor: pointer;
}
.modal-close:hover { color: #fff; }

/* Элементы внутри модалки */
.quiz-step { display: none; }
.quiz-step.active { display: block; animation: fadeIn 0.4s; }
@keyframes fadeIn { from { opacity: 0; transform: translateX(10px); } to { opacity: 1; transform: translateX(0); } }

.quiz-input {
    width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border);
    border-radius: 8px; color: #fff; font-size: 16px; margin: 16px 0; box-sizing: border-box; outline: none;
}
.quiz-input:focus { border-color: #4facfe; }

.answer-btn {
    display: block; width: 100%; padding: 14px; margin-bottom: 12px; text-align: left;
    background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px;
    color: #fff; cursor: pointer; transition: 0.2s; font-size: 15px;
}
.answer-btn:hover { background: rgba(43, 116, 255, 0.2); border-color: #4facfe; }

.result-circle {
    width: 120px; height: 120px; border-radius: 50%; border: 4px solid #4facfe;
    display: flex; justify-content: center; align-items: center; margin: 0 auto 24px auto;
    font-size: 32px; font-weight: bold; color: #4facfe; box-shadow: 0 0 20px rgba(79,172,254,0.3);
}
</style>

<div class="polls-grid" id="polls-container">
    <!-- Карточки сгенерируются через JS -->
</div>

<!-- Модальное окно тестирования -->
<div class="modal-overlay" id="quiz-overlay">
    <div class="poll-modal glass-panel">
        <button class="modal-close" id="quiz-close">&times;</button>
        <div id="quiz-content">
            <!-- Шаги генерируются динамически -->
        </div>
    </div>
</div>

<script>
// Передаем данные из PHP в JS
const testsData = <?=json_encode($testsData)?>;

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('polls-container');
    const overlay = document.getElementById('quiz-overlay');
    const content = document.getElementById('quiz-content');
    const closeBtn = document.getElementById('quiz-close');
    
    let currentTest = null;
    let userName = "";
    let currentStep = 0;
    let score = 0;

    // 1. Отрисовка карточек на странице
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
                <button class="poll-btn" onclick="startTest(${test.id})">Пройти опрос</button>
            `;
            container.appendChild(card);
        });
    }

    // 2. Логика запуска теста
    window.startTest = (testId) => {
        currentTest = testsData.find(t => t.id == testId);
        userName = ""; currentStep = 0; score = 0;
        renderStep();
        overlay.classList.add('active');
    };

    closeBtn.onclick = () => overlay.classList.remove('active');

    // 3. Рендер текущего шага модалки
    function renderStep() {
        // Шаг 0: Ввод имени
        if (currentStep === 0) {
            content.innerHTML = `
                <div class="quiz-step active">
                    <h2 style="margin-top:0">Представьтесь</h2>
                    <p style="color: var(--text-muted)">Укажите ФИО для сохранения результата</p>
                    <input type="text" id="quiz-name" class="quiz-input" placeholder="Иванов Иван">
                    <button class="poll-btn" style="width:100%" onclick="saveName()">Начать тест</button>
                </div>
            `;
            return;
        }

        const qIndex = currentStep - 1;
        
        // Шаг с вопросом
        if (qIndex < currentTest.questions.length) {
            const q = currentTest.questions[qIndex];
            let answersHtml = q.answers.map((ans, idx) => 
                `<button class="answer-btn" onclick="checkAnswer(${idx}, ${q.correct})">${ans}</button>`
            ).join('');

            content.innerHTML = `
                <div class="quiz-step active">
                    <div style="font-size:12px; color:#4facfe; margin-bottom:8px;">Вопрос ${qIndex + 1} из ${currentTest.questions.length}</div>
                    <h3 style="margin-top:0">${q.question}</h3>
                    <div style="margin-top: 20px;">${answersHtml}</div>
                </div>
            `;
        } else {
            // Финал теста (Результаты)
            const total = currentTest.questions.length;
            content.innerHTML = `
                <div class="quiz-step active" style="text-align:center;">
                    <h2 style="margin-top:0">Тест завершен</h2>
                    <div class="result-circle">${score} / ${total}</div>
                    <p style="color: var(--text-muted); margin-bottom:24px;">Ваш результат успешно сохранен.</p>
                    <button class="poll-btn" onclick="document.getElementById('quiz-overlay').classList.remove('active')">Закрыть</button>
                </div>
            `;
            sendResult(userName, currentTest.id, currentTest.name, score, total);
        }
    }

    // Обработчики кнопок
    window.saveName = () => {
        const nameInput = document.getElementById('quiz-name').value.trim();
        if(nameInput === "") { alert("Пожалуйста, введите имя"); return; }
        userName = nameInput;
        currentStep++;
        renderStep();
    };

    window.checkAnswer = (selected, correct) => {
        if(selected === correct) score++;
        currentStep++;
        renderStep();
    };

    // 4. Отправка результата на сервер (AJAX)
    function sendResult(name, testId, testName, score, total) {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('testName', testName);
        formData.append('score', `${score} из ${total}`);

        fetch('/ib-test/polls/ajax.php', {
            method: 'POST',
            body: formData
        }).then(res => res.text()).then(data => console.log('Результат сохранен:', data));
    }
});
</script>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>