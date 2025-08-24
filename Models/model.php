<?php
namespace Models;
use mysqli;
class Model {
    private $host = 'localhost';
    private $dbname = 'php_project';
    private $username = 'root';
    private $password = '12345678';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подключения к БД: ' . $this->conn->connect_error]);
            exit();
        }
    }

    public function PostRequest($name, $comment) {

    if (!$name || !$comment) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Не указаны имя или отзыв']);
        exit;
    }

    $sql = "INSERT INTO reviews (name, comment) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Ошибка подготовки запроса: ' . $this->conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $name, $comment);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Ошибка выполнения: ' . $stmt->error]);
        exit;
    }

    $stmt->close();
    echo json_encode(['success' => true]);
    exit;
}

    public function GetRequest() {
        $sql = "SELECT id, name, comment FROM reviews";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка БД: ' . $this->conn->error]);
            exit();
        }

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'reviews' => $reviews], JSON_UNESCAPED_UNICODE);
        exit;
    }

  public function getOne($id) {
    $sql = "SELECT id, name, comment FROM reviews WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Отзыв не найден']);
        return;
    }

    $review = $result->fetch_assoc();
    echo json_encode(['success' => true, 'review' => $review]);
    exit;
}

    public function update($id, $name, $comment) {
        $sql = "UPDATE reviews SET name = ?, comment = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $comment, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Не удалось обновить']);
            exit;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Отзыв не найден']);
            exit;
        }
    }

    public function getUsers() {
        $sql = "SELECT id, name, surname, email, message FROM users";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка БД: ' . $this->conn->error]);
            exit();
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    }

   public function getUser($id) {
    $sql = "SELECT id, name, surname, email, message FROM users WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Пользователь не найден']);
        return;
    }

    $user = $result->fetch_assoc();
    echo json_encode(['success' => true, 'user' => $user]);
    exit;
}

    public function createUser($name, $surname, $email, $message) {
        $sql = "INSERT INTO users (name, surname, email, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $surname, $email, $message);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка добавления']);
            exit;
        }
    }

    public function updateUser($id, $name, $surname, $email, $message) {
        $sql = "UPDATE users SET name = ?, surname = ?, email = ?, message = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $surname, $email, $message, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Не удалось обновить']);
            exit;
        }
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Пользователь не найден']);
            exit;
        }
    }
}