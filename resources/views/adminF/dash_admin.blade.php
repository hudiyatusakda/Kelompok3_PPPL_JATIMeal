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

    <link rel="stylesheet" href="{{ asset('css/dash_admin.css') }}">

    <title>Home Admin - MealGoal</title>
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
                            <li class="list {{ Request::routeIs('menu.create') ? 'active' : '' }}">
                                <a href="{{ route('menu.create') }}">Tambahkan Menu</a>
                            </li>

                            <li class="list {{ Request::routeIs('menu.index') ? 'active' : '' }}">
                                <a href="{{ route('menu.index') }}">List Menu</a>
                            </li>

                            <li class="list {{ Request::routeIs('admin.users') ? 'active' : '' }}">
                                <a href="{{ route('admin.users') }}">Pengelola Pengguna</a>
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
                    @if ($errors->any())
                        <div
                            style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div
                            style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="input-menu">
                            <label for="menu">Nama Menu:</label>
                            <input type="text" id="menu" name="nama_menu" required placeholder="Nama Makanan">
                        </div>

                        <div class="input-menu">
                            <label for="kategori">Kategori:</label>
                            <select name="kategori" id="kategori"
                                style="width: 750px; height: 55px; border: 1px solid #8F4738; padding: 10px;">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Daging">Daging</option>
                                <option value="Ayam">Ayam</option>
                                <option value="Goreng">Goreng</option>
                                <option value="Kuah">Kuah</option>
                            </select>
                        </div>

                        <div class="input-menu">
                            <label for="bahan_baku">Bahan Baku:</label>
                            <input type="text" id="bahan_baku" name="bahan_baku" required
                                placeholder="Contoh: Daging Sapi, Tepung">
                        </div>

                        <div class="input-menu">
                            <label for="harga_bahan">Harga Bahan:</label>
                            <input type="text" id="harga_bahan" name="harga_bahan"
                                value="{{ old('harga_bahan', isset($menu) ? 'Rp ' . number_format($menu->harga_bahan, 0, ',', '.') : '') }}"
                                required placeholder="Contoh: Rp 50.000">
                        </div>

                        <div class="input-menu">
                            <label for="referensi">Referensi:</label>
                            <input type="text" id="referensi" name="referensi"
                                placeholder="Masukkan Link atau Sumber">
                        </div>

                        <div class="insert-image">
                            <input type="file" id="file-input" name="gambar_menu" accept="image/*" required>

                            <label for="file-input" id="drop-area">
                                <svg id="upload-icon" xmlns="http://www.w3.org/2000/svg" width="80"
                                    height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>

                                <img id="img-preview" src="#" alt="Preview">
                            </label>
                        </div>

                        <div class="deskripsi-field">
                            <div class="label-deskripsi">Deskripsi Menu:</div>
                            <div class="input-deskripsi">
                                <textarea name="deskripsi" id="deskripsi" required placeholder="Masukkan Deskripsi Makanan"></textarea>
                            </div>
                        </div>

                        <div class="button-deskripsi">
                            <button type="submit">Tambahkan Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

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

        const fileInput = document.getElementById('file-input');
        const imgPreview = document.getElementById('img-preview');
        const uploadIcon = document.getElementById('upload-icon');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imgPreview.style.display = 'block';
                    uploadIcon.style.display = 'none';
                }

                reader.readAsDataURL(file);
            }
        });

        const hargaInput = document.getElementById('harga_bahan');

        if (hargaInput) {
            hargaInput.addEventListener('keyup', function(e) {
                // Tambahkan 'Rp ' pada saat mengetik
                hargaInput.value = formatRupiah(this.value, 'Rp ');
            });
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    </script>
</body>

</html>
