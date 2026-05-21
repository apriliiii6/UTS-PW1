<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../database/jamuku.db');
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='bahan'");
    if ($result->fetch() === false) {
        $sql = file_get_contents(__DIR__ . '/../database/seeder.sql');
        $pdo->exec($sql);
    }

} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}