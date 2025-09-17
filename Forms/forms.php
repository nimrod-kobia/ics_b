<?php
class Forms {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function signup(array $conf): void {
        ?>
        <div class="container my-5">
            <h2>Sign Up</h2>
            <form method="POST" action="signup.php" class="mt-3">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Fullname</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter your fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="signup_submit" class="btn btn-primary">Sign Up</button>
            </form>
        </div>
        <?php
    }

    public function signin(array $conf): void {
        ?>
        <div class="container my-5">
            <h2>Sign In</h2>
            <form method="POST" action="signin.php" class="mt-3">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="signin_submit" class="btn btn-primary">Sign In</button>
            </form>
        </div>
        <?php
    }
}
