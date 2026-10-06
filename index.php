<!-- http://localhost/PAW_JURNAL1_DAVI_102042500025/index.php -->

<?php
session_start();

$daftar_matkul = [
    "Algoritma dan Pemrograman",
    "Analisis dan Perancangan Sistem Informasi",
    "Arsitektur Enterprise",
    "Data Warehouse dan Business Intelligence",
    "Komputasi Awan",
    "Pemodelan Proses Bisnis",
    "Pengantar Sistem Informasi",
    "Pengembangan Aplikasi Bergerak",
    "Pengembangan Aplikasi Website",
    "Pengembangan UI Lanjut",
    "Proyek Perangkat Lunak",
    "Sistem Enterprise",
    "Sistem Informasi Akuntansi",
    "Sistem Operasi"
];

$nama = $whatsapp = $email = $matkul = $motivasi = "";
$namaErr = $waErr = $emailErr = $matkulErr = $motivasiErr = "";

$mode = "form";

if (isset($_GET['page']) && $_GET['page'] == 'id_card') {
    if (!empty($_SESSION['data_pendaftaran'])) {
        $nama = $_SESSION['data_pendaftaran']['nama'];
        $whatsapp = $_SESSION['data_pendaftaran']['whatsapp'];
        $email = $_SESSION['data_pendaftaran']['email'];
        $matkul = $_SESSION['data_pendaftaran']['matkul'];
        $motivasi = $_SESSION['data_pendaftaran']['motivasi'];
        $mode = "id_card";
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama_lengkap"] ?? '');
    if (empty($nama)) {
        $namaErr = "Nama lengkap harus diisi.";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $nama)) {
        $namaErr = "Nama lengkap hanya boleh mengandung huruf dan spasi.";
    }

    $whatsapp = trim($_POST["no_whatsapp"] ?? '');
    if (empty($whatsapp)) {
        $waErr = "Nomor Whatsapp Wajib diisi.";
    } else {
        $prefix = substr($whatsapp, 0, 1);
        $prefix2 = substr($whatsapp, 0, 2);
        if ($prefix !== '0' && $prefix2 !== '62') {
            $waErr = "Nomor Whatsapp tidak valid. Harus diawali dengan 0 atau 62.";
        }
    }

    $email = trim($_POST["email_institusi"] ?? '');
    if (empty($email)) {
        $emailErr = "Email institusi harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format email tidak valid.";
    } else {
        $domain_diizinkan = ['gmail.com', 'university.ac.id'];
        $domain = strtolower(substr(strrchr($email, "@"), 1));

        if (!in_array($domain, $domain_diizinkan)) {
            $emailErr = "Email harus menggunakan domain @gmail.com atau @university.ac.id.";
        }
    }

    $matkul = $_POST["pilih_matkul"] ?? '';
    if (empty($matkul)) {
        $matkulErr = "Silakan pilih mata kuliah.";
    }

    $motivasi = trim($_POST['motivasi'] ?? '');
    if (empty($motivasi)) {
        $motivasiErr = "Motivasi harus diisi.";
    }

    if (empty($namaErr) && empty($waErr) && empty($emailErr) && empty($matkulErr) && empty($motivasiErr)) {
        $_SESSION['data_pendaftaran'] = [
            'nama' => $nama,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'matkul' => $matkul,
            'motivasi' => $motivasi
        ];
        $mode = "id_card";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Calon Asisten Praktikum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($mode == "id_card") { ?>
    <div class="id-card">
        <img src="logo.png" alt="Logo" class="logo">
        <div class="id-card-header">
            <h2>Kartu Registrasi</h2>
            <p>Calon Asisten Praktikum Laboratorium</p>
        </div>

        <div class="alert alert-success">
            <strong>Selamat!</strong> Anda telah berhasil mendaftar sebagai calon asisten praktikum.
        </div>

        <div class="id-card-body">
            <div class="id-card-row">
                <span class="id-card-label">Nama Lengkap</span>
                <span class="id-card-value"><?php echo htmlspecialchars($nama); ?></span>
            </div>

            <div class="id-card-row">
                <span class="id-card-label">Nomor Whatsapp</span>
                <span class="id-card-value"><?php echo htmlspecialchars($whatsapp); ?></span>
            </div>

            <hr class="id-card-divider">

            <div class="id-card-row">
                <span class="id-card-label">Email Institusi</span>
                <span class="id-card-value"><?php echo htmlspecialchars($email); ?></span>
            </div>

            <div class="id-card-row">
                <span class="id-card-label">Mata Kuliah</span>
                <span class="id-card-value"><?php echo htmlspecialchars($matkul); ?></span>
            </div>

            <div class="id-card-row">
                <span class="id-card-label">Motivasi</span>
                <span class="id-card-value"><?php echo htmlspecialchars($motivasi); ?></span>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <span class="id-card-badge">Pendaftaran Berhasil</span>
        </div>

        <a href="?page=form" class="btn-kembali">Kembali ke Form</a>

        <div class="id-card-footer">
            Nomor Registrasi: REG-<?php echo strtoupper(substr(md5(time()), 0, 8)); ?> &bull; Dicetak otomatis oleh sistem
        </div>
    </div>
<?php } else { ?>
    <div class="container">
        <img src="logo.png" alt="Logo" class="logo">
        <h2>Pendaftaran Asisten Praktikum</h2>
        <p class="subtitle">Laboratorium Enterprise Application Development</p>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && (!empty($namaErr) || !empty($waErr) || !empty($emailErr) || !empty($matkulErr) || !empty($motivasiErr))) : ?>
            <div class="alert alert_danger">
                <strong>Pendaftaran Gagal!</strong> Harap Perbaiki Data yang Salah.
            </div>
    <?php endif; ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="form-group">
            <label>Nama Lengkap <span class="required">*</span></label>
            <input type="text" name="nama_lengkap" placeholder="Contoh: Budi Santoso" value="<?php echo htmlspecialchars($nama); ?>">
            <span class="error"><?php echo $namaErr ? "* $namaErr" : ""; ?></span>
        </div>

        <div class="form_group">
            <label>Nomor Whatsapp <span class="required">*</span></label>
            <input type="text" name="no_whatsapp" placeholder="Contoh: 08123456789" value="<?php echo htmlspecialchars($whatsapp); ?>">
            <span class="error"><?php echo $waErr ? "* $waErr" : ""; ?></span>
        </div>

        <div class="form_group">
            <label>Email Institusi <span class="required">*</span></label>
            <input type="email" name="email_institusi" placeholder="Contoh: budi@university.ac.id" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
        </div>

    <div class="form-group">
        <label>Pilihan Mata Kuliah Praktikum <span class="required">*</span></label>
        <select name="pilih_matkul">
            <option value="">Pilih Mata Kuliah</option>
            <?php foreach ($daftar_matkul as $mk) : ?>
                <option value="<?php echo htmlspecialchars($mk); ?>" <?php echo ($matkul == $mk) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($mk); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="error"><?php echo $matkulErr ? "* $matkulErr" : ""; ?></span>
    </div>
        <div class="form_group">
            <label>Motivasi <span class="required">*</span></label>
            <textarea name="motivasi" placeholder="Tuliskan motivasi Anda..."><?php echo htmlspecialchars($motivasi); ?></textarea>
            <span class="error"><?php echo $motivasiErr ? "* $motivasiErr" : ""; ?></span>
        </div>

        <div class="form_group">
            <button type="submit" class="btn_submit">Daftar Sekarang</button>
            <?php if (!empty($_SESSION['data_pendaftaran'])) : ?>
                <a href="?page=id_card" class="btn_id_card">Cetak Kartu Registrasi</a>
            <?php endif; ?>
        </div>
    </form>
    </div>
    <?php } ?>
</body>