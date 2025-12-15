<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu->nama_menu }} - MealGoal</title>

    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/menu_detail.css') }}">
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
                <li><a href="#">Riwayat Menu</a></li>
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
                    <a href="{{ route('dashboard') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </a>
                    <h1>{{ $menu->nama_menu }}</h1>
                </div>

                <div class="menu-image">
                    <img src="{{ $menu->gambar ? asset('storage/' . $menu->gambar) : 'https://placehold.co/800x400?text=No+Image' }}"
                        alt="{{ $menu->nama_menu }}">
                </div>

                <div class="menu-description">
                    <p>
                        <strong>{{ $menu->nama_menu }}</strong> {{ $menu->deskripsi }}
                    </p>
                </div>

                <div class="menu-ingredients">
                    <h3>• Bahan Utama:</h3>
                    <p>{{ $menu->bahan_baku }}</p>
                </div>

                <div class="action-button-container">
                    <button class="btn-primary" onclick="openModal()">Tambahkan Menu</button>
                </div>

            </div>

            <div id="weekModal" class="modal-overlay">
                <div class="modal-box">
                    <h3>Tambahkan ke Jadwal</h3>
                    <p>Pilih minggu untuk menu <strong>{{ $menu->nama_menu }}</strong>:</p>

                    <form action="{{ route('weekly.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                        <div class="form-group">
                            <label for="weekSelect">Pilih Minggu:</label>
                            <select name="week" id="weekSelect" class="form-control">
                                {{-- Loop Minggu yang sudah ada --}}
                                @for ($i = 1; $i <= $currentMaxWeek; $i++)
                                    <option value="{{ $i }}">Minggu {{ $i }}</option>
                                @endfor

                                {{-- Opsi Tambah Minggu Baru --}}
                                <option value="{{ $currentMaxWeek + 1 }}" selected>
                                    + Buat Minggu {{ $currentMaxWeek + 1 }} (Baru)
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pilih Hari:</label>
                            <select name="day" required class="form-control">
                                <option value="" disabled selected>-- Pilih Hari --</option>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jumat</option>
                                <option value="6">Sabtu</option>
                                <option value="7">Minggu</option>
                            </select>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                            <button type="submit" class="btn-confirm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="footer">
                <div class="footer-content">
                    <div class="f-col">
                        <h4>Tautan Cepat</h4>
                        <a href="{{ route('dashboard') }}">Halaman Utama</a>
                        <a href="#">Paket Menu Mingguan</a>
                        <a href="#">Riwayat Menu</a>
                    </div>
                    <div class="f-col">
                        <h4>Hubungi Kami</h4>
                        <p><i class="fa-solid fa-envelope"></i> help@jatimmeal.com</p>
                        <p><i class="fa-solid fa-phone"></i> +62 812 3456 7890</p>
                    </div>
                    <div class="f-col">
                        <h4>Informasi Hukum</h4>
                        <a href="#">Kebijakan Privasi</a>
                        <p>© 2025 JatiMeal. Hak cipta dilindungi.</p>
                    </div>
                </div>
            </footer>

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
