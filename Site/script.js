// Загрузка статистики на главной странице
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
});

async function loadStats() {
    // Здесь будет запрос к PHP для получения статистики
    // Пока заглушки
    document.getElementById('total-apartments').textContent = '156';
    document.getElementById('total-debt').textContent = '45,230 руб';
}

// Поиск собственников
function searchOwners() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#ownersTable tr');
    
    rows.forEach((row, index) => {
        if (index === 0) return; // Пропускаем заголовок
        
        const text = row.textContent.toLowerCase();
        if (text.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}