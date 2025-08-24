<?php

Namespace Classes;

abstract class AbstractMain {
    protected function header(): void
    {
        echo '
        <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSoft</title>
     <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>
    <section id="form1">
        <h2 style="text-align: center;">Тестовое Задание СмартСофт</h2>
        ';
    }

    protected function footer(): void
    {
        echo '
            <script src="script.js"></script>
          </body>
        </html>
        ';
    }
}