<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>STATUS</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.svg" sizes="32x32">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
    
    <!-- App Header -->
    <div class='appHeader'>
		<div class='left'>
			<div class='form-check form-switch ms-2'>
				<input class='form-check-input dark-mode-switch' type='checkbox' id='darkmodeSwitch'>
				<label class='form-check-label' for='darkmodeSwitch'></label>
			</div>
		</div>
        <div class='pageTitle'>
        </div>
		<div class='right'>
            <a href='#' class='headerButton' data-bs-toggle='modal' data-bs-target='#Notif'>
                <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1'  width='24' height='24' viewBox='0 0 24 24'>
				<path fill='#6236FF' d='M10 21H14C14 22.1 13.1 23 12 23S10 22.1 10 21M21 19V20H3V19L5 17V11C5 7.9 7 5.2 10 4.3V4C10 2.9 10.9 2 12 2S14 2.9 14 4V4.3C17 5.2 19 7.9 19 11V17L21 19M17 11C17 8.2 14.8 6 12 6S7 8.2 7 11V18H17V11Z' />
				</svg>
                <span class='badge badge-danger'>1</span>
            </a>
        </div>
    </div>
    <!-- * App Header -->
	
    <!-- Logout -->
    <div class='modal fade dialogbox' id='Logout' data-bs-backdrop='static' tabindex='-1' role='dialog'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Logout</h5>
                </div>
                <div class='modal-body'>
                    Anda yakin ingin logout.?
                </div>
                <form action='$(link-logout)' class='modal-footer' name='logout' onSubmit='return openLogout()'>
					<input type='hidden' name='erase-cookie' value='on'/>
                    <div class='btn-inline'>
                        <a href='#' class='btn btn-text-primary' data-bs-dismiss='modal'>Batal</a>
						<a class='btn btn-text-danger' href="http://10.10.10.1:3990/logoff" role='button'>Logout</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- * Logout -->
	
	<!-- Notifikasi -->
        <div class='modal fade dialogbox' id='Notif' data-bs-backdrop='static' tabindex='-1' role='dialog'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-icon'>
						<div class='avatar-section'>
							<a href='#'>
								<img src='assets/img/worldwide.gif' alt='avatar' class='imaged w100 rounded'>
								<span class='button btn-success'>
									<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1'  width='24' height='24' viewBox='0 0 24 24'>
									<path fill='#ffffff' d='M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z' />
									</svg>
								</span>
							</a>
						</div>
                    </div>
                    <div class='modal-header'>
                        <h5 class='modal-title'><script src='assets/config/namawifi.js'></script></h5>
                    </div>
                    <div class='modal-body'>
                        <script src='assets/config/informasi.js'></script>
                    </div>
                    <div class='modal-footer'>
                        <div class='btn-inline'>
                            <a href='#' class='btn btn-text-primary' data-bs-dismiss='modal'>Close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Notifikasi -->";
        
        <?php
	    if (isset($_GET['mac'])) {
            
            require './config/mysqli_db.php';

            $mac_address = $_GET['mac'];
			$query = "SELECT username, AcctStartTime,CASE WHEN AcctStopTime is NULL THEN timestampdiff(SECOND,AcctStartTime,NOW()) ELSE AcctSessionTime END AS AcctSessionTime,
					NASIPAddress,CalledStationId,FramedIPAddress,CallingStationId,AcctInputOctets,AcctOutputOctets 
					FROM radacct
					WHERE callingstationid = '$mac_address' ORDER BY RadAcctId DESC LIMIT 1";

            $result = $conn->query($query);

            $data = array();

            $sqlUser = "SELECT username FROM radacct WHERE callingstationid='$mac_address' ORDER BY acctstarttime DESC LIMIT 1;";
            $resultUser = mysqli_fetch_assoc(mysqli_query($conn, $sqlUser));
            $user = $resultUser['username'];
                    
            $sqlTotalSession = "SELECT g.value as total_session FROM radgroupcheck as g, radusergroup as u WHERE u.username = '$user' AND g.groupname = u.groupname AND g.attribute ='Max-All-Session';";
            $resultTotalSession = mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalSession));
            $totalSession = isset($resultTotalSession['total_session']) ? $resultTotalSession['total_session'] : 0;

            $sqlTotalKuota = "SELECT VALUE AS total_kuota
            FROM radgroupreply
            WHERE ATTRIBUTE = 'ChilliSpot-Max-Total-Octets'
              AND GROUPNAME = (
                SELECT GROUPNAME
                FROM radusergroup
                WHERE USERNAME = '$user'
              )";

            $resultTotalKuota = mysqli_fetch_assoc(mysqli_query($conn, $sqlTotalKuota));
            if (is_array($resultTotalKuota) && isset($resultTotalKuota['total_kuota'])) {
                $totalKuota = $resultTotalKuota['total_kuota'];
            } else {
                $totalKuota = 0;
            }
            
            $sqlKuotaDigunakan = "SELECT SUM(acctinputoctets + acctoutputoctets) as kuota_terpakai FROM radacct WHERE username = '$user';";
            $resultKuotaDigunakan = mysqli_fetch_assoc(mysqli_query($conn, $sqlKuotaDigunakan));
            $KuotaDigunakan = $resultKuotaDigunakan['kuota_terpakai'];
    
            $sqlFirstLogin = "SELECT acctstarttime AS first_login FROM radacct WHERE username='$user' ORDER BY acctstarttime ASC LIMIT 1;";
            $resultFirstLogin = mysqli_fetch_assoc(mysqli_query($conn, $sqlFirstLogin));
            $firstLogin = $resultFirstLogin['first_login'];

            $duration = $totalSession;
            $expiryTime = strtotime($firstLogin) + $duration;
            
            $sisaKuota = $totalKuota - $KuotaDigunakan;

            $remainingTime = $expiryTime - time();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $username = $row['username'];
                $userUpload = toxbyte($row['AcctInputOctets']);
				$userDownload = toxbyte($row['AcctOutputOctets']);
				$userTraffic = toxbyte($row['AcctOutputOctets'] + $row['AcctInputOctets']);
				$userLastConnected = $row['AcctStartTime'];
				$userOnlineTime = time2str($row['AcctSessionTime']);
				$nasIPAddress = $row['NASIPAddress'];
				$nasMacAddress = $row['CalledStationId'];
				$userIPAddress = $row['FramedIPAddress'];
				$userMacAddress = $row['CallingStationId'];
				$userExpired = time2str($remainingTime);
				$UserKuota = toxbyte($sisaKuota);
                
                $data[] = array(
                'username' => $username,
                'userIPAddress' => $userIPAddress,
                'userMacAddress' => $userMacAddress,
                'userDownload' => $userDownload,
                'userUpload' => $userUpload,
                'userTraffic' => $userTraffic,
				'userLastConnected' => $userLastConnected,
                'userOnlineTime' => $userOnlineTime,
                'userExpired' => $userExpired,
                'userKuota' => $UserKuota,
                );
            }
        }

