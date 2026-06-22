<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// header.php
// —————————————————————————————
// 1) Turn on output buffering immediately
ob_start();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// echo '<pre>SESSION: ';
// print_r($_SESSION);
// echo '</pre>';
// exit();

// 3) Redirect to login if not authenticated
if (empty($_SESSION['userid'])) {
  header('Location: index.php');
  exit(); // stop any further output
}

// 4) Load your users helper (adjust path if needed)
// require_once __DIR__ . '/db/users.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pre-genetic Counseling Agent</title>
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
        rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">

    <style>
    .node circle {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 3px;
    }

    .node rect {
        fill: #fff;
        stroke: steelblue;
        stroke-width: 3px;
    }

    .node text {
        font: 12px sans-serif;
    }

    .link {
        fill: none;
        stroke: #ccc;
        stroke-width: 2px;
    }
    </style>


    <!-- jQuery & Bootstrap -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" href="change_password.php">
                        <i class="fa fa-key"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">1</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">1 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signout.php" title="Sign Out" role="button">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="home.php" class="brand-link">
                <img src="images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">My Portal</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dist/img/ai_avatar.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $_SESSION["name"]; ?></a>
                    </div>
                </div>



                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="Genetic_counseling_and_testing.php" class="nav-link">
                                <i class="nav-icon fa fa-lock" id="linkIcon1"></i>
                                <p style="color:#6c757d;" id="linkText1">
                                    Genetic counseling and testing
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Genetic_Testing_Journey.php" class="nav-link">
                                <i class="nav-icon fa fa-lock" id="linkIcon2"></i>
                                <p style="color:#6c757d;" id="linkText2">
                                    Genetic Testing Journey
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="personal_info.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon3"></i>
                                <p style="color:#6c757d;" id="linkText3">
                                    Personal Info
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="clinical_history.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon4"></i>
                                <p style="color:#6c757d;" id="linkText5">
                                    clinical History
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="family_pedigree.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon5"></i>
                                <p style="color:#6c757d;" id="linkText5">
                                    Family Pedigree
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="Patient_Eligibility_for_NCCN_Test.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon6"></i>
                                <p style="color:#6c757d;" id="linkText6">
                                    Patient Eligibility for NCCN Test
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon"></i>
                                <p>
                                    Educational Sessions
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="Concept_of_genetics_and_mode_of_inheritance.php" class="nav-link"
                                        disabled="disabled">
                                        <i class="nav-icon fa fa-lock" id="linkIcon7"></i>
                                        <p style="color:#6c757d;" id="linkText7">
                                            Concept of genetics and mode of inheritance
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="Multigene_panel_and_Outcome_results.php" class="nav-link"
                                        disabled="disabled">
                                        <i class="nav-icon fa fa-lock" id="linkIcon8"></i>
                                        <p style="color:#6c757d;" id="linkText8">
                                            Multigene panel and Outcome results
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="Benefits_Risks_and_Limitations_of_the_genetic_testing.php" class="nav-link"
                                        disabled="disabled">
                                        <i class="nav-icon fa fa-lock" id="linkIcon9"></i>
                                        <p style="color:#6c757d;" id="linkText9">
                                            Benefits, Risks, and Limitations of the genetic testing
                                        </p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="consent_form.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon10"></i>
                                <p style="color:#6c757d;" id="linkText10">
                                    Patient Consent Form
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="assessment.php" class="nav-link" disabled="disabled">
                                <i class="nav-icon fa fa-lock" id="linkIcon11"></i>
                                <p style="color:#6c757d;" id="linkText11">
                                    Patient Assessment
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="chat_embed.php" class="nav-link">
                                <i class="nav-icon fas fa-user-astronaut"></i>
                                <p style="color:#c2c7d0;">
                                    Chat Bot
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="tutorial.php" class="nav-link">
                                <i class="nav-icon fab fa-youtube"></i>
                                <p style="color:#c2c7d0;">
                                    Tutorial
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <script>
        $(document).ready(function() {
            for (let i = 1; i <= 9; i++) {
                if (localStorage.getItem(`link${i}Changed`) === 'true') {
                    $(`#linkIcon${i}`).removeClass('fa-lock').addClass('fa-unlock');
                    $(`#linkText${i}`).css('color', '#c2c7d0');
                }
            }
        });
        </script>