<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&family=SUSE+Mono:ital,wght@0,100..800;1,100..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <title>Daftar - MealGoal</title>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo-placeholder">
                <div class="logo">
                    <img src="{{ asset('img/JatimMeal.png') }}" alt="JATIMeal">
                </div>
                <div class="image-food">
                    <img src="{{ asset('img/home.jpg') }}" alt="">
                </div>
            </div>
        </div>
        <div class="right-section">
            <div class="tabs">
                <div class="tab active">Daftar</div>
                <a href="{{ route('login') }}" class="tab transition-link" id="link-masuk">
                    Masuk
                </a>
            </div>
            <div class="form-box">
                <div class="form-register">
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf
                        <div class="formGroup">
                            <label for="Nama">Nama <span class="required">*</span></label>
                            <input type="text" id="Nama" name="name" required value="{{ old('name') }}">
                        </div>
                        <div class="formGroup">
                            <label for="Email">Email <span class="required">*</span></label>
                            <input type="email" id="Email" name="email" required value="{{ old('email') }}">
                        </div>
                        <div class="formGroup">
                            <label for="Password">Kata Sandi <span class="required">*</span></label>
                            <input type="password" id="Password" name="password" required>
                        </div>
                        <div class="formGroup">
                            <label for="C_Password">Konfirmasi Kata Sandi <span class="required">*</span></label>
                            <input type="password" id="C_Password" name="password_confirmation" required>
                        </div>
                        @if ($errors->any())
                            <div
                                style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="button-register">
                            <button type="submit" class="register">Daftar</button>
                        </div>
                    </form>
                </div>
                {{-- <div class="alt-register">
                    <button class="Google">
                        <img src="{{ asset('img/Google.png') }}" alt="Google">
                    </button>
                    <button class="Facebook">
                        <img src="{{ asset('img/Facebook.png') }}" alt="Facebook">
                    </button>
                    <button class="Apple">
                        <img src="{{ asset('img/Apple.png') }}" alt="Apple">
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const linkMasuk = document.getElementById('link-masuk');
        const formSection = document.querySelector('.right-section');

        if (linkMasuk) {
            linkMasuk.addEventListener('click', function(e) {
                e.preventDefault();

                formSection.classList.add('slide-exit');

                setTimeout(() => {
                    window.location.href = this.href;
                }, 400);
            });
        }
    });
</script>

</html>
