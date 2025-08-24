<?php
namespace Models;
class Model {
    protected $conn;
    private $host = 'localhost';
    private $dbname = 'php_project';
    private $username = 'root';
    private $password = '12345678';

    public function __construct() {
        $this->conn = new \mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Ошибка подключения к БД: ' . $this->conn->connect_error]);
            exit();
        }
    }

    // Вспомогательный метод: отправка JSON и выход
    protected function respond($success, $data = null, $error = null) {
        http_response_code($success ? 200 : 500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'error' => $error
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Проверка, есть ли результат
    protected function hasResults($result) {
        return $result && $result->num_rows > 0;
    }
}