<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>ফাইল প্রাপ্তি রেজিস্টার</title>

<!-- Bootstrap & DataTables CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

body {
/*    font-family: 'Noto Sans Bengali', sans-serif;*/
body { font-family: 'Noto Sans Bengali', sans-serif; background: #f9f9f9; }
}
    /* Print Styles */
    @media print {
      .no-print, .btn, .modal, .dataTables_length, .dataTables_filter, 
      .dataTables_paginate, #edit_btn, #view_btn, #action_col {
        display: none !important;
      }
      
      .table-bordered {
        border: 1px solid #000 !important;
      }
      
      .table th, .table td {
        border-color: #000 !important;
        background-color: white !important;
        color: black !important;
      }
    }

* {
  font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
}
/* Font Definitions */
@font-face {
  font-family: 'Nikosh';
  src: url('fonts/Nikosh.ttf') format('truetype'),
       url('fonts/Nikosh.woff') format('woff'),
       url('fonts/Nikosh.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

    /* Color Variables */
    :root {
      --custom-purple: #7e11bd;
      --custom-purple-light: #9c3dd4;
      --custom-purple-dark: #5c0d8a;
      --text-light: #ffffff;
      --text-dark: #333333;
    }

    /* Custom Color Classes */
    .bg-custom-purple {
      background-color: var(--custom-purple) !important;
    }
    
    .bg-custom-purple-light {
      background-color: var(--custom-purple-light) !important;
    }
    
    .bg-custom-purple-dark {
      background-color: var(--custom-purple-dark) !important;
    }

    .text-custom-purple {
      color: var(--custom-purple) !important;
    }

    .border-custom-purple {
      border-color: var(--custom-purple) !important;
    }

    .btn-custom-purple {
      background-color: var(--custom-purple);
      border-color: var(--custom-purple);
      color: white;
    }

    .btn-custom-purple:hover {
      background-color: var(--custom-purple-dark);
      border-color: var(--custom-purple-dark);
      color: white;
    }

    /* Table Styles */
    .table-purple thead {
      background-color: var(--custom-purple);
      color: var(--text-light);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(126, 17, 189, 0.1);
    }

    /* Logo Container */
    .imgcontainer {
      text-align: center;
/*      margin: 10px 0 15px 0;*/
       margin: 3px 0 5px 0;
      position: relative;
    }
    
    img.avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--custom-purple);
    }

    /* Navigation Enhancements */
    .navbar-custom {
      background: linear-gradient(135deg, var(--custom-purple) 0%, var(--custom-purple-dark) 100%);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);

      min-height: 50px;
      padding-top: 0.15rem;
      padding-bottom: 0.15rem;
    }

    /* Card Enhancements */
    .card-custom {
      border: 1px solid var(--custom-purple);
      border-radius: 10px;
      transition: transform 0.2s ease-in-out;
    }

    .card-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(126, 17, 189, 0.2);
    }

    /* Loading Spinner */
    .loading-spinner {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
      .imgcontainer img.avatar {
        width: 60px;
        height: 60px;
      }
      
      .table-responsive {
        font-size: 0.875rem;
      }
      
      .btn-group-vertical {
        width: 100%;
      }
    }

    /* Animation Classes */
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Badge Customization */
    .badge-custom {
      font-size: 0.75em;
      padding: 0.35em 0.65em;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--custom-purple);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--custom-purple-dark);
    }

    /* Modal Enhancements */
    .modal-header-custom {
      background: linear-gradient(135deg, var(--custom-purple) 0%, var(--custom-purple-dark) 100%);
      color: white;
      border-bottom: none;
    }

    /* Form Control Enhancements */
    .form-control:focus {
      border-color: var(--custom-purple);
      box-shadow: 0 0 0 0.2rem rgba(126, 17, 189, 0.25);
    }

    /* Alert Customization */
    .alert-custom {
      border-left: 4px solid var(--custom-purple);
    }

    /* Footer Styles */
    .footer-custom {
      background-color: var(--custom-purple);
      color: white;
      padding: 1rem 0;
      margin-top: 2rem;
    }

    /* Modal Height Fix */
    .modal-dialog-scrollable .modal-content {
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

   .modal-dialog-scrollable .modal-body {
    overflow-y: visible !important;
    flex: 1;
}


    .modal-dialog-scrollable .modal-footer {
        flex-shrink: 0;
    }

    .custom-alert {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .modal-dialog,
.modal-content {
    overflow: visible !important;
}

.chosen-container .chosen-drop {
    position: absolute !important;
    z-index: 99999 !important;
}

  </style>
</head>