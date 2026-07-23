<?php 
$pageTitle = 'Add Donation';
require_once __DIR__ . '/../layouts/header.php';

// Generate form token for CSRF secured
$formToken = bin2hex(random_bytes(32));
$_SESSION['form_token'] = $formToken;
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-4">Add New Donation</h2>
                
                <?php if (isset($_SESSION['donation_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['donation_errors'] as $error): ?>
                            <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['donation_errors']); ?>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/index.php?route=donor-add-donation" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="form_token" value="<?php echo $formToken; ?>">
                    <div class="mb-3">
                        <label for="food_name" class="form-label">Food Name</label>
                        <input type="text" class="form-control" id="food_name" name="food_name" 
                               value="<?php echo isset($_SESSION['donation_data']['food_name']) ? htmlspecialchars($_SESSION['donation_data']['food_name']) : ''; ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity_value" class="form-label">Quantity</label>
                            <input type="number" step="0.01" class="form-control" id="quantity_value" name="quantity_value" 
                                   value="<?php echo isset($_SESSION['donation_data']['quantity_value']) ? htmlspecialchars($_SESSION['donation_data']['quantity_value']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quantity_unit" class="form-label">Unit</label>
                            <select class="form-select" id="quantity_unit" name="quantity_unit" required>
                                <option value="">Select unit</option>
                                <option value="kg" <?php echo (isset($_SESSION['donation_data']['quantity_unit']) && $_SESSION['donation_data']['quantity_unit'] === 'kg') ? 'selected' : ''; ?>>Kilograms (kg)</option>
                                <option value="lbs" <?php echo (isset($_SESSION['donation_data']['quantity_unit']) && $_SESSION['donation_data']['quantity_unit'] === 'lbs') ? 'selected' : ''; ?>>Pounds (lbs)</option>
                                <option value="liters" <?php echo (isset($_SESSION['donation_data']['quantity_unit']) && $_SESSION['donation_data']['quantity_unit'] === 'liters') ? 'selected' : ''; ?>>Liters</option>
                                <option value="pieces" <?php echo (isset($_SESSION['donation_data']['quantity_unit']) && $_SESSION['donation_data']['quantity_unit'] === 'pieces') ? 'selected' : ''; ?>>Pieces</option>
                                <option value="servings" <?php echo (isset($_SESSION['donation_data']['quantity_unit']) && $_SESSION['donation_data']['quantity_unit'] === 'servings') ? 'selected' : ''; ?>>Servings</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="food_type" class="form-label">Food Type</label>
                        <select class="form-select" id="food_type" name="food_type" required>
                            <option value="">Select type</option>
                            <option value="cooked" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'cooked') ? 'selected' : ''; ?>>Cooked Food</option>
                            <option value="raw" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'raw') ? 'selected' : ''; ?>>Raw Ingredients</option>
                            <option value="packaged" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'packaged') ? 'selected' : ''; ?>>Packaged Food</option>
                            <option value="beverages" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'beverages') ? 'selected' : ''; ?>>Beverages</option>
                            <option value="bakery" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'bakery') ? 'selected' : ''; ?>>Bakery Items</option>
                            <option value="dairy" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'dairy') ? 'selected' : ''; ?>>Dairy Products</option>
                            <option value="fruits" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'fruits') ? 'selected' : ''; ?>>Fruits & Vegetables</option>
                            <option value="other" <?php echo (isset($_SESSION['donation_data']['food_type']) && $_SESSION['donation_data']['food_type'] === 'other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preparation_date" class="form-label">Preparation Date</label>
                            <input type="datetime-local" class="form-control" id="preparation_date" name="preparation_date" 
                                   value="<?php echo isset($_SESSION['donation_data']['preparation_date']) ? htmlspecialchars($_SESSION['donation_data']['preparation_date']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expiry_time" class="form-label">Expiry Time</label>
                            <input type="datetime-local" class="form-control" id="expiry_time" name="expiry_time" 
                                   value="<?php echo isset($_SESSION['donation_data']['expiry_time']) ? htmlspecialchars($_SESSION['donation_data']['expiry_time']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pickup_location" class="form-label">Pickup Location</label>
                        <textarea class="form-control" id="pickup_location" name="pickup_location" rows="3" required><?php echo isset($_SESSION['donation_data']['pickup_location']) ? htmlspecialchars($_SESSION['donation_data']['pickup_location']) : ''; ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude (optional)</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" 
                                   value="<?php echo isset($_SESSION['donation_data']['latitude']) ? htmlspecialchars($_SESSION['donation_data']['latitude']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude (optional)</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" 
                                   value="<?php echo isset($_SESSION['donation_data']['longitude']) ? htmlspecialchars($_SESSION['donation_data']['longitude']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Food Image (optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Maximum file size: 5MB. Allowed formats: JPG, PNG, GIF</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success" id="submitBtn" onclick="this.disabled=true; this.form.submit();">Add Donation</button>
                        <a href="<?php echo BASE_URL; ?>/index.php?route=donor-dashboard" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
unset($_SESSION['donation_data']);
require_once __DIR__ . '/../layouts/footer.php'; 
?>
