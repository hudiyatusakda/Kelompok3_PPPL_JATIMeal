<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola - User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pengelola_pengguna.css') }}">
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
                    <div style="flex: 1; color: white; font-weight: bold; padding-left: 20px;">
                        HAL PENGELOLA PENGGUNA
                    </div>

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
                                <a href="#" class="sub-item">
                                    <i class="fa-solid fa-user"></i> Profil Saya
                                </a>
                                <hr>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="sub-item">
                                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">

                    <h2 class="content-title">Pengelola Pengguna</h2>

                    <div class="stats-row">
                        <div class="stat-card">
                            <div class="stat-text">
                                <h1 class="stat-number">{{ $totalUsers ?? 0 }}</h1>
                                <p class="stat-label">Pengguna Aktif</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-text">
                                <h1 class="stat-number">{{ $totalMenuIdeas }}</h1>
                                <p class="stat-label">Ide Menu Pengguna</p>
                            </div>
                            <div class="stat-icon">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%; text-align: center;">No.</th>
                                    <th style="width: 40%; text-align: center;">Nama Pengguna</th>
                                    <th style="width: 50%; text-align: center;">Progres Bulan Ini</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td style="text-align: center;">{{ $users->firstItem() + $index }}</td>
                                        <td style="text-align: center; text-transform: uppercase;">{{ $user->name }}
                                        </td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar-bg">
                                                    <div class="progress-bar-fill"
                                                        style="width: {{ $user->weekly_progress }}%;"></div>
                                                </div>

                                                <span
                                                    style="font-size: 12px; margin-left: 8px; color: #666; min-width: 35px;">
                                                    {{ $user->weekly_progress }}%
                                                </span>

                                                <a href="{{ route('admin.users.show', $user->id) }}" class="menu-dots"
                                                    title="Lihat Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" align="center">Belum ada pengguna user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script>
        let subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
        // Klik di luar untuk menutup
        window.onclick = function(event) {
            if (!event.target.closest('.profile-dropdown')) {
                if (subMenu.classList.contains('open-menu')) {
                    subMenu.classList.remove('open-menu');
                }
            }
        }
    </script>
</body>

</html>
