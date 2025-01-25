<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'kelurahan') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: /autentikasi/unauthorized.php');
    exit;
}

$user_id = $_SESSION['id_user'];

$users = $koneksi->query(
    "SELECT * FROM users WHERE id = $user_id;"
)->fetch_assoc();

$id_kelurahan = $users['id_kelurahan'];

$administrasi = $koneksi->query(
    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan';"
)

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
        <?php include '../layouts/header.php'; ?>
        <?php include '../layouts/kelurahan_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Administrasi</h1>
                </div>
                <?php if ((isset($_GET['action'])) && ($_GET['status'] == 'success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Data administrasi kelurahan berhasil <?= ($_GET['action'] == 'add') ? 'diperbarui' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <?php if ((isset($_GET['add'])) && ($_GET['add'] == 'failed')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Data administrasi kelurahan gagal <?= ($_GET['action'] == 'add') ? 'diperbarui' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <!-- End Page Title -->
                <!-- Home Page -->
                <!-- Home Page -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <select name="orbitas" id="orbitas" class="form-control" onchange="changeAdministrasiPeriode(this.value)" required>
                                        <?php
                                        $user_id = $_SESSION['id_user'];

                                        $result = $koneksi->query(
                                            "SELECT * FROM users RIGHT JOIN kelurahan ON users.id_kelurahan = kelurahan.id WHERE users.id = $user_id;"
                                        );

                                        $kelurahan = $result->fetch_assoc();

                                        $periods = $koneksi->query(
                                            'SELECT * FROM periode ORDER BY id DESC'
                                        );
                                        $id_kelurahan = $kelurahan['id'];
                                        ?>
                                        <?php foreach ($periods as $periode): ?>
                                            <?php
                                            $id_periode = $periode['id'];
                                            $administrasi = $koneksi->query(
                                                "SELECT * FROM administrasi, nilai_sub_indikator WHERE administrasi.id_kelurahan = '$id_kelurahan' AND administrasi.id_periode = $id_periode AND administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id"
                                            );
                                            $val_administrasi = [$id_kelurahan, $id_periode];
                                            if ($administrasi->num_rows == 0) {
                                                // $val_administrasi = [$id_kelurahan, $id_periode, "-", "-", "-", "-", "-", "-", "-"];
                                                for ($i = 0; $i < 179; $i++) {
                                                    $val_administrasi[] = "-";
                                                    $val_administrasi[] = "-";
                                                }
                                            } else {
                                                // $administrasi = $administrasi->fetch_assoc();
                                                foreach ($administrasi as $administrasi_satu) {
                                                    $val_administrasi[] = $administrasi_satu['nama_nilai_sub_indikator'];
                                                    $val_administrasi[] = $administrasi_satu['point'];
                                                }
                                            }

                                            $val_administrasi = implode("| ", $val_administrasi);

                                            ?>
                                            <option value="<?= $val_administrasi ?>">Periode <?= $periode['periode'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                            $periods = $koneksi->query(
                                'SELECT * FROM periode ORDER BY id DESC'
                            );
                            $data = $periods->fetch_assoc();

                            $id_periode = $data['id'];

                            $administrasi_first = $koneksi->query(
                                "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                            );

                            ?>
                            <div class="col-auto">
                                <a href="#" id="buttonEdit" onclick="periodeADMStat('<?= $id_kelurahan ?>','<?= $id_periode ?>')">
                                    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#datawilayahedit">
                                        <i class="fas fa-solid fa-edit"></i> Edit
                                    </button>
                                </a>
                            </div>
                        </div>
                        <?php
                        $bidangs = $koneksi->query(
                            "SELECT * FROM bidang"
                        );
                        $indikators = $koneksi->query(
                            "SELECT * FROM indikator"
                        );
                        $sub_indikators = $koneksi->query(
                            "SELECT * FROM sub_indikator"
                        );
                        $nilai_sub_indikators = $koneksi->query(
                            "SELECT * FROM nilai_sub_indikator"
                        );
                        $i = 1;
                        ?>

                        <?php foreach ($bidangs as $bidang): ?>
                            <h4 class="mt-5" style="font-weight: bold;">Bidang <?= $bidang['nama_bidang'] ?></h4>
                            <hr style="border: 1px solid black;">
                            <?php foreach ($indikators as $indikator): ?>
                                <?php if ($indikator['id_bidang'] == $bidang['id']): ?>
                                    <div class="border border-secondary rounded m-3 p-2">
                                        <h5 style="font-weight: bold;">Indikator: <?= $indikator['nama_indikator'] ?></h5>
                                        <?php if ($indikator['id'] == '60'): ?>
                                            <?php if ($administrasi_first->num_rows == 0): ?>
                                                <div class="form-group p-2 m-1">
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <td style="width: 50%;"><span id="nilai<?= $i ?>">-</span></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <?php

                                                $adm_tak_bernilai = $koneksi->query(
                                                    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = '$id_periode' AND tak_bernilai = '1'"
                                                )->fetch_assoc();

                                                if ($adm_tak_bernilai['id_nilai_sub_indikator'] == '372') {
                                                    $pencaharian = 'Pertanian';
                                                } elseif ($adm_tak_bernilai['id_nilai_sub_indikator'] == '373') {
                                                    $pencaharian = 'Industri';
                                                } elseif ($adm_tak_bernilai['id_nilai_sub_indikator'] == '374') {
                                                    $pencaharian = 'Jasa';
                                                }

                                                echo '<div class="form-group p-2 m-1"><table style="width: 100%;"><tr><td style="width: 50%;"><span id="nilai' . $i . '">' . $pencaharian . '</span></td></tr></table></div>';

                                                ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php foreach ($sub_indikators as $sub_indikator): ?>
                                                <?php if ($indikator['id'] == $sub_indikator['id_indikator']): ?>
                                                    <div class="form-group p-2 m-1">
                                                        <label for="usia_kurang_15"><?= $sub_indikator['nama_sub_indikator'] ?></label>
                                                        <?php if ($administrasi_first->num_rows == 0): ?>
                                                            <table style="width: 100%;">
                                                                <tr>
                                                                    <td style="width: 50%;"><span id="nilai<?= ($i == 171) ? $i + 1 : $i ?>">-</span></td>
                                                                    <td style="width: 50%;">Poin : <span id="poin<?= ($i == 171) ? $i + 1 : $i ?>">-</span></td>
                                                                </tr>
                                                            </table>
                                                        <?php else: ?>
                                                            <?php foreach ($nilai_sub_indikators as $nilai_sub_indikator): ?>
                                                                <?php if ($sub_indikator['id'] == $nilai_sub_indikator['id_sub_indikator']): ?>
                                                                    <?php

                                                                    $adms = $koneksi->query(
                                                                        "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                                                                    );
                                                                    if ($adms) {
                                                                        foreach ($adms as $adm) {
                                                                            if ($adm['id_nilai_sub_indikator'] == $nilai_sub_indikator['id']) {
                                                                                echo '<table style="width: 100%;"><tr><td style="width: 50%;"><span id="nilai' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['nama_nilai_sub_indikator'] . '</span></td><td style="width: 50%;">Poin : <span id="poin' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['point'] . '</span></td></tr></table>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                    if ($i == 171) {
                                                        $i += 2;
                                                    } else {
                                                        $i++;
                                                    }
                                                    ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
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

    <script>
        function changeAdministrasiPeriode(administrasi = null) {
            // console.log(kelurahan_periode);
            let resultArray = administrasi.split("| ");

            <?php

            for ($i = 1; $i <= 179; $i++) {
                echo 'document.getElementById("nilai' . $i . '").textContent = resultArray[' . ($i * 2) . '];';
                if ($i != 171) {
                    echo 'document.getElementById("poin' . $i . '").textContent = resultArray[' . (($i * 2) + 1) . '];';
                }
            }

            ?>

            // Output the resulting array
            // console.log(resultArray[6]);
            // document.getElementById("usia_kurang_15").textContent = resultArray[2];
            // document.getElementById("usia_15_56").textContent = resultArray[3];
            // document.getElementById("usia_lebih_56").textContent = resultArray[4];
            // document.getElementById("penduduk_total").textContent = resultArray[5];
            // document.getElementById("penduduk_laki_laki").textContent = resultArray[6];
            // document.getElementById("penduduk_perempuan").textContent = resultArray[7];
            // document.getElementById("jumlah_kepala_keluarga").textContent = resultArray[8];

            // document.getElementById("buttonEdit").setAttribute("href", "/kelurahan/edit_adm_kelurahan.php?id_kelurahan=" + resultArray[0] + "&id_periode=" + resultArray[1]);
            document.getElementById("buttonEdit").setAttribute("onclick", "periodeADMStat('" + resultArray[0] + "','" + resultArray[1] + "')");
        }
    </script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk mengganti data tabel sesuai periode
            window.periodeADMStat = function(kelurahanId, periodeId) {
                $.ajax({
                    url: 'periodeADMStat.php', // Endpoint PHP untuk mendapatkan data berdasarkan periode
                    type: 'POST',
                    data: {
                        periode: periodeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data.tutup_periode_administrasi === "1") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Periode administrasi telah ditutup!',
                                text: 'Anda tidak bisa mengubah data administrasi pada periode ini.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
                            });
                        } else {
                            window.location.href = '/kelurahan/edit_adm_kelurahan.php?id_kelurahan=' + kelurahanId + '&id_periode=' + periodeId;
                        }
                        // // Bersihkan tabel
                        // table.clear();

                        // // Tambahkan data baru ke tabel
                        // response.data.forEach(row => {
                        //     table.row.add([
                        //         row.ranking,
                        //         row.kelurahan,
                        //         row.hasil,
                        //     ]);
                        // });

                        // // Render ulang tabel
                        // table.draw();
                    },
                    error: function() {
                        alert('Gagal memuat data. Silakan coba lagi.');
                    }
                });
            };
        });
    </script>
</body>

</html>