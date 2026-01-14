<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Profil - {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/user_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Hal_Utama.css') }}">
    <script src="https://kit.fontawesome.com/6306b536ce.js" crossorigin="anonymous"></script>
</head>

<body>
    <main>
        <div class="container">
            <div class="left-section">
            </div>

            <div class="right-section">
                <div class="navbar">
                </div>

                <div class="content" style="padding: 40px;">
                    <div class="h1">Edit Profil</div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="profile-info" style="align-items: flex-start;">

                            <div style="text-align: center; flex-shrink: 0;">
                                <div class="profile-picture" style="margin: 0 auto 20px auto;">
                                    <img id="imgPreview"
                                        src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('img/Tester.jpg') }}">
                                </div>
                                <div class="file-upload-wrapper">
                                    <button class="btn-upload"><i class="fa-solid fa-camera"></i> Ganti Foto</button>
                                    <input type="file" name="photo" id="photoInput" accept="image/*"
                                        onchange="previewImage()">
                                </div>
                                @error('photo')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="profile-details" style="flex: 1;">

                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-input"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Alamat Email</label>
                                    <input type="email" name="email" class="form-input"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="phone" class="form-input"
                                        value="{{ old('phone', $user->phone) }}" placeholder="+62...">
                                    @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea name="address" class="form-input">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div style="margin-top: 30px;">
                                    <a href="{{ route('profile.index') }}" class="btn-cancel">Batal</a>
                                    <button type="submit" class="edit-profile-btn"
                                        style="border:none; cursor:pointer;">
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
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
        function previewImage() {
            const photoInput = document.getElementById('photoInput');
            const imgPreview = document.getElementById('imgPreview');

            const file = photoInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

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
