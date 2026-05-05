<?php
include '../config/database.php.example';

$id = $_GET['id'];

$query = mysqli_query($conn, "
    SELECT peminjaman.*, barang.nama_barang 
    FROM peminjaman
    JOIN barang ON peminjaman.barang_id = barang.id
    WHERE peminjaman.id = $id
");

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Surat Peminjaman</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            width: 800px;
            margin: auto;
            line-height: 1.6;
        }

        .kop {
            display: flex;
            align-items: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text h2, .kop-text p {
            margin: 0;
        }

        .judul {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .isi {
            text-align: left;
            margin-top: 20px;
        }

        .ttd {
            width: 100%;
            margin-top: 50px;
        }

        .ttd-kanan {
            text-align: right;
        }

        table {
            margin-top: 10px;
        }

        td {
            padding: 5px;
        }
    </style>
</head>
<body>

<!-- KOP SURAT -->
<div class="kop">
    <img src="../image/labinf.png" class="logo">
    <div class="kop-text">
        <h2>LABORATORIUM INFORMATIKA</h2>
        <p>Gedung Laboratorium Bersaama Lantai 1 UNIVERSITAS MADURA</p>
    </div>
</div>

<!-- JUDUL -->
<div class="judul">
    <h3><u>SURAT KETERANGAN PEMINJAMAN BARANG</u></h3>
    <p>No:<?php echo $data['nomor_surat']; ?></p>

<!-- ISI -->
<div class="isi">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

    <table>
        <tr>
            <td>Nama</td>
            <td>: <?= $data['nama_peminjam']; ?></td>
        </tr>
        <tr>
            <td>Barang</td>
            <td>: <?= $data['nama_barang']; ?></td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td>: <?= $data['jml_brng_pinjam']; ?></td>
        </tr>
        <tr>
            <td>Keperluan</td>
            <td>: <?= $data['keperluan']; ?></td>
        </tr>
        <tr>
            <td>Tanggal Pinjam</td>
            <td>: <?= date("d F Y", strtotime($data['tanggal_pinjam'])); ?></td>
        </tr>
    </table>

    <p style="text-align: justify;">
        Barang tersebut dipinjam dalam kondisi baik, lengkap, dan layak digunakan. Peminjam berkewajiban untuk menjaga, merawat, serta menggunakan barang sesuai dengan fungsinya selama masa peminjaman. Apabila di kemudian hari terjadi kerusakan, kehilangan, atau penurunan fungsi barang akibat kelalaian atau penggunaan yang tidak semestinya, maka hal tersebut sepenuhnya menjadi tanggung jawab peminjam. Peminjam wajib memperbaiki atau mengganti barang yang dipinjam sesuai dengan ketentuan yang berlaku di lingkungan instansi.
    </p>

    <p>
        Dengan ini, peminjam menyatakan bersedia mematuhi seluruh ketentuan yang berlaku selama masa peminjaman.
    </p>
</div>

<!-- TANDA TANGAN -->
<div class="ttd">
    <div class="ttd-kanan">
        <p><?= date("d F Y"); ?></p>
        <p>Petugas Laboratorium</p>
        <br><br><br>
        <p><b>(_____________________)</b></p>
    </div>
</div>

<script>
    window.print();
</script>

</body>
</html>