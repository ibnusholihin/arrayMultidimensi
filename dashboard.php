<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Daftar barang
$barang = [
    "01" => ["nama" => "sapu", "harga" => 15000],
    "02" => ["nama" => "televisi",  "harga" => 12000],
    "03" => ["nama" => "speakear", "harga" => 20000],
    "04" => ["nama" => "keyboard",    "harga" => 5000],
    "05" => ["nama" => "mouse",      "harga" => 8000]
];

$kode   = $_POST['kode']   ?? '';
$nama   = $_POST['nama']   ?? '';
$harga  = (int)($_POST['harga'] ?? 0);
$jumlah = (int)($_POST['jumlah'] ?? 0);

$lineTotal   = $harga * $jumlah;
$grandtotal  = $lineTotal;

// Hitung diskon
if ($grandtotal == 0) {
    $d = "0%";
    $diskon = 0;
} elseif ($grandtotal < 50000) {
    $d = "5%";
    $diskon = 0.05 * $grandtotal;
} elseif ($grandtotal <= 100000) {
    $d = "10%";
    $diskon = 0.10 * $grandtotal;
} else {
    $d = "15%";
    $diskon = 0.15 * $grandtotal;
}

$totalbayar = $grandtotal - $diskon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLGAN MART</title>

    <script>
        function autofill() {
            let data = <?php echo json_encode($barang); ?>;
            let kode = document.getElementById("kode").value;

            if (kode in data) {
                document.getElementById("nama").value = data[kode].nama;
                document.getElementById("harga").value = data[kode].harga;
            } else {
                document.getElementById("nama").value = "";
                document.getElementById("harga").value = "";
            }
        }
    </script>

    <style>
       body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
}

.container {
    width: 70%;
    margin: auto;
    background: white;
    padding: 25px;
    margin-top: 30px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    border-radius: 10px;
}

h1 {
    text-align: center;
    background: #4CAF50;
    color: white;
    padding: 12px;
    border-radius: 8px;
}

h2 {
    margin-top: 30px;
    color: #333;
}

label {
    font-weight: bold;
}

input, select {
    width: 95%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #999;
    border-radius: 5px;
}

button {
    padding: 10px 20px;
    background: #4CAF50;
    border: none;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background: #45a049;
}

.logout-btn {
    background: #e53935;
    float: right;
    margin-top: -50px;
}

.logout-btn:hover {
    background: #c62828;
}

table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}

table th {
    background: #4CAF50;
    color: white;
    padding: 10px;
}

table td {
    padding: 8px;
    background: #fafafa;
}

table tr:nth-child(even) td {
    background: #f1f1f1;
}

tfoot td {
    background: #ddd !important;
    font-weight: bold;
}

    </style>
</head>
<body>
<div class="container">
    <h1>--POLGAN MART--</h1>
    <p>SELAMAT DATANG TUAN <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <a href="logout.php"><button class="logout-btn">Logout</button></a>

    <h2>Input Barang</h2>
    <form method="post">
        <div>
            <label>Pilih Kode Barang</label><br>
            <select name="kode" id="kode" onchange="autofill()">
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($kode == $k) ? 'selected' : '' ?>>
                        <?= $k ?> - <?= $v["nama"] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Nama Barang</label><br>
            <input type="text" id="nama" name="nama" readonly value="<?php echo $nama; ?>">
        </div>

        <div>
            <label>Harga</label><br>
            <input type="number" id="harga" name="harga" readonly value="<?php echo $harga; ?>">
        </div>

        <div>
            <label>Jumlah</label><br>
            <input type="number" name="jumlah" min="1" value="<?php echo $jumlah ?: 1; ?>">
        </div>

        <div style="margin-top:8px;">
            <button type="submit">Kirim</button>
        </div>
    </form>

    <h2>Daftar Pembelian</h2>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga (Rp)</th>
                <th>Jumlah</th>
                <th>Total Baris (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $kode ?></td>
                <td><?= $nama ?></td>
                <td style="text-align:right;"><?= number_format($harga,0,',','.') ?></td>
                <td style="text-align:center;"><?= $jumlah ?></td>
                <td style="text-align:right;"><?= number_format($lineTotal,0,',','.') ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Subtotal</strong></td>
                <td style="text-align:right;"><?= number_format($grandtotal,0,',','.') ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Diskon (<?= $d ?>)</strong></td>
                <td style="text-align:right;"><?= number_format($diskon,0,',','.') ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total Bayar</strong></td>
                <td style="text-align:right;"><?= number_format($totalbayar,0,',','.') ?></td>
            </tr>
        </tfoot>
    </table>

</div>
</body>
</html>