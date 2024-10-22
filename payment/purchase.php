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
session_start();

require '../config/mysqli_db.php';

function generateRandomCode($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

if (isset($_GET['user_id']) && isset($_GET['plan'])) {
    $user_id = $_GET['user_id'];
    $planName = $_GET['plan'];
    $mac = $_GET['mac'];

    $query = "SELECT username, whatsapp_number, balance FROM client WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $whatsapp_number, $balance);
    $stmt->fetch();
    $stmt->close();

    $query = "SELECT planCost FROM billing_plans WHERE planName = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $planName);
    $stmt->execute();
    $stmt->bind_result($planCost);
    $stmt->fetch();
    $stmt->close();

    if ($balance < $planCost) {
        header("Location: ../utilities/paket.php?mac=$mac&message=" . urlencode("Saldo Anda tidak cukup."));
        exit();
    } else {
        $new_balance = $balance - $planCost;

        $query = "UPDATE client SET balance = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $new_balance, $user_id);
        $stmt->execute();
        $stmt->close();

        $code = generateRandomCode();

        $stmt = $conn->prepare("INSERT INTO radcheck (username, attribute, op, value) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $code, $attribute, $op, $value);
        $attribute = "Auth-Type";
        $op = ":=";
        $value = "Accept";
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO radusergroup (username, groupname, priority) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $code, $planName, $priority);
        $priority = "0";
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO userinfo (username, firstname, lastname, email, department, company, workphone, homephone, mobilephone, address, city, state, country, zip, notes, changeuserinfo, portalloginpassword, creationdate, creationby) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssssss", $code, $username, $lastname, $email, $department, $company, $workphone, $homephone, $whatsapp_number, $address, $city, $state, $country, $zip, $notes, $changeuserinfo, $portalloginpassword, $now, $username);
        $lastname = '';
        $email = '';
        $department = '';
        $company = '';
        $workphone = '';
        $homephone = '';
        $address = '';
        $city = '';
        $state = '';
        $country = '';
        $zip = '';
        $notes = '';
        $changeuserinfo = '0';
        $portalloginpassword = '';
        $now = date('Y-m-d H:i:s');
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO userbillinfo (username, planName, contactperson, company, email, phone, address, city, state, country, zip, paymentmethod, cash, creditcardname, creditcardnumber, creditcardverification, creditcardtype, creditcardexp, notes, changeuserbillinfo, lead, coupon, ordertaker, billstatus, nextinvoicedue, billdue, postalinvoice, faxinvoice, emailinvoice, creationdate, creationby) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssssssssssssssssss", $code, $planName, $username, $company, $email, $whatsapp_number, $address, $city, $state, $country, $zip, $paymentmethod, $planCost, $creditcardname, $creditcardnumber, $creditcardverification, $creditcardtype, $creditcardexp, $notes, $changeuserbillinfo, $lead, $coupon, $ordertaker, $billstatus, $nextinvoicedue, $billdue, $postalinvoice, $faxinvoice, $emailinvoice, $now, $username);
        $company = '';
        $email = '';
        $address = '';
        $city = '';
        $state = '';
        $country = '';
        $zip = '';
        $paymentmethod = 'cash';
        $creditcardname = '';
        $creditcardnumber = '';
        $creditcardverification = '';
        $creditcardtype = '';
        $creditcardexp = '';
        $notes = '';
        $changeuserbillinfo = '0';
        $lead = '';
        $coupon = '';
        $ordertaker = '';
        $billstatus = '';
        $nextinvoicedue = '0';
        $billdue = '0';
        $postalinvoice = '';
        $faxinvoice = '';
        $emailinvoice = '';

        if ($stmt->execute()) {
            
            $message = "▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*▬▬▬ VOUCHER INVOICE ▬▬▬*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*Paket : $planName*\n*Harga : Rp.$planCost*\n*Sisa Saldo : Rp.$new_balance*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬\n*▬▬▬▬▬  $code  ▬▬▬▬▬*\n▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬";

            kirimPesanWhatsApp($whatsapp_number, $message);
            
            header("Location: ./success.php?mac=$mac&code=" . urlencode($code) . "&plan=" . urlencode($planName));
            exit();
        } else {
            header("Location: ../utilities/paket.php?mac=$mac&message=" . urlencode("Pembelian gagal. Silakan coba lagi."));
            exit();
        }
        $stmt->close();
    }
} else {
    header("Location: ../utilities/paket.php?mac=$mac&message=" . urlencode("Tidak ada paket yang dipilih."));
    exit();
}

$conn->close();

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

?>
