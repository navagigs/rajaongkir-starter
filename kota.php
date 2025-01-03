<?php
$apiKey = 'GANTI_API_KEY_STARTER'; // Ganti dengan API Key Anda
if (isset($_POST['province_id'])) {
    $provinceId = $_POST['province_id'];
    $url = "https://api.rajaongkir.com/starter/city?province=$provinceId";

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

    $cities = json_decode($response, true);
    foreach ($cities['rajaongkir']['results'] as $city) {
        echo "<option value=\"{$city['city_id']}\">{$city['city_name']}</option>";
    }
}
?>
