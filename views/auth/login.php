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
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
<<<<<<< HEAD

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

=======
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>" required>
>>>>>>> c423979f69fd81446d838837b51356d62256a068
                        <?php unset($_SESSION['login_email']); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-grid">
<<<<<<< HEAD
                        <button type="submit" class="btn btn-success btn-lg w-100">
                              🔐 Login
                        </button>
=======
                        <button type="submit" class="btn btn-success">Login</button>
>>>>>>> c423979f69fd81446d838837b51356d62256a068
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/index.php?route=register">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
