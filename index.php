<?php
if (isset($_GET['action'])) {
    require_once 'Controllers/MainController.php';
    $controller = new Controllers\MainController();
    $controller->handleRequest();
}
//если есть параметр action то открывается контроллер обработчик, если action нет то открывается
else {
    require_once 'Views/MainLayout/Main.php';
    (new MainLayout\Main())->View();
}