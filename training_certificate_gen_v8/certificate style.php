<style>
@font-face {
    font-family: 'Certificate';
    src: url('fonts/Certificate.ttf') format('truetype');
}

/* ===== BCC Government Style Border ===== */
.certificate-wrapper {
    width: 297mm;
    height: 210mm;
    margin: 20px auto;
    padding: 18px;
    background: #ffffff;
    position: relative;

    /* Outer Dark Blue Border */
    border: 10px solid #0b3d91;

    /* Inner Gold Border Effect */
    box-shadow: 
        0 0 0 6px #d4af37,
        0 0 30px rgba(0,0,0,0.25);
}

/* Decorative Corner Design */
.certificate-wrapper::before,
.certificate-wrapper::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    border: 6px solid #d4af37;
}

/* Top Left Corner */
.certificate-wrapper::before {
    top: -6px;
    left: -6px;
    border-right: none;
    border-bottom: none;
}

/* Bottom Right Corner */
.certificate-wrapper::after {
    bottom: -6px;
    right: -6px;
    border-left: none;
    border-top: none;
}

/* Inner Content Area */
.certificate-inner {
    width: 100%;
    height: 100%;
    background: #ffffff;
    padding: 15mm;
    position: relative;
}

/* Light Watermark Background */
.certificate-inner::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 380px;
    height: 380px;
    background: url('../logo/bcic_logo.png') no-repeat center;
    background-size: contain;
    opacity: 0.05;
}

/* Layout */
.certificate-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Header */
.header-logos {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    height: 80px;
}

.header-text {
    text-align: center;
    flex: 1;
}

.header-text h3 {
    font-size: 24px;
    color: #0b3d91;
    margin: 2px 0;
}

.header-text h4 {
    font-size: 18px;
    color: #0b3d91;
    margin: 2px 0;
}

/* Title */
h2 {
    font-family: 'Certificate';
    font-size: 56px;
    text-align: center;
    color: #0b3d91;
}

/* Participant Name */
.participant-name {
    font-size: 42px;
    font-weight: bold;
    text-align: center;
    color: #222;
}

/* Certificate Text */
.certificate-text {
    font-size: 19px;
    line-height: 1.8;
    text-align: center;
    color: #333;
}

/* Signatures */
.signatures {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
}

.signature {
    text-align: center;
    width: 250px;
}

.signature-img img {
    width: 120px;
}

.signature-name {
    margin-top: 10px;
    padding-top: 5px;
    border-top: 1px solid #000;
    font-weight: bold;
}

.signature-title {
    font-size: 12px;
}

/* QR */
.qr-code {
    text-align: center;
}

.qr-code img {
    width: 100px;
}

.qr-code span {
    font-size: 12px;
}
</style>