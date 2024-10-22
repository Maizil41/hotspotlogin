<!doctype html>
<html lang="en">

<head>
	<meta http-equiv="refresh" content="3; url=$(link-redirect)">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>Redirect</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="assets/css/style.css">
    <script language="JavaScript">
        function startClock() {
            location.href = 'http://10.10.10.1:3990';
        }
    </script>
</head>

<body class="bg-white">

    <!-- App Header -->
    <div class="appHeader no-border">
        <div class="left">
            <a href="$(link-login)" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Verifikasi
        </div>
        <div class="right"> </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section">
            <div class="splash-page mt-5 mb-5">

                <div class="transfer-verification">
                    <div class="transfer-amount">
                        <h2><span class="text-success">Memverifikasi kode voucher</span></h2><br/>
                    </div>
                    <div class="from-to-block mb-5">
                        <div class="item text-start">
                <div class="iconbox bg-warning mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="36" height="36" viewBox="0 0 24 24">
					<path fill="#ffffff" d="M6 8C6 5.79 7.79 4 10 4S14 5.79 14 8 12.21 12 10 12 6 10.21 6 8M10 14C5.58 14 2 15.79 2 18V20H13.09C13.04 19.67 13 19.34 13 19C13 17.36 13.66 15.87 14.74 14.78C13.41 14.29 11.78 14 10 14M23 19L20 16V18H16V20H20V22L23 19Z" />
					</svg>
                </div>
                        </div>
                        <div class="item text-end">
                <div class="iconbox bg-success mb3">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="36" height="36" viewBox="0 0 24 24">
					<path fill="#ffffff" d="M21.1,12.5L22.5,13.91L15.97,20.5L12.5,17L13.9,15.59L15.97,17.67L21.1,12.5M10,17L13,20H3V18C3,15.79 6.58,14 11,14L12.89,14.11L10,17M11,4A4,4 0 0,1 15,8A4,4 0 0,1 11,12A4,4 0 0,1 7,8A4,4 0 0,1 11,4Z" />
					</svg>
                </div>
                        </div>
                        <div class="arrow"></div>
                    </div>
                </div>
                <div class="spinner-border text-primary" role="status"></div><h2 class="mb-2 mt-2">Anda akan dialihkan ke Halaman Status</h2>
                <p>
                    <strong class="text-primary">Jika tidak otomatis,</strong><br>Silahkan klik Lanjutkan
                </p>
            </div>
        </div>

        <div class="fixed-bar">
            <div class="row">
                <div class="col-6">
                    <a href="http://10.10.10.1:3990/logoff" class="btn btn-lg btn-outline-secondary btn-block">Kembali</a>
                </div>
                <div class="col-6">
                    <a href="http://10.10.10.1:3990" class="btn btn-lg btn-primary btn-block">Lanjutkan</a>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->


    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>


</body>

</html>