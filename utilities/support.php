<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>KONTAK</title>
    <link rel="icon" type="image/png" href="../assets/img/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="36" height="36" viewBox="0 0 24 24">
			<path fill="#6236FF" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
			</svg>
        </a>
    </div>
    <div class="pageTitle">
        Kontak
    </div>
    <div class="right">
        <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#DialogTelepon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"  width="24" height="24" viewBox="0 0 24 24">
            <path fill="#6236FF" d="M20,15.5C18.8,15.5 17.5,15.3 16.4,14.9C16.3,14.9 16.2,14.9 16.1,14.9C15.8,14.9 15.6,15 15.4,15.2L13.2,17.4C10.4,15.9 8,13.6 6.6,10.8L8.8,8.6C9.1,8.3 9.2,7.9 9,7.6C8.7,6.5 8.5,5.2 8.5,4C8.5,3.5 8,3 7.5,3H4C3.5,3 3,3.5 3,4C3,13.4 10.6,21 20,21C20.5,21 21,20.5 21,20V16.5C21,16 20.5,15.5 20,15.5M5,5H6.5C6.6,5.9 6.8,6.8 7,7.6L5.8,8.8C5.4,7.6 5.1,6.3 5,5M19,19C17.7,18.9 16.4,18.6 15.2,18.2L16.4,17C17.2,17.2 18.1,17.4 19,17.4V19Z" />
            </svg>
        </a>
    </div>	
</div>
<!-- * App Header -->
	
<!-- Dialog Panggilan Telepon -->
<div class="modal fade dialogbox" id="DialogTelepon" data-bs-backdrop="static" tabindex="-1" role="dialog">
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
                    <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">BATAL</a>
                    <script src="../assets/config/notelepon.js"></script>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Dialog Panggilan Telepon -->

<!-- App Capsule -->
<div id="appCapsule">

<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <div class="p-1">
                <div class="text-center">
                    <h2 class="text-primary">Hubungi Kami</h2>
                    <p>Apa yang ingin anda sampaikan</p>
                </div>
                <form method="post" action="./message.php" id="messageForm">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="Nama">Nama</label>
                            <input type="text" id="Nama" name="name" class="form-control" placeholder="Masukkan Nama" required>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <label class="label" for="whatsapp">WhatsApp:</label>
                        <div class="d-flex">
                            <input type="number" id="whatsapp" name="whatsapp" class="form-control flex-grow-1" placeholder="Masukkan nomor" required value="62">
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="pesan">Pesan</label>
                            <textarea name="message" id="pesan" class="form-control" placeholder="Pesan Anda ....." required></textarea>
                        </div>
                    </div>

                    <div class="mt-2">
                        <input type="submit" value="KIRIM" class="btn btn-primary btn-lg btn-block">
                    </div>
                    <br/>
                    <strong><h3 class="badge badge-success" id="status"></h3></strong>
                </form>
            </div>
        </div>
    </div>
</div>
	
<!-- Dialog Pemberitahuan -->
<div class="modal fade dialogbox" id="DialogPemberitahuan" data-bs-backdrop="static" tabindex="-1" role="dialog">
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
<!-- * Dialog Pemberitahuan -->

<script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
<script>
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const message = getQueryParam('message');
    
    if (message) {
        document.getElementById('modalMessage').innerText = decodeURIComponent(message);
        const modal = new bootstrap.Modal(document.getElementById('DialogPemberitahuan'));
        modal.show();

        modal._element.addEventListener('hidden.bs.modal', function () {
            window.location.href = './kontak.php';
        });
    }
</script>

<!-- Alamat -->
<div class="section mt-2 mb-2">
    <div class="card">
        <div class="card-body">
            <div class="p-1">
                <div class="text-center">
                    <h2 class="text-primary">Alamat Rumah</h2>
                    <p class="card-text" id="alamat-container">
                        <p class="modal-title"><script src="../assets/config/alamat.js"></script></p>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Alamat -->

    </div>
    <!-- * App Capsule -->
	
    <!-- Bootstrap -->
    <script src="../assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Base Js File -->
    <script src="../assets/js/base.js"></script>

</body>

</html>