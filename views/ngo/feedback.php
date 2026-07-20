<?php 
$pageTitle = 'Submit Feedback';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="mb-4">
    <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-dashboard" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body p-4">
                <h2 class="mb-4">Submit Feedback</h2>
                
                <div class="alert alert-info">
                    <strong>Donation:</strong> <?php echo htmlspecialchars($request['food_name']); ?><br>
                    <strong>Donor:</strong> <?php echo htmlspecialchars($request['donor_name']); ?>
                </div>
                
                <?php if (isset($_SESSION['feedback_errors'])): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($_SESSION['feedback_errors'] as $error): ?>
                            <?php echo htmlspecialchars($error); ?><br>
                        <?php endforeach; ?>
                    </div>
                    <?php unset($_SESSION['feedback_errors']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['feedback_success'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_SESSION['feedback_success']); ?>
                    </div>
                    <?php unset($_SESSION['feedback_success']); ?>
                <?php endif; ?>
                
                <form action="<?php echo BASE_URL; ?>/index.php?route=ngo-submit-feedback&id=<?php echo $request['request_id']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (1-5)</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="">Select rating</option>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comments" class="form-label">Comments (optional)</label>
                        <textarea class="form-control" id="comments" name="comments" rows="4"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Submit Feedback</button>
                        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-dashboard" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
