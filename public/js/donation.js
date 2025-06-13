document.addEventListener('DOMContentLoaded', function () {
    const qrButton = document.getElementById('show-qr-button');
    const qrPopup = document.getElementById('qr-code-popup');
    const closeQr = document.querySelector('.close-qr');

    // Show the QR Code popup when the button is clicked
    qrButton.addEventListener('click', function () {
        qrPopup.style.display = 'flex';
    });

    // Close the QR Code popup when the close button is clicked
    closeQr.addEventListener('click', function () {
        qrPopup.style.display = 'none';
    });

    // Close the QR Code popup when clicking outside the popup content
    window.addEventListener('click', function (e) {
        if (e.target === qrPopup) {
            qrPopup.style.display = 'none';
        }
    });
});