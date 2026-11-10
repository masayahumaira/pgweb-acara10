<?php
    // 1. Menangkap ID data yang dikirimkan melalui URL (metode GET) dari tautan 'Hapus'
    $id = $_GET['id'];

    // 2. Konfigurasi kredensial koneksi ke basis data MySQL
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "latihan_wifi";

    // 3. Membuat objek koneksi baru menggunakan kelas mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 4. Memeriksa status keberhasilan koneksi ke basis data
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 5. Menyusun perintah query SQL DELETE untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM sebaran_wifi WHERE id = $id";

    // 6. Mengeksekusi query dan memeriksa apakah proses penghapusan data berhasil
    if ($conn->query($sql) === TRUE) {
        // Menampilkan pesan alert JavaScript dan mengalihkan layar kembali ke index.php
        echo "<script>
                alert('Data lokasi WiFi berhasil dihapus!');
                window.location.href='index.php';
              </script>";
    } else {
        // Menampilkan pesan kesalahan jika query DELETE gagal dieksekusi
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 7. Menutup koneksi aktif ke basis data MySQL
    $conn->close();
?>