<?php

Namespace Classes;

require_once 'HeaderAndFooter.php';
class Main extends AbstractMain
{
    public function renderContent() {
         require_once ("Body/fio.php");
         require_once ("Body/order.php");
         require_once ("Body/reviews.php");
         require_once ("Body/makeReview.php");
    }

    public function View() {
        $this->header();
        $this->renderContent();
        $this->footer();
    }
}

