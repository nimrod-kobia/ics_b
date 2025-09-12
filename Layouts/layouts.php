<?php

class Layouts {

    public function header(array $conf = []) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $conf['site_name'] ?? 'ICS System'; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <?php
    }

    public function navbar(array $conf = []) {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><?= $conf['site_name'] ?? 'ICS System'; ?></a>
            </div>
        </nav>
        <?php
    }

    public function banner(array $conf = []) {
        ?>
        <div class="p-5 mb-4 bg-light rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"><?= $conf['banner_title'] ?? 'Welcome to ICS'; ?></h1>
                <p class="col-md-8 fs-4"><?= $conf['banner_subtitle'] ?? 'Signup system with email verification.'; ?></p>
            </div>
        </div>
        <?php
    }

    public function footer(array $conf = []) {
        ?>
        <footer class="bg-dark text-light text-center py-3 mt-5">
            <p>&copy; <?= date("Y") ?> <?= $conf['site_name'] ?? 'ICS System'; ?>. All rights reserved.</p>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}
