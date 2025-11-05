 function generatePrintView(data) {
    // Create print content
    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>BCIC Employee Data Report - ${data.date}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
             <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { font-family: Arial, sans-serif; margin: 20px; }
                .print-header { background: #f8f9fa; padding: 20px; border-bottom: 2px solid #dee2e6; margin-bottom: 20px; border-radius: 8px; }
                .print-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
                .print-table th, .print-table td { border: 1px solid #dee2e6; padding: 6px; text-align: center; }
                .print-table th { background-color: #e9ecef; font-weight: bold; }
                .department-cell { text-align: left; font-weight: bold; background-color: #f8f9fa !important; min-width: 180px; }
                .total-row { background-color: #e9ecef !important; font-weight: bold; }
                .male-col { background-color: #e3f2fd !important; }
                .female-col { background-color: #fce4ec !important; }
                .grade-total { background-color: #f5f5f5 !important; }
                .section-total { background-color: #e9ecef !important; font-weight: bold; }
                .grand-total { background-color: #495057 !important; color: white !important; }
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; }
                    .print-table { font-size: 10px; }
                    .print-header { margin: 10px; }
                }
                .text-center { text-align: center; }
                .mb-2 { margin-bottom: 10px; }
                .mb-1 { margin-bottom: 5px; }
                .mb-0 { margin-bottom: 0; }
                font-family: 'Noto Sans Bengali', sans-serif;
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

                    /* Base Typography */
                    * {
                      font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
                    }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header text-center">
                <h2 class="mb-2">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                <h5 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০।</h5>
                    <h2 class="mb-2">অফিসার তথ্য</h2>
                    <h4 class="mb-1">কারখান/প্রতিষ্ঠান/প্রকল্পের নাম : <?php echo $factory_name; ?></h4>
                    <h5 class="mb-0">বিদ্যমান জনবলেন পরিসংখ্যান ( ${data.date} তারিখে)</h5>
                    

                </div>
    `;

    // Create the table structure
    printContent += `
        <table class="print-table">
            <thead>
                <tr>
                    <th  auto increment>ক্রম </th>
                      <th designation >পদের নাম </th>
    `;

    // Add grade headers
    <?php foreach($grades as $grade): ?>
    printContent += `
        <th colspan="3" class="text-center">
            গ্রেড <?php echo englishToBanglaNumber( substr($grade, 1)); ?>
        </th>
    `;
    <?php endforeach; ?>

    printContent += `
        <th colspan="3" class="text-center">সর্বমোট</th>
                </tr>
                <tr>
                    <th class="department-cell"></th>
    `;

    // Add sub-headers for each grade
    <?php foreach($grades as $grade): ?>
    printContent += `
        <th class="male-col">পুরুষ</th>
        <th class="female-col">মহিলা</th>
        <th class="grade-total">মোট</th>
    `;
    <?php endforeach; ?>

    printContent += `
        <th class="male-col">পুরুষ</th>
        <th class="female-col">মহিলা</th>
        <th class="grade-total">মোট</th>
                </tr>
            </thead>
            <tbody>
    `;

    // Define sections array for JavaScript
    const sections = <?php echo json_encode($sections); ?>;
    const grades = <?php echo json_encode($grades); ?>;

    // Add data rows
    let grandMaleTotal = 0;
    let grandFemaleTotal = 0;
    let grandTotal = 0;

    sections.forEach((section, index) => {
        printContent += `
            <tr>
                <td class="department-cell">${section}</td>
        `;

        let sectionMaleTotal = 0;
        let sectionFemaleTotal = 0;
        let sectionTotal = 0;

        grades.forEach(grade => {
            // Get the values for this section and grade
            const grade_m_values = data[grade + '_m'] ? data[grade + '_m'].split(',') : [];
            const grade_f_values = data[grade + '_f'] ? data[grade + '_f'].split(',') : [];
            const grade_m = grade_m_values[index] || 0;
            const grade_f = grade_f_values[index] || 0;
            const grade_total = parseInt(grade_m) + parseInt(grade_f);
            
            printContent += `
                <td class="male-col"><?php echo englishToBanglaNumber($grade_m); ?></td>
                <td class="female-col"><?php echo englishToBanglaNumber($grade_f); ?></td>
                <td class="grade-total">${grade_total}</td>
            `;
            
            // Accumulate section totals
            sectionMaleTotal += parseInt(grade_m);
            sectionFemaleTotal += parseInt(grade_f);
            sectionTotal += grade_total;
        });
        
        printContent += `
            <td class="male-col section-total">${sectionMaleTotal}</td>
            <td class="female-col section-total">${sectionFemaleTotal}</td>
            <td class="grade-total section-total">${sectionTotal}</td>
            </tr>
        `;

        // Accumulate grand totals
        grandMaleTotal += sectionMaleTotal;
        grandFemaleTotal += sectionFemaleTotal;
        grandTotal += sectionTotal;
    });

    // Add grand totals row
    printContent += `
        <tr class="total-row">
            <td class="department-cell grand-total"><strong>সর্বমোট: </strong></td>
    `;

    // Calculate and display grade-wise grand totals
    grades.forEach(grade => {
        let gradeMaleTotal = 0;
        let gradeFemaleTotal = 0;
        let gradeTotal = 0;

        if (data[grade + '_m']) {
            gradeMaleTotal = data[grade + '_m'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        if (data[grade + '_f']) {
            gradeFemaleTotal = data[grade + '_f'].split(',').reduce((sum, val) => sum + parseInt(val || 0), 0);
        }
        gradeTotal = gradeMaleTotal + gradeFemaleTotal;

        printContent += `
            <td class="male-col grand-total"><strong>${gradeMaleTotal}</strong></td>
            <td class="female-col grand-total"><strong>${gradeFemaleTotal}</strong></td>
            <td class="grade-total grand-total"><strong>${gradeTotal}</strong></td>
        `;
    });

    printContent += `
        <td class="male-col grand-total"><strong>${grandMaleTotal}</strong></td>
        <td class="female-col grand-total"><strong>${grandFemaleTotal}</strong></td>
        <td class="grade-total grand-total"><strong>${grandTotal}</strong></td>
        </tr>
    `;

    printContent += `
            </tbody>
        </table>
        <div class="mt-4 text-center no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print Report
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times me-1"></i>Close
            </button>
        </div>
        <div class="text-center no-print">
            <small class="text-muted">Report generated on ${new Date().toLocaleString()}</small>
        </div>
    `;

    printContent += `
            </div>
        </body>
        </html>
    `;

    // Open print window
    const printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Focus the print window
    printWindow.focus();
  }