<?php
if (empty($_SESSION['active'])) {
    header('Location: ../');
}
/// selsct show bg danger when active
$currentPage = basename($_SERVER['PHP_SELF']);
// Assuming you have a session variable for the user's role
$user = $_SESSION['rol'];
// Define roles allowed to access the dashboard
$user_role = 'user';
$admin_role = 'admin';
$manager_role = 'manager';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://icon-library.com/images/pos-system-icon/pos-system-icon-0.jpg">



    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">



</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
            <!-- Brand Logo -->
            <!-- <a href="dashboard.php" class="brand-link">
                <img src="../assets/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?php echo $_SESSION['names']; ?></span>
            </a> -->

            <div class="dropdown">
                <button type="button" class="border-0 navbar-dark navbar-dark" data-toggle="dropdown">
                    <a href="dashboard.php" class="brand-link">
                        <img src="../assets/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                        <!-- <span class="brand-text font-weight-light"><?php echo $_SESSION['names']; ?></span> -->
                    </a>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-success" href="#"> User Name : <?php echo $_SESSION['names']; ?></a>
                    <a class="dropdown-item text-success" href="#"> User Type : <?php echo $_SESSION['rol']; ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Log out</a>
                </div>
            </div>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="dashboard.php" class="brand-link">
                <img src="../assets/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">RestBAR</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <!-- <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li> -->
                        <?php if ($user !== $user_role): ?>
                            <li class="nav-item">
                                <a href="dashboard.php" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-pizza-slice"></i>
                                <p>
                                    Sale
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {
                                    echo '<li class="nav-item">
                                        <a href="index.php" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Nueva venta</p>
                                        </a>
                                    </li>';
                                }
                                if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
                                    echo '<li class="nav-item">
                                        <a href="showroom.php" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Show Room</p>
                                        </a>
                                    </li>';
                                } ?>
                            </ul>

                        </li> -->
                   
                        <?php if ($user !== $user_role): ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-pizza-slice"></i>
                                    <p>
                                        Sale Product
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                   
                                    <li class="nav-item <?php echo $currentPage === 'sale.php' ? 'bg-danger rounded' : ''; ?>">
                                        <a href="sale.php" class="nav-link <?php echo $currentPage === 'product.php' ? 'text-white' : ''; ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sale</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                      


                        <li class="nav-item <?php echo $currentPage === 'category.php' ? 'bg-danger rounded' : ''; ?>">
                            <a href="category.php" class="nav-link <?php echo $currentPage === 'product.php' ? 'text-white' : ''; ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>ប្រភេទ</p>
                            </a>
                        </li>


                        <?php if ($user !== $user_role): ?>
                            <li class="nav-item <?php echo $currentPage === 'category.php' ? 'bg-danger rounded' : ''; ?>">
                                <a href="category.php" class="nav-link <?php echo $currentPage === 'category.php' ? 'text-white' : ''; ?>">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>Category</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item <?php echo $currentPage === 'dashboard.php' ? 'bg-danger rounded' : ''; ?>">
                            <a href="dashboard.php" class="nav-link <?php echo $currentPage === 'product.php' ? 'text-white' : ''; ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item <?php echo $currentPage === 'room.php' ? 'bg-danger rounded' : ''; ?>">
                            <a href="room.php" class="nav-link <?php echo $currentPage === 'product.php' ? 'text-white' : ''; ?>">
                                <i class="nav-icon fas fa-door-closed"></i>
                                <p>Room</p>
                            </a>
                        </li>

                        <li class="nav-item <?php echo $currentPage === 'testchart.php' ? 'bg-danger rounded' : ''; ?>">
                            <a href="testchart.php" class="nav-link <?php echo $currentPage === 'product.php' ? 'text-white' : ''; ?>">
                                <i class="nav-icon fas fa-door-closed"></i>
                                <p>Room</p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid py-2">