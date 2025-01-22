<?php
session_start();
include '../../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: unauthorized.php');
    exit;
}

if ((isset($_GET['tutup_adm'])) && ($_GET['tutup_adm'] == true)) {
    $id_periode = $_GET['id_periode'];
    $update = $koneksi->query(
        "UPDATE periode SET tutup_periode_administrasi = '1' WHERE id = $id_periode"
    );
    $adms = $koneksi->query(
        "SELECT kelurahan.id, kelurahan.kelurahan, SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi JOIN nilai_sub_indikator ON administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id JOIN kelurahan ON administrasi.id_kelurahan = kelurahan.id WHERE administrasi.id_periode = $id_periode GROUP BY administrasi.id_kelurahan, kelurahan.kelurahan ORDER BY total_nilai_akhir DESC LIMIT 4;"
    );
    foreach ($adms as $adm) {
        $id_kelurahan = $adm['id'];
        $insert = $koneksi->query(
            "INSERT INTO alternatif (id_periode, id_kelurahan) VALUES ($id_periode, '$id_kelurahan')"
        );

        $alternatif = $koneksi->query(
            "SELECT * FROM alternatif WHERE id_periode = $id_periode AND id_kelurahan = '$id_kelurahan'"
        )->fetch_assoc();
        $id_alternatif = $alternatif['id'];

        $data_administrasi = $koneksi->query(
            "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode;"
        );
        foreach ($data_administrasi as $data_administrasi_item) {
            $id_nilai_sub_indikator = $data_administrasi_item['id_nilai_sub_indikator'];
            $tak_bernilai = $data_administrasi_item['tak_bernilai'];
            $insert = $koneksi->query(
                "INSERT INTO sub_verifikasi_lapangan (id_alternatif, id_nilai_sub_indikator, tak_bernilai) VALUES ($id_alternatif, $id_nilai_sub_indikator, '$tak_bernilai')"
            );
        }
    }
    $periode = $koneksi->query(
        "SELECT * FROM periode WHERE id = '$id_periode'"
    )->fetch_assoc();
    $nama_periode = $periode['periode'];
    if ($update) {
        $success = 'Periode adimistrasi ' . $nama_periode . ' telah ditutup!';
    } else {
        $failed = 'Periode adimistrasi ' . $nama_periode . ' gagal ditutup!';
    }
}
if ((isset($_GET['tutup_periode'])) && ($_GET['tutup_periode'] == true)) {
    $id_periode = $_GET['id_periode'];
    $update = $koneksi->query(
        "UPDATE periode SET tutup_periode = '1' WHERE id = $id_periode"
    );
    $periode = $koneksi->query(
        "SELECT * FROM periode WHERE id = '$id_periode'"
    )->fetch_assoc();
    $nama_periode = $periode['periode'];
    if ($update) {
        $success = 'Periode ' . $nama_periode . ' telah ditutup!';
    } else {
        $failed = 'Periode ' . $nama_periode . ' gagal ditutup!';
    }
}

