<div class="menu-card {{ $extraClass ?? '' }}">
    <div class="card-img">
        <img src="{{ $menu->gambar ? asset('storage/' . $menu->gambar) : 'https://placehold.co/300x200?text=No+Image' }}"
            alt="{{ $menu->nama_menu }}">
    </div>
    <div class="card-info">
        <span class="category-tag">{{ $menu->kategori }}</span>
        <h3>{{ $menu->nama_menu }}</h3>
        <p class="ingredients">{{ Str::limit($menu->bahan_baku, 40) }}</p>
        <div class="card-footer">
            <span class="price">Rp {{ number_format($menu->harga_bahan, 0, ',', '.') }}</span>

            <a href="{{ route('menu.show', $menu->id) }}" class="btn-detail"
                style="text-decoration: none; display: inline-block; text-align: center;">
                Lihat
            </a>
        </div>
    </div>
</div>
