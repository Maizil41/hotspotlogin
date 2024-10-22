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

$admin_number = trim(shell_exec("uci get whatsapp-bot.@whatsapp_bot[0].admin_number"));

if (!$admin_number) {
    header("Location: ./kontak.php?message=" . urlencode("Kirim pesan gagal karena nomor admin belum disetting"));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['whatsapp'], $_POST['message'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $number = htmlspecialchars(trim($_POST['whatsapp']));
        $message = htmlspecialchars(trim($_POST['message']));

        if (!preg_match('/^\d{10,15}$/', $number)) {
            header("Location: ./kontak.php?message=Nomor tidak valid");
            exit();
        }

        $send_message = "*Nama : $name*\n*Nomor : $number*\n*Pesan : $message*";

        $url = 'http://localhost:3000/send-message';
        $data = [
            'to' => $admin_number,
            'message' => $send_message,
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === FALSE) {
            header("Location: ./kontak.php?message=Kirim pesan gagal");
            exit();
        } else {
            header("Location: ./kontak.php?message=Pesan terkirim");
            exit();
        }
    } else {
        header("Location: ./kontak.php?message=Data tidak valid!");
        exit();
    }
} else {
    header("Location: ./kontak.php?message=Request gagal");
    exit();
}
?>
