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
ob_start();

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);

session_name('hotspot_session');
session_start();

if (headers_sent($file, $line)) {
    exit();
}

if (isset($_GET['mac'])) {
    $mac = $_GET['mac'];

    require '../config/mysqli_db.php';

    $sql = "SELECT * FROM radacct WHERE callingstationid = '$mac' AND acctstoptime IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $conn->close();
}

if (!isset($_SESSION['username'])) {
    header("Location: ./login.php?mac=$mac");
    exit();
}

function getBillingPlans() {
    require '../config/mysqli_db.php';

    $sql = "SELECT planName, planCost FROM billing_plans WHERE planCost > 0 LIMIT 4";
    $plans = [];

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $plans[] = $row;
        }

        $stmt->close();
    }

    return $plans;
}

function getUserInfo($username) {
    require '../config/mysqli_db.php';

    $sql = "SELECT id, password, whatsapp_number, balance FROM client WHERE username = ?";
    $userInfo = [];

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $userInfo = $row;
        }

        $stmt->close();
    }

    return $userInfo;
}

function money($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

$billingPlans = getBillingPlans();
$userInfo = getUserInfo($_SESSION['username']);
$user_name = htmlspecialchars($_SESSION['username']);
$pass_word = htmlspecialchars($userInfo['password']);
$whats_app = htmlspecialchars($userInfo['whatsapp_number']);
$balance = money($userInfo['balance']);
$user_id = htmlspecialchars($userInfo['id']);

ob_end_flush();
?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>PROFILE</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="../index.php?mac=<?php echo "$mac" ?>" class="headerButton goBack">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="36" height="36" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
				</svg>
            </a>
        </div>
        <div class="pageTitle">
            Profile
        </div>
        <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#DialogIconedButtonInline">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
                <path fill="#6236FF" d="M20,15.5C18.8,15.5 17.5,15.3 16.4,14.9C16.3,14.9 16.2,14.9 16.1,14.9C15.8,14.9 15.6,15 15.4,15.2L13.2,17.4C10.4,15.9 8,13.6 6.6,10.8L8.8,8.6C9.1,8.3 9.2,7.9 9,7.6C8.7,6.5 8.5,5.2 8.5,4C8.5,3.5 8,3 7.5,3H4C3.5,3 3,3.5 3,4C3,13.4 10.6,21 20,21C20.5,21 21,20.5 21,20V16.5C21,16 20.5,15.5 20,15.5M5,5H6.5C6.6,5.9 6.8,6.8 7,7.6L5.8,8.8C5.4,7.6 5.1,6.3 5,5M19,19C17.7,18.9 16.4,18.6 15.2,18.2L16.4,17C17.2,17.2 18.1,17.4 19,17.4V19Z" />
                </svg>
            </a>
        </div>	
    </div>
    <!-- * App Header -->
	
	<!-- Dialog Iconed Inline -->
    <div class="modal fade dialogbox" id="DialogIconedButtonInline" data-bs-backdrop="static" tabindex="-1"
        role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hubungi Kami</h5>
                </div>
                <div class="modal-body">
                    Melakukan panggilan telepon?
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                            BATAL
                        </a>
						<script src="../assets/config/notelepon.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Dialog Iconed Inline -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="p-1">
                        <div class="text-center">
                            <h2 class="text-primary">Profile Anda</h2>
                        </div>
                        <form method="post" action="./edit.php?mac=<?php echo "$mac" ?>">
                        <form id="messageForm">
						<div class="form-group basic">
							<div class="input-wrapper">
								<label class="label" for="username">Username</label>
								<input type="disabled" name="username" class="form-control" value="<?php echo "$user_name" ?>" disabled>
							</div>
						</div>
						
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="password">Password</label>
                                <input type="text" name="password" class="form-control" value="<?php echo "$pass_word"; ?>" disabled>
                            </div>
                        </div>
						
						<div class="form-group basic">
							<div class="input-wrapper">
								<label class="label" for="pesan">No Whatsapp</label>
								<input type="number" name="whatsapp" class="form-control" value="<?php echo "$whats_app"; ?>" disabled>
							</div>
						</div>
						<div class="form-group basic">
							<div class="input-wrapper">
								<label class="label" for="pesan">Saldo</label>
								<input name="Pesan" class="form-control" value="<?php echo "$balance"; ?>" disabled>
							</div>
						</div>
						<div class="mt-2">
						    <input type="hidden" id="user_id" name="user_id" value="<?php echo "$user_id"; ?>">
							<input type="submit" value="EDIT" class="btn btn-primary btn-lg btn-block">
						</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Menu Bawah -->
        <div class="appBottomMenu">
        <a href="../index.php?mac=<?php echo "$mac" ?>" class="item">
            <div class="col">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M17 14H19V17H22V19H19V22H17V19H14V17H17V14M5 20V12H2L12 3L22 12H17V10.19L12 5.69L7 10.19V18H12C12 18.7 12.12 19.37 12.34 20H5Z" />
				</svg>
				<strong>Beranda</strong>
            </div>
        </a>
        <a href="../utilities/paket.php?mac=<?php echo "$mac" ?>" class="item">
            <div class="col">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M19 23.3L18.4 22.8C16.4 20.9 15 19.7 15 18.2C15 17 16 16 17.2 16C17.9 16 18.6 16.3 19 16.8C19.4 16.3 20.1 16 20.8 16C22 16 23 16.9 23 18.2C23 19.7 21.6 20.9 19.6 22.8L19 23.3M18 2C19.1 2 20 2.9 20 4V13.08L19 13L18 13.08V4H13V12L10.5 9.75L8 12V4H6V20H13.08C13.2 20.72 13.45 21.39 13.8 22H6C4.9 22 4 21.1 4 20V4C4 2.9 4.9 2 6 2H18Z" />
				</svg>
				<strong>Paket</strong>
            </div>
        </a>
		<a href="https://maizil41.github.io/scanner/" class="item">
            <div class="col">
                <div class="action-button large">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				<path fill="#ffffff" d="M4,4H10V10H4V4M20,4V10H14V4H20M14,15H16V13H14V11H16V13H18V11H20V13H18V15H20V18H18V20H16V18H13V20H11V16H14V15M16,15V18H18V15H16M4,20V14H10V20H4M6,6V8H8V6H6M16,6V8H18V6H16M6,16V18H8V16H6M4,11H6V13H4V11M9,11H13V15H11V13H9V11M11,6H13V10H11V6M2,2V6H0V2A2,2 0 0,1 2,0H6V2H2M22,0A2,2 0 0,1 24,2V6H22V2H18V0H22M2,18V22H6V24H2A2,2 0 0,1 0,22V18H2M22,22V18H24V22A2,2 0 0,1 22,24H18V22H22Z" />
				</svg>
                </div>
            </div>
        </a>
		<a href="../utilities/faq.php?mac=<?php echo "$mac" ?>" class="item">
            <div class="col">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M18,15H6L2,19V3A1,1 0 0,1 3,2H18A1,1 0 0,1 19,3V14A1,1 0 0,1 18,15M23,9V23L19,19H8A1,1 0 0,1 7,18V17H21V8H22A1,1 0 0,1 23,9M8.19,4C7.32,4 6.62,4.2 6.08,4.59C5.56,5 5.3,5.57 5.31,6.36L5.32,6.39H7.25C7.26,6.09 7.35,5.86 7.53,5.7C7.71,5.55 7.93,5.47 8.19,5.47C8.5,5.47 8.76,5.57 8.94,5.75C9.12,5.94 9.2,6.2 9.2,6.5C9.2,6.82 9.13,7.09 8.97,7.32C8.83,7.55 8.62,7.75 8.36,7.91C7.85,8.25 7.5,8.55 7.31,8.82C7.11,9.08 7,9.5 7,10H9C9,9.69 9.04,9.44 9.13,9.26C9.22,9.08 9.39,8.9 9.64,8.74C10.09,8.5 10.46,8.21 10.75,7.81C11.04,7.41 11.19,7 11.19,6.5C11.19,5.74 10.92,5.13 10.38,4.68C9.85,4.23 9.12,4 8.19,4M7,11V13H9V11H7M13,13H15V11H13V13M13,4V10H15V4H13Z" />
				</svg>
				<strong>FAQ</strong>
            </div>
        </a>
        <a href="../utilities/kontak.php?mac=<?php echo "$mac" ?>" class="item">
            <div class="col">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M6,17C6,15 10,13.9 12,13.9C14,13.9 18,15 18,17V18H6M15,9A3,3 0 0,1 12,12A3,3 0 0,1 9,9A3,3 0 0,1 12,6A3,3 0 0,1 15,9M3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3H5C3.89,3 3,3.9 3,5Z" />
				</svg>
				<strong>Kontak</strong>
            </div>
        </a>
    </div>
    
    <!-- Dialog Iconed Inline -->
    <div class="modal fade dialogbox" id="DialogInformation" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">Pemberitahuan</h5>
                </div>
                <div class="modal-body text-secondary" id="modalMessage">
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-primary" data-bs-dismiss="modal">OKE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Dialog Iconed Inline -->

    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
    <script>
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
    
        const message = getQueryParam('message');
        
        if (message) {
            document.getElementById('modalMessage').innerText = decodeURIComponent(message);
            const modal = new bootstrap.Modal(document.getElementById('DialogInformation'));
            modal.show();
    
            modal._element.addEventListener('hidden.bs.modal', function () {
                window.location.href = './profile.php?mac=<?php echo $_GET['mac']; ?>';
            });
        }
    </script>
    
    </div>
	
    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/base.js"></script>

</body>

</html>