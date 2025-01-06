<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şehir Sil</title>
    <link rel="stylesheet" href="sehir.css">  <!-- Stil dosyasını bağlama -->
</head>
<body>
    <header>
        <h1>Şehir Sil</h1>
    </header>

    <main>
        <!-- Silmek istediğiniz şehir ID'sini alacak form -->
        <form method="post" action="">
            <label for="id">Silmek İstediğiniz Şehrin ID'si:</label>
            <input type="number" id="id" name="id" required min="0">
            <button type="submit">Sil</button>
        </form>

        <?php
        // Veritabanı bağlantı bilgileri
        $servername = "localhost";
        $username = "root";  // phpMyAdmin kullanıcı adı
        $password = "";      // phpMyAdmin şifresi (boş)
        $dbname = "gezirehberi";  // Veritabanı adı

        // MySQL bağlantısını kur
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Bağlantıyı kontrol et
        if ($conn->connect_error) {
            die("Bağlantı başarısız: " . $conn->connect_error);
        }

        // Silme işlemi
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Formdan gelen ID'yi kontrol et
            if (isset($_POST['id']) && $_POST['id'] !== '') {
                $silinecek_id = $_POST['id'];  // Silinecek şehir ID'si

                // ID'nin tamsayı olup olmadığını kontrol et (0 dahil)
                if (!is_numeric($silinecek_id) || $silinecek_id < 0) {
                    echo "<p style='color:red;'>Geçerli bir şehir ID'si girin.</p>";
                } else {
                    // Silme sorgusu
                    $sql = "DELETE FROM gezirehberii WHERE ID = ?";

                    // Sorguyu hazırlama ve çalıştırma
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("i", $silinecek_id); // Parametreyi bağla

                        // Silme işlemini çalıştır
                        if ($stmt->execute()) {
                            echo "<p style='color:green;'>ID $silinecek_id olan şehir başarıyla silindi.</p>";
                        } else {
                            echo "<p style='color:red;'>Silme işlemi sırasında hata oluştu: " . $stmt->error . "</p>";
                        }

                        // Sorguyu kapat
                        $stmt->close();
                    } else {
                        echo "<p style='color:red;'>SQL sorgusu hazırlanırken bir hata oluştu.</p>";
                    }
                }
            } else {
                echo "<p style='color:red;'>Lütfen geçerli bir şehir ID'si girin.</p>";
            }
        }

        // Bağlantıyı kapat
        $conn->close();
        ?>
    </main>

    <footer>
        <p>&copy; 2024 Gezi Rehberi. Tüm Hakları Saklıdır.</p>
    </footer>
</body>
</html>
