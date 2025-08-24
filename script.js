//ЗАГРУЗКА ДАННЫХ
async function loadItems(type, containerSelector, action) {
    const container = document.querySelector(containerSelector);
    if (!container) return;

    try {
        const res = await fetch(`/index.php?action=${action}`);
        const data = await res.json();

        if (!data.success || !data.data || !Array.isArray(data.data[type])) return;

        container.innerHTML = '';
        data.data[type].forEach(item => {
            const id = parseInt(item.id);
            if (isNaN(id)) return;
            container.appendChild(createItem(item, type, id));
        });
    } catch (err) {
        console.error(`Ошибка загрузки ${type}:`, err);
    }
}

//ПЕРВАЯ ФОРМА(КОНТАКТ)
document.getElementById('contact-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('name')?.value.trim();
    const email = document.getElementById('email')?.value.trim();
    const secondname = document.getElementById('second name')?.value.trim();
    const message = document.getElementById('message')?.value.trim();

    if (name.length < 3) {
        Swal.fire({ icon: 'error', title: 'Ошибка валидации', text: 'Имя должно быть не менее 3 символов', confirmButtonText: 'Понятно' });
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({ icon: 'error', title: 'Ошибка валидации', text: 'Введите корректный email', confirmButtonText: 'Понятно' });
        return;
    }

    if (!message) {
        Swal.fire({ icon: 'error', title: 'Ошибка валидации', text: 'Сообщение должно быть заполнено', confirmButtonText: 'Понятно' });
        return;
    }

    try {
        const res = await fetch('http://127.0.0.1', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, message })
        });

        const data = await res.json();
        Swal.fire({ icon: 'success', title: 'Форма успешно отправлена!', confirmButtonText: 'OK' });
    } catch (error) {
        console.error('Ошибка:', error);
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: `Произошла ошибка при отправке формы: ${error.message}`,
            confirmButtonText: 'Понятно'
        });
    }
});

//ВТОРАЯ ФОРМА (ЗАКАЗ)
document.getElementById('order-form')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const customername = document.getElementById('customer-name')?.value.trim();
    const address = document.getElementById('delivery-address')?.value.trim();
    const comment = document.getElementById('comment')?.value.trim();
    const terms = document.getElementById('terms')?.checked;

    if (!customername || !terms) {
        Swal.fire({
            icon: 'error',
            title: 'Ошибка',
            text: 'Нужно обязательно заполнить имя и подтвердить условия',
            confirmButtonText: 'Понятно'
        });
        return;
    }

    Swal.fire({
        title: 'Создание заказа',
        text: `Имя: ${customername}, Адресс доставки: ${address}, Комментарий к заказу: ${comment}`,
        icon: 'success',
        confirmButtonText: 'OK'
    });
});

//ТРЕТЬЯ ФОРМА (ОТЗЫВ)
document.getElementById('make-form')?.addEventListener('submit', async e => {
    e.preventDefault();

    const name = document.getElementById('NameOwnReview')?.value.trim();
    const comment = document.getElementById('OwnReview')?.value.trim();

    if (!name || !comment) {
        Swal.fire('Ошибка', 'Заполните все поля', 'error');
        return;
    }

    try {
        const res = await fetch('/index.php?action=postReview', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, comment })
        });

        const data = await res.json();
        if (data.success) {
            Swal.fire('Успех', 'Отзыв добавлен', 'success');
            e.target.reset();
            loadItems('reviews', '.review-container', 'getReviews');
        } else {
            Swal.fire('Ошибка', data.error || 'Не удалось добавить', 'error');
        }
    } catch (err) {
        Swal.fire('Ошибка', 'Сетевая ошибка', 'error');
    }
});

//ЧЕТВЕРТАЯ ФОРМА (ПОЛЬЗОВАТЕЛИ)
document.addEventListener('submit', async function(e) {
    if (e.target && e.target.id === 'create-user-form') {
        e.preventDefault();

        const name = e.target.querySelector('[name="name"]')?.value.trim();
        const surname = e.target.querySelector('[name="surname"]')?.value.trim();
        const email = e.target.querySelector('[name="email"]')?.value.trim();
        const message = e.target.querySelector('[name="message"]')?.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!name || !surname || !email || !message) {
            Swal.fire('Ошибка', 'Вы должны заполнить все поля', 'error');
            return;
        }

        if (!emailRegex.test(email)) {
            Swal.fire('Ошибка', 'Введите корректный email', 'error');
            return;
        }

        try {
            const res = await fetch('/index.php?action=createUser', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, surname, email, message })
            });

            const data = await res.json();
            if (data.success) {
                Swal.fire('Успех', 'Пользователь добавлен', 'success');
                e.target.reset();
                document.getElementById('create-user-container').style.display = 'none';
                loadItems('users', '.users-list', 'getUsers');
            } else {
                Swal.fire('Ошибка', data.error || 'Не удалось добавить', 'error');
            }
        } catch (err) {
            Swal.fire('Ошибка', 'Сетевая ошибка', 'error');
        }
    }
});

