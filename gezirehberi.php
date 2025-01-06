<?php
// Veritabanı bağlantı bilgileri
$servername = "localhost";  // Sunucu adı
$username = "root";         // Veritabanı kullanıcı adı
$password = "";             // Veritabanı şifresi
$dbname = "gezirehberi";    // Veritabanı adı

// MySQLi ile veritabanına bağlanma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol etme
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

// SQL sorgusunu hazırlama
$sql = "SELECT ID, Plaka, Sehir_Ad, Sehir_Bolge FROM gezirehberii";
$result = $conn->query($sql);

// HTML kısmı
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gezi Rehberi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Gezi Rehberi</h1>

    <?php
    // Verileri tablo şeklinde yazdırma
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Plaka</th>
                    <th>Şehir Ad</th>
                    <th>Şehir Bölge</th>
                </tr>";
        
        // Her bir satır için döngü
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["ID"]) . "</td>
                    <td>" . htmlspecialchars($row["Plaka"]) . "</td>
                    <td>" . htmlspecialchars($row["Sehir_Ad"]) . "</td>
                    <td>" . htmlspecialchars($row["Sehir_Bolge"]) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Veri bulunamadı.</p>";
    }

    // Bağlantıyı kapatma
    $conn->close();
    ?>

</body>
</html>
