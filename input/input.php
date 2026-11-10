<?php
    // 1. Menangkap seluruh data variabel yang dikirimkan melalui metode POST dari formulir input
    $latitude      = $_POST['latitude'];
    $longitude     = $_POST['longitude'];
    $kelurahan     = $_POST['kelurahan'];
    $rw            = $_POST['rw'];
    $rt            = $_POST['rt'];
    $pasang        = $_POST['pasang'];
    $bangunan      = $_POST['bangunan'];
    $alamat_lokasi = $_POST['alamat_lokasi'];

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

    // 5. Menyusun query SQL INSERT INTO untuk menyimpan record data baru ke dalam tabel sebaran_wifi
    $sql = "INSERT INTO sebaran_wifi (latitude, longitude, kelurahan, rw, rt, pasang, bangunan, alamat_lokasi) 
            VALUES ('$latitude', '$longitude', '$kelurahan', '$rw', '$rt', '$pasang', '$bangunan', '$alamat_lokasi')";

    // 6. Mengeksekusi query dan menampilkan pesan konfirmasi keberhasilan beserta tautan navigasi balik
    if ($conn->query($sql) === TRUE) {
        echo "Data WiFi berhasil ditambahkan!<br><br>";
        echo "<a href='../index.php'>Kembali ke Halaman Utama</a>";
    } else {
        // Menampilkan pesan error jika query INSERT gagal dieksekusi
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 7. Menutup koneksi aktif ke basis data MySQL
    $conn->close();
?>