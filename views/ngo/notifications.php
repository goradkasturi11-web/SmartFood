<?php 
$pageTitle = 'Notifications';
require_once __DIR__ . '/../layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Notifications</h2>
    <div>
        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-mark-all-read" class="btn btn-secondary">
            <i class="bi bi-check-all"></i> Mark All Read
        </a>
        <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-dashboard" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<?php if (empty($notifications)): ?>
    <div class="alert alert-info">
        You have no notifications.
    </div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($notifications as $notification): ?>
            <div class="list-group-item <?php echo $notification['is_read'] ? 'list-group-item-light' : 'list-group-item-primary'; ?>">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h5 class="mb-1">
                        <?php if (!$notification['is_read']): ?>
                            <span class="badge bg-primary">New</span>
                        <?php endif; ?>
                        <?php
                        $typeIcons = [
                            'new_donation' => 'bi-gift',
                            'request_submitted' => 'bi-envelope',
                            'request_approved' => 'bi-check-circle',
                            'request_rejected' => 'bi-x-circle',
                            'food_collected' => 'bi-basket'
                        ];
                        $icon = $typeIcons[$notification['type']] ?? 'bi-bell';
                        ?>
                        <i class="bi <?php echo $icon; ?>"></i>
                        <?php echo ucfirst(str_replace('_', ' ', $notification['type'])); ?>
                    </h5>
                    <small><?php echo date('M d, Y H:i', strtotime($notification['created_at'])); ?></small>
                </div>
                <p class="mb-1"><?php echo htmlspecialchars($notification['message']); ?></p>
                <?php if (!$notification['is_read']): ?>
                    <a href="<?php echo BASE_URL; ?>/index.php?route=ngo-mark-read&id=<?php echo $notification['notification_id']; ?>" class="btn btn-sm btn-outline-primary mt-2">
                        Mark as Read
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
