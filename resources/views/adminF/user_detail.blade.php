<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/detail_user.css') }}">
</head>

<body>
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

                <a href="{{ route('admin.users') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>

                <h2 class="content-title">Data Pengguna: {{ $user->name }}</h2>

                <h2 class="content-title">Detail Progres</h2>

                <div class="progress-section-card">

                    @if ($hasPlan)

                        <div class="user-progress-header">
                            <div class="user-avatar-large">
                                <img src="{{ asset('img/Tester.jpg') }}" alt="User">
                            </div>
                            <div class="user-info-bar">
                                <div class="ui-top">
                                    <span class="ui-name">{{ strtoupper($user->name) }}</span>
                                    <span class="ui-email">{{ strtoupper($user->email) }}</span>
                                </div>
                                <div class="big-progress-track">
                                    <div class="big-progress-fill" style="width: {{ $percentage }}%;"></div>
                                </div>
                                <div style="text-align: right; font-size: 12px; margin-top: 5px; color: #666;">
                                    {{ round($percentage) }}% Terisi (Minggu 1)
                                </div>
                            </div>
                        </div>

                        <div class="progress-table-wrapper">
                            <table class="progress-table">
                                <thead>
                                    <tr>
                                        <th width="15%">Minggu</th>
                                        <th width="20%">Hari</th>
                                        <th width="65%">Menu Terjadwal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Loop Minggu yang ada di database --}}
                                    @foreach ($formattedPlans as $weekNum => $days)
                                        {{-- Loop Hari 1 s/d 7 (Senin-Minggu) agar tabel rapi --}}
                                        @for ($d = 1; $d <= 7; $d++)
                                            <tr>
                                                {{-- Kolom Minggu (Rowspan cuma muncul di hari pertama/Senin) --}}
                                                @if ($d === 1)
                                                    <td rowspan="7" class="week-cell">Minggu {{ $weekNum }}
                                                    </td>
                                                @endif

                                                {{-- Kolom Nama Hari --}}
                                                <td class="day-cell">
                                                    {{ $dayNames[$d] }}
                                                </td>

                                                {{-- Kolom Menu --}}
                                                <td class="status-cell" style="text-align: left; padding-left: 20px;">
                                                    @if (isset($days[$d]))
                                                        {{-- JIKA ADA PLAN DI HARI INI --}}
                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            {{-- Cek apakah menu punya gambar --}}
                                                            @if ($days[$d]->menu->gambar)
                                                                <img src="{{ asset('storage/' . str_replace('public/', '', $days[$d]->menu->gambar)) }}"
                                                                    alt="Menu"
                                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                            @else
                                                                <div
                                                                    style="width: 40px; height: 40px; background: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="fa-solid fa-utensils"
                                                                        style="color: #999; font-size: 14px;"></i>
                                                                </div>
                                                            @endif

                                                            <div>
                                                                <div style="font-weight: bold; color: #8F4738;">
                                                                    {{ $days[$d]->menu->nama_menu }}
                                                                </div>
                                                                <div style="font-size: 12px; color: #666;">
                                                                    {{ $days[$d]->menu->kategori }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- JIKA KOSONG --}}
                                                        <span style="color: #bbb; font-style: italic;">- Tidak ada
                                                            rencana -</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor

                                        {{-- Pemisah antar minggu (opsional) --}}
                                        @if (!$loop->last)
                                            <tr style="height: 10px; background-color: #8F4738;">
                                                <td colspan="3"></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="no-plan-state">
                            <div class="no-plan-icon">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>
                            <h3>Belum Ada Rencana Mingguan</h3>
                            <p>User ini belum menyusun jadwal menu makanannya.</p>
                        </div>
                    @endif

                </div>

                <br><br>

                <div class="detail-card">
                    <div class="section-header">
                        <i class="fa-regular fa-id-card"></i> Informasi Akun
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nama Lengkap</label>
                            <div class="value-box">{{ $user->name }}</div>
                        </div>
                        <div class="info-item">
                            <label>Alamat Email</label>
                            <div class="value-box">{{ $user->email }}</div>
                        </div>
                        <div class="info-item">
                            <label>Status Role</label>
                            <div class="value-box">{{ ucfirst($user->role) }}</div>
                        </div>
                        <div class="info-item">
                            <label>Tanggal Bergabung</label>
                            <div class="value-box">{{ $user->created_at->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="section-header">
                        <i class="fa-solid fa-utensils"></i> Preferensi Makanan (Survey)
                    </div>

                    @if ($user->preference)
                        <div class="info-grid">
                            <div class="info-item">
                                <label>1. Protein Pilihan</label>
                                <div class="value-box">{{ $user->preference->protein_preference }}</div>
                            </div>
                            <div class="info-item">
                                <label>2. Budget / Harga</label>
                                <div class="value-box">{{ $user->preference->price_range }}</div>
                            </div>
                            <div class="info-item">
                                <label>3. Gaya Masakan</label>
                                <div class="value-box">{{ $user->preference->cooking_style }}</div>
                            </div>
                            <div class="info-item">
                                <label>4. Lauk Sampingan</label>
                                <div>
                                    @if ($user->preference->has_side_dish)
                                        <span class="badge badge-yes">Ya, Suka</span>
                                    @else
                                        <span class="badge badge-no">Tidak</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <label>5. Sayur & Biji-bijian</label>
                                <div>
                                    @if ($user->preference->has_vegetables)
                                        <span class="badge badge-yes">Ya, Suka</span>
                                    @else
                                        <span class="badge badge-no">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fa-regular fa-folder-open"></i>
                            <p>User ini belum mengisi data preferensi makanan.</p>
                        </div>
                    @endif
                </div>

            </div>
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
    </script>
</body>

</html>
