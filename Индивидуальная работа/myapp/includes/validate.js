function validateForm() {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;

    if (title === "" && category === "") {
        alert("Пожалуйста, заполните хотя бы одно поле для поиска.");
        return false; // Останавливает отправку формы
    }
    return true;
}
