document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('create-article-form');
    const title = document.getElementById('title');
    const content = document.getElementById('content');
    const category = document.getElementById('category');
    const errorBox = document.getElementById('form-errors');

    form.addEventListener('submit', function (e) {
        let errors = [];

        const titleVal = title.value.trim();
        const contentVal = content.value.trim();
        const categoryVal = category.value;

        // Проверка заголовка
        if (titleVal === '') {
            errors.push('Введите заголовок статьи.');
        } else if (titleVal.length > 255) {
            errors.push('Заголовок не должен превышать 255 символов.');
        }

        // Проверка содержимого
        if (contentVal.length < 20) {
            errors.push('Содержимое должно быть не менее 20 символов.');
        }

        // Проверка категории
        if (categoryVal === '') {
            errors.push('Выберите категорию.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerHTML = '<ul style="color: red;"><li>' + errors.join('</li><li>') + '</li></ul>';
        }
    });
});
