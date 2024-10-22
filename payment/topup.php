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

function getUserInfo($username) {
    require '../config/mysqli_db.php';

    $sql = "SELECT id, whatsapp_number, balance FROM client WHERE username = ?";
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
    return "" . number_format($number, 0, ',', '.');
}

$userInfo = getUserInfo($_SESSION['username']);
$userId = htmlspecialchars($userInfo['id']);

require '../config/mysqli_db.php';
$stmt = $conn->prepare("SELECT COUNT(*) FROM topup WHERE user_id = ? AND status = 'Pending' AND date >= NOW() - INTERVAL 1 DAY");
$stmt->bind_param("s", $userId);
$stmt->execute();
$stmt->bind_result($pendingCount);
$stmt->fetch();
$stmt->close();

$harga = [10000, 15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 70000, 80000, 90000, 100000];
$warna = ['text-success', 'text-danger', 'text-warning', 'text-primary', 'text-info', 'text-secondary', 'text-dark'];
$total_harga = count($harga);

ob_end_flush();
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>TOPUP</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="#" class="headerButton goBack">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 24 24">
                    <path fill="#6236FF" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
                </svg>
            </a>
        </div>
        <div class="pageTitle">
            Topup
        </div>
        <div class="right">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#DialogIconedInfo">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="#6236FF" d="M10 21H14C14 22.1 13.1 23 12 23S10 22.1 10 21M21 19V20H3V19L5 17V11C5 7.9 7 5.2 10 4.3V4C10 2.9 10.9 2 12 2S14 2.9 14 4V4.3C17 5.2 19 7.9 19 11V17L21 19M17 11C17 8.2 14.8 6 12 6S7 8.2 7 11V18H17V11Z" />
                </svg>
                <span class="badge badge-danger">1</span>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- DialogIconedInfo -->
    <div class="modal fade dialogbox" id="DialogIconedInfo" data-bs-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="48" height="48" viewBox="0 0 24 24">
                        <path fill="#6236FF" d="M12 12C9.97 12 8.1 12.67 6.6 13.8L4.8 11.4C6.81 9.89 9.3 9 12 9S17.19 9.89 19.2 11.4L17.92 13.1C17.55 13.17 17.18 13.27 16.84 13.41C15.44 12.5 13.78 12 12 12M21 9L22.8 6.6C19.79 4.34 16.05 3 12 3S4.21 4.34 1.2 6.6L3 9C5.5 7.12 8.62 6 12 6S18.5 7.12 21 9M12 15C10.65 15 9.4 15.45 8.4 16.2L12 21L13.04 19.61C13 19.41 13 19.21 13 19C13 17.66 13.44 16.43 14.19 15.43C13.5 15.16 12.77 15 12 15M17.75 19.43L16.16 17.84L15 19L17.75 22L22.5 17.25L21.34 15.84L17.75 19.43Z" />
                    </svg>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"><script src="../assets/config/namawifi.js"></script></h5>
                </div>
                <div class="modal-body">
                    <?php echo "<p>Hi " . htmlspecialchars($_SESSION['username']) . ", <br> Sisa saldo Anda adalah : " . money($userInfo['balance']) . "</p>"; ?>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-bs-dismiss="modal">OKE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * DialogIconedInfo -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-2">

            <!-- Paket Unggulan -->
            <div class="section">
                <?php for ($i = 0; $i < $total_harga; $i += 2): ?>
                    <div class="row mt-2">
                        <div class="col-6">
                            <div class="stat-box" style="cursor: pointer;" onclick="showConfirmationModal(<?php echo $harga[$i]; ?>, <?php echo $userId; ?>)">
                                <div class="value <?php echo $warna[$i % count($warna)]; ?>">
                                    <center><?php echo money($harga[$i]); ?></center>
                                </div>
                            </div>
                        </div>
                        <?php if ($i + 1 < $total_harga): ?>
                            <div class="col-6">
                                <div class="stat-box" style="cursor: pointer;" onclick="showConfirmationModal(<?php echo $harga[$i + 1]; ?>, <?php echo $userId; ?>)">
                                    <div class="value <?php echo $warna[($i + 1) % count($warna)]; ?>">
                                        <center><?php echo money($harga[$i + 1]); ?></center>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
            <!-- * Paket Unggulan -->

            <!-- Modal Konfirmasi Pembelian -->
            <div class="modal fade dialogbox" id="DialogPurchaseConfirmation" data-bs-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Topup</h5>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin topup dengan jumlah <span id="selectedAmount"></span>?</p>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <button id="confirmPurchase" class="btn btn-text-primary" onclick="redirectToPurchase()">Ya</button>
                                <button type="button" class="btn btn-text-danger" data-bs-dismiss="modal">Tidak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let currentPurchaseUrl = "";

                function showConfirmationModal(selectedAmount, userId) {
                    currentPurchaseUrl = "./request.php?mac=<?php echo "$mac" ?>&user_id=" + encodeURIComponent(userId) + "&amount=" + encodeURIComponent(selectedAmount);
                    document.getElementById('selectedAmount').textContent = "Rp " + selectedAmount;
                    var myModal = new bootstrap.Modal(document.getElementById('DialogPurchaseConfirmation')); 
                    myModal.show();
                }

                function redirectToPurchase() {
                    window.location.href = currentPurchaseUrl;
                }

                document.addEventListener("DOMContentLoaded", function() {
                    <?php if ($pendingCount > 0): ?>
                        var pendingModal = new bootstrap.Modal(document.getElementById('DialogPendingTopup'));
                        pendingModal.show();
                    <?php endif; ?>
                });
            </script>

            <!-- Popup untuk permintaan topup yang belum dikonfirmasi -->
            <div class="modal fade dialogbox" id="DialogPendingTopup" data-bs-backdrop="static" tabindex="-1" aria-labelledby="DialogPendingTopupLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="DialogPendingTopupLabel">Warning!!</h5>
                        </div>
                        <div class="modal-body">
                            <p>Anda masih memiliki permintaan topup yang belum dikonfirmasi.</p>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <button class="btn btn-text-primary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * Popup untuk permintaan topup yang belum dikonfirmasi -->

            <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
            <script>
                const modalElement = document.getElementById('DialogPendingTopup');
                const modal = new bootstrap.Modal(modalElement);
            
                modalElement.addEventListener('hidden.bs.modal', function () {
                    window.location.href = '../index.php?mac=<?php echo "$mac" ?>';
                });

                function showModal() {
                    modal.show();
                }
            </script>

        </div>
    </div>
    <!-- * App Capsule -->

    <!-- Bootstrap -->
    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Base Js File -->
    <script src="../assets/js/base.js"></script>


</body>

</html>
