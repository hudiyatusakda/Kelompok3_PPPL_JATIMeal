<div class="menu-card {{ $extraClass ?? '' }}">
    <div class="card-img" style="position: relative;">
        <img src="{{ $menu->gambar ? asset('storage/' . $menu->gambar) : 'https://placehold.co/300x200' }}">

        <form action="{{ route('favorites.toggle', $menu->id) }}" method="POST"
            style="position: absolute; top: 10px; right: 10px;">
            @csrf
            <button type="submit"
                style="background: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                @php
                    $isLiked = \App\Models\Favorite::where('user_id', Auth::id())
                        ->where('menu_id', $menu->id)
                        ->exists();
                @endphp

                @if ($isLiked)
                    <i class="fa-solid fa-heart" style="color: #e74c3c; font-size: 18px;"></i>
                @else
                    <i class="fa-regular fa-heart" style="color: #8F4738; font-size: 18px;"></i>
                @endif
            </button>
        </form>
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
