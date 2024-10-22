<?php
/*
*******************************************************************************************************************
* Warning!!!, Tidak untuk diperjual belikan!, Cukup pakai sendiri atau share kepada orang lain secara gratis
*******************************************************************************************************************
* Dibuat oleh @Maizil https://t.me/maizil41
*******************************************************************************************************************
* © 2024 Mutiara-Net By @Maizil
*******************************************************************************************************************
*/

if (isset($_GET['mac'])) {
    $mac = $_GET['mac'];
}

$admin_number = trim(shell_exec("uci get whatsapp-bot.@whatsapp_bot[0].admin_number"));

if (!$admin_number) {
    header("Location: ../index.php?mac=$mac&status=error&message=" . urlencode("Topup gagal karena nomor admin belum disetting"));
    exit();
}

if (isset($_GET['user_id']) && isset($_GET['amount'])) {
    $userId = htmlspecialchars($_GET['user_id']);
    $amount = htmlspecialchars($_GET['amount']);

    require '../config/mysqli_db.php';

    $stmt = $conn->prepare("SELECT username, whatsapp_number FROM client WHERE id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($username, $client_number);
    $stmt->fetch();
    $stmt->close();

    if (empty($username)) {
        header("Location: ../index.php?mac=$mac&status=error&message=" . urlencode("User tidak ditemukan"));
        exit();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM topup WHERE user_id = ? AND status = 'Pending' AND date >= NOW() - INTERVAL 1 DAY");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        header("Location: ../index.php?mac=$mac&status=error&message=" . urlencode("Anda masih memiliki permintaan topup yang belum dikonfirmasi"));
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO topup (user_id, username, amount, status) VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("ssd", $userId, $username, $amount);
        
        if ($stmt->execute()) {
            $stmt = $conn->prepare("SELECT id FROM topup WHERE username = ? AND status = 'Pending' ORDER BY date DESC LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();

            $client_message = "▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*▬▬▬   TOPUP INVOICE  ▬▬▬*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*# LAKUKAN PEMBAYARAN KE #*\n\n*•DANA : $admin_number*\n*•OVO    : $admin_number*\n\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*JIKA SUDAH MELAKUKAN*\n*PEMBAYARAN KIRIMKAN BUKTI*\n*TRANSFER KESINI*\n\n*BATAS WAKTU PEMBAYARAN*\n*SAMPAI 1 JAM KEDEPAN*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*Nama : $username*\n*Nomor : $client_number*\n*Jumlah : Rp " . number_format($amount, 0, ',', '.') . "*\n*Topup ID : $id*\n*Status : PENDING*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬";

            $admin_message = "▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*▬▬   PERMINTAAN TOPUP  ▬▬*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*Nama : $username*\n*Nomor : $client_number*\n*Jumlah : Rp " . number_format($amount, 0, ',', '.') . "*\n*Topup ID : $id*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬";

            kirimPesanWhatsApp($admin_number, $client_number, $admin_message, $client_message);

            header("Location: ../index.php?mac=$mac&status=success&message=" . urlencode("Permintaan topup anda dengan jumlah Rp " . number_format($amount, 0, ',', '.') . " Akan diproses %0AAnda akan menerima pemberitahuan WhatsApp"));
            exit();
        } else {
            header("Location: ../index.php?mac=$mac&status=error&message=" . urlencode("Terjadi kesalahan saat memproses permintaan topup"));
            exit();
        }
        $stmt->close();
    }

    $conn->close();
} else {
    header("Location: ../index.php?mac=$mac&status=error&message=" . urlencode("Entahlah, ada eror"));
    exit();
}

function kirimPesanWhatsApp($admin_number, $client_number, $admin_message, $client_message) {
    $url = 'http://localhost:3000/send-message';

    $dataAdmin = [
        'to' => $admin_number,
        'message' => $admin_message,
    ];

    $dataClient = [
        'to' => $client_number,
        'message' => $client_message,
    ];

    function sendMessage($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if ($response === false) {
            die('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

    $responseAdmin = sendMessage($url, $dataAdmin);

    $responseClient = sendMessage($url, $dataClient);

    return [
        'responseAdmin' => $responseAdmin,
        'responseClient' => $responseClient
    ];
}

?>
