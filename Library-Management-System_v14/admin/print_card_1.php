<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
}
else { 

// Correcting the SQL query to directly query for StudentId = 0001
$sql = "SELECT * FROM tblstudents WHERE StudentId = '5620-0' LIMIT 1";
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

// Fetching data from the result
$Image = $result->Image;
$StudentId = $result->StudentId;
$FullName = $result->FullName; 
$designation = $result->designation;
$division = $result->division;
$section = $result->section;
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Aladin&family=Arimo:ital,wght@0,400..700;1,400..700&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&family=Trochut:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <title>Library Card</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style type="text/css">
      .akaya-kanadaka-regular {
        font-family: "Akaya Kanadaka", system-ui;
        font-weight: 400;
        font-style: normal;
      }
      .righteous-regular {
        font-family: "Righteous", sans-serif;
        font-weight: 400;
        font-style: normal;
      }

      .josefin-sans-<uniquifier> {
        font-family: "Josefin Sans", sans-serif;
        font-optical-sizing: auto;
        font-weight: <weight>;
        font-style: normal;
      }
      .trochut-regular {
        font-family: "Trochut", sans-serif;
        font-weight: 400;
        font-style: normal;
      }

      .trochut-bold {
        font-family: "Trochut", sans-serif;
        font-weight: 700;
        font-style: normal;
      }

      .trochut-regular-italic {
        font-family: "Trochut", sans-serif;
        font-weight: 400;
        font-style: italic;
      }
      .aladin-regular {
        font-family: "Aladin", system-ui;
        font-weight: 400;
        font-style: normal;
      }

      .student-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%; /* Makes it round */
        border: 2px solid #28a745; /* Green border */
        box-shadow:
          0 0 0 4px rgba(40, 167, 69, 0.3), /* Outer green glow */
          0 4px 8px rgba(0, 0, 0, 0.2);      /* Subtle black shadow */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      /* Optional: Add a hover effect */
      .student-img:hover {
        transform: scale(1.05);
        box-shadow:
          0 0 0 6px rgba(40, 167, 69, 0.4),
          0 6px 12px rgba(0, 0, 0, 0.3);
      }
      
      #idcard-preview {
        width: 70%;
        height: auto;
        border: 1px solid #ddd;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin: 0px 0;
      }
      
      #card-content {
        width: 300px;
        margin: 0 auto;
        padding: 20px;
      }
      
      @media print {
        body * {
          visibility: hidden;
        }
        #card-content, #card-content * {
          visibility: visible;
        }
        #card-content {
          position: absolute;
          left: 0;
          top: 0;
        }
      }
    </style>
  </head>
  <body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <div class="container">
      <h1 class="text-center mb-4">e-Library Card Generator</h1>
      
      <div class="row">
        <div class="col-md-6">
          <!-- Preview Section -->
          <div class="card mb-4">
            <div class="card-header">
              <h3>Card Preview</h3>
            </div>
            <div class="card-body rounded" >
              <!-- Preview container - will be populated by JavaScript -->
              <div id="idcard-preview" class="card-preview-container rounded"></div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <!-- Controls Section -->
          <div class="card mb-4">
            <div class="card-header">
              <h3>Controls</h3>
            </div>
            <div class="card-body">
              <div class="d-grid gap-3">
                <button onclick="printCard()" class="btn btn-info btn-lg">
                  <i class="fas fa-print"></i> Print Card
                </button>
                <button onclick="downloadPDF()" class="btn btn-success btn-lg">
                  <i class="fas fa-download"></i> Download PDF
                </button>
              </div>
              
              <div class="mt-4">
                <small>Generated on <?php echo date('d M Y, h:i A'); ?></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card content template (HIDDEN in main page, only used for preview/print) -->
    <div id="card-template" style="display: none;">
      <div class="card h-100">
        <!-- Logo & Header -->
        <div class="d-flex align-items-center p-3 me-4">
          <img src="assets/img/bcic_logo.png" alt="Logo" width="60" height="60" class="me-2 ms-2 " id="logo">
          <div>
            <h5 class="mb-0 fw-bold text-uppercase p-0 aladin-regular">Bangladesh Chemical Industries Corporation (BCIC)</h5>
            <small class=" p-0">30-31, Dilkusha, C/A, Motijheel, Dhaka-1000</small><br>
            <small class="p-0">Website: bcic.gov.bd</small>
            
          </div>
        </div>

        <div class="text-center mb-3">
          <span class="p-0 text-center fw-bold text-info text-uppercase ">BCIC Central Library</span><br>
          <span class="badge bg-gradient bg-success text-white fw-bold px-4 py-2 my-2 rounded-pill shadow righteous-regular fs-5 " id="librarycard_text">
            📚 Library Card
          </span>
        </div>

        <div class="text-center mb-3">
          <?php 
            $imagePath = htmlentities($result->Image);
            $defaultImage = "student_images/default.jpg";
            
            if(file_exists($imagePath)) {
                echo '<img src="'.$imagePath.'" class="student-img" alt="Student Image" >';
            } else {
                echo '<img src="'.$defaultImage.'" class="student-img" alt="Default Image">';
            }
          ?>
        </div>            
        
        <div class="card-body bg-light bg-gradient rounded shadow-lg akaya-kanadaka-regular"> 
          <!-- Left-aligned Text -->
          <h5 class="card-title text-body-secondary ">EMP No.: <?php echo $StudentId; ?></h5>
          <!-- <h5 class="card-title text-body-secondary">Validity Date: <?php echo $std_session; ?></h5> -->
          <h5 class="card-title text-body-secondary">Name: <?php echo $FullName; ?></h5>
          <h5 class="card-title text-body-secondary">Designation: <?php echo $result->designation; ?></h5>
          <h5 class="card-title text-body-secondary d-inline-block">Division: <?php echo $result->division; ?> ;</h5>
          <h5 class="card-title text-body-secondary d-inline-block me-3 ">Section: <?php echo $result->section; ?></h5>         
          </div>
        <div class="card-footer text-center bg-success">
          <small class="text-body-success ">Issued on <?php echo date('d M Y'); ?></small>
        </div>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
      // Initialize preview on page load
      window.onload = function() {
        const cardTemplate = document.getElementById('card-template').innerHTML;
        const previewContainer = document.getElementById('idcard-preview');
        
        // Insert the template content into preview container
        previewContainer.innerHTML = cardTemplate;
      };


