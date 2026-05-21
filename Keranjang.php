<?php
session_start();
require_once 'config/database.php';

if (isset($_POST['tambah_jamu'])) {
    $bahan_terpilih = $_POST['bahan_terpilih'] ?? [];
    $porsi = $_POST['porsi'] ?? 1;

    $_SESSION['keranjang'] = [
        'bahan' => $bahan_terpilih,
        'porsi' => $porsi
    ];
}

if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_SESSION['keranjang']['bahan'])) {
        $key = array_search($id, $_SESSION['keranjang']['bahan']);
        if ($key !== false) {
            unset($_SESSION['keranjang']['bahan'][$key]);
        }
    }
}

if (isset($_POST['update_porsi'])) {
    $porsi_baru = $_POST['porsi'] ?? 1;
    $_SESSION['keranjang']['porsi'] = $porsi_baru;
}

$total_harga = 0;
$daftar_bahan = [];

if (!empty($_SESSION['keranjang']['bahan'])) {
    $ids = implode(',', array_map('intval', $_SESSION['keranjang']['bahan']));
    $sql = "SELECT * FROM bahan WHERE id IN ($ids)";
    $stmt = $pdo->query($sql);
    $daftar_bahan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($daftar_bahan as $bahan) {
        $total_harga += $bahan['harga'];
    }

    $total_harga *= $_SESSION['keranjang']['porsi'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Jamu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Keranjang Jamu</h1>

    <?php if (!empty($daftar_bahan)): ?>
        <form method="POST">
            <label for="porsi">Jumlah Porsi:</label>
            <input type="number" name="porsi" value="<?= $_SESSION['keranjang']['porsi']; ?>" min="1" required>
            <button type="submit" name="update_porsi">Update Porsi</button>
        </form>

        <table border="1" cellpadding="5">
            <tr>
                <th>Nama Bahan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($daftar_bahan as $bahan): ?>
                <tr>
                    <td><?= htmlspecialchars($bahan['nama']); ?></td>
                    <td><?= number_format($bahan['harga'], 0, ',', '.'); ?></td>
                    <td><a href="keranjang.php?aksi=hapus&id=<?= $bahan['id']; ?>">Hapus</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Total Harga: Rp <?= number_format($total_harga, 0, ',', '.'); ?></h2>
    <?php else: ?>
        <p>Keranjang kosong.</p>
    <?php endif; ?>
</body>
</html>
