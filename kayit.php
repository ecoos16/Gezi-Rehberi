<?php
// Veritabanı bağlantısı
$servername = "localhost"; // Genellikle localhost
$username = "root"; // WAMP'da default kullanıcı genellikle "root"
$password = ""; // Eğer parola belirlemediyseniz, bu boş olabilir
$dbname = "gezirehberi"; // Kullanıcı bilgilerini ekleyeceğiniz veritabanı ismi

// Bağlantıyı oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Formdan gelen verileri al ve kontrol et
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $parola = $_POST['parola'];

    // Parolayı güvenli bir şekilde saklamak için hash'lemek
    $parola_hash = password_hash($parola, PASSWORD_DEFAULT);

    // SQL sorgusunu oluştur
    $sql = "INSERT INTO kullanici (ad, parola) VALUES ('$ad', '$parola_hash')";

    // Sorguyu çalıştır ve kontrol et
    if ($conn->query($sql) === TRUE) {
        echo "Kayıt başarılı! <a href='giris.html'>Giriş yapabilirsiniz.</a>";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }
}

// Bağlantıyı kapat
$conn->close();
?>