$conn->close();
foreach ($data as $row) {
                    
echo '<div id="appCapsule">
    <div class="section mt-3 text-center">
        <div class="avatar-section">
            <a href="#">
                <img src="assets/img/worldwide.gif" alt="avatar" class="imaged w100 rounded">
                <span class="button btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#ffffff" d="M10,17L6,13L7.41,11.59L10,14.17L16.59,7.58L18,9M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1Z" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <div class="listview-title mt-1">Account</div>
    <ul class="listview image-listview text inset">
        <li><div class="item"><div class="in"><div>Username</div><div>' . $row['username'] . '</div></div></div></li>
        <li><div class="item"><div class="in"><div>IP Address</div><div>' . $row['userIPAddress'] . '</div></div></div></li>
        <li><div class="item"><div class="in"><div>MAC Address</div><div>' . $row['userMacAddress'] . '</div></div></div></li>
    </ul>

    <div class="listview-title mt-1">Usage</div>
    <ul class="listview image-listview text inset">
        <li><div class="item"><div class="in"><div>Upload</div><div id="upload">' . $row['userUpload'] . '</div></div></div></li>
        <li><div class="item"><div class="in"><div>Download</div><div id="userDownload">' . $row['userDownload'] . '</div></div></div></li>
        <li><div class="item"><div class="in"><div>Traffic</div><div id="traffic">' . $row['userTraffic'] . '</div></div></div></li>
        <li><div class="item"><div class="in"><div>Connected</div><div id="onlineTime">' . $row['userOnlineTime'] . '</div></div></div></li>';

if ($userExpired >= 1) {
    echo '<li><div class="item"><div class="in"><div>Remaining</div><div id="userExpired">' . $row['userExpired'] . '</div></div></div></li>';
}
if ($totalKuota >= 1) {
    echo '<li><div class="item"><div class="in"><div>Quota</div><div id="userKuota">' . $row['userKuota'] . '</div></div></div></li>';
}

echo '</ul>';

    }
}
        
