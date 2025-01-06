<?php
// Veritabanı bağlantısı
$servername = "localhost";   // Veritabanı sunucu adresi
$username = "root";  // Veritabanı kullanıcı adı
$password = "";  // Veritabanı şifresi
$dbname = "gezirehberi";  // Veritabanı adı

// Bağlantı oluşturuluyor
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Şehirleri sorgula (ID'yi de seçiyoruz)
$sql = "SELECT ID, Sehir_Ad, Sehir_Bolge, Plaka, sehir_foto FROM gezirehberii";  // Şehirleri alacak sorgu
$result = $conn->query($sql);

$sehirler = array();  // Şehirler dizisini başlatıyoruz

// Eğer veri varsa
if ($result->num_rows > 0) {
    // Verileri diziye al
    while($row = $result->fetch_assoc()) {
        // Her bir şehir verisini diziye ekliyoruz
        $sehirler[] = $row;
    }
} else {
    // Eğer veritabanında şehir yoksa, boş bir dizi döndürüyoruz
    echo json_encode(array());
    exit;
}

// JSON olarak veriyi döndür
echo json_encode($sehirler);  // JSON formatında döndürülür

// Veritabanı bağlantısını kapatıyoruz
$conn->close();
?> 