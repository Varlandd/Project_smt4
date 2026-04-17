<div class="property-card">
    <a href="{{ route('properti.show', $rumah->_id) }}" style="text-decoration:none;color:inherit;">
        <div class="property-card-img">
            @if($rumah->foto)
                @if(\Illuminate\Support\Str::startsWith($rumah->foto, 'http'))
                    <img src="{{ $rumah->foto }}" alt="{{ $rumah->nama }}">
                @elseif(\Illuminate\Support\Str::startsWith($rumah->foto, 'rumah_photos/'))
                    <img src="{{ asset('storage/' . $rumah->foto) }}" alt="{{ $rumah->nama }}">
                @else
                    <img src="{{ asset($rumah->foto) }}" alt="{{ $rumah->nama }}">
                @endif
            @else
                🏠
            @endif
        </div>
        <div class="property-card-body">
            <span class="property-card-type">{{ ucfirst($rumah->tipe ?? 'Rumah') }}</span>
            <h3 class="property-card-name">{{ $rumah->nama }}</h3>
            <div class="property-card-loc">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $rumah->lokasi }}
            </div>
            <div class="property-card-specs">
                <span class="spec-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    {{ $rumah->luas_tanah ?? '-' }} m²
                </span>
                <span class="spec-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/><path d="M3 7h18l-2-4H5z"/></svg>
                    {{ $rumah->kamar_tidur ?? '-' }} KT
                </span>
                <span class="spec-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h16v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5z"/><path d="M4 12V7a4 4 0 014-4h0"/></svg>
                    {{ $rumah->kamar_mandi ?? '-' }} KM
                </span>
            </div>
            <div class="property-card-bottom">
                <span class="property-card-price">Rp {{ number_format($rumah->harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </a>
    <form action="{{ route('properti.favorit', $rumah->_id) }}" method="POST" style="position:absolute;top:12px;right:12px;">
        @csrf
        <button type="submit" class="btn-fav {{ ($rumah->is_favorit ?? false) ? 'active' : '' }}" title="Favorit">
            <svg viewBox="0 0 24 24" fill="{{ ($rumah->is_favorit ?? false) ? '#ef4444' : 'none' }}" stroke="{{ ($rumah->is_favorit ?? false) ? '#ef4444' : '#94a3b8' }}" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </form>
</div>
