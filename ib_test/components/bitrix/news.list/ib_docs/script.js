// Битрикс сам подгрузит этот файл к компоненту
document.addEventListener('DOMContentLoaded', () => {
    const headers = document.querySelectorAll('.accordion-header');
    
    headers.forEach(header => {
        header.addEventListener('click', () => {
            const currentItem = header.closest('.accordion-item');
            
            // Если хотим, чтобы при открытии одного закрывались другие (как настоящий аккордеон)
            /*
            document.querySelectorAll('.accordion-item').forEach(item => {
                if(item !== currentItem) item.classList.remove('active');
            });
            */
            
            // Переключаем класс (CSS Grid сделает всю анимацию сам)
            currentItem.classList.toggle('active');
        });
    });
});