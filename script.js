document.getElementById('contact-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const secondname = document.getElementById('second name').value.trim();
    const message = document.getElementById('message').value.trim();
    
    
    if (name.length < 3) {
    Swal.fire({
        icon: 'error',
        title: 'Ошибка валидации',
        text: 'Имя должно быть не менее 3 символов',
        confirmButtonText: 'Понятно'
    });
    return;
}

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Ошибка валидации',
            text: 'Введите корректный email',
            confirmButtonText: 'Понятно'
        });
    }

    if (message.length < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Ошибка валидации',
            text: 'Сообщение должно быть заполнено',
            confirmButtonText: 'Понятно'
        });
        return;
    }
    fetch('http://127.0.0.1', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name,
            email,
            message
        })
    })

    .then(response => response.json())
    .then(data => {
        console.log('Отправлено:', data);
        Swal.fire({
            icon: 'success',
            title: 'Форма успешно отправлена!',
            confirmButtonText: 'OK'
        });
    })

   .catch(error => {
    console.error('Ошибка:', error);
    Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: `Произошла ошибка при отправке формы: ${error.message}`,
        confirmButtonText: 'Понятно'
    });
});
});

        
document.getElementById('order-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const terms = document.getElementById('terms').checked;
    const customername = document.getElementById('customer-name').value.trim();
    const address = document.getElementById('delivery-address').value.trim();
    const comment = document.getElementById('comment').value.trim();
    if (!customername || !terms) {
         Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: `Нужно обязательн заполнить имя и подтвердить условия`,
        confirmButtonText: 'Понятно'
         })
        return;
    }
    Swal.fire({
        title: 'Создание заказа',
        text: `Имя: ${customername} Адресс доставки: ${address}, Комментарий к заказу: ${comment}`,
        icon: 'success',
        confirmButtonText: 'OK'
    });
});
function fetchReviews() {
    fetch('/model.php?action=getReviews')
        .then(response => {
            return response.json();
        })
        .then(data => {
            const reviewContainer = document.querySelector('.review-container');
            reviewContainer.innerHTML = '';
            if (Array.isArray(data)) {
                data.forEach(item => {
                    const reviewElement = createReview(item.name, item.comment);
                    reviewContainer.appendChild(reviewElement);
                });
            } 
        })
}

function createReview(name, comment) {
    const reviewElement = document.createElement('div');
    reviewElement.classList.add('review');

    reviewElement.innerHTML = `
        <div>
            <h3>${name}</h3>
            <p>${comment}</p>
        </div>
    `;
    return reviewElement;
}

fetchReviews();

const reviewButton = document.getElementById('add-review-button');
const reviewFormContainer = document.getElementById('create-container');

reviewButton.addEventListener('click', function() {
    reviewFormContainer.style.display = 'block';
});
document.getElementById('make-form').addEventListener('submit', function(event) {
    event.preventDefault(); 
    const name = document.getElementById('NameOwnReview').value.trim();
    const comment = document.getElementById('OwnReview').value.trim();

     if (!name || !comment) {
         Swal.fire({
        icon: 'error',
        title: 'Ошибка',
        text: `Заполните ваше имя и напишите отзыв`,
        confirmButtonText: 'Понятно'
         })
        return;
    }
    Swal.fire({
        title: 'Отзыв успешно сохранен',
        text: `Имя: ${name} Отзыв: ${comment}`,
        icon: 'success',
        confirmButtonText: 'OK'
    });
            document.getElementById('NameOwnReview').value = '';
            document.getElementById('OwnReview').value = '';

            fetchReviews();

    fetch('/model.php?action=postReview', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, comment })
    })

    .then(response => response.json())
});