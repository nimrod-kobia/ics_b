<?php
namespace App\Forms;

class Forms
{
    private \PDO $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    // Render Sign Up form
    public function signup(): void
    {
        ?>
        <div class="container my-5">
            <h2>Sign Up</h2>
            <form method="POST" action="" class="mt-3">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Fullname</label>
                    <input type="text" name="name" id="fullname" class="form-control" placeholder="Enter your fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
            </form>
        </div>
        <?php
    }

    // Render Sign In form
    public function signin(): void
    {
        ?>
        <div class="container my-5">
            <h2>Sign In</h2>
            <form method="POST" action="" class="mt-3">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="signin" class="btn btn-primary">Sign In</button>
            </form>
        </div>
        <?php
    }

    // Optional: decide which form to show
    public function render(string $formType): void
    {
        if ($formType === 'signup') {
            $this->signup();
        } else {
            $this->signin();
        }
    }
}
