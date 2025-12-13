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

    <link rel="stylesheet" href="{{ asset('css/list_menu.css') }}">
    <title>List Menu</title>
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
                            <li class="list {{ Request::routeIs('menu.create') ? 'active' : '' }}"><a
                                    href="{{ route('menu.crate') }}">Tambahkan Menu</a></li>
                            <li class="list {{ Request::routeIs('menu.index') ? 'active' : '' }}"><a
                                    href="{{ route('menu.index') }}">List Menu</a></li>
                            <li class="list"><a href="#">Pengelola Pengguna</a></li>
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
                    <div class="search-container">
                        <form action="{{ route('menu.index') }}" method="GET">
                            <div class="search-box">
                                <input type="text" name="search" placeholder="Cari menu..."
                                    value="{{ request('search') }}">

                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="menu-board">
                        @php $kategoriList = ['Daging', 'Ayam', 'Goreng', 'Kuah']; @endphp

                        @foreach ($kategoriList as $kategoriName)
                            <div class="category-column">
                                <div class="category-header">
                                    <h2>{{ $kategoriName }}</h2>
                                </div>

                                <div class="menu-items-container">
                                    @if (isset($menus[$kategoriName]))
                                        @foreach ($menus[$kategoriName] as $menu)
                                            <div class="menu-card"
                                                onclick="window.location='{{ route('menu.edit', $menu->id) }}'">
                                                <div class="card-img">
                                                    <img src="{{ asset('storage/' . $menu->gambar) }}"
                                                        alt="{{ $menu->nama_menu }}">
                                                </div>
                                                <div class="card-title">
                                                    {{ $menu->nama_menu }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="empty-placeholder"></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
