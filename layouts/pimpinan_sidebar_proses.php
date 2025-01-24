<aside class="main-sidebar sidebar-secondary-primary elevation-4" style="background-color: rgb(5,68,104);">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/pimpinan/periode.php" class="nav-link">
                        <i class="nav-icon fas fa-arrow-left" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Home </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pimpinan_hasiladm.php?id_periode=<?= $_GET['id_periode'] ?>" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-list-ol" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Hasil Administrasi </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pimpinan_hasilsmart.php?id_periode=<?= $_GET['id_periode'] ?>" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-trophy" style="color: #fcfcfc;"></i>
                        <p style="color: #fcfcfc;"> Hasil Proses SMART </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>