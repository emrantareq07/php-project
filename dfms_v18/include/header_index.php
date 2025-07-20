<!DOCTYPE html>
<html lang="en">
<head>
  <title>DFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap 5.1.2 (most recent stable version) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Google Fonts (merged all into one link) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&family=Cinzel:wght@400;900&family=Itim&family=Katibeh&family=Merienda:wght@300;900&family=Montserrat+Alternates:wght@100;900&family=Rowdies:wght@300;700&display=swap" rel="stylesheet">

<!-- Font Awesome (optional, only include if needed) -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->

<!-- jQuery (required for Bootstrap 4 and Bootstrap Select) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Popper.js (required for Bootstrap 4 tooltips and popovers) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

<!-- Bootstrap JS (use the version matching the Bootstrap CSS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap Select JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- jsPDF (if needed for PDF generation) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
.akaya-kanadaka-regular {
  font-family: "Akaya Kanadaka", system-ui;
  font-weight: 400;
  font-style: normal;
}

    .itim-regular {
  font-family: "Itim", cursive;
  font-weight: 400;
  font-style: normal;
}

/* Hide the print button when printing */
@media print {
    #print-btn {
        display: none;
    }
     #search-btn {
        display: none;
    }
     #login-btn {
        display: none;
    }
         #date {
        display: none;
    }
    #download_pdf {
        display: none;
    }
    #start_date {
        display: none;
    }
    #end_date {
        display: none;
    }
     #navbarDropdown {
        display: none;
    }
     #logout {
        display: none;
    }
    #reload-btn {
        display: none;
    }
    #print_ind_tenants_aa {
        display: none;
    }
}
/*@page {
  size: A4 portrait;
  margin: 5mm; /* Adjust margins as needed */
}*/
@media print {
        /* Display the print header */
        #print-header {
            display: block !important;
        }
    }

/* Landscape */
@page {
  size: A4 landscape;
  margin: 0mm;
}
 @media print { 
    /* Ensure content fits within the print area */
    #print-content {
      margin: 0;
      padding: 0;
      border: none;
      overflow: hidden; /* Prevent scrolling */
      width: 100%; /* Ensure it spans the full printable width */
    }

    /* General body adjustments for print */
    body {
      overflow: hidden; /* Remove horizontal scroll */
      margin: 0;
    }
  }

  @media print {
        .no-wrap {
            white-space: nowrap;
        }
    }
  
    /* Center the reloading message */
    #reload-message {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    color: white; /* White text for contrast */
    font-weight: bold;
    text-align: center;
    background-color: green; /* Solid green background */
    padding: 20px;
    border: 2px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

</style> 

<link rel="icon" type="image/gif/png" href="assets/img/bcic_logo.png">
</head>
<body>
<div class=" mt-1" >

</div> 