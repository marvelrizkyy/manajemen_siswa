
<?php
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Siswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>
                <h2>Student<span>Hub</span></h2>
                <small>Management System</small>
            </div>
        </div>

        <div class="menu-label">MENU UTAMA</div>

        <a href="#" class="menu-item active">
            <span>▦</span> Dashboard
        </a>

        <a href="#daftar-siswa" class="menu-item">
            <span>♙</span> Data Siswa
        </a>

        <div class="sidebar-bottom">
            <div class="profile">
                <div class="avatar">AD</div>
                <div>
                    <strong>Administrator</strong>
                    <small>Admin Sistem</small>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <header class="topbar">
            <div>
                <p class="breadcrumb">Menu / Dashboard</p>
                <h1>Dashboard</h1>
            </div>
            <div class="admin-badge">
                <span class="online-dot"></span>
                Sistem Aktif
            </div>
        </header>

        <!-- Welcome -->
        <section class="welcome">
            <div>
                <p>SELAMAT DATANG</p>
                <h2>Manajemen Data Siswa</h2>
                <span>Kelola informasi siswa dengan mudah dan cepat.</span>
            </div>
            <div class="welcome-icon">🎓</div>
        </section>

        <!-- Statistics -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">♙</div>
                <div>
                    <p>Total Siswa</p>
                    <h2 id="totalSiswa">0</h2>
                    <small>Data terdaftar</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">✓</div>
                <div>
                    <p>Status Database</p>
                    <h2>Aktif</h2>
                    <small>Koneksi tersedia</small>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">▤</div>
                <div>
                    <p>Jurusan</p>
                    <h2>RPL</h2>
                    <small>Rekayasa Perangkat Lunak</small>
                </div>
            </div>
        </section>

        <!-- Form -->
        <section class="content-card" id="form-section">
            <div class="section-heading">
                <div>
                    <h2 id="formTitle">Tambah Data Siswa</h2>
                    <p>Masukkan informasi siswa pada formulir berikut.</p>
                </div>
            </div>

            <form id="siswaForm">
                <input type="hidden" name="id" id="id">

                <div class="form-grid">

                    <div class="form-group">
                        <label for="nis">Nomor Induk Siswa (NIS)</label>
                        <input type="text" id="nis" name="nis"
                               placeholder="Contoh: 2026001" required>
                        <small class="error-message" id="errorNis"></small>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama"
                               placeholder="Masukkan nama lengkap" required>
                        <small class="error-message" id="errorNama"></small>
                    </div>

                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select id="kelas" name="kelas" required>
                            <option value="">Pilih kelas</option>
                            <option value="XI RPL 1">XI RPL 1</option>
                            <option value="XI RPL 2">XI RPL 2</option>
                            <option value="XII RPL 1">XII RPL 1</option>
                            <option value="XII RPL 2">XII RPL 2</option>
                        </select>
                        <small class="error-message" id="errorKelas"></small>
                    </div>

                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <select id="jurusan" name="jurusan" required>
                            <option value="">Pilih jurusan</option>
                            <option value="RPL">Rekayasa Perangkat Lunak</option>
                            <option value="TKJ">Teknik Komputer dan Jaringan</option>
                            <option value="DKV">Desain Komunikasi Visual</option>
                        </select>
                        <small class="error-message" id="errorJurusan"></small>
                    </div>

                    <div class="form-group full-width">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat"
                                  rows="3" placeholder="Masukkan alamat siswa"></textarea>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="reset" class="btn-reset" id="resetForm">
                        Reset
                    </button>
                    <button type="submit" class="btn-primary" id="btnSubmit">
                        + Simpan Siswa
                    </button>
                </div>
            </form>
        </section>

        <!-- Table -->
        <section class="content-card" id="daftar-siswa">
            <div class="section-heading table-heading">
                <div>
                    <h2>Daftar Siswa</h2>
                    <p>Informasi seluruh siswa yang terdaftar.</p>
                </div>

                <div class="search-box">
                    <span>⌕</span>
                    <input type="text" id="searchInput"
                           placeholder="Cari nama atau NIS...">
                </div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="dataSiswa">
                        <tr>
                            <td colspan="7" class="empty-state">
                                Data siswa akan ditampilkan di sini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span id="jumlahData">Menunggu data siswa...</span>
                <span>StudentHub Management System</span>
            </div>
        </section>

        <footer>
            © 2026 StudentHub. Aplikasi Manajemen Data Siswa.
        </footer>

    </main>
</div>

<script src="js/script.js"></script>
</body>
</html>
