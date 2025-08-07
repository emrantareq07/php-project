<!DOCTYPE html>
<html lang="en">
<head>
  <title>DFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"> 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">    
  
   <style>

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

</style>
<link rel="icon" type="image/gif/png" href="assets/img/bcic_logo.png">
</head>
<body>
<div class="text-center mt-3" >
  <!--<h1>Shahjalal Fertilizer Company Ltd.(SFCL)</h1>
  <p>A Govt. owned Company of BCIC under Ministry of Industries.</p> 
  <p>Digital Fertilizer....</p>-->
</div> 