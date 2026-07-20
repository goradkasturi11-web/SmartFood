<?php 
$pageTitle = 'Register';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Register</h2>
                
                <?php if (isset($_SESSION['register_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['register_errors'] as $error): ?>
                            <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['register_errors']); ?>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/index.php?route=register" method="POST">
                    <div class="mb-3">
                        <label for="role" class="form-label">I want to register as</label>
                        <select class="form-select" id="role" name="role" required onchange="toggleNGOFields()">
                            <option value="">Select role</option>
                            <option value="donor" <?php echo (isset($_SESSION['register_data']['role']) && $_SESSION['register_data']['role'] === 'donor') ? 'selected' : ''; ?>>Donor</option>
                            <option value="ngo" <?php echo (isset($_SESSION['register_data']['role']) && $_SESSION['register_data']['role'] === 'ngo') ? 'selected' : ''; ?>>NGO</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo isset($_SESSION['register_data']['name']) ? htmlspecialchars($_SESSION['register_data']['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_SESSION['register_data']['email']) ? htmlspecialchars($_SESSION['register_data']['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo isset($_SESSION['register_data']['phone']) ? htmlspecialchars($_SESSION['register_data']['phone']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($_SESSION['register_data']['address']) ? htmlspecialchars($_SESSION['register_data']['address']) : ''; ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude (optional)</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" 
                                   value="<?php echo isset($_SESSION['register_data']['latitude']) ? htmlspecialchars($_SESSION['register_data']['latitude']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude (optional)</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" 
                                   value="<?php echo isset($_SESSION['register_data']['longitude']) ? htmlspecialchars($_SESSION['register_data']['longitude']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div id="ngo-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="organization_name" class="form-label">Organization Name</label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" 
                                   value="<?php echo isset($_SESSION['register_data']['organization_name']) ? htmlspecialchars($_SESSION['register_data']['organization_name']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" 
                                   value="<?php echo isset($_SESSION['register_data']['registration_number']) ? htmlspecialchars($_SESSION['register_data']['registration_number']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="<?php echo BASE_URL; ?>/index.php?route=login">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleNGOFields() {
    const role = document.getElementById('role').value;
    const ngoFields = document.getElementById('ngo-fields');
    const orgName = document.getElementById('organization_name');
    const regNumber = document.getElementById('registration_number');
    
    if (role === 'ngo') {
        ngoFields.style.display = 'block';
        orgName.required = true;
        regNumber.required = true;
    } else {
        ngoFields.style.display = 'none';
        orgName.required = false;
        regNumber.required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleNGOFields();
});
</script>

<?php 
unset($_SESSION['register_data']);
require_once __DIR__ . '/../layouts/footer.php'; 
?>
