<?php
    // 1. Koneksi ke basis data MySQL latihan_wifi
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "latihan_wifi";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Query mengambil seluruh data sebaran WiFi
    $sql    = "SELECT * FROM sebaran_wifi";
    $result = $conn->query($sql);

    // Array penampung data untuk marker LeafletJS
    $wifi_data = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>WebGIS Sebaran WiFi RT/RW</title>
    <!-- Leaflet CSS Library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
    <style>
        /* Pengaturan pembungkus layar penuh */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Container peta memenuhi layar */
        #map-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Panel melayang untuk tabel CRUD */
        .floating-panel {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 1000; /* Memastikan tabel berada di atas elemen peta */
            background: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-height: 40vh;
            overflow-y: auto; /* Scrollbar jika data tabel panjang */
            max-width: 600px;
        }

        .floating-panel table {
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
        }

        .floating-panel th, .floating-panel td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        .floating-panel th {
            background-color: #f2f2f2;
        }

        .btn-tambah {
            display: inline-block;
            color: blue;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div id="map-container">
        <!-- 3. Container Peta LeafletJS -->
        <div id="map"></div>

        <!-- 4. Panel Mengambang (Floating Panel) Berisi Tabel Data -->
        <div class="floating-panel">
            <h3 style="margin-top: 0;">Data Sebaran WiFi</h3>
            <a href='input/index.html' class="btn-tambah">+ Tambah Data WiFi</a>

            <?php
            if ($result && $result->num_rows > 0) {
                echo "<table>
                        <tr>
                            <th>No</th>
                            <th>Kelurahan</th>
                            <th>RT/RW</th>
                            <th>Pasang</th>
                            <th>Bangunan</th>
                            <th>Alamat Lokasi</th>
                            <th colspan='2'>Aksi</th>
                        </tr>";

                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $wifi_data[] = $row;

                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $row["kelurahan"] . "</td>
                            <td>RT " . $row["rt"] . " / RW " . $row["rw"] . "</td>
                            <td>" . $row["pasang"] . "</td>
                            <td>" . $row["bangunan"] . "</td>
                            <td>" . $row["alamat_lokasi"] . "</td>
                            <td><a href='edit/index.php?id=" . $row["id"] . "'>Edit</a></td>
                            <td><a href='delete.php?id=" . $row["id"] . "'>Hapus</a></td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Belum ada data sebaran WiFi.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Leaflet JavaScript Library -->
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
    <script>
        /* Inisialisasi Peta */
        var map = L.map('map').setView([-7.455, 110.224], 13);

        /* Tile Basemaps */
        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        });

        var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });

        basemap1.addTo(map);

        /* Layer Group untuk Marker WiFi */
        var wifiLayer = L.layerGroup().addTo(map);

        /* Generate Marker Dinamis dari PHP/MySQL */
        <?php
        if (!empty($wifi_data)) {
            foreach ($wifi_data as $data) {
                $lat      = $data['latitude'];
                $lng      = $data['longitude'];
                $kel      = addslashes($data['kelurahan']);
                $rt       = addslashes($data['rt']);
                $rw       = addslashes($data['rw']);
                $pasang   = addslashes($data['pasang']);
                $bangunan = addslashes($data['bangunan']);
                $alamat   = addslashes($data['alamat_lokasi']);

                $popup = "<b>Sebaran WiFi RT/RW</b><br>" .
                         "<b>Kelurahan:</b> $kel<br>" .
                         "<b>RT/RW:</b> RT $rt / RW $rw<br>" .
                         "<b>Pasang:</b> $pasang<br>" .
                         "<b>Bangunan:</b> $bangunan<br>" .
                         "<b>Alamat:</b> $alamat";

                echo "L.marker([$lat, $lng]).addTo(wifiLayer).bindPopup('$popup');\n";
            }
        }
        ?>

        /* Control Layer */
        var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri Imagery": basemap2
        };

        var overlayMaps = {
            "Titik Sebaran WiFi": wifiLayer
        };

        L.control.layers(baseMaps, overlayMaps, {collapsed: false}).addTo(map);

        /* Scale Bar */
        L.control.scale({maxWidth: 150, position: 'bottomleft'}).addTo(map);
    </script>
</body>
</html>
<?php $conn->close(); ?>