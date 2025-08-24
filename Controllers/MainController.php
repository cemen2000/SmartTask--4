<?php
require_once 'Controllers/UserController.php';
require_once 'Controllers/ReviewController.php';

class MainController {
    public function handleRequest() {
        header('Content-Type: application/json; charset=utf-8');

        $action = $_GET['action'] ?? '';

        switch ($action) {
            //Пользователи
            case 'getUsers':
                (new UserController())->getUsers();
                break;

            case 'getUser':
                $id = $_GET['id'] ?? null;
                if (!$id || !is_numeric($id)) {
                    $this->error(400, 'Неверный ID');
                    return;
                }
                (new UserController())->getUser($id);
                break;

            case 'createUser':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['name'], $data['surname'], $data['email'])) {
                    $this->error(400, 'Не указаны обязательные поля: имя, фамилия, email');
                    return;
                }
                (new UserController())->createUser(
                    $data['name'],
                    $data['surname'],
                    $data['email'],
                    $data['message'] ?? ''
                );
                break;

            case 'updateUser':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['id'], $data['name'], $data['surname'], $data['email']) || !is_numeric($data['id'])) {
                    $this->error(400, 'Неверные данные для обновления');
                    return;
                }
                (new UserController())->updateUser(
                    $data['id'],
                    $data['name'],
                    $data['surname'],
                    $data['email'],
                    $data['message'] ?? ''
                );
                break;

            case 'deleteUser':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['id']) || !is_numeric($data['id'])) {
                    $this->error(400, 'Неверный ID');
                    return;
                }
                (new UserController())->deleteUser($data['id']);
                break;

            //Отзывы
            case 'getReviews':
                (new ReviewController())->getReviews();
                break;

            case 'postReview':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['name'], $data['comment'])) {
                    $this->error(400, 'Не указаны имя или отзыв');
                    return;
                }
                (new ReviewController())->postReview($data['name'], $data['comment']);
                break;

            case 'getOne':
                $id = $_GET['id'] ?? null;
                if (!$id || !is_numeric($id)) {
                    $this->error(400, 'Неверный ID');
                    return;
                }
                (new ReviewController())->getOne($id);
                break;

            case 'update':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['id'], $data['name'], $data['comment']) || !is_numeric($data['id'])) {
                    $this->error(400, 'Неверные данные для обновления отзыва');
                    return;
                }
                (new ReviewController())->update($data['id'], $data['name'], $data['comment']);
                break;

            case 'delete':
                $this->requireMethod('POST');
                $data = $this->getJsonData();
                if (!isset($data['id']) || !is_numeric($data['id'])) {
                    $this->error(400, 'Неверный ID');
                    return;
                }
                (new ReviewController())->delete($data['id']);
                break;

            default:
                $this->error(400, 'Неизвестное действие');
                break;
        }
    }

    // Проверка метода запроса
    private function requireMethod($method) {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => "Метод не разрешён. Ожидается $method"]);
            exit;
        }
    }

    // Получение JSON 
    private function getJsonData() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error(400, 'Некорректный JSON');
        }
        return $data ?? [];
    }

    // Вывод ошибок
    private function error($code, $message) {
        http_response_code($code);
        echo json_encode(['success' => false, 'error' => $message]);
        exit;
    }
}