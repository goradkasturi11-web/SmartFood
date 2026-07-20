<?php 
$pageTitle = 'Login';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Login</h2>
                
                <?php if (isset($_SESSION['login_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['login_errors'] as $error): ?>
                            <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
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
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>" required>
                        <?php unset($_SESSION['login_email']); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Login</button>
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
