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
require '../config/mysqli_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $whatsapp_number = $_POST['whatsapp'];
    $random_number = rand(100000, 999999);
    $message = "Kode OTP anda adalah $random_number\nBerlaku sampai 5 menit kedepan";

    function kirimPesanWhatsApp($whatsapp_number, $message) {
        $url = 'http://localhost:3000/send-message';
        $data = [
            'to' => $whatsapp_number,
            'message' => $message,
        ];

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

    $result = kirimPesanWhatsApp($whatsapp_number, $message);

    if ($result) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM otp_requests WHERE whatsapp_number = ?");
        $stmt->bind_param("s", $whatsapp_number);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $stmt = $conn->prepare("UPDATE otp_requests SET otp_code = ?, created_at = NOW() WHERE whatsapp_number = ?");
            $stmt->bind_param("is", $random_number, $whatsapp_number);
        } else {
            $stmt = $conn->prepare("INSERT INTO otp_requests (whatsapp_number, otp_code, created_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("si", $whatsapp_number, $random_number);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'OTP sent and saved to database successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save OTP to database']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
    }
}

$conn->close();
?>
