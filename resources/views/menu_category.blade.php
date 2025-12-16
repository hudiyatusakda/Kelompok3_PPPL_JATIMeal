<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori: {{ ucfirst($category) }} - MealGoal</title>

    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category_view.css') }}">

    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container">
            <div class="left-section">
                <div class="logo_placeholder">
                    <div class="logo"><img src="{{ asset('img/JatimMeal.png') }}" alt="JatimMeal"></div>
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
                            <li class="list class="list {{ Request::routeIs('history.index') ? 'active' : '' }}"><a
                                    href="{{ route('history.index') }}">Riwayat Menu</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-section">
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

                <div class="content">

                    <div class="category-header-block">
                        <a href="{{ route('dashboard') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left"></i> Kembali
                        </a>
                        <h2>Menampilkan Kategori: <span>{{ ucfirst($category) }}</span></h2>
                    </div>

                    <div class="menu-grid">
                        @forelse($menus as $menu)
                            @include('partials.menu_card', ['menu' => $menu])
                        @empty
                            <div class="empty-state">
                                <p>Tidak ada menu ditemukan dalam kategori ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="pagination-wrapper">
                        {{ $menus->links() }}
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
</body>

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

</html>
