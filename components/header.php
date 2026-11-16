<?php
/**
 * File: components/header.php
 * Template Header - Linear Design System
 * Uncluttered 56px Nav with Pure Dropdown Navigation
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$themeAktif = function_exists('getTheme') ? getTheme() : ($_COOKIE['theme'] ?? 'dark');
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$isLoggedIn = isset($_SESSION['isLogin']) && $_SESSION['isLogin'] === true;
$title = $pageTitle ?? 'Portal Mahasiswa';
$initials = function_exists('getInitials') && isset($_SESSION['nama']) ? getInitials($_SESSION['nama']) : 'M';

// Liquid Glass — dikecualikan di landing.php & login.php
$isLandingPage = ($currentPage === 'landing.php' || $currentPage === 'login.php');
$liquidGlassAktif = (!$isLandingPage && ($_COOKIE['liquid_glass'] ?? '0') === '1') ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="<?= htmlspecialchars($themeAktif) ?>" data-liquid-glass="<?= $liquidGlassAktif ?>" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> &mdash; Student Portal</title>

    <!-- Brand Favicon -->
    <link rel="icon" type="image/svg+xml" href="assets/img/favicon.svg">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Linear Design System CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

<?php if ($isLoggedIn && !isset($hideNavbar)): ?>
<nav class="navbar linear-nav sticky-top">
    <div class="container-xl d-flex align-items-center justify-content-between">
        
        <!-- Linear Brand Mark -->
        <a class="linear-brand" href="dashboard.php">
            <span class="linear-brand-icon">
                <i class="bi bi-command"></i>
            </span>
            <span class="fw-semibold">Student Portal</span>
            <span class="linear-pill linear-pill-default ms-1 d-none d-sm-inline" style="font-size: 10px;">UKRIDA</span>
        </a>

        <!-- User Account Dropdown (Navigasi Utama Aplikasi) -->
        <div class="dropdown user-dropdown-container">
            <button class="btn btn-user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-flex align-items-center gap-2 text-truncate pe-1">
                    <span class="linear-avatar flex-shrink-0">
                        <?= htmlspecialchars($initials) ?>
                    </span>
                    <span class="small fw-medium text-truncate text-body">
                        <?= htmlspecialchars($_SESSION['nama'] ?? 'Mahasiswa') ?>
                    </span>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-linear mt-2">
                <!-- Header Informasi Pengguna -->
                <li class="px-3 py-2 border-bottom mb-1" style="border-color: var(--linear-hairline) !important;">
                    <div class="fw-semibold small text-truncate text-body"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></div>
                    <div class="text-tertiary" style="font-size: 11px;"><?= htmlspecialchars($_SESSION['nim'] ?? '') ?> &bull; <?= htmlspecialchars($_SESSION['prodi'] ?? '') ?></div>
                </li>

                <!-- Item Menu Navigasi -->
                <li>
                    <a class="dropdown-item-linear <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-columns-gap text-secondary"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-linear <?= ($currentPage === 'index.php' || $currentPage === 'tambah.php') ? 'active' : '' ?>" href="index.php">
                        <i class="bi bi-people text-secondary"></i> Data Mahasiswa
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-linear <?= $currentPage === 'profile.php' ? 'active' : '' ?>" href="profile.php">
                        <i class="bi bi-person text-secondary"></i> Profil Saya
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-linear <?= $currentPage === 'preference.php' ? 'active' : '' ?>" href="preference.php">
                        <i class="bi bi-sliders text-secondary"></i> Pengaturan Tema
                    </a>
                </li>

                <!-- Divider & Logout -->
                <li><hr class="dropdown-divider my-1" style="border-color: var(--linear-hairline);"></li>
                <li>
                    <a class="dropdown-item-linear text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>
<?php endif; ?>

<main class="flex-grow-1">