<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Favorit Saya - MealGoal</title>

    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <link rel="stylesheet" href="{{ asset('css/weekly_overview.css') }}">

    <link rel="stylesheet" href="{{ asset('css/favorit.css') }}">

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
                        <a href="{{ route('favorites.index') }}"
                            style="margin-right: 20px; color: #ffcccc; font-size: 20px;">
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

                <div class="content" style="padding: 30px 40px;">
                    <div class="overview-header"
                        style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px;">
                        <div>
                            <h2 style="color: #8F4738;">Menu Favorit Saya</h2>
                            <p style="color: #666;">Daftar makanan yang kamu sukai.</p>
                        </div>
                    </div>

                    @if ($favorites->isEmpty())
                        <div style="text-align: center; padding: 50px; color: #999;">
                            <i class="fa-regular fa-heart" style="font-size: 40px; margin-bottom: 10px;"></i>
                            <p>Belum ada menu favorit.</p>
                            <a href="{{ route('dashboard') }}" style="color: #8F4738; font-weight: bold;">Cari Menu</a>
                        </div>
                    @else
                        <div class="menu-grid">
                            @foreach ($favorites as $fav)
                                <div class="menu-card">
                                    <div class="card-img" style="position: relative;">
                                        <img
                                            src="{{ $fav->menu->gambar ? asset('storage/' . $fav->menu->gambar) : 'https://placehold.co/300x200' }}">

                                        <form action="{{ route('favorites.toggle', $fav->menu->id) }}" method="POST"
                                            style="position: absolute; top: 10px; right: 10px;">
                                            @csrf
                                            <button type="submit"
                                                style="background: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-heart" style="color: #e74c3c;"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="card-info">
                                        <h3>{{ $fav->menu->nama_menu }}</h3>
                                        <div class="card-footer" style="margin-top: auto;">
                                            <button
                                                onclick="openScheduleModal('{{ $fav->menu->id }}', '{{ $fav->menu->nama_menu }}')"
                                                class="btn-schedule">
                                                <i class="fa-regular fa-calendar-plus"></i> Jadwalkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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

    <div id="scheduleModal" class="modal-overlay">
        <div class="modal-box">
            <h3 style="color: #8F4738; margin-bottom: 15px;">Jadwalkan Menu</h3>
            <p id="modalMenuName" style="margin-bottom: 20px; font-weight: bold; color: #555;"></p>

            <form action="{{ route('weekly.store') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" id="modalMenuId">

                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex:1">
                        <label>Bulan:</label>
                        <select name="month" class="form-control">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div style="flex:1">
                        <label>Tahun:</label>
                        <select name="year" class="form-control">
                            @for ($y = date('Y'); $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Minggu Ke:</label>
                    <select name="week" class="form-control">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">Minggu {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label>Hari:</label>
                    <select name="day_of_week" class="form-control">
                        <option value="1">Senin</option>
                        <option value="2">Selasa</option>
                        <option value="3">Rabu</option>
                        <option value="4">Kamis</option>
                        <option value="5">Jumat</option>
                        <option value="6">Sabtu</option>
                        <option value="7">Minggu</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" onclick="closeScheduleModal()"
                        style="background: #ccc; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Batal</button>
                    <button type="submit"
                        style="background: #8F4738; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

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
            let modal = document.getElementById('scheduleModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function openScheduleModal(id, name) {
            document.getElementById('modalMenuId').value = id;
            document.getElementById('modalMenuName').innerText = name;
            document.getElementById('scheduleModal').style.display = 'flex';
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').style.display = 'none';
        }
    </script>
</body>

</html>
