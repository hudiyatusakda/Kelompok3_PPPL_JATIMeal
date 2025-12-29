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

    <link rel="stylesheet" href="{{ asset('css/weekly_menu_detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

    <title>Detail Jadwal - {{ $plan->menu->nama_menu }}</title>
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
                            <li class="list">
                                <a href="{{ route('dashboard') }}">Halaman Utama</a>
                            </li>
                            <li class="list active">
                                <a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a>
                            </li>
                            <li class="list">
                                <a href="{{ route('history.index') }}">Riwayat Menu</a>
                            </li>
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

                    <div class="detail-wrapper">

                        <div class="detail-header">
                            {{-- Tombol Kembali --}}
                            <a href="{{ route('weekly.show', ['week' => $plan->week, 'month' => $plan->month, 'year' => $plan->year]) }}"
                                class="btn-back">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>

                            <div class="detail-title">
                                {{-- Info Hari --}}
                                @php
                                    $days = [
                                        1 => 'Senin',
                                        2 => 'Selasa',
                                        3 => 'Rabu',
                                        4 => 'Kamis',
                                        5 => 'Jumat',
                                        6 => 'Sabtu',
                                        7 => 'Minggu',
                                    ];
                                    $dayName = $days[$plan->day_of_week] ?? 'Hari Belum Diatur';
                                @endphp
                                <span class="detail-subtitle">
                                    {{ $dayName }}, MINGGU {{ $plan->week }}
                                </span>
                                <h1>{{ $plan->menu->nama_menu }}</h1>
                            </div>
                        </div>

                        <div class="menu-image">
                            {{-- Overlay Status jika Selesai (Di atas gambar) --}}
                            @if ($plan->is_completed)
                                <div class="status-completed-banner">
                                    <i class="fa-solid fa-check-circle"></i> MENU INI SUDAH DISELESAIKAN
                                </div>
                            @endif

                            <img src="{{ $plan->menu->gambar ? asset('storage/' . $plan->menu->gambar) : 'https://placehold.co/800x400' }}"
                                alt="{{ $plan->menu->nama_menu }}">
                        </div>

                        <div class="menu-description">
                            <p>
                                <strong>{{ $plan->menu->nama_menu }}</strong> {{ $plan->menu->deskripsi }}
                            </p>
                        </div>

                        <div class="menu-ingredients">
                            <h3>• Bahan Utama:</h3>
                            <p>{{ $plan->menu->bahan_baku }}</p>
                        </div>

                        <div class="action-button-container">

                            <form action="{{ route('weekly.complete', $plan->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn-action {{ $plan->is_completed ? 'btn-grey' : 'btn-green' }}">
                                    <i class="fa-solid {{ $plan->is_completed ? 'fa-xmark' : 'fa-check' }}"></i>
                                    {{ $plan->is_completed ? 'Batalkan Status' : 'Tandai Selesai' }}
                                </button>
                            </form>

                            <form action="{{ route('weekly.destroy', $plan->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus menu ini dari jadwal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-red">
                                    <i class="fa-solid fa-trash"></i> Hapus dari Jadwal
                                </button>
                            </form>

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
    </script>
</body>

</html>
