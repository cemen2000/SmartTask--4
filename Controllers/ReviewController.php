<?php
namespace Controllers;
class ReviewController {
    private $model;

    public function __construct() {
        $this->model = new \Models\ReviewModel();
    }

    public function getReviews() {
        $this->model->getAllReviews();
    }

    public function postReview($name, $comment) {
        $this->model->createReview($name, $comment);
    }

    public function getOne($id) {
        $this->model->getReviewById($id);
    }

    public function update($id, $name, $comment) {
        $this->model->updateReview($id, $name, $comment);
    }

    public function delete($id) {
        $this->model->deleteReview($id);
    }
}