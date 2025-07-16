<?php

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
            echo json_encode(['success' => false, 'error' => 'Ошибка подключения к базе данных: ' . $this->conn->connect_error]);
            exit();
        }
    }

    public function PostRequest() {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['name']) || !isset($data['comment'])) {
            exit();
        }

        $name = $data['name'];
        $comment = $data['comment'];

        $sql = "INSERT INTO reviews (name, comment) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подготовки запроса: ' . $this->conn->error]);
            exit();
        }

        $stmt->bind_param("ss", $name, $comment);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка выполнения запроса: ' . $stmt->error]);
            exit();
        }

        $stmt->close();
        echo json_encode(['success' => true]);
    }

    public function GetRequest() {
        $sql = "SELECT name, comment FROM reviews";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка при получении отзывов: ' . $this->conn->error]);
            exit();
        }

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($reviews, JSON_UNESCAPED_UNICODE);
    }
}

$method = $_GET['action'] ?? '';

switch ($method) {
    case 'postReview':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Model();
            $model->PostRequest();
        } 
        break;

    case 'getReviews':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $model = new Model();
            $model->GetRequest();
        }
        break;

}
?>