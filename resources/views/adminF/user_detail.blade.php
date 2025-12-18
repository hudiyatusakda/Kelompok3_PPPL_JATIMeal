<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/detail_user.css') }}">

    <style>
        .filter-container {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .filter-select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-family: 'Poppins', sans-serif;
            background-color: white;
            cursor: pointer;
        }

        .week-header {
            background-color: #eee;
            padding: 10px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
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
                        <li
                            class="list {{ Request::routeIs('admin.users') || Request::routeIs('admin.users.show') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}">Pengelola Pengguna</a>
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
                            <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <div class="account">
                                <img src="https://placehold.co/50x50" alt="Profile">
                            </div>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                        <div class="dropdown-content" id="subMenu">
                            <form action="{{ route('logout') }}" method="POST">
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

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('admin.users') }}" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>

                <h2 class="content-title">Data Pengguna: {{ $user->name }}</h2>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <h2 class="content-title" style="margin:0;">Detail Progres: {{ $monthName }}</h2>

                    <form action="{{ route('admin.users.show', $user->id) }}" method="GET">
                        <select name="period_select" class="filter-select" onchange="updateFilter(this)">
                            @foreach ($availablePeriods as $period)
                                @php
                                    $val = $period->month . '-' . $period->year;
                                    $isSelected = $period->month == $selectedMonth && $period->year == $selectedYear;
                                    $pDate = \Carbon\Carbon::createFromDate($period->year, $period->month, 1);
                                @endphp
                                <option value="{{ $val }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $pDate->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                            @if ($availablePeriods->isEmpty())
                                <option disabled selected>Belum ada riwayat</option>
                            @endif
                        </select>
                        <input type="hidden" name="month" id="inputMonth" value="{{ $selectedMonth }}">
                        <input type="hidden" name="year" id="inputYear" value="{{ $selectedYear }}">
                    </form>
                </div>

                <div class="progress-section-card" style="margin-top: 15px;">

                    @if ($hasPlan)
                        <div class="user-progress-header">
                            <div class="user-avatar-large">
                                <img src="https://placehold.co/100x100" alt="User">
                            </div>
                            <div class="user-info-bar">
                                <div class="ui-top">
                                    <span class="ui-name">{{ strtoupper($user->name) }}</span>
                                    <span class="ui-email">{{ strtoupper($user->email) }}</span>
                                </div>

                                <div class="big-progress-track">
                                    <div class="big-progress-fill" style="width: {{ $percentage }}%;"></div>
                                </div>

                                <div
                                    style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 5px; color: #666;">
                                    <span>Total Bulan Ini</span>
                                    <span>
                                        {{ $completedPlansMonth ?? 0 }} dari {{ $totalPlansMonth ?? 0 }} Menu Selesai
                                        ({{ round($percentage) }}%)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="progress-table-wrapper">
                            <table class="progress-table">
                                <thead>
                                    <tr>
                                        <th width="15%">Minggu</th>
                                        <th width="20%">Hari</th>
                                        <th width="50%">Menu Terjadwal</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Loop Pasti 4 Minggu --}}
                                    @for ($w = 1; $w <= 4; $w++)
                                        @for ($d = 1; $d <= 7; $d++)
                                            <tr>
                                                {{-- Kolom Minggu (Rowspan) --}}
                                                @if ($d === 1)
                                                    <td rowspan="7" class="week-cell"
                                                        style="vertical-align: top; padding-top: 20px;">
                                                        <strong>Minggu Ke-{{ $w }}</strong>
                                                    </td>
                                                @endif

                                                {{-- Kolom Hari --}}
                                                <td class="day-cell">
                                                    {{ $dayNames[$d] }}
                                                </td>

                                                {{-- Kolom Menu --}}
                                                <td class="status-cell" style="text-align: left; padding-left: 20px;">
                                                    {{-- Cek apakah ada plan di Minggu ($w) dan Hari ($d) --}}
                                                    @if (isset($formattedPlans[$w][$d]) && $formattedPlans[$w][$d]->menu)
                                                        @php $plan = $formattedPlans[$w][$d]; @endphp

                                                        <div style="display: flex; align-items: center; gap: 10px;">
                                                            {{-- Gambar Menu --}}
                                                            @if ($plan->menu->gambar)
                                                                <img src="{{ asset('storage/' . str_replace('public/', '', $plan->menu->gambar)) }}"
                                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                            @else
                                                                <div
                                                                    style="width: 40px; height: 40px; background: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="fa-solid fa-utensils"
                                                                        style="color: #999; font-size: 12px;"></i>
                                                                </div>
                                                            @endif

                                                            {{-- Teks Menu --}}
                                                            <div>
                                                                <div style="font-weight: bold; color: #8F4738;">
                                                                    {{ $plan->menu->nama_menu }}
                                                                </div>
                                                                <div style="font-size: 12px; color: #666;">
                                                                    {{ $plan->menu->kategori }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Tampilan Kosong --}}
                                                        <span style="color: #bbb; font-style: italic; font-size: 13px;">
                                                            - Tidak ada jadwal -
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Kolom Status --}}
                                                <td style="text-align: center;">
                                                    @if (isset($formattedPlans[$w][$d]))
                                                        @if ($formattedPlans[$w][$d]->is_completed)
                                                            <span
                                                                style="background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid #c3e6cb;">
                                                                <i class="fa-solid fa-check"></i> Selesai
                                                            </span>
                                                        @else
                                                            <span
                                                                style="background: #f8d7da; color: #721c24; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; border: 1px solid #f5c6cb;">
                                                                Belum
                                                            </span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor

                                        {{-- Pemisah antar minggu agar lebih jelas --}}
                                        @if ($w < 4)
                                            <tr
                                                style="height: 10px; background-color: #f9f9f9; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                                                <td colspan="4"></td>
                                            </tr>
                                        @endif
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="no-plan-state">
                            <div class="no-plan-icon">
                                <i class="fa-regular fa-calendar-xmark"></i>
                            </div>
                            <h3>Belum Ada Rencana</h3>
                            <p>User ini belum menyusun jadwal untuk bulan <strong>{{ $monthName }}</strong>.</p>
                        </div>
                    @endif

                </div>

                <br>

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

        // Script untuk menghandle perubahan dropdown filter
        function updateFilter(selectObject) {
            var value = selectObject.value;
            var parts = value.split('-'); // Format value "bulan-tahun"

            document.getElementById('inputMonth').value = parts[0];
            document.getElementById('inputYear').value = parts[1];

            // Submit form parent
            selectObject.form.submit();
        }
    </script>
</body>

</html>
