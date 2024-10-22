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
}

if (!isset($_SESSION['username'])) {
    header("Location: ../profile/login.php?mac=$mac");
    exit();
}

require '../config/mysqli_db.php';

$creationBy = $_SESSION['username'];
$sql = "SELECT username, planName, creationdate FROM userbillinfo WHERE creationby = ? ORDER BY creationdate DESC LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $creationBy);
$stmt->execute();
$result = $stmt->get_result();

$pembelianTerakhir = [];

while ($row = $result->fetch_assoc()) {
    $pembelianTerakhir[] = $row;
}

$stmt->close();
$conn->close();

$cardClasses = ['bg-primary', 'bg-secondary', 'bg-warning', 'bg-success'];
$index = 0; 

ob_end_flush();
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>VOUCHER</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
</head>

<body class="">

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
            Pembelian Terakhir
        </div>
    </div>
    <!-- * App Header -->
	<div id="appCapsule">
<?php if (empty($pembelianTerakhir)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('DialogMessage'));
            document.getElementById('modalMessage').innerText = 'Tidak ada data pembelian terakhir yang tersedia.';
            modal.show();

            var dialog = document.getElementById('DialogMessage');
            dialog.addEventListener('hidden.bs.modal', function() {
                window.location.href = '../index.php?mac=<?php echo "$mac" ?>';
            });
        });
    </script>
    
    <!-- Dialog Info -->
    <div class="modal fade dialogbox" id="DialogMessage" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">Pemberitahuan</h5>
                </div>
                <div class="modal-body text-secondary" id="modalMessage">
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-primary" id="btnOk" data-bs-dismiss="modal">OKE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Dialog Info -->

    <?php else: ?>
        <?php foreach ($pembelianTerakhir as $index => $pembelian): ?>
            <div class="section mt-3 mb-3">
                <div class="card <?php echo $cardClasses[$index % count($cardClasses)]; ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($pembelian['creationdate']); ?></h5>
                        <p class="card-text">
                            Paket : <strong><?php echo htmlspecialchars($pembelian['planName']); ?></strong><br>
                            Kode  : <strong><?php echo htmlspecialchars($pembelian['username']); ?></strong>
                        </p>
                        <a href="#" class="btn btn-light" onclick="showConfirmModal('http://10.10.10.1:3990/login?username=<?php echo htmlspecialchars($pembelian['username']); ?>&password=Accept')">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    </div>
    <!-- Dialog Iconed Inline -->
    <div class="modal fade dialogbox" id="DialogIconedButtonInline" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning">Konfrmasi</h5>
                </div>
                <div class="modal-body text-secondary" id="modalMessage">
                    Apakah Anda yakin ingin login?
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-primary" data-bs-dismiss="modal">Batal</a>
                        <a href="#" class="btn btn-text-danger" id="confirmLoginBtn">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Dialog Iconed Inline -->

    <script>
        let loginUrl = '';

        function showConfirmModal(url) {
            loginUrl = url;
            const modal = new bootstrap.Modal(document.getElementById('DialogIconedButtonInline'));
            modal.show();
        }

        document.getElementById('confirmLoginBtn').addEventListener('click', function() {
            if (loginUrl) {
                window.location.href = loginUrl;
            }
        });
    </script>

</body>
</html>
