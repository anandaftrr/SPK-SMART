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

$id_periode = $_GET['id_periode'];
if (isset($_GET['id_kelurahan']) && isset($_GET['id_sub_indikator'])) {
    $id_kelurahan = $_GET['id_kelurahan'];
    $id_sub_indikator = $_GET['id_sub_indikator'];
} else {
    $user_id = $_SESSION['id_user'];

    $users = $koneksi->query(
        "SELECT * FROM users WHERE id = $user_id;"
    )->fetch_assoc();

    $id_kelurahan = $users['id_kelurahan'];

    $sis = $koneksi->query(
        "SELECT * FROM sub_indikator"
    );

    foreach ($sis as $si) {
        $nsis = $koneksi->query(
            "SELECT * FROM nilai_sub_indikator WHERE id_sub_indikator = " . $si['id'] . ";"
        );
        $tobefilled = true;
        foreach ($nsis as $nsi) {
            $administrasi = $koneksi->query(
                "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode AND id_nilai_sub_indikator =" . $nsi['id'] . ";"
            );
            if ($administrasi->num_rows > 0) {
                $tobefilled = false;
                break;
            }
        }
        if ($tobefilled) {
            $id_sub_indikator = $si['id'];
            break;
        }
    }
}
if (($id_sub_indikator == '172') || ($id_sub_indikator == '173') || ($id_sub_indikator == '174')) {
    $nilai_si = $koneksi->query(
        "SELECT * FROM nilai_sub_indikator WHERE id_sub_indikator = 172 OR id_sub_indikator = 173 OR id_sub_indikator = 174;"
    );
} else {
    $nilai_si = $koneksi->query(
        "SELECT * FROM nilai_sub_indikator WHERE id_sub_indikator = $id_sub_indikator"
    );
}

$sub_indikator = $koneksi->query(
    "SELECT * FROM sub_indikator WHERE id = $id_sub_indikator"
);
$sub_indikator = $sub_indikator->fetch_assoc();

$isFill = false;
foreach ($nilai_si as $nsi) {
    $administrasi = $koneksi->query(
        "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode AND id_nilai_sub_indikator =" . $nsi['id'] . ";"
    );
    if ($administrasi->num_rows > 0) {
        $isFill = true;
    }
}

$administrasi = $koneksi->query(
    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
);

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_nilai_sub_indikator = $_POST['id_nilai_sub_indikator'];

    $insert = $koneksi->query(
        "INSERT INTO administrasi (id_kelurahan, id_periode, id_nilai_sub_indikator) VALUES ('$id_kelurahan', $id_periode, $id_nilai_sub_indikator)"
    );

    $id_administrasi = $koneksi->insert_id;

    // Tentukan folder tempat menyimpan file
    $uploadDir = '../uploads/file_administrasi/'; // Folder tujuan untuk menyimpan file
    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png']; // Daftar ekstensi file yang diperbolehkan

    // Cek apakah folder upload ada, jika tidak buat folder
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Variabel untuk menyimpan nama file yang sukses diupload
    $uploadedFiles = [];

    // Loop untuk memproses setiap file yang diupload    
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
        // Hanya lanjutkan jika ada file yang diupload

        $files = $_FILES['files'];

        // Validasi jumlah file (1 atau lebih)
        if (count($files['name']) < 1) {
            echo "Anda harus mengunggah minimal 1 file.";
            exit;
        }

        // Loop untuk memeriksa dan mengunggah setiap file
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileError = $files['error'][$i];
            $fileType = $files['type'][$i];

            // Ambil ekstensi file
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validasi ekstensi file
            if (!in_array($fileExt, $allowedExtensions)) {
                echo "Format file $fileName tidak diperbolehkan. Hanya .pdf, .jpg, .jpeg, .png yang diperbolehkan.";
                exit;
            }

            // Validasi jika file tidak ada error
            if ($fileError !== UPLOAD_ERR_OK) {
                echo "Terjadi kesalahan saat mengunggah file $fileName.";
                exit;
            }

            // Enkripsi nama file menggunakan hash
            $encryptedFileName = md5(uniqid(rand(), true)) . '.' . $fileExt;

            // Tentukan path tujuan untuk menyimpan file
            $uploadPath = $uploadDir . $encryptedFileName;

            // Pindahkan file ke folder tujuan
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // Simpan nama file terenkripsi di database
                try {
                    $pdo = new PDO("mysql:host=localhost;dbname=kelurahan_terbaik", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Query untuk memasukkan data nama file
                    $stmt = $pdo->prepare("INSERT INTO administrasi_bukti (id_administrasi, file_bukti) VALUES (:id_administrasi, :file_name)");
                    $stmt->bindParam(':id_administrasi', $id_administrasi);
                    $stmt->bindParam(':file_name', $encryptedFileName);

                    // Eksekusi query
                    $stmt->execute();
                    $uploadedFiles[] = $fileName; // Menyimpan nama file yang sukses
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                    exit;
                }
            } else {
                echo "Gagal mengunggah file $fileName.";
                exit;
            }
        }
        // Menampilkan hasil
        if (count($uploadedFiles) > 0) {
            echo "File berhasil diunggah: " . implode(", ", $uploadedFiles);
        }
    } else {
        echo "Tidak ada file yang diunggah.";
    }

    if ($insert) {
        header('Location: /kelurahan/tambah_adm.php?id_periode=' . $id_periode . '&action=add&status=success');
    } else {
        header('Location: /kelurahan/tambah_adm.php?id_periode=' . $id_periode . '&action=add&status=failed');
    }
}

