<?php
if (isset($_GET['action'])) {
    require_once 'Controllers/MainController.php';
    ($controller = new MainController());
    $controller->handleRequest();
} else {
    require_once 'Views/MainLayout/Main.php';
    (new MainLayout\Main())->View();
}