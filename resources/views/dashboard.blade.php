<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    {{-- Link Font dirapikan --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:wght@100..900&family=SUSE:wght@100..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">

    <title>Home - MealGoal</title>
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
                            <li class="list active"><a href="#">Halaman Utama</a></li>
                            <li class="list"><a href="#">Paket Menu Mingguan</a></li>
                            <li class="list"><a href="#">Riwayat Menu</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="right-section">
                <div class="navbar">
                    {{-- Kalau Belum masuk akun --}}
                    {{-- <div class="navbar-guest">
                        <nav class="masuk">Masuk</nav>
                        <div class="div">|</div>
                        <nav class="daftar">Daftar</nav>
                    </div> --}}

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

                <div class="search-container">
                    <div class="search-box">
                        <input type="text" placeholder="Cari Menu Makanan">
                        <i class="fa-solid fa-magnifying-glass"></i>
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

        // Menutup menu jika user klik di luar area menu (sedikit diperbaiki logikanya)
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