<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - {{ $plan->menu->nama_menu }}</title>

    <link rel="stylesheet" href="{{ asset('css/menu_detail.css') }}">
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <div class="sidebar">
            <div class="logo-area">
                <img src="{{ asset('img/JatimMeal.png') }}" alt="JatimMeal">
            </div>
            <ul class="nav-links">
                <li class="list {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">Halaman Utama</a>
                </li>
                <li class="list {{ Request::routeIs('weekly.index') ? 'active' : '' }}">
                    <a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a>
                </li>
                <li class="list class="list {{ Request::routeIs('history.index') ? 'active' : '' }}"><a
                        href="{{ route('history.index') }}">Riwayat Menu</a>
                </li>
            </ul>
        </div>

        <div class="content-area">
            <div class="navbar">
                {{-- Kalau sudah Login --}}
                <div class="navbar-user">
                    <div class="profile-dropdown">
                        <div class="profile-trigger" onclick="toggleMenu()">
                            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                            <div class="account">
                                <img src="{{ asset('img/Tester.jpg') }}" alt="Profile">
                            </div>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>

                        <div class="dropdown-content" id="subMenu">
                            <a href="#" class="sub-item">
                                <i class="fa-solid fa-user"></i> Profil Saya
                            </a>
                            <a href="#" class="sub-item">
                                <i class="fa-solid fa-gear"></i> Pengaturan
                            </a>
                            <hr>
                            <form action="{{ route('logout') }}" method="POST" style="padding: 0; margin: 0;">
                                @csrf
                                <button type="submit" class="sub-item logout-btn">
                                    <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-wrapper">
                <div class="detail-header">
                    <a href="{{ route('weekly.index') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </a>
                    <div>
                        <span style="font-size: 14px; color: #8F4738; font-weight: 600;">MINGGU
                            {{ $plan->week }}</span>
                        <h1>{{ $plan->menu->nama_menu }}</h1>
                    </div>
                </div>

                <div class="menu-image">
                    <img src="{{ $plan->menu->gambar ? asset('storage/' . $plan->menu->gambar) : 'https://placehold.co/800x400' }}"
                        alt="{{ $plan->menu->nama_menu }}">
                </div>

                <div class="menu-description">
                    <p><strong>{{ $plan->menu->nama_menu }}</strong> {{ $plan->menu->deskripsi }}</p>
                </div>

                <div class="menu-ingredients">
                    <h3>• Bahan Utama:</h3>
                    <p>{{ $plan->menu->bahan_baku }}</p>
                </div>

                <div class="action-button-container" style="gap: 20px; flex-wrap: wrap;">

                    <form action="{{ route('weekly.complete', $plan->id) }}" method="POST">
                        @csrf
                        @if ($plan->is_completed)
                            {{-- Tampilan jika SUDAH selesai (Tombol abu-abu/kuning untuk batal) --}}
                            <button type="submit" class="btn-primary"
                                style="background-color: #27ae60; cursor: pointer;">
                                <i class="fa-solid fa-check-circle"></i> Sudah Selesai (Klik untuk Batal)
                            </button>
                        @else
                            {{-- Tampilan jika BELUM selesai (Tombol Hijau Fresh) --}}
                            <button type="submit" class="btn-primary" style="background-color: #2ecc71;">
                                <i class="fa-regular fa-circle-check"></i> Tandai Selesai
                            </button>
                        @endif
                    </form>

                    <form action="{{ route('weekly.destroy', $plan->id) }}" method="POST"
                        onsubmit="return confirm('Yakin hapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary" style="background-color: #c0392b;">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </button>
                    </form>

                    <a href="{{ route('dashboard') }}" class="btn-primary"
                        style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-rotate"></i> Ganti
                    </a>

                </div>

                @if ($plan->is_completed)
                    <div
                        style="background: #27ae60; color: white; padding: 10px; text-align: center; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                        <i class="fa-solid fa-medal"></i> MENU INI TELAH DISELESAIKAN
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

    <script>
        let subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown')) {
                if (subMenu && subMenu.classList.contains('open-menu')) {
                    subMenu.classList.remove('open-menu');
                }
            }
        }

        function openModal() {
            document.getElementById('weekModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('weekModal').style.display = 'none';
        }
    </script>
</body>

</html>