if ((isset($_GET['action'])) && ($_GET['action'] == 'add')) {
    $periode = $_POST['periode'];

    $checkPeriode = $koneksi->query(
        "SELECT * FROM periode WHERE periode = '$periode'"
    );

    if ($checkPeriode->num_rows != 0) {
        $failed = 'Periode sudah ada!!';
    } else {
        $insert = $koneksi->query(
            "INSERT INTO periode (periode) VALUES ('$periode')"
        );
        if ($insert) {
            $success = 'Berhasil menambahkan periode!';
        } else {
            $failed = 'Gagal menambahkan periode!';
        }
    }
}
if ((isset($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $status = $_GET['status'];

    if ($status == 'success') {
        $success = 'Berhasil menghapus periode!';
    } else if ($status == 'failed') {
        $failed = 'Gagal menghapus periode!!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Kelurahan Terbaik</title>
    <link rel="shortcut icon" type="x-ixon" href="gambar/Logo Pemkot Padang.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/js/adminlte.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Kelola Periode</h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $success ?>
                    </div>
                <?php elseif (isset($failed)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> <?= $failed ?>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#periodAdd">
                                    <i class="fas fa-solid fa-plus"></i> Tambah Periode
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No</th>
                                            <th style="text-align: center;">Periode</th>
                                            <th style="text-align: center;">Periode Administrasi</th>
                                            <th style="text-align: center;">Status Periode</th>
                                            <th style="text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $periods = $koneksi->query(
                                            'SELECT * FROM periode ORDER BY periode DESC'
                                        );
                                        $no = 1;
                                        ?>
                                        <?php foreach ($periods as $periode): ?>
                                            <tr align="center">
                                                <td><?= $no ?></td>
                                                <td>
                                                    <?= $periode['periode'] ?>
                                                </td>
                                                <td>
                                                    <?php if ($periode['tutup_periode_administrasi'] == '0'): ?>
                                                        <a href="#" class="btn-close-period-adm" data-id-periode="<?= $periode['id'] ?>" data-nama-periode="<?= $periode['periode'] ?>">
                                                            Dibuka
                                                        </a>
                                                    <?php else: ?>
                                                        Ditutup
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($periode['tutup_periode'] == '0'): ?>
                                                        <?php if ($periode['tutup_periode_administrasi'] == '1'): ?>
                                                            <a href="#" class="btn-close-period" data-id-periode="<?= $periode['id'] ?>" data-nama-periode="<?= $periode['periode'] ?>">
                                                                Dibuka
                                                            </a>
                                                        <?php else: ?>
                                                            Dibuka
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        Ditutup
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusPeriode('<?= $periode['id']; ?>','<?= $periode['periode']; ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php $no++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Tambah Data-->
                </div>
            </section>
        </div>
        <!-- Modal Tambah periode-->
        <div class="modal fade" id="periodAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Periode</h5>
                    </div>
                    <div class="modal-body">
                        <form class="mx-3" action="lihat.php?action=add" method="POST" onsubmit="">
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <input type="number" class="form-control" id="periode" name="periode" min="2020" max="2024" required>
                            </div>
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Membuat data table memiliki fungsi show dan search
            $(document).ready(function() {
                $('#myTable').DataTable(); //Mengubah tabel dengan ID myTable menjadi tabel yang interaktif dengan fitur pencarian, paginasi, dan pengurutan.
            });


            ClassicEditor
                .create(document.querySelector('#detail'))
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    </div>
    <script>
        function hapusPeriode(id = null, periode = null) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger mr-3'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Hapus periode ini?',
                text: "Anda akan menghapus periode " + periode,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, yakin!',
                cancelButtonText: 'Tidak, batal!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/admin/periode/hapus.php?id=' + id;
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {

                }
            })
        }
    </script>
    <script>
        // Event listener untuk semua elemen dengan class "btn-close-period-adm"
        document.querySelectorAll('.btn-close-period-adm').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah aksi default dari tag <a>

                const idPeriode = this.getAttribute('data-id-periode'); // Ambil data id_periode
                const namaPeriode = this.getAttribute('data-nama-periode'); // Ambil data id_periode

                // SweetAlert konfirmasi
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin menutup periode administrasi ${namaPeriode}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tutup!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke halaman dengan membawa parameter id_periode
                        window.location.href = `?tutup_adm=true&id_periode=${idPeriode}`;
                    }
                });
            });
        });
        // Event listener untuk semua elemen dengan class "btn-close-period"
        document.querySelectorAll('.btn-close-period').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah aksi default dari tag <a>

                const idPeriode = this.getAttribute('data-id-periode'); // Ambil data id_periode
                const namaPeriode = this.getAttribute('data-nama-periode'); // Ambil data id_periode

                // SweetAlert konfirmasi
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin menutup periode ${namaPeriode}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tutup!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect ke halaman dengan membawa parameter id_periode
                        window.location.href = `?tutup_periode=true&id_periode=${idPeriode}`;
                    }
                });
            });
        });
    </script>
</body>

</html>