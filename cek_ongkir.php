<?php
$apiKey = 'GANTI_API_KEY_STARTER'; // Ganti dengan API Key Anda


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origin = $_POST['origin_city'];
    $destination = $_POST['destination_city'];
    $weight = $_POST['weight'];
    $courier = $_POST['courier'];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ]),
        CURLOPT_HTTPHEADER => [
            "key: $apiKey",
            "Content-Type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response, true);

    if (isset($result['rajaongkir']['results'][0]['costs'])) {
        foreach ($result['rajaongkir']['results'][0]['costs'] as $cost) {
            echo "<p><strong>{$cost['service']}</strong>: Rp " . number_format($cost['cost'][0]['value'], 0, ',', '.') . " (Estimasi: {$cost['cost'][0]['etd']} hari)</p>";
        }
    } else {
        echo "<p class='text-danger'>Ongkir tidak ditemukan. Periksa kembali input Anda.</p>";
    }
}
?>
