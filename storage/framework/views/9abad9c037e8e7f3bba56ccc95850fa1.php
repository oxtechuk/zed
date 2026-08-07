<a href="<?php echo e(route('crm.bookings.show', $booking)); ?>" class="text-decoration-none">
    <div class="kanban-card">
        <span class="kanban-card-id">#BK-<?php echo e(str_pad($booking->id, 4, '0', STR_PAD_LEFT)); ?></span>
        <div class="kanban-card-name"><?php echo e($booking->client_name); ?></div>
        <div class="kanban-card-car text-truncate">
            <i class="bi bi-car-front"></i>
            <?php echo e($booking->car?->brand?->name); ?> <?php echo e($booking->car?->name); ?>

        </div>
        
        <div class="kanban-card-footer">
            <div class="kanban-card-meta">
                <span class="kanban-card-time">
                    <i class="bi bi-clock me-1"></i>
                    <?php echo e($booking->created_at->diffForHumans()); ?>

                </span>
                <?php if($booking->notes_list_count > 0): ?>
                <span class="kanban-note-badge">
                    <i class="bi bi-chat-left-text me-1"></i>
                    <?php echo e($booking->notes_list_count); ?>

                </span>
                <?php endif; ?>
            </div>
            
            <?php if($booking->assignedTo): ?>
            <div class="kanban-avatar" title="<?php echo e($booking->assignedTo->name); ?>">
                <?php echo e(strtoupper(substr($booking->assignedTo->name, 0, 1))); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</a>
<?php /**PATH C:\wamp64\www\zed\resources\views/crm/tracking/partials/card.blade.php ENDPATH**/ ?>