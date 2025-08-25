<?php
namespace Models;
require_once 'Model.php';
class UserModel extends Model {
    // Получение всех пользователей
    public function getAllUsers() {
        $sql = "SELECT id, name, surname, email, message FROM users";
        $result = $this->conn->query($sql);

        if (!$result) {
            $this->respond(false, null, 'Ошибка БД: ' . $this->conn->error);
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        $this->respond(true, ['users' => $users]);
    }

    // Получение одного пользователя
    public function getUserById($id) {
        $sql = "SELECT id, name, surname, email, message FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$this->hasResults($result)) {
            $this->respond(false, null, 'Пользователь не найден');
        }

        $user = $result->fetch_assoc();
        $this->respond(true, ['user' => $user]);
    }

    // Добавление пользователя
    public function createUser($name, $surname, $email, $message) {
        if (!$name || !$surname || !$email) {
            $this->respond(false, null, 'Не указаны обязательные поля');
        }

        $sql = "INSERT INTO users (name, surname, email, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $surname, $email, $message);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Ошибка добавления пользователя');
        }
    }

    // Обновление пользователя
    public function updateUser($id, $name, $surname, $email, $message) {
        $sql = "UPDATE users SET name = ?, surname = ?, email = ?, message = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $surname, $email, $message, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Не удалось обновить пользователя');
        }
    }

    // Удаление пользователя
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Пользователь не найден');
        }
    }

    
}