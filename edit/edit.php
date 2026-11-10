<?php
// 1. Memeriksa apakah berkas diakses menggunakan metode pengiriman POST (dari formulir edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Menangkap seluruh data variabel yang dikirimkan melalui metode POST dari formulir edit
    $id            = $_POST['id'];
    $latitude      = $_POST['latitude'];
    $longitude     = $_POST['longitude'];
    $kelurahan     = $_POST['kelurahan'];
    $rw            = $_POST['rw'];
    $rt            = $_POST['rt'];
    $pasang        = $_POST['pasang'];
    $bangunan      = $_POST['bangunan'];
    $alamat_lokasi = $_POST['alamat_lokasi'];

    // 3. Konfigurasi kredensial koneksi ke basis data MySQL
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "latihan_wifi";

    // 4. Membuat objek koneksi baru menggunakan kelas mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 5. Memeriksa status keberhasilan koneksi ke basis data
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 6. Menyusun query SQL UPDATE untuk memperbarui data pada tabel sebaran_wifi berdasarkan ID
    $sql = "UPDATE sebaran_wifi SET 
                latitude = '$latitude', 
                longitude = '$longitude', 
                kelurahan = '$kelurahan', 
                rw = '$rw', 
                rt = '$rt', 
                pasang = '$pasang', 
                bangunan = '$bangunan', 
                alamat_lokasi = '$alamat_lokasi' 
            WHERE id = $id";

    // 7. Mengeksekusi query dan memeriksa keberhasilan proses pembaruan data
    if ($conn->query($sql) === TRUE) {
        // Menampilkan alert JavaScript dan mengalihkan layar kembali ke halaman utama index.php
        echo "<script>
                alert('Data lokasi WiFi berhasil diperbarui!');
                window.location.href='../index.php';
              </script>";
    } else {
        // Menampilkan pesan kesalahan jika query UPDATE gagal dieksekusi
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 8. Menutup koneksi aktif ke basis data MySQL
    $conn->close();
} else {
    // 9. Mengalihkan layar secara otomatis ke index.php jika berkas diakses langsung tanpa lewat form submit
    header("Location: ../index.php");
    exit();
}
?>