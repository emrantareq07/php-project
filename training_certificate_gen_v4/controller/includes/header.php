
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Batch Certificates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Aladin&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Lora:ital,wght@0,400..700;1,400..700&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&family=Trochut:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
.montserrat-alternates-regular {
  font-family: "Montserrat Alternates", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.montserrat-alternates-bold {
  font-family: "Montserrat Alternates", sans-serif;
  font-weight: 700;
  font-style: normal;
}

.righteous-regular {
  font-family: "Righteous", sans-serif;
  font-weight: 450;
  font-style: normal;
}

.lobster-two-regular {
  font-family: "Lobster Two", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.aladin-regular {
  font-family: "Aladin", system-ui;
  font-weight: 400;
  font-style: normal;
}
.rowdies-regular {
  font-family: "Rowdies", sans-serif;
  font-weight: 400;
  font-style: normal;
}

body { font-family: 'Roboto', sans-serif; background: #ffff; padding: 20px; }
.container { max-width: 100%; margin: auto; }

/* Outer wrapper with gradient border */
.certificate-wrapper {
    width: 297mm; height: 210mm; /* A4 landscape */
    margin: auto;
    position: relative;
    
    padding-top: 25px;    /* top border width */
    padding-right: 15px;  /* right border width */
    padding-bottom: 20px; /* bottom border width */
    padding-left: 10px;   /* left border width */
     background: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491);
/*    background: linear-gradient(45deg, #2c3e50, #27ae60, #4a6491);*/ for border color
    border-radius: 20px;
    box-sizing: border-box;
}

/* Inner white content */
.certificate-inner {
    width: 100%; height: 100%;
    background: #fff;
    border-radius: 10px;
    padding: 15mm;
    position: relative;
    overflow: hidden;
}

/* Watermark */
.certificate-inner::before {
    content: "";
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-25deg);
    width: 380px; height: 380px;
    background: url('../logo/bcic_logo.png') no-repeat center center;
    background-size: contain;
    opacity: 0.05;
    z-index: 1;
    pointer-events: none;
}

.certificate-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; justify-content: space-between; }

.header-logos { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.header-text { text-align: center; flex: 1; }
.header-text h3 { margin: 2px 0; font-size: 24px; color:#4a6491; }
.header-text h4 { margin: 1px 0; font-size: 18px; color:#4a6491; }
.header-text h5 { margin: 2px 0; font-size: 14px; color:#4a6491; }
.header-text h2 { font-size: 36px; margin: 5px 0; }
h6{ font-size: 11px; color:#4a6491;}
.logo { height: 80px; width: auto; }
span{ font-size: 10px; color:#4a6491;}
/* Center participant name and certificate text */
.participant-name { 
    font-size: 42px; 
    font-weight: 900; 
    margin: 5px 0 10px; 
    color: #2c3e50; 
    text-align: center; /* Centered */
}
.certificate-text {
    font-size: 19px;
    line-height: 1.8;
    margin: 0px 0;
    color: #333;
    text-align: justify;
    text-align-last: center; /* ✅ last line centered */
}

.signatures { display: flex; justify-content: space-around; margin-top: 20px; }
.signature { text-align: center; width: 250px; }
.signature-img img { width: 120px; height: auto; }
.signature-name { margin-top: 10px; padding-top: 5px; border-top: 1px solid #000; font-weight: bold; }
.signature-title { font-size: 12px; }
</style>
</head>
<body>