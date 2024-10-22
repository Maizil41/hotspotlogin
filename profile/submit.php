<?php
/*
*******************************************************************************************************************
* Warning!!!, Tidak untuk diperjual belikan!, Cukup pakai sendiri atau share kepada orang lain secara gratis
*******************************************************************************************************************
* Original Loginpage untuk Mikrotik dibuat oleh @Badaro
*
* Modifikasi Untuk coova-chilli oleh @Maizil https://t.me/maizil41
*******************************************************************************************************************
* © 2024 Mutiara-Net By @Maizil
*******************************************************************************************************************
*/
require '../config/mysqli_db.php';

if (isset($_GET['mac'])) {
    $mac = $_GET['mac'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $whatsapp_number = $_POST['whatsapp'];

    $stmt = $conn->prepare("UPDATE client SET username = ?, password = ?, whatsapp_number = ? WHERE id = ?");
    
    $stmt->bind_param("sssi", $username, $password, $whatsapp_number, $user_id);
    
    if ($stmt->execute()) {
        header("Location: ./profile.php?mac=$mac&message=" . urlencode("Profile berhasil diperbarui."));
    } else {
        header("Location: ./profile.php?mac=$mac&message=" . urlencode("Gagal memperbarui profile."));
    }

    $stmt->close();
}

$conn->close();
?>
