<?php
// Veritabanı bağlantı bilgileri
$host = 'localhost';     // Veritabanı sunucu adresi
$dbname = 'gezirehberi'; // Veritabanı adı
$username = 'root';      // Veritabanı kullanıcı adı
$password = '';          // Veritabanı şifresi

// PDO ile veritabanına bağlanma
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Hata modunu ayarlama
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
    exit();
}

// Formdan gelen verileri kontrol etme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST parametrelerinden gelen verileri al
    $plaka = trim($_POST['plaka']);      // Plaka kodu
    $sehir_ad = trim($_POST['sehir_ad']); // Şehir adı
    $sehir_bolge = trim($_POST['sehir_bolge']); // Şehir bölgesi

    // Fotoğraf yükleme işlemi
    $sehir_foto = '';
    if (isset($_FILES['sehir_foto']) && $_FILES['sehir_foto']['error'] == 0) {
        // Yüklenen dosyanın adı ve geçici dosya yolu
        $foto_ad = $_FILES['sehir_foto']['name'];
        $foto_tmp = $_FILES['sehir_foto']['tmp_name'];
        
        // Dosya uzantısını kontrol et (Yalnızca resim formatları)
        $foto_uzantisi = strtolower(pathinfo($foto_ad, PATHINFO_EXTENSION));
        $izinli_uzantilar = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($foto_uzantisi, $izinli_uzantilar)) {
            // Fotoğrafı kaydetmek için hedef dizin
            $hedef_dizin = 'uploads/';
            $yeni_foto_ad = uniqid() . '.' . $foto_uzantisi; // Benzersiz dosya adı
            $hedef_yol = $hedef_dizin . $yeni_foto_ad;

            // Fotoğrafı yükle
            if (move_uploaded_file($foto_tmp, $hedef_yol)) {
                $sehir_foto = $hedef_yol; // Fotoğrafın yolu veritabanına kaydedilecek
            } else {
                echo "<p style='color:red;'>Fotoğraf yüklenirken bir hata oluştu.</p>";
            }
        } else {
            echo "<p style='color:red;'>Geçersiz dosya formatı. Lütfen JPG, JPEG, PNG veya GIF formatında bir resim yükleyin.</p>";
        }
    }

    // Basit form doğrulama
    if (empty($plaka) || empty($sehir_ad) || empty($sehir_bolge)) {
        echo "<p style='color:red;'>Lütfen tüm alanları doldurduğunuzdan emin olun.</p>";
    } else {
        // SQL sorgusu ile veritabanına yeni şehir ekleme
        $sql = "INSERT INTO gezirehberii (Plaka, Sehir_Ad, Sehir_Bolge, Sehir_Foto) VALUES (:plaka, :sehir_ad, :sehir_bolge, :sehir_foto)";
        $stmt = $pdo->prepare($sql);

        // Parametreleri bağlama
        $stmt->bindParam(':plaka', $plaka, PDO::PARAM_STR);
        $stmt->bindParam(':sehir_ad', $sehir_ad, PDO::PARAM_STR);
        $stmt->bindParam(':sehir_bolge', $sehir_bolge, PDO::PARAM_STR);
        $stmt->bindParam(':sehir_foto', $sehir_foto, PDO::PARAM_STR);

        // Sorguyu çalıştırma
        if ($stmt->execute()) {
            // Şehir başarıyla eklendiğinde, şehir.php sayfasına yönlendir
            echo "<p style='color:green;'>Şehir başarıyla eklendi!</p>";
            header("Refresh: 2; url=şehir.php"); // 2 saniye sonra yönlendir
            exit(); // Yönlendirme sonrası scripti sonlandır
        } else {
            echo "<p style='color:red;'>Şehir eklenirken bir hata oluştu.</p>";
        }
    }
}

// Veritabanı bağlantısını kapatma
$pdo = null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şehir Ekle</title>
    <link rel="stylesheet" href="sehir.css">  <!-- Stil dosyasını bağlama -->
</head>
<body>
    <header>
        <h1>Şehir Ekle</h1>
    </header>

    <main>
    <!-- Şehir bilgilerini eklemek için form -->
    <form action="sehirekle.php" method="POST" enctype="multipart/form-data">
        <label for="id">Şehir ID:</label>
        <input type="number" id="id" name="id" required><br><br> <!-- ID giriş alanı -->

        <label for="plaka">Plaka Kodu:</label>
        <input type="text" id="plaka" name="plaka" required><br><br>

        <label for="sehir_ad">Şehir Adı:</label>
        <input type="text" id="sehir_ad" name="sehir_ad" required><br><br>

        <label for="sehir_bolge">Şehir Bölgesi:</label>
        <input type="text" id="sehir_bolge" name="sehir_bolge" required><br><br>

        <label for="sehir_foto">Şehir Fotoğrafı:</label>
        <input type="file" id="sehir_foto" name="sehir_foto" accept="image/*"><br><br>

        <input type="submit" value="Şehri Ekle">
    </form>

    <br>
</main>
    <footer>
        <p>&copy; 2024 Gezi Rehberi. Tüm Hakları Saklıdır.</p>
    </footer>
</body>
</html>    