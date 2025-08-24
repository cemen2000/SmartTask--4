<?php
namespace Controllers;
class UserController {
    private $model;
    public function __construct() {
        $this->model = new \Models\UserModel(); 
    }

    public function getUsers() {
        $this->model->getAllUsers();
    }

    public function getUser($id) {
        $this->model->getUserById($id);
    }

    public function createUser($name, $surname, $email, $message) {
        $this->model->createUser($name, $surname, $email, $message);
    }

    public function updateUser($id, $name, $surname, $email, $message) {
        $this->model->updateUser($id, $name, $surname, $email, $message);
    }

    public function deleteUser($id) {
        $this->model->deleteUser($id);
    }
}