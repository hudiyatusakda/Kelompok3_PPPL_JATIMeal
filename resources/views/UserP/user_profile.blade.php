<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

    <title>{{ Auth::user()->name }} - Profile</title>
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
                            <li class="list"><a href="{{ route('weekly.index') }}">Paket Menu Mingguan</a></li>
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
                                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('img/Tester.jpg') }}"
                                        alt="Profile"
                                        style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                                </div>
                                <i class="fa-solid fa-caret-down"></i>
                            </div>

                            <div class="dropdown-content" id="subMenu">
                                <a href="{{ route('profile.index') }}" class="sub-item">
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

                <div class="content" style="padding: 40px;">
                    <div class="h1">Profile Saya</div>

                    @if (session('success'))
                        <div
                            style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="profile-info">
                        <div class="profile-picture">
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('img/Tester.jpg') }}"
                                alt="{{ Auth::user()->name }}">
                        </div>

                        <div class="profile-details">
                            <div class="detail-row">
                                <label>Nama</label>
                                <div class="value">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="detail-row">
                                <label>Email</label>
                                <div class="value">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="detail-row">
                                <label>No. Telepon</label>
                                <div class="value">{{ Auth::user()->phone ?? '-' }}</div>
                            </div>
                            <div class="detail-row">
                                <label>Alamat</label>
                                <div class="value">{{ Auth::user()->address ?? '-' }}</div>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                            </a>
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
                    <li class="link-foward"><a href="{{ route('dashboard') }}">Halaman Utama</a></li>
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
