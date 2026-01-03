<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Detail Minggu {{ $weekNumber ?? '-' }} - MealGoal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <link rel="stylesheet" href="{{ asset('css/weekly_overview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/weekly_detail.css') }}">

    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>
</head>

<body>
    <main>
        <div class="container">
            <div class="left-section">
                <div class="logo_placeholder">
                    <div class="logo">
                        <img src="{{ asset('img/JatimMeal.png') }}" alt="JatimMeal">
                    </div>
                </div>
                <div class="side-bar-menu">
                    <div class="side-bar">
                        <ul>
                            <li class="list"><a href="{{ route('dashboard') }}">Halaman Utama</a></li>
                            <li class="list active"><a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a></li>
                            <li class="list"><a href="{{ route('history.index') }}">Riwayat Menu</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-section">
                <div class="navbar">
                    <div class="navbar-user">
                        <a href="{{ route('favorites.index') }}" title="Menu Favorit Saya"
                            style="margin-right: 20px; color: white; font-size: 20px; position: relative;">
                            <i class="fa-solid fa-heart"></i>
                        </a>
                        <div class="profile-dropdown">
                            <div class="profile-trigger" onclick="toggleMenu()">
                                <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                                <div class="account">
                                    <img src="{{ asset('img/Tester.jpg') }}" alt="Profile">
                                </div>
                                <i class="fa-solid fa-caret-down"></i>
                            </div>
                            <div class="dropdown-content" id="subMenu">
                                <a href="#" class="sub-item"><i class="fa-solid fa-user"></i> Profil Saya</a>
                                <a href="#" class="sub-item"><i class="fa-solid fa-gear"></i> Pengaturan</a>
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

                <div class="content">

                    <div class="overview-header" style="display: block; border: none; margin-bottom: 20px;">
                        <a href="{{ route('weekly.index', ['month' => $month, 'year' => $year]) }}"
                            style="text-decoration: none; color: #555; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Overview
                        </a>

                        {{-- Judul menggunakan $weekNumber --}}
                        <h2 style="color: #8F4738;">Detail Minggu {{ $weekNumber ?? '?' }}</h2>

                        {{-- Rentang Tanggal --}}
                        <p style="color: #666;">
                            {{ $startDate->format('d M') }} - {{ $startDate->copy()->endOfWeek()->format('d M Y') }}
                        </p>
                    </div>

                    <div class="day-grid">
                        @foreach ($days as $dayInfo)
                            <div class="day-card"
                                style="{{ $dayInfo['is_today'] ? 'border: 2px solid #8F4738;' : '' }}">

                                <div class="day-header">
                                    <div style="display: flex; flex-direction: column;">
                                        {{-- Akses array day_name --}}
                                        <span
                                            style="font-size: 16px; font-weight: 600;">{{ $dayInfo['day_name'] }}</span>
                                        {{-- Akses array display_date --}}
                                        <span
                                            style="font-size: 12px; opacity: 0.9;">{{ $dayInfo['display_date'] }}</span>
                                    </div>

                                    {{-- Cek Plan berdasarkan TANGGAL (Key Array) --}}
                                    @php
                                        $currentPlan = $plans[$dayInfo['date']] ?? null;
                                    @endphp

                                    @if ($currentPlan && $currentPlan->is_completed)
                                        <span class="status-badge"><i class="fa-solid fa-check"></i> Selesai</span>
                                    @endif
                                </div>

                                @if ($currentPlan)
                                    <div class="menu-content" style="position: relative;">

                                        {{-- Logic Favorit --}}
                                        @php
                                            $isLiked = \App\Models\Favorite::where('user_id', Auth::id())
                                                ->where('menu_id', $currentPlan->menu_id)
                                                ->exists();
                                        @endphp

                                        <form action="{{ route('favorites.toggle', $currentPlan->menu_id) }}"
                                            method="POST"
                                            style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                            @csrf
                                            <button type="submit"
                                                style="background: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                                                @if ($isLiked)
                                                    <i class="fa-solid fa-heart"
                                                        style="color: #e74c3c; font-size: 14px;"></i>
                                                @else
                                                    <i class="fa-regular fa-heart"
                                                        style="color: #8F4738; font-size: 14px;"></i>
                                                @endif
                                            </button>
                                        </form>

                                        <a href="{{ route('weekly.edit', $currentPlan->id) }}"
                                            style="text-decoration: none; color: inherit; display: block;">
                                            <img src="{{ $currentPlan->menu->gambar ? asset('storage/' . $currentPlan->menu->gambar) : 'https://placehold.co/300x200' }}"
                                                class="menu-img">
                                            <h4 style="margin: 10px 0; font-size: 16px;">
                                                {{ $currentPlan->menu->nama_menu }}</h4>
                                        </a>

                                        <div class="actions">
                                            <form action="{{ route('weekly.complete', $currentPlan->id) }}"
                                                method="POST" style="flex:1;">
                                                @csrf
                                                <button type="submit" class="btn-action btn-check"
                                                    style="width:100%;">
                                                    {{ $currentPlan->is_completed ? 'Batal' : 'Selesai' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('weekly.destroy', $currentPlan->id) }}"
                                                method="POST" style="flex:1;"
                                                onsubmit="return confirm('Hapus menu hari {{ $dayInfo['day_name'] }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete"
                                                    style="width:100%;">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="empty-slot">
                                        <p style="margin-bottom:10px;">Belum ada menu</p>
                                        {{-- Tombol Tambah mengirim Tanggal Spesifik --}}
                                        <button onclick="openSpecificDateModal('{{ $dayInfo['date'] }}')"
                                            class="btn-add-day"
                                            style="background:none; border:none; cursor:pointer; color: #8F4738; font-weight:600;">
                                            <i class="fa-solid fa-plus-circle"></i> Cari Menu
                                        </button>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="footer-section">
        <div class="f-container">
            <div class="footer-col">
                <ul>
                    <li class="title">Tautan Cepat</li>
                    <li class="link-foward"><a href="/dashboard">Halaman Utama</a></li>
                    <li class="link-foward"><a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a></li>
                    <li class="link-foward"><a href="{{ route('history.index') }}">Riwayat Menu</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <ul class="contact-list">
                    <li class="title">Hubungi Kami</li>
                    <li><i class="fa-solid fa-envelope"></i> help@jatimmeal.com</li>
                    <li><i class="fa-solid fa-phone"></i> +62 812 3456 7890</li>
                </ul>
                <div class="media-social">
                    <ul>
                        <li class="title">Media Sosial</li>
                        <div class="social-icons">
                            <i class="fa-brands fa-instagram"></i>
                            <i class="fa-brands fa-facebook"></i>
                            <i class="fa-brands fa-twitter"></i>
                        </div>
                    </ul>
                </div>
            </div>
            <div class="footer-col">
                <div class="privacy">
                    <h4>Informasi Hukum</h4>
                    <a href="#">Kebijakan Privasi</a>
                    <p>© 2025 JatiMeal. Hak cipta dilindungi undang-undang.</p>
                </div>
            </div>
        </div>
    </footer>

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

        //Redirect ke Dashboard membawa Tanggal
        function openSpecificDateModal(date) {
            window.location.href = "{{ route('dashboard') }}?schedule_date=" + date;
        }
    </script>
</body>

</html>
