<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@100..900&family=SUSE:wght@100..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">
    <title>Riwayat Menu - MealGoal</title>
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
                            <li class="list {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}">Halaman Utama</a>
                            </li>
                            <li class="list {{ Request::routeIs('weekly.index') ? 'active' : '' }}">
                                <a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a>
                            </li>
                            <li class="list {{ Request::routeIs('history.index') ? 'active' : '' }}">
                                <a href="{{ route('history.index') }}">Riwayat Menu</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-section">
                <div class="navbar">
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

                <div class="content">

                    <div class="riwayat-wrapper">
                        <div class="page-header">
                            <h2>Riwayat Menu Mingguan</h2>
                            <p>Gunakan kembali menu favoritmu dari minggu-minggu sebelumnya.</p>
                        </div>

                        <div class="history-list">
                            @forelse($history as $weekNum => $plans)
                                <div class="history-card-group">

                                    <div class="history-header">
                                        <h3><i class="fa-regular fa-calendar-check"></i> Arsip Minggu
                                            Ke-{{ $weekNum }}</h3>

                                        <form action="{{ route('history.restoreFull') }}" method="POST"
                                            onsubmit="return confirmValidation('Apakah Anda yakin ingin menyalin SEMUA menu dari Minggu {{ $weekNum }} ke minggu baru?')">
                                            @csrf
                                            <input type="hidden" name="source_week" value="{{ $weekNum }}">
                                            <button type="submit" class="btn-restore-all">
                                                <i class="fa-solid fa-copy"></i> Pakai Paket Ini Lagi
                                            </button>
                                        </form>
                                    </div>

                                    <div class="history-grid">
                                        @foreach ($plans as $plan)
                                            <div class="mini-card-history">
                                                <img src="{{ $plan->menu->gambar ? asset('storage/' . $plan->menu->gambar) : 'https://placehold.co/150x100' }}"
                                                    class="h-img">
                                                <div class="h-info">
                                                    <h4>{{ $plan->menu->nama_menu }}</h4>

                                                    <form action="{{ route('history.restoreSingle') }}" method="POST"
                                                        onsubmit="return confirmValidation('Tambahkan {{ $plan->menu->nama_menu }} ke jadwal minggu ini?')">
                                                        @csrf
                                                        <input type="hidden" name="menu_id"
                                                            value="{{ $plan->menu_id }}">
                                                        <button type="submit" class="btn-icon-add"
                                                            title="Tambahkan ke Minggu Ini">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @empty
                                <div class="empty-state">
                                    <div style="font-size: 40px; margin-bottom: 10px; color: #ccc;">
                                        <i class="fa-solid fa-clock-rotate-left"></i>
                                    </div>
                                    <p>Belum ada riwayat menu yang tersimpan.</p>
                                </div>
                            @endforelse
                        </div>
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
                    <li class="link-foward"><a href="#">Paket Menu Mingguan</a></li>
                    <li class="link-foward"><a href="#">Riwayat Menu</a></li>
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

        // Script Validasi Konfirmasi
        function confirmValidation(message) {
            return confirm(message);
        }
    </script>
</body>

</html>
