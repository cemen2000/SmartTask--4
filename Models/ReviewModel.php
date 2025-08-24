<?php
namespace Models;
require_once __DIR__ . '/Model.php';
class ReviewModel extends Model {
    // Добавление отзыва
    public function createReview($name, $comment) {
        if (!$name || !$comment) {
            $this->respond(false, null, 'Не указаны имя или отзыв');
        }

        $sql = "INSERT INTO reviews (name, comment) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            $this->respond(false, null, 'Ошибка подготовки запроса: ' . $this->conn->error);
        }

        $stmt->bind_param("ss", $name, $comment);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Ошибка выполнения запроса');
        }
    }

    // Получение всех отзывов
    public function getAllReviews() {
        $sql = "SELECT id, name, comment FROM reviews";
        $result = $this->conn->query($sql);

        if (!$result) {
            $this->respond(false, null, 'Ошибка БД: ' . $this->conn->error);
        }

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        $this->respond(true, ['reviews' => $reviews]);
    }

    // Получение одного отзыва
    public function getReviewById($id) {
        $sql = "SELECT id, name, comment FROM reviews WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$this->hasResults($result)) {
            $this->respond(false, null, 'Отзыв не найден');
        }

        $review = $result->fetch_assoc();
        $this->respond(true, ['review' => $review]);
    }

    // Обновление отзыва
    public function updateReview($id, $name, $comment) {
        $sql = "UPDATE reviews SET name = ?, comment = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $comment, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Не удалось обновить отзыв');
        }
    }

    // Удаление отзыва
    public function deleteReview($id) {
        $sql = "DELETE FROM reviews WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $this->respond(true);
        } else {
            $this->respond(false, null, 'Отзыв не найден');
        }
    }
}