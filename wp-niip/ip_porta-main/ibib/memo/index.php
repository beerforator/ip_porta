<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Памятка сотрудника");
?>

<style>
.memo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--space-lg);
    margin-top: var(--space-lg);
}
.memo-card {
    padding: var(--space-lg);
    border-top: 4px solid #4facfe; /* Яркий акцент сверху карточки */
}
.memo-icon {
    width: 64px; height: 64px;
    background: rgba(43, 116, 255, 0.1);
    border-radius: 16px; display: flex; justify-content: center; align-items: center;
    margin-bottom: var(--space-md);
}
.memo-title { font-size: 22px; font-weight: bold; margin-bottom: 16px; color: var(--text-main); }
.memo-list { padding-left: 20px; color: var(--text-muted); line-height: 1.8; margin: 0; }
.memo-list li::marker { color: #4facfe; font-size: 1.2em; }
</style>

<div class="glass-panel" style="padding: var(--space-lg); margin-top: var(--space-lg); display: flex; align-items: center; gap: 24px;">
    <div style="font-size: 48px;">🛑</div>
    <div>
        <h2 style="margin: 0 0 8px 0;">Главное правило информационной безопасности</h2>
        <p style="margin: 0; color: var(--text-muted); font-size: 16px;">
            Информационная безопасность — это ответственность каждого сотрудника. Любой инцидент может привести к финансовым и репутационным потерям компании. Если вы сомневаетесь в безопасности действия — обратитесь в отдел ИБ!
        </p>
    </div>
</div>

<div class="memo-grid">
    <!-- Карточка 1 -->
    <div class="memo-card glass-panel">
        <div class="memo-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4facfe" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
        <div class="memo-title">Пароли и Учетные записи</div>
        <ul class="memo-list">
            <li>Длина пароля должна быть не менее 12 символов.</li>
            <li>Не используйте один и тот же пароль для личных и рабочих сервисов.</li>
            <li>Блокируйте экран компьютера при отходе от рабочего места (Win + L).</li>
            <li>Никогда не передавайте свои логины и пароли коллегам.</li>
        </ul>
    </div>

    <!-- Карточка 2 -->
    <div class="memo-card glass-panel" style="border-top-color: #a5b4fc;">
        <div class="memo-icon" style="background: rgba(165, 180, 252, 0.1);">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#a5b4fc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        </div>
        <div class="memo-title">Электронная почта и Фишинг</div>
        <ul class="memo-list">
            <li>Проверяйте адрес отправителя. Мошенники часто меняют одну букву в домене.</li>
            <li>Не открывайте вложения от неизвестных адресатов (особенно .exe, .zip, .doc с макросами).</li>
            <li>Не переходите по подозрительным ссылкам, требующим ввести пароль.</li>
        </ul>
    </div>

    <!-- Карточка 3 -->
    <div class="memo-card glass-panel" style="border-top-color: #00f2fe;">
        <div class="memo-icon" style="background: rgba(0, 242, 254, 0.1);">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#00f2fe" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
        </div>
        <div class="memo-title">Носители информации (Флешки)</div>
        <ul class="memo-list">
            <li>Запрещено подключать личные флешки к рабочим компьютерам.</li>
            <li>Если вы нашли флешку в офисе, не вставляйте её в ПК, отнесите в отдел ИБ.</li>
            <li>Конфиденциальные документы должны передаваться только по защищенным корпоративным каналам.</li>
        </ul>
    </div>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>