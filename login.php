<?php

/**
 * File: login.php
 * Halaman Otentikasi Mahasiswa - Linear Design System
 * Pure Sans-Serif (Inter), Fully Responsive
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";

$error = "";

// Jika sudah login, redirect langsung ke dashboard
if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"] === true) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["pass"] ?? "");

    if ($email === "" || $password === "") {
        $error = "Email dan password wajib diisi.";
    } else {
        // Otentikasi Pengguna via Database MySQL
        $user = loginMahasiswa($conn, $email, $password);

        if ($user !== null) {
            $_SESSION["isLogin"] = true;
            $_SESSION["id"] = $user["id"];
            $_SESSION["nim"] = $user["nim"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["prodi"] = $user["nama_prodi"];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Kombinasi email atau password tidak cocok.";
        }
    }
}

$pageTitle = "Login";
$hideNavbar = true;
require_once "components/header.php";
?>

<div class="container d-flex align-items-center justify-content-center py-5" style="min-height: calc(100vh - 120px);">
    <div style="max-width: 420px; width: 100%;">
        <!-- Linear Auth Panel -->
        <div class="linear-card p-4 p-sm-5">

            <!-- Header & Brand Mark -->
            <div class="text-center mb-4">
                <a href="landing.php" class="d-inline-flex align-items-center justify-content-center mb-3 text-decoration-none" title="Ke Halaman Utama">
                    <span class="linear-brand-icon" style="width: 40px; height: 40px; font-size: 20px; border-radius: 10px;">
                        <i class="bi bi-command"></i>
                    </span>
                </a>
                <div class="linear-eyebrow mb-1">Portal Akademik</div>
                <h2 class="h3 fw-semibold mb-1">Masuk ke Akun</h2>
                <p class="linear-muted small mb-0">Akses evaluasi akademik dan direktori mahasiswa</p>
            </div>

            <!-- Error Banner -->
            <?php if ($error !== ""): ?>
                <div class="linear-alert linear-alert-danger alert-auto-dismiss d-flex align-items-center gap-2 mb-3 py-2 px-3 small">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div class="flex-grow-1"><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label small fw-medium mb-1">Email Mahasiswa</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input
                            type="email"
                            class="form-control border-start-0"
                            id="email"
                            name="email"
                            placeholder="nama@domain.ac.id"
                            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                            required
                            autocomplete="username">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="pass" class="form-label small fw-medium mb-1">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            class="form-control border-start-0"
                            id="pass"
                            name="pass"
                            placeholder="Masukkan kata sandi"
                            required
                            autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-linear-primary w-100 py-2">
                    <i class="bi bi-arrow-right-short fs-5 me-1"></i> Masuk Sekarang
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center" style="border-color: var(--linear-hairline) !important;">
                <a href="landing.php" class="btn-linear-ghost text-decoration-none" style="font-size: 12px; padding: 4px 8px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>