function printCard() {
    const cardTemplate = document.getElementById('card-template').innerHTML;
    const printWindow = window.open('', '_blank', 'fullscreen=yes');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Library Card - <?php echo $FullName; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
             <style>
                /* Screen view styles */
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                    padding: 20px;
                    background: white !important;
                }
                .card {
                    border: 2px solid gray !important;
                    box-shadow: none !important;
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                }
                .student-img {
                    height: 120px;
                    width: 120px;
                    border-radius: 50%;
                    object-fit: cover;
                    border: 3px solid #ffffff;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    display: block;
                    margin: 0 auto;
                }
                
                /* Print-specific styles */
                @media print {
                    body {
                        display: block;
                        padding: 0 !important;
                        width: 100%;
                        height: 100%;
                        font-size: 10pt !important;
                    }
                    .card {
                        width: 70mm;
                        height: auto;
                        margin: 5mm auto !important;
                        padding: 0mm !important;
                        border: 1px solid #ccc !important;
                    }
                    .card h4 {
                        font-size: 12pt !important;
                    }
                    .card h5 {
                        font-size: 10pt !important;
                        margin-bottom: 0 !important;
                    }
                    .card small {
                        font-size: 7pt !important;
                    }
                    .student-img {
                        height: 80px !important;
                        width: 80px !important;
                        border: 2px solid #ffffff !important;
                    }
                    #logo {
                        height: 50px !important;
                        width: 50px !important;
                        
                    }
                   
                    .badge {
                       background-color: #28a745 !important;
                       color: green !important;
                        font-size: 9pt !important;
                        padding: 0.25em 0.6em !important;
                    }
                    @page {
                        size: A5 ;
                        margin: 5mm;
                    }
                }
            </style>
        </head>
        <body>
            ${cardTemplate}
            <script>
                window.onafterprint = function() {
                    window.close();
                };
                setTimeout(function() {
                    window.print();
                }, 500);
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}


      function downloadPDF() {
    // Create a clone of the template and make it visible temporarily
    const element = document.getElementById("card-template").cloneNode(true);
    element.style.display = 'block';
    element.style.position = 'absolute';
    element.style.left = '0';
    element.style.top = '0';
    element.style.visibility = 'visible';
    document.body.appendChild(element);

    const opt = {
        margin: 0.2,
        filename: 'library_card_<?php echo htmlentities($StudentId); ?>.pdf',
        image: { 
            type: 'jpeg', 
            quality: 0.98 
        },
        html2canvas: { 
            scale: 2,
            logging: true,
            useCORS: true,
            allowTaint: true,
            scrollX: 0,
            scrollY: 0,
            windowWidth: element.scrollWidth,
            windowHeight: element.scrollHeight
        },
        jsPDF: { 
            unit: 'in', 
            format: [3.375, 2.125], // ID card size
            orientation: 'portrait' 
        }
    };
    
    // Show loading indicator
    const loading = document.createElement('div');
    loading.style.position = 'fixed';
    loading.style.top = '0';
    loading.style.left = '0';
    loading.style.width = '100%';
    loading.style.height = '100%';
    loading.style.backgroundColor = 'rgba(0,0,0,0.5)';
    loading.style.color = 'white';
    loading.style.display = 'flex';
    loading.style.justifyContent = 'center';
    loading.style.alignItems = 'center';
    loading.style.zIndex = '9999';
    loading.innerHTML = '<h2>Generating PDF... Please wait</h2>';
    document.body.appendChild(loading);
    
    // Generate PDF
    html2pdf().set(opt).from(element).save().then(() => {
        document.body.removeChild(element);
        document.body.removeChild(loading);
    }).catch(err => {
        console.error('PDF generation error:', err);
        document.body.removeChild(element);
        document.body.removeChild(loading);
        alert('PDF generation failed: ' + err.message);
    });
}
    </script>
  </body>
</html>
<?php } ?>