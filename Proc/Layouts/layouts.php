<?php
namespace App\Layouts;

use App\Forms\Forms;
use App\GlobalFunctions;

class Layouts
{
    /**
     * Render the HTML header
     */
    public function header(array $conf): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <?php
    }

    /**
     * Render the navbar
     */
    public function navbar(array $conf): void
    {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?form=signup">Sign Up</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?form=signin">Sign In</a></li>
                        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php
    }

    /**
     * Render the banner
     */
    public function banner(array $conf): void
    {
        ?>
        <div class="container my-5">
            <div class="p-5 mb-4 bg-light rounded-3 text-center">
                <h1 class="display-5 fw-bold">Welcome to <?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?></h1>
                <p class="col-md-8 mx-auto fs-4">Join our community and explore features like signup, signin, and user management.</p>
                <a href="index.php?form=signup" class="btn btn-primary btn-lg">Join Now</a>
            </div>
        </div>
        <?php
    }

    /**
     * Render the footer
     */
    public function footer(array $conf): void
    {
        ?>
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?>. All rights reserved.</p>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }

    /**
     * Render the dynamic form content and display flash messages
     */
    public function form_content(array $conf, Forms $ObjForm, GlobalFunctions $ObjFncs): void
    {
        echo '<div class="container my-5">';

        // Display flash messages if any
        if ($msg = $ObjFncs->getMsg('msg')) {
            echo "<div class='alert alert-{$msg['type']}'>{$msg['msg']}</div>";
        }

        // Render the correct form
        $ObjForm->render($ObjFncs->showForm);

        echo '</div>';
    }
}
