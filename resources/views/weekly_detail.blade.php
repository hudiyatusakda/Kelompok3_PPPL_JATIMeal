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

    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <link rel="stylesheet" href="{{ asset('css/weekly_overview.css') }}">
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

    <style>
        .day-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .day-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .day-header {
            background: #8F4738;
            color: white;
            padding: 10px 15px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
        }

        .menu-content {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .menu-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .empty-slot {
            padding: 30px;
            text-align: center;
            border: 2px dashed #ccc;
            border-radius: 8px;
            color: #888;
            margin: 15px;
        }

        .btn-add-day {
            color: #8F4738;
            text-decoration: none;
            font-weight: 600;
        }

        .status-badge {
            background: #27ae60;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .actions {
            margin-top: auto;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }

        .btn-check {
            background: #eafaf1;
            color: #27ae60;
        }

        .btn-delete {
            background: #fceceb;
            color: #c0392b;
        }
    </style>

    <title>Detail Minggu {{ $week }} - MealGoal</title>
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

                    <div class="overview-header" style="display: block; border: none; margin-bottom: 20px;">
                        <a href="{{ route('weekly.index', ['month' => $month, 'year' => $year]) }}"
                            style="text-decoration: none; color: #555; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Overview
                        </a>
                        <h2 style="color: #8F4738;">Detail Minggu {{ $week }}</h2>
                        <p style="color: #666;">Menu makan untuk {{ date('F Y', mktime(0, 0, 0, $month, 1)) }}</p>
                    </div>

                    <div class="day-grid">
                        @foreach ($days as $num => $dayName)
                            <div class="day-card">

                                <div class="day-header">
                                    <span>{{ $dayName }}</span>
                                    @if (isset($plans[$num]) && $plans[$num]->is_completed)
                                        <span class="status-badge"><i class="fa-solid fa-check"></i> Selesai</span>
                                    @endif
                                </div>

                                @if (isset($plans[$num]))
                                    <div class="menu-content">

                                        <a href="{{ route('weekly.edit', $plans[$num]->id) }}"
                                            style="text-decoration: none; color: inherit; display: block;">
                                            <img src="{{ $plans[$num]->menu->gambar ? asset('storage/' . $plans[$num]->menu->gambar) : 'https://placehold.co/300x200' }}"
                                                class="menu-img">
                                            <h4 style="margin: 10px 0; font-size: 16px;">
                                                {{ $plans[$num]->menu->nama_menu }}</h4>
                                        </a>

                                        <div class="actions">
                                            <form action="{{ route('weekly.complete', $plans[$num]->id) }}"
                                                method="POST" style="flex:1;">
                                                @csrf
                                                <button type="submit" class="btn-action btn-check" style="width:100%;">
                                                    {{ $plans[$num]->is_completed ? 'Batal' : 'Selesai' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('weekly.destroy', $plans[$num]->id) }}"
                                                method="POST" style="flex:1;"
                                                onsubmit="return confirm('Hapus menu hari {{ $dayName }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete"
                                                    style="width:100%;">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="empty-slot">
                                        <p style="margin-bottom:10px;">Belum ada menu</p>
                                        <a href="{{ route('dashboard') }}" class="btn-add-day">
                                            <i class="fa-solid fa-plus-circle"></i> Cari Menu
                                        </a>
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
    </script>
</body>

</html>
