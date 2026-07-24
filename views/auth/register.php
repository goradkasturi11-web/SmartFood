<?php 
$pageTitle = 'Register';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-1g rounded-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                <i class="fas fa-user-plus fa-3x text-success mb-3"></i>
                <h2 class="fw-bold">Create Your Account</h2>
                <p class="text-muted">Join the Smart Food Redistribution Platform</p>
                <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted fw-bold">
                        Registration Progress
                    </small>

                    <small id="progress-text" class="fw-bold text-success">
                        0%
                    </small>
                </div>

                <div class="progress rounded-pill" style="height:12px;">
                    <div
                        id="formProgress"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                        role="progressbar"
                        style="width:0%; transition:width .4s ease;">
                    </div>
                </div>
            </div>
            </div>
                
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
                        <label for="name" class="form-label">
                        <i class="fas fa-user text-success me-2"></i>
                         Full Name
                        </label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo isset($_SESSION['register_data']['name']) ? htmlspecialchars($_SESSION['register_data']['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                        <i class="fas fa-envelope text-success me-2"></i>
                        Email
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_SESSION['register_data']['email']) ? htmlspecialchars($_SESSION['register_data']['email']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">
                        <i class="fas fa-phone text-success me-2"></i>
                         Phone Number
                        </label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo isset($_SESSION['register_data']['phone']) ? htmlspecialchars($_SESSION['register_data']['phone']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                        <i class="fas fa-lock text-success me-2"></i>
                        Password
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                     <div class="alert alert-light border rounded-3 mt-2">
                        <strong class="text-success">
                        <i class="fas fa-shield-alt me-2"></i>
                         Password Requirements
                        </strong>

                        <ul class="mb-0 mt-2">
                        <li>Minimum 6 characters</li>
                        <li>At least one uppercase letter (A-Z)</li>
                        <li>At least one lowercase letter (a-z)</li>
                        <li>At least one number (0-9)</li>
                        <li>At least one special character (@, #, $, %, etc.)</li>
                         </ul>
                    </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock text-success me-2"></i>
                        Confirm Password
                        </label>
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
                        <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill">
    <i class="fas fa-user-plus me-2"></i>
    Create Account
</button>
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
<script>
const fields = [
    "role",
    "name",
    "email",
    "phone",
    "password",
    "confirm_password",
    "address"
];

function updateProgress() {

    let filled = 0;
    let total = 7;

    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);

        if (field && field.value.trim() !== "") {
            filled++;
        }
    });

    if (document.getElementById("role").value === "ngo") {

        total = 9;

        if (document.getElementById("organization_name").value.trim() !== "")
            filled++;

        if (document.getElementById("registration_number").value.trim() !== "")
            filled++;
    }

    const percentage = Math.round((filled / total) * 100);

    const bar = document.getElementById("formProgress");
    const text = document.getElementById("progress-text");

    bar.style.width = percentage + "%";
    text.innerHTML = percentage + "%";

    // Change colors
    bar.classList.remove("bg-danger","bg-warning","bg-success");

    if (percentage <= 30) {
        bar.classList.add("bg-danger");
    }
    else if (percentage <= 70) {
        bar.classList.add("bg-warning");
    }
    else {
        bar.classList.add("bg-success");
    }
}

document.querySelectorAll("input, textarea, select").forEach(element => {
    element.addEventListener("input", updateProgress);
    element.addEventListener("change", updateProgress);
});

document.addEventListener("DOMContentLoaded", updateProgress);
</script>

<?php 
unset($_SESSION['register_data']);
require_once __DIR__ . '/../layouts/footer.php'; 
?>
