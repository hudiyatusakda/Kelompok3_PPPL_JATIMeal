<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalisasi Menu - MealGoal</title>
    <link rel="stylesheet" href="{{ asset('css/personal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Halo, {{ Auth::user()->name }}!</h1>
            <p>Bantu kami memilihkan menu terbaik untukmu.</p>
        </div>

        <form id="preferenceForm" action="{{ route('personal.store') }}" method="POST">
            @csrf

            <div class="question-step">
                <h3>1. Dari ketiga gambar di bawah, mana yang lebih Anda suka?</h3>
                <div class="image-options">
                    <label class="option-card">
                        <input type="radio" name="protein" value="Ayam" required>
                        <img src="{{ asset('img/imgMakanan/Ayam.png') }}" alt="Ayam">
                        <span>Ayam</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="protein" value="Ikan">
                        <img src="{{ asset('img/imgMakanan/Ikan.jpg') }}" alt="Ikan">
                        <span>Ikan</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="protein" value="Sayur">
                        <img src="{{ asset('img/imgMakanan/Sayuran.jpg') }}" alt="Sayur">
                        <span>Sayuran</span>
                    </label>
                </div>
            </div>

            <div class="question-step">
                <h3>2. Rentang harga bahan baku yang Anda pilih?</h3>
                <div class="select-wrapper">
                    <select name="price" id="priceSelect" required>
                        <option value="" disabled selected>Pilih Rentang Harga</option>
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

            <div class="question-step">
                <h3>3. Jenis olahan mana yang lebih Anda sukai?</h3>
                <div class="image-options">
                    <label class="option-card">
                        <input type="radio" name="style" value="Berkuah" required>
                        <img src="{{ asset('img/imgMakanan/berkuah.jpg') }}" alt="Berkuah">
                        <span>Berkuah</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="style" value="Kering">
                        <img src="{{ asset('img/imgMakanan/Kering.jpeg') }}" alt="Kering">
                        <span>Kering/Goreng</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="style" value="Nyemek">
                        <img src="{{ asset('img/imgMakanan/nyemek.jpg') }}" alt="Nyemek">
                        <span>Nyemek</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="style" value="Bakar">
                        <img src="{{ asset('img/imgMakanan/bakar.jpg') }}" alt="Bakar">
                        <span>Bakar</span>
                    </label>
                </div>
            </div>

            <div class="question-step">
                <h3>4. Apakah Anda menyukai lauk sampingan di piring Anda?</h3>
                <div class="yes-no-options">
                    <label class="option-card small">
                        <input type="radio" name="side_dish" value="yes" required>
                        <img src="https://placehold.co/100x100?text=Ya" alt="Ya">
                        <span>Ya</span>
                    </label>
                    <label class="option-card small">
                        <input type="radio" name="side_dish" value="no">
                        <img src="https://placehold.co/100x100?text=Tidak" alt="Tidak">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <div class="question-step">
                <h3>5. Apakah Anda menyukai jika terdapat sayur/biji-bijian?</h3>
                <div class="yes-no-options">
                    <label class="option-card small">
                        <input type="radio" name="veggies" value="yes" required>
                        <img src="https://placehold.co/100x100?text=Ya" alt="Ya">
                        <span>Ya</span>
                    </label>
                    <label class="option-card small">
                        <input type="radio" name="veggies" value="no">
                        <img src="https://placehold.co/100x100?text=Tidak" alt="Tidak">
                        <span>Tidak</span>
                    </label>
                </div>
            </div>

            <div class="actions">
                <button type="button" class="btn-review" onclick="showSummary()">Lihat Ringkasan</button>
            </div>

            <div id="summarySection" class="summary-box" style="display: none;">
                <h2>Konfirmasi Pilihan Anda</h2>
                <ul class="summary-list">
                    <li><strong>Protein:</strong> <span id="sum-protein">-</span></li>
                    <li><strong>Budget:</strong> <span id="sum-price">-</span></li>
                    <li><strong>Gaya Masak:</strong> <span id="sum-style">-</span></li>
                    <li><strong>Lauk Sampingan:</strong> <span id="sum-side">-</span></li>
                    <li><strong>Sayur/Biji:</strong> <span id="sum-veggies">-</span></li>
                </ul>
                <div class="actions">
                    <button type="button" class="btn-back" onclick="hideSummary()">Ubah</button>
                    <button type="submit" class="btn-submit">Simpan & Cari Menu</button>
                </div>
            </div>

        </form>
    </div>

    <script>
        function getRadioValue(name) {
            let options = document.getElementsByName(name);
            for (let i = 0; i < options.length; i++) {
                if (options[i].checked) return options[i].value;
            }
            return "Belum dipilih";
        }

        function showSummary() {
            if (!document.getElementById('preferenceForm').checkValidity()) {
                document.getElementById('preferenceForm').reportValidity();
                return;
            }

            document.getElementById('sum-protein').innerText = getRadioValue('protein');
            document.getElementById('sum-price').innerText = document.getElementById('priceSelect').value;
            document.getElementById('sum-style').innerText = getRadioValue('style');

            let side = getRadioValue('side_dish') === 'yes' ? 'Ya' : 'Tidak';
            document.getElementById('sum-side').innerText = side;

            let veg = getRadioValue('veggies') === 'yes' ? 'Ya' : 'Tidak';
            document.getElementById('sum-veggies').innerText = veg;

            document.getElementById('summarySection').style.display = 'block';
            document.querySelector('.btn-review').style.display = 'none';

            document.getElementById('summarySection').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function hideSummary() {
            document.getElementById('summarySection').style.display = 'none';
            document.querySelector('.btn-review').style.display = 'inline-block';
        }
    </script>

</body>

</html>
