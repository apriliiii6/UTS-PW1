<?php
session_start();
require_once 'config/database.php';

$sql = "SELECT * FROM bahan ORDER BY jenis, nama";
$stmt = $pdo->query($sql);
$daftar_bahan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Jamu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form action="keranjang.php" method="POST">
    <h1>Pilih Bahan Jamu</h1>
    <p class="subtitle">Racik ramuan tradisional personal Anda sesuai khasiatnya</p>

    <div class="kategori-wrapper">
        
        <?php 
        $jenis_saat_ini = '';
        foreach ($daftar_bahan as $bahan): 
            
            if ($jenis_saat_ini != $bahan['jenis']): 
                $jenis_saat_ini = $bahan['jenis'];
                echo "<h3>" . htmlspecialchars($jenis_saat_ini) . "</h3>";
            endif; 
        ?>

            <div class="item-bahan">
                <input type="checkbox" name="bahan_terpilih[]" value="<?= $bahan['id']; ?>" id="bahan_<?= $bahan['id']; ?>">
                <label for="bahan_<?= $bahan['id']; ?>">
                    <strong><?= htmlspecialchars($bahan['nama']); ?></strong> 
                    <span class="harga">(Rp <?= number_format($bahan['harga']); ?>)</span>
                    <small>Khasiat: <?= htmlspecialchars($bahan['deskripsi']); ?></small>
                </label>
            </div>

        <?php endforeach; ?>

    </div> 
    <div class="form-footer">
        <div>
            <label for="porsi">Jumlah Porsi:</label>
            <input type="number" id="porsi" name="porsi" value="1" min="1" required>
        </div>
        <button type="submit" name="tambah_jamu">Masukkan ke Keranjang</button>
    </div>
</form>
</body>
</html>
