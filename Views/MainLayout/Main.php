<?php
namespace MainLayout;
class Main {
    // загрузка всего HTML контента требуемого на основной странице, формы с редактированием отзывов и пользователей открываются отдельно
    public function renderContent() {
        require_once "Views/MainLayout/header.php";
        require_once "Views/MainLayout/fio.php";
        require_once "Views/MainLayout/order.php";
        require_once "Views/ReviewLayout/reviews.php";
        require_once "Views/ReviewLayout/makeReview.php";
        require_once "Views/UserLayout/UserList.php";
        require_once "Views/MainLayout/footer.php";
    }

    public function View() {
        $this->renderContent();
    }
}
