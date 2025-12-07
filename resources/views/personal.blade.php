<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfigurasi Menu - MealGoal</title>
    <link rel="stylesheet" href="{{ asset('css/personal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="logo-container">
        <img src="{{ asset('img/JatimMeal.png') }}" alt="JatimMeal Logo">
    </div>

    <div class="main-content">
        <h1 class="page-title">Konfigurasi Menu :</h1>

        <form action="{{ route('personal.store') }}" method="POST">
            @csrf

            <div class="question-block">
                <label class="question-text">1. Dari ketiga pilihan dibawah, mana yang lebih Anda suka?</label>
                <div class="options-list">
                    <label class="custom-radio-container">
                        <input type="radio" name="protein" value="Ayam" required>
                        <span class="checkmark"></span>
                        <span class="label-text">Ayam</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="protein" value="Ikan">
                        <span class="checkmark"></span>
                        <span class="label-text">Ikan</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="protein" value="Sayur">
                        <span class="checkmark"></span>
                        <span class="label-text">Sayuran</span>
                    </label>
                </div>
            </div>

            <div class="question-block">
                <label class="question-text">2. Rentang harga bahan baku yang Anda pilih?</label>
                <div class="options-list">
                    <label class="custom-radio-container">
                        <input type="radio" name="price" value="<10k" required>
                        <span class="checkmark"></span>
                        <span class="label-text">
                            < Rp10.000</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="price" value="10k-15k">
                        <span class="checkmark"></span>
                        <span class="label-text">Rp10.000 - Rp15.000</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="price" value="15k-25k">
                        <span class="checkmark"></span>
                        <span class="label-text">Rp15.000 - Rp25.000</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="price" value="25k-40k">
                        <span class="checkmark"></span>
                        <span class="label-text">Rp25.000 - Rp40.000</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="price" value=">40k">
                        <span class="checkmark"></span>
                        <span class="label-text">> Rp40.000</span>
                    </label>
                </div>
            </div>

            <div class="question-block">
                <label class="question-text">3. Jenis olahan mana yang lebih Anda sukai?</label>
                <div class="options-list">
                    <label class="custom-radio-container">
                        <input type="radio" name="style" value="Berkuah" required>
                        <span class="checkmark"></span>
                        <span class="label-text">Berkuah</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="style" value="Kering">
                        <span class="checkmark"></span>
                        <span class="label-text">Kering / Goreng</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="style" value="Bakar">
                        <span class="checkmark"></span>
                        <span class="label-text">Bakar / Panggang</span>
                    </label>
                </div>
            </div>

            <div class="question-block">
                <label class="question-text">4. Apakah Anda menyukai lauk sampingan?</label>
                <div class="options-list">
                    <label class="custom-radio-container">
                        <input type="radio" name="side_dish" value="yes" required>
                        <span class="checkmark"></span>
                        <span class="label-text">Ya, saya suka</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="side_dish" value="no">
                        <span class="checkmark"></span>
                        <span class="label-text">Tidak</span>
                    </label>
                </div>
            </div>

            <div class="question-block">
                <label class="question-text">5. Apakah Anda menyukai tambahan sayur/biji-bijian?</label>
                <div class="options-list">
                    <label class="custom-radio-container">
                        <input type="radio" name="veggies" value="yes" required>
                        <span class="checkmark"></span>
                        <span class="label-text">Ya</span>
                    </label>
                    <label class="custom-radio-container">
                        <input type="radio" name="veggies" value="no">
                        <span class="checkmark"></span>
                        <span class="label-text">Tidak</span>
                    </label>
                </div>
            </div>

            <div class="footer-action">
                <button type="submit" class="btn-next">SELANJUTNYA</button>
            </div>

        </form>
    </div>

</body>

</html>
