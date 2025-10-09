<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Phone Book App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bengali Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Aladin&family=Arimo:ital,wght@0,400..700;1,400..700&family=Asimovian&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Cinzel:wght@400..900&family=Gluten:wght@100..900&family=Itim&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Katibeh&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Lora:ital,wght@0,400..700;1,400..700&family=Merienda:wght@300..900&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Righteous&family=Rowdies:wght@300;400;700&family=Sansita+Swashed:wght@300..900&family=Trochut:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>

  .asimovian-regular {
  font-family: "Asimovian", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.lobster-two-regular {
  font-family: "Lobster Two", sans-serif;
  font-weight: 400;
  font-style: normal;
}

.lobster-two-bold {
  font-family: "Lobster Two", sans-serif;
  font-weight: 700;
  font-style: normal;
}

.lobster-two-regular-italic {
  font-family: "Lobster Two", sans-serif;
  font-weight: 400;
  font-style: italic;
}

.lobster-two-bold-italic {
  font-family: "Lobster Two", sans-serif;
  font-weight: 700;
  font-style: italic;
}
.aladin-regular {
  font-family: "Aladin", system-ui;
  font-weight: 400;
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
.righteous-regular {
  font-family: "Righteous", sans-serif;
  font-weight: 400;
  font-style: normal;
}

  


.contact-row { cursor: pointer; }
.contact-row:hover { background: #f8f9fa; }
.contact-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border-left: 5px solid #0d6efd; /* Blue accent */
}
.contact-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
 /* Bengali Font Stack */
        .bn {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Kalpurush', 'Arial', sans-serif;
            line-height: 1.6;
        }
        
        /* English Font Stack */
        .en {
            font-family: 'Arial', 'Helvetica', sans-serif;
        }
        
        /* Apply to all elements by default */
        body {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', 'Arial', sans-serif;
        }
        
        /* Header specific styles */
        .header-bangla {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
            font-weight: 600;
        }
        
        .header-english {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: 500;
        }
        
        /* Navigation styles */
        .nav-bangla {
            font-family: 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', sans-serif;
            font-size: 1.1rem;
        }
  * {
    font-family: 'Open Sans', sans-serif;

    font-family: 'Tiro Bangla', serif;
    font-family: 'Noto Sans Bengali', sans-serif;

    font-family: 'Nikosh', sans-serif;

    font-family: 'Nikosh', serif;
}
</style>
</head>
<body>
