<?php

require_once __DIR__ . "/../config/database.php";

session_start();

/*
|--------------------------------------------------------------------------
| VALIDASI METHOD
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: tambah_peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA FORM
|--------------------------------------------------------------------------
*/

$id_admin         = $_SESSION['id_admin'] ?? null;
$nama_peminjam    = trim($_POST['nama_peminjam'] ?? '');
$tanggal_pinjam   = $_POST['tanggal_pinjam'] ?? '';
$tanggal_kembali  = $_POST['tanggal_kembali'] ?? '';
$barang_dipilih   = $_POST['barang'] ?? [];

/*
|--------------------------------------------------------------------------
| VALIDASI INPUT
|--------------------------------------------------------------------------
*/

if (
    empty($nama_peminjam) ||
    empty($tanggal_pinjam) ||
    empty($tanggal_kembali)
) {

    $_SESSION['error'] = "Semua field wajib diisi";

    header("Location: tambah_peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| VALIDASI BARANG
|--------------------------------------------------------------------------
*/

if (empty($barang_dipilih)) {

    $_SESSION['error'] = "Pilih minimal 1 barang";

    header("Location: tambah_peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| VALIDASI TANGGAL
|--------------------------------------------------------------------------
*/

if ($tanggal_kembali < $tanggal_pinjam) {

    $_SESSION['error'] = "Tanggal kembali tidak valid";

    header("Location: tambah_peminjaman.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| MULAI TRANSACTION
|--------------------------------------------------------------------------
|
| Transaction penting agar:
|
| - insert peminjaman aman
| - insert detail aman
| - update status barang aman
| - jika gagal semua rollback
|
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($conn);

try {

    /*
    |--------------------------------------------------------------------------
    | INSERT TABLE PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    $query_peminjaman = mysqli_query($conn, "
        INSERT INTO peminjaman (

            id_admin,
            nama_peminjam,
            tanggal_pinjam,
            tanggal_kembali,
            status_pinjam

        ) VALUES (

            '$id_admin',
            '$nama_peminjam',
            '$tanggal_pinjam',
            '$tanggal_kembali',
            'Proses'
        )
    ");

    /*
    |--------------------------------------------------------------------------
    | VALIDASI INSERT
    |--------------------------------------------------------------------------
    */

    if (!$query_peminjaman) {

        throw new Exception("Gagal menyimpan data peminjaman");
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID PEMINJAMAN TERAKHIR
    |--------------------------------------------------------------------------
    */

    $id_peminjaman = mysqli_insert_id($conn);

    /*
    |--------------------------------------------------------------------------
    | LOOP BARANG DIPILIH
    |--------------------------------------------------------------------------
    */

    foreach ($barang_dipilih as $id_detail) {

        $id_detail = (int) $id_detail;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI BARANG MASIH TERSEDIA
        |--------------------------------------------------------------------------
        */

        $cek_barang = mysqli_query($conn, "
            SELECT *
            FROM barang_detail
            WHERE id_detail = '$id_detail'
            AND status = 'Tersedia'
        ");

        /*
        |--------------------------------------------------------------------------
        | JIKA BARANG SUDAH TIDAK TERSEDIA
        |--------------------------------------------------------------------------
        */

        if (mysqli_num_rows($cek_barang) == 0) {

            throw new Exception("Ada barang yang sudah tidak tersedia");
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT PEMINJAMAN DETAIL
        |--------------------------------------------------------------------------
        */

        $insert_detail = mysqli_query($conn, "
            INSERT INTO peminjaman_detail (

                id_peminjaman,
                id_detail

            ) VALUES (

                '$id_peminjaman',
                '$id_detail'
            )
        ");

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INSERT DETAIL
        |--------------------------------------------------------------------------
        */

        if (!$insert_detail) {

            throw new Exception("Gagal menyimpan detail peminjaman");
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS BARANG
        |--------------------------------------------------------------------------
        |
        | Barang otomatis menjadi:
        | Tidak Tersedia
        |
        |--------------------------------------------------------------------------
        */

        $update_barang = mysqli_query($conn, "
            UPDATE barang_detail
            SET status = 'Tidak Tersedia'
            WHERE id_detail = '$id_detail'
        ");

        /*
        |--------------------------------------------------------------------------
        | VALIDASI UPDATE
        |--------------------------------------------------------------------------
        */

        if (!$update_barang) {

            throw new Exception("Gagal update status barang");
        }

    }

    /*
    |--------------------------------------------------------------------------
    | COMMIT TRANSACTION
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    $_SESSION['success'] = "Peminjaman berhasil ditambahkan";

    header("Location: peminjaman.php");
    exit;

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | ROLLBACK JIKA ERROR
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    /*
    |--------------------------------------------------------------------------
    | ERROR MESSAGE
    |--------------------------------------------------------------------------
    */

    $_SESSION['error'] = $e->getMessage();

    header("Location: tambah_peminjaman.php");
    exit;
}
?>