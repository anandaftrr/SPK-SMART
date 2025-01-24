<aside class="main-sidebar sidebar-secondary-primary elevation-4" style="background-color: rgb(5,68,104);">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= $back ?>" class="nav-link">
                        <i class="nav-icon fa fa-arrow-left" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Back </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/penilai/nilai/nilai.php?id_periode=<?= $_GET['id_periode'] ?>" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-check" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Data Penilaian </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/penilai/normalisasi/normalisasi.php?id_periode=<?= $_GET['id_periode'] ?>" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-list" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Hasil Normalisasi </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/penilai/hasil_smart/hasil_smart.php?id_periode=<?= $_GET['id_periode'] ?>" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-trophy" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Hasil SMART </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>