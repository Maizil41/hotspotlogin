<?php
if (isset($_GET['mac'])) {
    $mac = $_GET['mac'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>FAQ</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="">

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="../profile/profile.php?mac=<?php echo "$mac" ?>" class="headerButton goBack">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="36" height="36" viewBox="0 0 24 24">
				<path fill="#6236FF" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
				</svg>
            </a>
        </div>
        <div class="pageTitle">
            Pusat Bantuan
        </div>
    </div>
    <!-- * App Header -->
	
	<!-- DialogIconedInfo -->
        <div class="modal fade dialogbox" id="DialogIconedInfo" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="48" height="48" viewBox="0 0 24 24">
						<path fill="#6236FF" d="M12 12C9.97 12 8.1 12.67 6.6 13.8L4.8 11.4C6.81 9.89 9.3 9 12 9S17.19 9.89 19.2 11.4L17.92 13.1C17.55 13.17 17.18 13.27 16.84 13.41C15.44 12.5 13.78 12 12 12M21 9L22.8 6.6C19.79 4.34 16.05 3 12 3S4.21 4.34 1.2 6.6L3 9C5.5 7.12 8.62 6 12 6S18.5 7.12 21 9M12 15C10.65 15 9.4 15.45 8.4 16.2L12 21L13.04 19.61C13 19.41 13 19.21 13 19C13 17.66 13.44 16.43 14.19 15.43C13.5 15.16 12.77 15 12 15M17.75 19.43L16.16 17.84L15 19L17.75 22L22.5 17.25L21.34 15.84L17.75 19.43Z" />
						</svg>
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title"><script src="../assets/config/namawifi.js"></script></h5>
                    </div>
                    <div class="modal-body">
                        Gunakan internet dengan bijak!!!
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


        <div class="section mt-2 text-center">
            <div class="card">
                <div class="card-body pt-3 pb-3">
                    <img src="../assets/img/faq.svg" alt="image" class="imaged w-50 ">
                    <h2 class="mt-2">Halaman <br> Pusat Bantuan</h2>
                </div>
            </div>
        </div>

        <div class="section inset mt-2">
            <div class="accordion" id="accordionExample1">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion01">
                            Kenapa saat login user selalu penuh ?
                        </button>
                    </h2>
                    <div id="accordion01" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
                        <div class="accordion-body">
                            Biasanya hal ini terjadi karena Penggunaan MAC Acak, Solusinya sebelum anda login tahan pada nama wifi dan cari menu privasi lalu ganti menjadi <b>"Gunakan MAC Perangkat".</b>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion02">
                            Kenapa Saya tidak bisa login?
                        </button>
                    </h2>
                    <div id="accordion02" class="accordion-collapse collapse" data-bs-parent="#accordionExample1">
                        <div class="accordion-body">
                            Saat anda memasukan Kode voucher atau username dan password harap perhatikan penulisan huruf besar, kecil ataupun angka. ini sangat berpengaruh saat anda login.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section inset mt-2">
            <div class="accordion" id="accordionExample2">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion11">
                            Kenapa internet lambat?
                        </button>
                    </h2>
                    <div id="accordion11" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                        <div class="accordion-body">
                            Internet lambat biasanya di pengaruhi oleh beberapa faktor, misalnya saat anda terhubung ke layanan kami mungkin perangkat anda langsung download beberapa update aplikasi atau sofware. ini sangat berpengaruh. Atau perangkat anda memory/ram tinggal sedikit lagi, ini sangat berpengaruh terhadap loading aplikasi yang di gunakan. Kami juga tidak menyangkal mungkin kami sedang mengalami gangguan atau sedang ada perbaikan. jadi harap di maklum jika itu yang terjadi.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-3 mb-3">
            <div class="card bg-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">Masih punya pertanyaan?</h5>
                    <p class="card-text">
                        Silahkan hubungi kami
                    </p>
                    <a href="./kontak.php?mac=<?php echo "$mac" ?>" class="btn btn-dark">
                        Kontak
                    </a>
                </div>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

    <!-- Bootstrap -->
    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Base Js File -->
    <script src="../assets/js/base.js"></script>

</body>

</html>