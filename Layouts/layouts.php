<?php
class Layouts {

    public function header(array $conf = []) {
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="ICS Dev Team">
      <title><?= $conf['site_name'] ?? 'ICS System'; ?></title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <meta name="theme-color" content="#712cf9">
   </head>
<?php
    }

    public function navbar(array $conf = []) {
?>
   <body>
      <main>
         <div class="container py-4">
            <header class="pb-3 mb-4 border-bottom">
               <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Main navbar">
                  <div class="container-fluid">
                     <a class="navbar-brand" href="./"><?= $conf['site_name'] ?? 'ICS System'; ?></a>
                     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample" aria-controls="navbarsExample" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="collapse navbar-collapse" id="navbarsExample">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                           <li class="nav-item"><a class="nav-link active" href="./">Home</a></li>
                           <li class="nav-item"><a class="nav-link" href="signup.php">Sign Up</a></li>
                           <li class="nav-item"><a class="nav-link" href="signin.php">Sign In</a></li>
                           <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                        </ul>
                        <form role="search">
                           <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                        </form>
                     </div>
                  </div>
               </nav>
            </header>
<?php
    }

    public function banner(array $conf = []) {
?>
            <div class="p-5 mb-4 bg-body-tertiary rounded-3">
               <div class="container-fluid py-5">
                  <h1 class="display-5 fw-bold"><?= $conf['banner_title'] ?? 'Welcome to ICS'; ?></h1>
                  <p class="col-md-8 fs-4"><?= $conf['banner_subtitle'] ?? 'Signup system with email verification.'; ?></p>
                  <a href="signup.php" class="btn btn-primary btn-lg">Get Started</a>
               </div>
            </div>
<?php
    }

    public function content(array $conf = []) {
?>
            <div class="row align-items-md-stretch">
               <div class="col-md-6">
                  <div class="h-100 p-5 text-bg-dark rounded-3">
                     <h2>Change the background</h2>
                     <p>Swap the background-color utility and add a `.text-*` color utility to mix up the jumbotron look.</p>
                     <button class="btn btn-outline-light" type="button">Example button</button>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                     <h2>Add borders</h2>
                     <p>Or, keep it light and add a border for some added definition to your content blocks.</p>
                     <button class="btn btn-outline-secondary" type="button">Example button</button>
                  </div>
               </div>
            </div>
<?php
    }

    public function form_content(array $conf, $ObjForm) {
?>
            <div class="row align-items-md-stretch">
               <div class="col-md-6">
                  <div class="h-100 p-5 text-bg-light rounded-3">
                     <?php 
                        $page = basename($_SERVER['PHP_SELF']);
                        if ($page === 'signup.php') {
                            $ObjForm->signup();
                        } else {
                            $ObjForm->signin();
                        }
                     ?>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                     <h2>Info Section</h2>
                     <p>Additional information, announcements, or branding can go here.</p>
                     <button class="btn btn-outline-secondary" type="button">Learn More</button>
                  </div>
               </div>
            </div>
<?php
    }

    public function footer(array $conf = []) {
?>
            <footer class="pt-3 mt-4 text-body-secondary border-top">
               <p>&copy; <?= date("Y") ?> <?= $conf['site_name'] ?? 'ICS System'; ?>. All rights reserved.</p>
            </footer>
         </div>
      </main>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   </body>
</html>
<?php
    }
}
