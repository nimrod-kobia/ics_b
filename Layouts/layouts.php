<?php
class Layouts {

    // Header
    public function header(array $conf): void {
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

    // Navbar
    public function navbar(array $conf): void {
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
                        <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                        <li class="nav-item"><a class="nav-link" href="signin.php">Sign In</a></li>
                        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
        <?php
    }

    // Banner / Jumbotron
    public function banner(array $conf): void {
        ?>
        <div class="container my-5">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5 text-center">
                    <h1 class="display-5 fw-bold">Welcome to <?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?></h1>
                    <p class="col-md-8 mx-auto fs-4">
                        Join our community and start exploring features like signup, signin, and user management.
                    </p>
                    <a href="signup.php" class="btn btn-primary btn-lg">Join Now</a>
                </div>
            </div>
        </div>
        <?php
    }

    // Footer
    public function footer(array $conf): void {
        ?>
        <footer class="bg-dark text-white text-center py-3 mt-5">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($conf['site_name'], ENT_QUOTES) ?>. All rights reserved.</p>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }

    // Form content: show signup or signin depending on $ObjFncs->showForm
    public function form_content(array $conf, $ObjForm, $ObjFncs): void {
        echo '<div class="container my-5">';

        if ($ObjFncs->showForm === 'signup') {
            $ObjForm->signup($conf);
        } elseif ($ObjFncs->showForm === 'signin') {
            $ObjForm->signin($conf);
        } else {
            echo "<div class='alert alert-danger'>Form type not specified.</div>";
        }

        echo '</div>';
    }
}
