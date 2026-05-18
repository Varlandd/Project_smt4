<div class="profile-orders">
    <h3>Pesanan / Riwayat</h3>
    <p class="section-desc">Lihat semua pesanan dan riwayat aktivitas Anda.</p>

    <div class="orders-filter">
        <button class="filter-btn active" onclick="filterOrders('all')">Semua</button>
        <button class="filter-btn" onclick="filterOrders('active')">Aktif</button>
        <button class="filter-btn" onclick="filterOrders('completed')">Selesai</button>
        <button class="filter-btn" onclick="filterOrders('cancelled')">Dibatalkan</button>
    </div>

    @if($orders && count($orders) > 0)
        <div class="orders-list">
            @foreach($orders as $order)
                <div class="order-card" data-status="{{ $order->status ?? 'pending' }}">
                    <div class="order-header">
                        <div class="order-info">
                            <h4>{{ $order->title ?? 'Pesanan #' . $order->id }}</h4>
                            <p class="order-date">{{ optional($order->created_at)->format('d F Y, H:i') ?? '-' }}</p>
                        </div>
                        <span class="order-status status-{{ $order->status ?? 'pending' }}">
                            {{ ucfirst($order->status ?? 'Pending') }}
                        </span>
                    </div>

                    <div class="order-details">
                        <div class="detail-row">
                            <span class="label">Nomor Pesanan:</span>
                            <span class="value">#{{ $order->id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Total:</span>
                            <span class="value">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if($order->description)
                            <div class="detail-row">
                                <span class="label">Deskripsi:</span>
                                <span class="value">{{ $order->description }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="order-actions">
                        <a href="#" class="button button-secondary-small">Lihat Detail</a>
                        @if($order->status === 'pending' || $order->status === 'active')
                            <a href="#" class="button button-danger-small">Batalkan</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h4>Belum Ada Pesanan</h4>
            <p>Anda belum memiliki riwayat pesanan. Mulai dengan membuat pesanan baru.</p>
            <a href="{{ route('dashboard') }}" class="button button-primary">Buat Pesanan</a>
        </div>
    @endif
</div>

<script>
function filterOrders(status) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Filter orders
    const cards = document.querySelectorAll('.order-card');
    cards.forEach(card => {
        if (status === 'all') {
            card.style.display = 'block';
        } else {
            if (card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}
</script>

<style>
    .profile-orders h3 {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .section-desc {
        color: #666;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .orders-filter {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 0.6rem 1.2rem;
        border: 1px solid #ddd;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        color: #666;
    }

    .filter-btn:hover {
        border-color: #16a085;
        color: #16a085;
    }

    .filter-btn.active {
        background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
        color: white;
        border-color: #16a085;
    }

    .orders-list {
        display: grid;
        gap: 1.5rem;
    }

    .order-card {
        padding: 1.5rem;
        background: white;
        border: 1px solid #eee;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .order-info h4 {
        margin: 0 0 0.25rem 0;
        color: #1a1a1a;
        font-size: 1rem;
    }

    .order-date {
        margin: 0;
        color: #999;
        font-size: 0.85rem;
    }

    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .order-status.status-pending,
    .order-status.status-active {
        background: #fff3cd;
        color: #856404;
    }

    .order-status.status-completed {
        background: #d4edda;
        color: #155724;
    }

    .order-status.status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-details {
        margin-bottom: 1.5rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row .label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .detail-row .value {
        color: #1a1a1a;
        font-size: 0.9rem;
        word-break: break-word;
    }

    .order-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .button {
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-size: 0.9rem;
    }

    .button-secondary-small {
        background: #f5f5f5;
        color: #333;
    }

    .button-secondary-small:hover {
        background: #eee;
    }

    .button-danger-small {
        background: #f8d7da;
        color: #721c24;
    }

    .button-danger-small:hover {
        background: #f5c6cb;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
        border: 2px dashed #ddd;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        margin: 0 0 0.5rem 0;
        color: #1a1a1a;
        font-size: 1.1rem;
    }

    .empty-state p {
        margin: 0 0 1.5rem 0;
        color: #666;
    }

    .button-primary {
        background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
        color: white;
    }

    .button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 160, 133, 0.3);
    }

    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            gap: 1rem;
        }

        .order-actions {
            flex-direction: column;
        }

        .button {
            width: 100%;
        }
    }
</style>
