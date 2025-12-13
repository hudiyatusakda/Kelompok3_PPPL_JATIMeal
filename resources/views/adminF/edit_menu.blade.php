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

    <link rel="stylesheet" href="{{ asset('css/edit_menu.css') }}">

    <title>Edit Menu - {{ $menu->nama_menu }}</title>
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
                {{-- <div class="side-bar-menu">
                    <div class="side-bar">
                        <ul>
                            <li class="list"><a href="{{ route('menu.create') }}">Tambahkan Menu</a></li>
                            <li class="list active"><a href="{{ route('menu.index') }}">List Menu</a></li>
                            <li class="list"><a href="#">Pengelola Pengguna</a></li>
                        </ul>
                    </div>
                </div> --}}
            </div>

            <div class="right-section">
                <div class="navbar">
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
                                <a href="#" class="sub-item">Profil Saya</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="sub-item"
                                        style="width:100%; border:none; background:none; text-align:left; cursor:pointer;">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="form-header">
                        <h2>Edit Menu</h2>
                    </div>

                    <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="input-menu">
                            <label for="menu">Nama Menu:</label>
                            <input type="text" id="menu" name="nama_menu"
                                value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                        </div>

                        <div class="input-menu">
                            <label for="kategori">Kategori:</label>
                            <select name="kategori" id="kategori" class="custom-select">
                                <option value="Daging" {{ $menu->kategori == 'Daging' ? 'selected' : '' }}>Daging
                                </option>
                                <option value="Ayam" {{ $menu->kategori == 'Ayam' ? 'selected' : '' }}>Ayam</option>
                                <option value="Goreng" {{ $menu->kategori == 'Goreng' ? 'selected' : '' }}>Goreng
                                </option>
                                <option value="Kuah" {{ $menu->kategori == 'Kuah' ? 'selected' : '' }}>Kuah</option>
                            </select>
                        </div>

                        <div class="insert-image">
                            <input type="file" id="file-input" name="gambar_menu" accept="image/*">

                            <label for="file-input" id="drop-area">
                                <img id="img-preview" src="{{ asset('storage/' . $menu->gambar) }}" alt="Preview">
                            </label>
                        </div>
                        <p style="font-size: 12px; margin-left: 2rem; color: #666; margin-top: 5px;">*Klik gambar untuk
                            mengubah foto.</p>

                        <div class="deskripsi-field">
                            <div class="label-deskripsi">Deskripsi Menu:</div>
                            <div class="input-deskripsi">
                                <textarea name="deskripsi" id="deskripsi" required>{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="button-deskripsi">
                            <button type="submit" class="btn-update">Update Menu</button>

                            <button type="button" class="btn-delete" onclick="confirmDelete()">Hapus Menu</button>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('menu.destroy', $menu->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>

    <script>
        let subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            if (subMenu) subMenu.classList.toggle("open-menu");
        }

        const fileInput = document.getElementById('file-input');
        const imgPreview = document.getElementById('img-preview');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        function confirmDelete() {
            if (confirm("Apakah Anda yakin ingin menghapus menu ini? Data tidak bisa dikembalikan.")) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
</body>

</html>
