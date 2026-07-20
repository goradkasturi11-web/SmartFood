<?php require_once __DIR__ . '/layouts/header.php'; ?>

<div class="hero-section bg-light py-5 mb-5 rounded">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-success mb-3">Reduce Food Waste, Feed Communities</h1>
        <p class="lead mb-4">Connect surplus food from donors with NGOs and charitable organizations</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div>
                <a href="<?php echo BASE_URL; ?>/index.php?route=register" class="btn btn-success btn-lg me-2">Get Started</a>
                <a href="<?php echo BASE_URL; ?>/index.php?route=login" class="btn btn-outline-success btn-lg">Login</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-heart text-danger" style="font-size: 3rem;"></i>
                </div>
                <h3 class="card-title">For Donors</h3>
                <p class="card-text">Easily donate surplus food from your restaurant, hotel, or home to those in need.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="card-title">For NGOs</h3>
                <p class="card-text">Browse available food donations and request items for your community programs.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 text-center">
            <div class="card-body">
                <div class="mb-3">
                    <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h3 class="card-title">Verified Platform</h3>
                <p class="card-text">All NGOs are verified by administrators to ensure safe and reliable food distribution.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-12">
        <h2 class="text-center mb-4">How It Works</h2>
    </div>
    <div class="col-md-3 mb-4">
        <div class="text-center">
            <div class="badge bg-success rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem; line-height: 50px;">1</div>
            <h5>Register</h5>
            <p>Create an account as a donor or NGO</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="text-center">
            <div class="badge bg-success rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem; line-height: 50px;">2</div>
            <h5>List Food</h5>
            <p>Donors post available food items</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="text-center">
            <div class="badge bg-success rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem; line-height: 50px;">3</div>
            <h5>Request</h5>
            <p>NGOs browse and request donations</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="text-center">
            <div class="badge bg-success rounded-circle mb-3" style="width: 50px; height: 50px; font-size: 1.5rem; line-height: 50px;">4</div>
            <h5>Collect</h5>
            <p>NGOs collect and distribute food</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
