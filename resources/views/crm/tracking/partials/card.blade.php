<a href="{{ route('crm.bookings.show', $booking) }}" class="text-decoration-none">
    <div class="kanban-card">
        <span class="kanban-card-id">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
        <div class="kanban-card-name">{{ $booking->client_name }}</div>
        <div class="kanban-card-car text-truncate">
            <i class="bi bi-car-front"></i>
            {{ $booking->car?->brand?->name }} {{ $booking->car?->name }}
        </div>
        
        <div class="kanban-card-footer">
            <div class="kanban-card-meta">
                <span class="kanban-card-time">
                    <i class="bi bi-clock me-1"></i>
                    {{ $booking->created_at->diffForHumans() }}
                </span>
                @if($booking->notes_list_count > 0)
                <span class="kanban-note-badge">
                    <i class="bi bi-chat-left-text me-1"></i>
                    {{ $booking->notes_list_count }}
                </span>
                @endif
            </div>
            
            @if($booking->assignedTo)
            <div class="kanban-avatar" title="{{ $booking->assignedTo->name }}">
                {{ strtoupper(substr($booking->assignedTo->name, 0, 1)) }}
            </div>
            @endif
        </div>
    </div>
</a>