$kelurahan = $koneksi->query(
    "SELECT * FROM kelurahan WHERE id = '$id_kelurahan'"
);
$kelurahan = $kelurahan->fetch_assoc();

$periode = $koneksi->query(
    "SELECT * FROM periode WHERE id = $id_periode"
);
$periode = $periode->fetch_assoc();

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
    <?php if ($isFill) : ?>
        <script>
            // Menjalankan SweetAlert secara otomatis saat halaman dimuat
            window.onload = function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data ini telah diisi!',
                    text: 'Silakan pilih data lain untuk diisi.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
                }).then((result) => {
                    // Redirect ke halaman tertentu setelah pesan ditutup
                    if (result.isConfirmed) {
                        window.location.href = '/kelurahan/dataadm.php'; // Ganti URL dengan halaman tujuan
                    }
                });
            };
        </script>
    <?php endif; ?>
    <div class="wrapper">
        <?php include '../layouts/header.php'; ?>
        <?php include '../layouts/kelurahan_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1> Tambah Data Administrasi Periode <?= $periode['periode'] ?></h1>
                </div>
                <?php if ((isset($_GET['action'])) && ($_GET['status'] == 'success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Data administrasi kelurahan berhasil disimpan!
                    </div>
                <?php endif; ?>
                <?php if ((isset($_GET['add'])) && ($_GET['add'] == 'failed')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Data administrasi kelurahan gagal disimpan!
                    </div>
                <?php endif; ?>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group p-2 m-1">
                                                <label for=""><?= (($id_sub_indikator == '172') || ($id_sub_indikator == '173') || ($id_sub_indikator == '174')) ? 'Mata Pencaharian/Sumber Pendapatan' : $sub_indikator['nama_sub_indikator'] ?> <span class="text-danger">*</span></label>
                                                <select class="form-control" name="id_nilai_sub_indikator" required>
                                                    <option value="" selected disabled>-Pilih-</option>
                                                    <?php foreach ($nilai_si as $nsi): ?>
                                                        <option value="<?= $nsi['id'] ?>"><?= $nsi['nama_nilai_sub_indikator'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group p-2 m-1">
                                                <label for="">Bukti <?= ($sub_indikator['butuh_bukti'] == '1') ? '<span class="text-danger">*</span>' : '' ?></label>
                                                <input class="form-control" type="file" id="fileInput" name="files[]" multiple <?= ($sub_indikator['butuh_bukti'] == '1') ? 'required' : '' ?>>
                                                <div class="form-text">Format file yang diperbolehkan: .pdf, .jpg, .png, .jpeg</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary float-end" id="submitBtn" data-bs-toggle="modal" data-bs-target="#datawilayahadd">
                                        Simpan Data
                                    </button>
                                </div>
                                <div id="previewContainer" class="mt-2"></div> <!-- Container for preview -->
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        // Menangani submit form dengan konfirmasi SweetAlert2
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah form langsung terkirim

            // Validasi form secara manual
            var form = document.querySelector('form');
            if (!form.checkValidity()) {
                // Jika form tidak valid (misalnya ada input required yang kosong)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Pastikan semua kolom yang wajib diisi sudah terisi.',
                });
                return; // Hentikan proses pengiriman jika form tidak valid
            }

            // Konfirmasi menggunakan SweetAlert2 jika form valid
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang sudah disimpan tidak dapat diubah. Pastikan data yang Anda masukkan sudah benar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, kirim!',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna klik "Ya, kirim!"
                    // Mengirimkan form
                    form.submit();
                }
            });
        });
    </script>
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
        function changeAdministrasiPeriode(kelurahan_periode = null) {
            // console.log(kelurahan_periode);
            let resultArray = kelurahan_periode.split(", ");

            // Output the resulting array
            console.log(resultArray[6]);
            document.getElementById("usia_kurang_15").textContent = resultArray[2];
            document.getElementById("usia_15_56").textContent = resultArray[3];
            document.getElementById("usia_lebih_56").textContent = resultArray[4];
            document.getElementById("penduduk_total").textContent = resultArray[5];
            document.getElementById("penduduk_laki_laki").textContent = resultArray[6];
            document.getElementById("penduduk_perempuan").textContent = resultArray[7];
            document.getElementById("jumlah_kepala_keluarga").textContent = resultArray[8];

            document.getElementById("buttonEdit").setAttribute("href", "/kelurahan/edit_kelurahan_periode.php?id_kelurahan=" + resultArray[0] + "&id_periode=" + resultArray[1]);
        }
    </script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            var files = event.target.files;
            var allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            var previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = ''; // Clear previous previews

            var isValid = true;
            var errorMessage = '';

            // Check if files are selected when required
            if (<?= ($sub_indikator['butuh_bukti'] == '1') ? 'true' : 'false' ?> && files.length === 0) {
                isValid = false;
                errorMessage = 'Bukti file harus diupload.';
            }

            // Iterate through selected files
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    isValid = false;
                    errorMessage = 'Format file tidak diperbolehkan.';
                    break;
                }

                // Create a div for file name and preview
                var filePreviewDiv = document.createElement('div');

                // Display file name above the preview
                var fileName = document.createElement('div');
                fileName.textContent = file.name; // Set the file name
                filePreviewDiv.appendChild(fileName);

                if (fileExtension === 'pdf') {
                    // Create a preview for PDF
                    var pdfPreview = document.createElement('embed');
                    pdfPreview.src = URL.createObjectURL(file);
                    pdfPreview.type = 'application/pdf';
                    pdfPreview.width = '100%';
                    pdfPreview.height = '500px';
                    filePreviewDiv.appendChild(pdfPreview);
                } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                    // Create a preview for image files
                    var imagePreview = document.createElement('img');
                    imagePreview.src = URL.createObjectURL(file);
                    imagePreview.classList.add('img-fluid');
                    imagePreview.style.maxWidth = '100%';
                    filePreviewDiv.appendChild(imagePreview);
                }

                // Append the file preview to the container
                previewContainer.appendChild(filePreviewDiv);
            }

            // Show validation error message if not valid
            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: errorMessage,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
                });
                event.target.value = ''; // Optionally clear the input if invalid
                previewContainer.innerHTML = ''; // Clear the preview container
            }
        });
    </script>
</body>

</html>