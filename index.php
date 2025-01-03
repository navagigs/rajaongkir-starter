<?php
// API Key RajaOngkir
$apiKey = 'GANTI_API_KEY_STARTER'; // Ganti dengan API Key Anda

// Fungsi untuk mengambil data dari API
function getDataFromRajaOngkir($url, $apiKey) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "key: $apiKey"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

// Ambil daftar provinsi
$provinces = getDataFromRajaOngkir('https://api.rajaongkir.com/starter/province', $apiKey);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Ongkir RajaOngkir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cek Ongkir RajaOngkir</h1>
        <form id="cekOngkirForm">
            <div class="mb-3">
                <label for="origin" class="form-label">Provinsi Asal</label>
                <select id="origin-province" name="origin_province" class="form-control" required>
                    <option value="">Pilih Provinsi</option>
                    <?php foreach ($provinces['rajaongkir']['results'] as $province): ?>
                        <option value="<?= $province['province_id'] ?>"><?= $province['province'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="origin-city" class="form-label">Kota/Kabupaten Asal</label>
                <select id="origin-city" name="origin_city" class="form-control" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="destination" class="form-label">Provinsi Tujuan</label>
                <select id="destination-province" name="destination_province" class="form-control" required>
                    <option value="">Pilih Provinsi</option>
                    <?php foreach ($provinces['rajaongkir']['results'] as $province): ?>
                        <option value="<?= $province['province_id'] ?>"><?= $province['province'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="destination-city" class="form-label">Kota/Kabupaten Tujuan</label>
                <select id="destination-city" name="destination_city" class="form-control" required>
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">Berat (gram)</label>
                <input type="number" name="weight" id="weight" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="courier" class="form-label">Kurir</label>
                <select name="courier" id="courier" class="form-control" required>
                    <option value="">Pilih Kurir</option>
                    <option value="jne">JNE</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="tiki">TIKI</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cek Ongkir</button>
        </form>
        <hr>
        <div id="result" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Load kota/kabupaten berdasarkan provinsi
        function loadCities(provinceId, targetSelect) {
            if (provinceId) {
                $.ajax({
                    url: 'kota.php',
                    type: 'POST',
                    data: { province_id: provinceId },
                    success: function(data) {
                        $(targetSelect).html(data);
                    }
                });
            }
        }

        $('#origin-province').on('change', function() {
            loadCities($(this).val(), '#origin-city');
        });

        $('#destination-province').on('change', function() {
            loadCities($(this).val(), '#destination-city');
        });

        // Proses cek ongkir menggunakan AJAX
        $('#cekOngkirForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'cek_ongkir.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    $('#result').html(data);
                },
                error: function() {
                    $('#result').html('<p class="text-danger">Terjadi kesalahan saat memproses data.</p>');
                }
            });
        });
    </script>
</body>
</html>
