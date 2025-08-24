<?php
require_once 'Models/Model.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new Model();
    }

    public function getUsers() {
        $this->model->getUsers();
    }

    public function getUser($id) {
        $this->model->getUser($id);
    }

    public function createUser($name, $surname, $email, $message) {
        $this->model->createUser($name, $surname, $email, $message);
    }

    public function updateUser($id, $name, $surname, $email, $message) {
    if (!$name || !$surname || !$email || !$message) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Не все поля заполнены']);
        exit;
    }

    $this->model->updateUser($id, $name, $surname, $email, $message);

    http_response_code(200);
    echo json_encode(['success' => true]);
}

    public function deleteUser($id) {
        $this->model->deleteUser($id);
    }
}