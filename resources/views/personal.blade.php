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

    <div class="main-container">

        <header class="top-header">
            <div class="logo-box">
                <img src="{{ asset('img/JatimMeal.png') }}" alt="JatimMeal Logo">
            </div>
            <h1>Konfigurasi Menu :</h1>
        </header>

        <div class="content-body">
            <form action="{{ route('personal.store') }}" method="POST">
                @csrf

                <div class="question-item">
                    <p class="q-title">1. Dari ketiga gambar dibawah, mana yang lebih Anda suka?</p>
                    <div class="options-grid">
                        <label class="option-card">
                            <input type="radio" name="protein" value="Ayam" required>
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/Ayam.png') }}" alt="Ayam">
                                <span>Ayam</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="protein" value="Ikan">
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/Ikan.jpg') }}" alt="Ikan">
                                <span>Ikan</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="protein" value="Sayur">
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/Sayuran.jpg') }}" alt="Sayur">
                                <span>Sayuran</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="question-item">
                    <p class="q-title">2. Rentang harga bahan baku yang Anda pilih?</p>
                    <div class="select-container">
                        <select name="price" required>
                            <option value="" disabled selected>-- Pilih Rentang Harga --</option>
                            <option value="<10k">
                                < Rp10.000</option>
                            <option value="10k-15k">Rp10.000 - Rp15.000</option>
                            <option value="15k-20k">Rp15.000 - Rp20.000</option>
                            <option value="20k-25k">Rp20.000 - Rp25.000</option>
                            <option value="25k-30k">Rp25.000 - Rp30.000</option>
                            <option value="30k-35k">Rp30.000 - Rp35.000</option>
                            <option value="35k-40k">Rp35.000 - Rp40.000</option>
                            <option value="40k-50k">Rp40.000 - Rp50.000</option>
                            <option value=">50k">> Rp50.000</option>
                        </select>
                    </div>
                </div>

                <div class="question-item">
                    <p class="q-title">3. Jenis olahan mana yang lebih Anda sukai?</p>
                    <div class="options-grid">
                        <label class="option-card">
                            <input type="radio" name="style" value="Berkuah" required>
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/berkuah.jpg') }}" alt="Berkuah">
                                <span>Berkuah</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="style" value="Kering">
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/Kering.jpeg') }}" alt="Kering">
                                <span>Kering</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="style" value="Nyemek">
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/nyemek.jpg') }}" alt="Nyemek">
                                <span>Nyemek</span>
                            </div>
                        </label>
                        <label class="option-card">
                            <input type="radio" name="style" value="Bakar">
                            <div class="card-inner">
                                <img src="{{ asset('img/imgMakanan/baka.jpg') }}" alt="Bakar">
                                <span>Bakar</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="question-item">
                    <p class="q-title">4. Apakah Anda menyukai lauk sampingan?</p>
                    <div class="options-row">
                        <label class="option-check">
                            <input type="radio" name="side_dish" value="yes" required>
                            <span class="box-label">
                                <img src="https://placehold.co/50x50?text=Y" alt="Ya"> Ya
                            </span>
                        </label>
                        <label class="option-check">
                            <input type="radio" name="side_dish" value="no">
                            <span class="box-label">
                                <img src="https://placehold.co/50x50?text=T" alt="Tidak"> Tidak
                            </span>
                        </label>
                    </div>
                </div>

                <div class="question-item">
                    <p class="q-title">5. Apakah Anda menyukai jika terdapat sayur/biji-bijian?</p>
                    <div class="options-row">
                        <label class="option-check">
                            <input type="radio" name="veggies" value="yes" required>
                            <span class="box-label">
                                <img src="https://placehold.co/50x50?text=Y" alt="Ya"> Ya
                            </span>
                        </label>
                        <label class="option-check">
                            <input type="radio" name="veggies" value="no">
                            <span class="box-label">
                                <img src="https://placehold.co/50x50?text=T" alt="Tidak"> Tidak
                            </span>
                        </label>
                    </div>
                </div>

                <div class="button-area">
                    <button type="submit" class="btn-next">SELANJUTNYA</button>
                </div>

            </form>
        </div>
    </div>

</body>

</html>
