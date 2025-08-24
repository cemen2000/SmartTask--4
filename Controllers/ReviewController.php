<?php
require_once 'Models/Model.php';

class ReviewController {
    private $model;

    public function __construct() {
        $this->model = new Model();
    }

    public function getReviews() {
        $this->model->GetRequest();
    }

    public function postReview() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['name'], $data['comment'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Не указаны имя или отзыв']);
            exit;
        }
        $this->model->PostRequest();
    }

    public function getOne($id) {
        $this->model->getOne($id);
    }

    public function update($id, $name, $comment) {
    if (!$name || !$comment) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Не указано имя или отзыв']);
        exit;
    }

    $this->model->update($id, $name, $comment);

    http_response_code(200);
    echo json_encode(['success' => true]);
}

    public function delete($id) {
        $this->model->delete($id);
    }
}