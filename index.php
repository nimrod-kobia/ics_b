<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Autoload classes and config
require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/ClassAutoLoad.php';

// Initialize Layouts object
$ObjLayout = new Layouts();

// Page metadata overrides
$pageConf = $conf;
$pageConf['banner_title']    = 'ICS B Project';
$pageConf['banner_subtitle'] = 'Welcome to your modern PHP + Bootstrap practice app';

// Render page
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($pageConf);
?>

<div class="container my-5">
    <div class="row g-4">

        <!-- Welcome Card -->
        <div class="col-md-6">
            <div class="card shadow-lg p-4 rounded-4 h-100 text-center bg-light hover-shadow">
                <h2 class="fw-bold animate__animated animate__fadeIn">
                    <i class="bi bi-house-fill"></i> Welcome
                </h2>
                <p class="mt-3 animate__animated animate__fadeInUp">
                    Welcome to the ICS B Project! Explore the navigation menu to sign up, sign in, or view registered users.
                </p>
                <a href="signup.php" class="btn btn-primary mt-3 btn-hover animate__animated animate__pulse animate__infinite">
                    <i class="bi bi-person-plus-fill"></i> Get Started
                </a>
            </div>
        </div>

        <!-- Features Card -->
        <div class="col-md-6">
            <div class="card shadow-lg p-4 rounded-4 h-100 bg-white hover-shadow">
                <h2 class="fw-bold animate__animated animate__fadeIn">
                    <i class="bi bi-star-fill"></i> Features
                </h2>
                <ul class="list-group list-group-flush mt-3 animate__animated animate__fadeInUp">
                    <?php
                    $features = [
                        "Email verified signup",
                        "Secure login with password hashing",
                        "User management dashboard",
                        "Responsive and modern design"
                    ];
                    foreach ($features as $feature) {
                        echo "<li class='list-group-item'>
                                <i class='bi bi-check-circle-fill text-success me-2'></i>$feature
                              </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>

    <!-- Call-to-action section -->
    <div class="mt-5 p-5 rounded-4 text-center shadow-lg cta-gradient text-white">
        <h2 class="fw-bold animate__animated animate__fadeInDown">Join ICS B Today</h2>
        <p class="fs-5 animate__animated animate__fadeInUp">
            Start building projects, managing users, and learning modern PHP & Bootstrap practices.
        </p>
        <a href="signup.php" class="btn btn-light btn-lg mt-3 btn-hover animate__animated animate__pulse animate__infinite">
            <i class="bi bi-arrow-right-circle-fill"></i> Sign Up Now
        </a>
    </div>
</div>

<style>
/* Hover shadows */
.hover-shadow:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* Button hover effect */
.btn-hover:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* CTA gradient */
.cta-gradient {
    background: linear-gradient(135deg, #6f42c1, #e83e8c);
}
</style>

<?php
$ObjLayout->footer($conf);
?>