function toxbyte($size) {
    if ($size > 1073741824) {
        return round($size / 1073741824, 2) . " GB";
    } elseif ($size > 1048576) {
        return round($size / 1048576, 2) . " MB";
    } elseif ($size > 1024) {
        return round($size / 1024, 2) . " KB";
    } else {
        return $size . " B";
    }
}
	
function time2str($time) {

$str = "";
$time = floor($time);
if (!$time)
	return "0 detik";
$d = $time/86400;
$d = floor($d);
if ($d){
	$str .= "$d hari, ";
	$time = $time % 86400;
}
$h = $time/3600;
$h = floor($h);
if ($h){
	$str .= "$h jam, ";
	$time = $time % 3600;
}
$m = $time/60;
$m = floor($m);
if ($m){
	$str .= "$m menit, ";
	$time = $time % 60;
}
if ($time)
	$str .= "$time detik, ";
$str = preg_replace("/, $/",'',$str);
return $str;
}
?>
<div class="listview-title mt-1"></div>
    <div class="appBottomMenu">
	    <a href="./index.php?mac=<?php echo $_GET['mac']; ?>" class="item goBack">
		    <div class="col">
			    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				    <path fill="#6236FF" d="M17 14H19V17H22V19H19V22H17V19H14V17H17V14M5 20V12H2L12 3L22 12H17V10.19L12 5.69L7 10.19V18H12C12 18.7 12.12 19.37 12.34 20H5Z" />
				    </svg>
				    <strong>Beranda</strong>
				</div>
			</a>
		    <a href="javascript:;" class="item" data-bs-toggle="modal" data-bs-target="#Logout">
				<div class="col">
					<div class="action-button large">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
					   <path fill="#ffffff" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" />
					</svg>
					</div>
				</div>
			</a>
			<a href="./utilities/support.php?mac=<?php echo $_GET['mac']; ?>" class="item">
				<div class="col">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
				    <path fill="#6236FF" d="M6,17C6,15 10,13.9 12,13.9C14,13.9 18,15 18,17V18H6M15,9A3,3 0 0,1 12,12A3,3 0 0,1 9,9A3,3 0 0,1 12,6A3,3 0 0,1 15,9M3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3H5C3.89,3 3,3.9 3,5Z" />
				    </svg>	
					<strong>Kontak</strong>
				</div>
			</a>
		</div>
    </div>
</div>

<script>
    function loadUsageData() {
        var macAddress = "<?php echo $_GET['mac']; ?>";
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./template/default/get_usage.php?mac=" + macAddress, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);

                document.getElementById("upload").innerHTML = data.userUpload;
                document.getElementById("userDownload").innerHTML = data.userDownload;
                document.getElementById("traffic").innerHTML = data.userTraffic;
                document.getElementById("onlineTime").innerHTML = data.userOnlineTime;
                document.getElementById("userExpired").innerHTML = data.userExpired;
                document.getElementById("userKuota").innerHTML = data.userKuota;
            }
        };

        xhr.onerror = function() {
            console.error("Request failed");
        };

        xhr.send();
    }

    setInterval(loadUsageData, 1000);
    window.onload = loadUsageData;
</script>

    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>
</body>
</html>