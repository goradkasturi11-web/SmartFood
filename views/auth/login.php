<?php 
$pageTitle = 'Login';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">

                <h2 class="text-center mb-2 fw-bold">
                   👋 Welcome Back!
                </h2>
                <p class="text-center text-muted mb-4">
                    Sign in to continue your Smart Food journey.
                </p>

                <?php if (isset($_SESSION['login_errors'])): ?>
                    <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($_SESSION['login_errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                    <?php unset($_SESSION['login_errors']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['register_success'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_SESSION['register_success']); ?>
                    </div>
                    <?php unset($_SESSION['register_success']); ?>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/index.php?route=login" method="POST">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            autocomplete="email"
                            autofocus
                            value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>"
                            required>

                        <?php unset($_SESSION['login_email']); ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required>

                        <!-- Show Password -->
                        <div class="form-check mt-2">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="showPassword"
                                onclick="togglePassword()">

                            <label class="form-check-label" for="showPassword">
                                Show Password
                            </label>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                              🔐 Login
                        </button>
                    </div>

                </form>

                <div class="text-center mt-3">
                    <p>
                        Don't have an account?
                        <a href="<?php echo BASE_URL; ?>/index.php?route=register">
                            Register here
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {

    var password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }

}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>