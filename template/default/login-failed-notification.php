<?php
echo "<div class='container-fluid'><div class='alert-wrapper'>";

echo "<div class='modal fade dialogbox' id='DialogIconedButtonInline' data-bs-backdrop='static' tabindex='-1' role='dialog'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title text-warning'>Pemberitahuan</h5>
                </div>
                <div class='modal-body text-danger' id='modalMessage'>
                </div>
                <div class='modal-footer'>
                    <div class='btn-inline'>
                        <a href='#' class='btn btn-text-primary' data-bs-dismiss='modal'>OKE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>";

echo "</div></div>";
?>

<script src="./assets/js/lib/bootstrap.bundle.min.js"></script>
<script>
    const h1Failed = <?php echo json_encode($h1Failed); ?>;
    let reply = <?php echo json_encode($reply); ?>;

    if (reply === 'Your maximum never usage time has been reached') {
        reply = 'Kode voucher sudah kadaluarsa';
    }

    let message = '';

    if (reply) {
        message = reply;
    } else if (h1Failed) {
        message = h1Failed;
    }

    if (message) {
        document.getElementById('modalMessage').innerHTML = message;
        const modal = new bootstrap.Modal(document.getElementById('DialogIconedButtonInline'));
        modal.show();

        modal._element.addEventListener('hidden.bs.modal', function () {
            window.location.href = 'http://10.10.10.1:3990';
        });
    }
</script>