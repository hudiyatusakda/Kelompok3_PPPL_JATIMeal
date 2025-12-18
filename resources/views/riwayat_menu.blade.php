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
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

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
                        <div class="overview-header">
                            <div>
                                <h2>Arsip Riwayat</h2>
                                <p>Lihat kembali apa yang sudah kamu rencanakan sebelumnya.</p>
                            </div>

                            <form action="{{ route('history.index') }}" method="GET" class="date-filter">
                                <select name="month" onchange="this.form.submit()">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="year" onchange="this.form.submit()">
                                    @for ($y = 2024; $y <= 2026; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
                        </div>

                        <div class="history-list">
                            @forelse($history as $weekNum => $plans)
                                <div class="history-card-group">

                                    <div class="history-header">
                                        <div>
                                            <h3>Minggu {{ $weekNum }}</h3>
                                            <span style="font-size:12px; color:#777;">
                                                {{ $plans->count() }} Menu tersimpan
                                            </span>
                                        </div>

                                        <form action="{{ route('history.restoreFull') }}" method="POST"
                                            onsubmit="return confirm('Salin semua menu dari Minggu {{ $weekNum }} ({{ date('F', mktime(0, 0, 0, $month, 1)) }}) ke Jadwal Bulan Ini?')">
                                            @csrf
                                            <input type="hidden" name="source_week" value="{{ $weekNum }}">
                                            <input type="hidden" name="source_month" value="{{ $month }}">
                                            <input type="hidden" name="source_year" value="{{ $year }}">

                                            <button type="submit" class="btn-restore-all">
                                                <i class="fa-solid fa-clone"></i> Gunakan Paket Ini
                                            </button>
                                        </form>
                                    </div>

                                    <div class="history-grid">
                                        @foreach ($plans as $plan)
                                            <div class="mini-card-history">
                                                <img src="{{ $plan->menu->gambar ? asset('storage/' . $plan->menu->gambar) : 'https://placehold.co/150x100' }}"
                                                    class="h-img">

                                                <div class="h-info">
                                                    <div style="overflow: hidden;">
                                                        <span
                                                            style="font-size: 10px; color: #8F4738; font-weight: bold; display: block;">
                                                            {{-- Tampilkan Hari --}}
                                                            @php $days = [1=>'SENIN', 2=>'SELASA', 3=>'RABU', 4=>'KAMIS', 5=>'JUMAT', 6=>'SABTU', 7=>'MINGGU']; @endphp
                                                            {{ $days[$plan->day_of_week] ?? '-' }}
                                                        </span>
                                                        <h4
                                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                            {{ $plan->menu->nama_menu }}
                                                        </h4>
                                                    </div>

                                                    <form action="{{ route('history.restoreSingle') }}" method="POST"
                                                        onsubmit="return confirm('Tambahkan {{ $plan->menu->nama_menu }} ke jadwal bulan ini?')">
                                                        @csrf
                                                        <input type="hidden" name="menu_id"
                                                            value="{{ $plan->menu_id }}">
                                                        <button type="submit" class="btn-icon-add"
                                                            title="Ambil menu ini saja">
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
                                    <i class="fa-solid fa-folder-open"
                                        style="font-size: 40px; margin-bottom: 10px;"></i>
                                    <p>Tidak ada riwayat menu di bulan {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        {{ $year }}.</p>
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