//СОЗДАНИЕ ЭЛЕМЕНТА (ДОБАВЛЕНИЕ КОММЕНТАРИЯ ИЛИ ПОЛЬЗОВАТЕЛЯ)
function createItem(item, type, id) {
    const el = document.createElement('div');
    el.classList.add(type === 'reviews' ? 'review' : 'user');

    let html = `<h${type === 'reviews' ? '3' : '4'}>${item.name} ${type === 'users' ? item.surname : ''}</h${type === 'reviews' ? '3' : '4'}>`;

    if (type === 'reviews') {
        html += `<p>${item.comment}</p>`;
    } else {
        html += `
            <p><strong>Email:</strong> ${item.email || '—'}</p>
            <p><strong>Сообщение:</strong> ${item.message || '—'}</p>
        `;
    }

    html += `
        <button class="editbtn" data-id="${id}" type="button">Редактировать</button>
        <button class="deletebtn" data-id="${id}" type="button">Удалить</button>
    `;

    el.innerHTML = html;
    return el;
}

// УДАЛЕНИЕ ЭЛЕМЕНТА (УДАЛЕНИЕ КОММЕНТАРИЯ ИЛИ ПОЛЬЗОВАТЕЛЯ)
async function deleteItem(id, type) {
    const action = type === 'reviews' ? 'delete' : 'deleteUser';
    const res = await fetch(`/index.php?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id })
    });

    const data = await res.json();
    if (data.success) {
        Swal.fire('Успешно', 'Элемент удалён', 'success');
        loadItems(type === 'reviews' ? 'reviews' : 'users', type === 'reviews' ? '.review-container' : '.users-list', type === 'reviews' ? 'getReviews' : 'getUsers');
    } else {
        Swal.fire('Ошибка', data.error || 'Не удалось удалить', 'error');
    }
}

async function editItem(id, type) {
    const action = type === 'reviews' ? 'getOne' : 'getUser';
    const res = await fetch(`/index.php?action=${action}&id=${id}`);
    const data = await res.json();

    if (!data.success) {
        Swal.fire('Ошибка', data.error || 'Не удалось загрузить данные', 'error');
        return;
    }

    let item;
    if (data.data && data.data[type === 'reviews' ? 'review' : 'user']) {
        item = data.data[type === 'reviews' ? 'review' : 'user'];
    } else if (data[type === 'reviews' ? 'review' : 'user']) {
        item = data[type === 'reviews' ? 'review' : 'user'];
    } else {
        console.error('Не найден элемент для редактирования:', data);
        Swal.fire('Ошибка', 'Не удалось загрузить данные', 'error');
        return;
    }

    const parent = document.querySelector(`[data-id="${id}"]`).closest('.review, .user');

    // Загрузка формы
    const formRes = await fetch(`/Views/${type === 'reviews' ? 'ReviewLayout/EditReview.php' : 'UserLayout/UserEdit.php'}`);
    const html = await formRes.text();
    const temp = document.createElement('div');
    temp.innerHTML = html;
    const form = temp.querySelector('form');

    // Подстановка полей
    const idInput = form.querySelector('[name="id"]');
    if (idInput) {
        idInput.value = item.id;
    }

    form.querySelector('[name="name"]').value = item.name;

    if (type === 'users') {
        form.querySelector('[name="surname"]').value = item.surname;
        form.querySelector('[name="email"]').value = item.email;
        form.querySelector('[name="message"]').value = item.message || '';
    } else {
        form.querySelector('[name="comment"]').value = item.comment;
    }

    parent.replaceWith(form);

    // Отправка
    form.onsubmit = async e => {
        e.preventDefault();
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());
        const res = await fetch(`/index.php?action=${type === 'reviews' ? 'update' : 'updateUser'}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        if (result.success) {
            Swal.fire('Готово', 'Обновлено', 'success');
            loadItems(type === 'reviews' ? 'reviews' : 'users', 
                      type === 'reviews' ? '.review-container' : '.users-list', 
                      type === 'reviews' ? 'getReviews' : 'getUsers');
        } else {
            Swal.fire('Ошибка', result.error || 'Не удалось сохранить', 'error');
        }
    };
}

// ОБРАБОТКА УДАЛЕНИЯ И РЕДАКТИРОВАНИЯ
document.addEventListener('click', e => {
    const target = e.target;
    if (target.classList.contains('deletebtn')) {
        e.preventDefault();
        const id = parseInt(target.dataset.id);
        if (isNaN(id)) return;
        const type = target.closest('.review') ? 'reviews' : 'users';
        deleteItem(id, type);
    }

    if (target.classList.contains('editbtn')) {
        e.preventDefault();
        const id = parseInt(target.dataset.id);
        if (isNaN(id)) return;
        const type = target.closest('.review') ? 'reviews' : 'users';
        editItem(id, type);
    }
});

// ОТОБРАЖЕНИЕ ФОРМ
document.getElementById('add-review-button')?.addEventListener('click', () => {
    document.getElementById('create-container').style.display = 'block';
});

document.getElementById('add-user-button')?.addEventListener('click', async () => {
    const container = document.getElementById('create-user-container');
    try {
        const res = await fetch('/Views/UserLayout/UserCreate.php');
        const html = await res.text();
        container.innerHTML = html;
        container.style.display = 'block';
    } catch (err) {
        Swal.fire('Ошибка', 'Не удалось загрузить форму', 'error');
    }
});

// ЗАГРУЗКА ПРИ ОТКРЫВАНИИ
window.addEventListener('load', () => {
    if (document.querySelector('.review-container')) loadItems('reviews', '.review-container', 'getReviews');
    if (document.querySelector('.users-list')) loadItems('users', '.users-list', 'getUsers');
})