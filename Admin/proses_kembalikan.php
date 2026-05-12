<?php

require_once __DIR__ . "/../config/database.php";

session_start();

/*
|--------------------------------------------------------------------------
| VALIDASI METHOD
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: kembalikan.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA
|--------------------------------------------------------------------------
*/

$id_peminjaman     = (int) ($_POST['id_peminjaman'] ?? 0);

$id_detail         = $_POST['id_detail'] ?? [];

$id_pinjam_detail  = $_POST['id_pinjam_detail'] ?? [];

$kondisi           = $_POST['kondisi'] ?? [];

$tanggal_sekarang  = date('Y-m-d');

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if ($id_peminjaman <= 0) {

    $_SESSION['error'] = "ID peminjaman tidak valid";

    header("Location: kembalikan.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| VALIDASI DATA ARRAY
|--------------------------------------------------------------------------
*/

if (
    empty($id_detail) ||
    empty($id_pinjam_detail) ||
    empty($kondisi)
) {

    $_SESSION['error'] = "Data pengembalian tidak lengkap";

    header("Location: kembalikan.php?id=$id_peminjaman");
    exit;
}

/*
|--------------------------------------------------------------------------
| TRANSACTION
|--------------------------------------------------------------------------
*/

mysqli_begin_transaction($conn);

try {

    /*
    |--------------------------------------------------------------------------
    | LOOP BARANG YANG DIKEMBALIKAN
    |--------------------------------------------------------------------------
    */

    for ($i = 0; $i < count($id_detail); $i++) {

        $detail_id        = (int) $id_detail[$i];

        $pinjam_detail_id = (int) $id_pinjam_detail[$i];

        $kondisi_barang   = mysqli_real_escape_string($conn, $kondisi[$i]);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI KONDISI
        |--------------------------------------------------------------------------
        */

        if (empty($kondisi_barang)) {

            throw new Exception("Semua kondisi barang wajib diisi");
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PEMINJAMAN DETAIL
        |--------------------------------------------------------------------------
        |
        | Menandakan barang ini SUDAH dikembalikan
        |
        |--------------------------------------------------------------------------
        */

        $update_detail = mysqli_query($conn, "
            UPDATE peminjaman_detail
            SET

                tgl_kembali = '$tanggal_sekarang',
                kondisi_saat_kembali = '$kondisi_barang'

            WHERE id_pinjam_detail = '$pinjam_detail_id'
        ");

        if (!$update_detail) {

            throw new Exception("Gagal update detail pengembalian");
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE BARANG DETAIL
        |--------------------------------------------------------------------------
        |
        | Barang kembali tersedia
        |
        |--------------------------------------------------------------------------
        */

        $update_barang = mysqli_query($conn, "
            UPDATE barang_detail
            SET

                status = 'Tersedia',
                kondisi = '$kondisi_barang'

            WHERE id_detail = '$detail_id'
        ");

        if (!$update_barang) {

            throw new Exception("Gagal update status barang");
        }

    }

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH MASIH ADA BARANG YANG BELUM KEMBALI
    |--------------------------------------------------------------------------
    |
    | Jika masih ada:
    | status tetap Proses
    |
    | Jika semua sudah kembali:
    | status jadi Selesai
    |
    |--------------------------------------------------------------------------
    */

    $cek_sisa = mysqli_query($conn, "
        SELECT *
        FROM peminjaman_detail
        WHERE id_peminjaman = '$id_peminjaman'
        AND tgl_kembali IS NULL
    ");

    /*
    |--------------------------------------------------------------------------
    | JIKA SEMUA SUDAH KEMBALI
    |--------------------------------------------------------------------------
    */

    if (mysqli_num_rows($cek_sisa) == 0) {

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        $update_peminjaman = mysqli_query($conn, "
            UPDATE peminjaman
            SET status_pinjam = 'Selesai'
            WHERE id_peminjaman = '$id_peminjaman'
        ");

        if (!$update_peminjaman) {

            throw new Exception("Gagal update status peminjaman");
        }

    }

    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    mysqli_commit($conn);

    /*
    |--------------------------------------------------------------------------
    | SUCCESS MESSAGE
    |--------------------------------------------------------------------------
    */

    $_SESSION['success'] = "Pengembalian barang berhasil diproses";

    header("Location: detail_peminjaman.php?id=$id_peminjaman");
    exit;

} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    mysqli_rollback($conn);

    /*
    |--------------------------------------------------------------------------
    | ERROR
    |--------------------------------------------------------------------------
    */

    $_SESSION['error'] = $e->getMessage();

    header("Location: kembalikan.php?id=$id_peminjaman");
    exit;
}
?>