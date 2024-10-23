<?php

$mac = isset($_GET['mac']) ? $_GET['mac'] : '';

$user = isset($_GET['user']) ? $_GET['user'] : '';

require '../config/mysqli_db.php';

$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT username FROM userbillinfo WHERE planName = 'TRIAL' AND contactperson = ? AND DATE(creationdate) = ?");
$stmt->bind_param("ss", $user, $today);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ../index.php?mac=$mac&message=Anda sudah menggunakan trial hari ini, silahkan kembali besok");
    exit();
}

$stmt->close();

function generateRandomString($length = 4) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function insertRadcheck($username, $user) {
    
    $plan_name = "TRIAL";
        
    require '../config/mysqli_db.php';

    try {
        $stmt = $conn->prepare("INSERT INTO radcheck (username, attribute, op, value) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $attribute, $op, $value);
        $attribute = "Auth-Type";
        $op = ":=";
        $value = "Accept";
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO radusergroup (username, groupname, priority) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $plan_name, $priority);
        $priority = "0";
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO userinfo (username, firstname, lastname, email, department, company, workphone, homephone, mobilephone, address, city, state, country, zip, notes, changeuserinfo, portalloginpassword, creationdate, creationby) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssssss", $username, $firstname, $lastname, $email, $department, $company, $workphone, $homephone, $mobilephone, $address, $city, $state, $country, $zip, $notes, $changeuserinfo, $portalloginpassword, $now, $user);
        $firstname = '';
        $lastname = '';
        $email = '';
        $department = '';
        $company = '';
        $workphone = '';
        $homephone = '';
        $mobilephone = '';
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
        $stmt->bind_param("sssssssssssssssssssssssssssssss", $username, $plan_name, $user, $company, $email, $phone, $address, $city, $state, $country, $zip, $paymentmethod, $cash, $creditcardname, $creditcardnumber, $creditcardverification, $creditcardtype, $creditcardexp, $notes, $changeuserbillinfo, $lead, $coupon, $ordertaker, $billstatus, $nextinvoicedue, $billdue, $postalinvoice, $faxinvoice, $emailinvoice, $now, $user);
        $contactperson = '';
        $company = '';
        $email = '';
        $phone = '';
        $address = '';
        $city = '';
        $state = '';
        $country = '';
        $zip = '';
        $paymentmethod = '';
        $cash = '';
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
        $stmt->execute();
        $stmt->close();

        $conn->close();
    } catch (mysqli_sql_exception $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

$randomUsername = generateRandomString();

insertRadcheck($randomUsername, $user);

header("Location: http://10.10.10.1:3990/login?username={$randomUsername}&password=Accept");
exit();
?>
