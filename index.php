<?php
require_once __DIR__ . '/Controllers/MainController.php';
require_once __DIR__ . '/Models/Model.php';
require_once __DIR__ . '/Models/UserModel.php';
require_once __DIR__ . '/Models/ReviewModel.php';

if (isset($_GET['action'])) {
    require_once __DIR__ . '/Controllers/MainController.php';
    $controller = new Controllers\MainController();
    $controller->handleRequest();
}
//если есть параметр action то открывается контроллер обработчик, если action нет то открывается разметка страницы
else {
    require_once 'Controllers/IndexController.php';
    (new Controllers\IndexController())->View();
}
