// Function to generate combine workers print view with grade and designation combined in one table

function cleanValue(val) {
    if (!val) return '-';

    // Remove commas and spaces
    let cleaned = val.replace(/,/g, '').replace(/\s+/g, '').trim();

    return cleaned === '' ? '-' : val.trim();
}

function generateCombineWorkersPrintView(monthsData) {
    if (!monthsData || monthsData.length === 0) {
        alert('No data available for printing.');
        return;
    }

    let printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Combined Workers Report - Bangladesh Chemical Industries Corporation</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
                body { 
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial, sans-serif; 
                    margin: 20px; 
                    direction: ltr;
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 0px;
                    border-bottom: 0px solid #333;
                    padding-bottom: 0px;
                }
                .month-section { 
                    margin-bottom: 20px; 
                    page-break-after: always; 
                }
                .month-section:last-child { 
                    page-break-after: avoid; 
                }
                .month-title { 
                    background: #f8f9fa; 
                    padding: 15px; 
                    border-radius: 5px; 
                    margin-bottom: 20px;
                    border-left: 4px solid #007bff;
                }
                .summary-table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 20px;
                    font-size: 14px;
                }
                .summary-table th, .summary-table td { 
                    border: 1px solid #000; 
                    padding: 8px; 
                    text-align: center; 
                }
                .summary-table th { 
                    background-color: #e9ecef; 
                    font-weight: bold;
                }
                .total-row {
                    background-color: #d1ecf1 !important;
                    font-weight: bold;
                }
                .bangla-number, .bangla-text {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-size: 14px;
                }
                @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 10px; font-size: 12px; }
                    .summary-table { font-size: 12px; }
                }
                .text-bangla {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                }
                .bengali-title {
                    font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                    font-weight: bold;
                    font-size: 16px;
                }
                .factory-details {
                    margin-top: 20px;
                    font-size: 12px;
                }
                .factory-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }
                .factory-table th, .factory-table td {
                    border: 1px solid #000;
                    padding: 5px;
                    font-size: 11px;
                }
                .factory-total-row {
                    background-color: #f8f9fa !important;
                    font-weight: bold;
                }
                .print-footer {
                    text-align: center;
                    margin-top: 2px;
                    padding-top: 9px;
                    border-top: 1px solid #dee2e6;
                    font-size: 10px;
                    color: #6c757d;
                }
                @media print {
                    .no-print { display: none; }
                    .month-section { page-break-inside: avoid; }
                    body { margin: 10px; font-size: 12px; }
                    @page {
                        size: Letter portrait;
                        margin: 10mm;
                    }
                    .bengali-title {
                        font-family: 'Noto Sans Bengali', 'SolaimanLipi', Arial;
                        font-weight: bold;
                        font-size: 16px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container-fluid">
                <div class="print-header">
                    <h2 class="text-bangla">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                    <h5 class="text-bangla">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা-১০০০</h5>
                    <h5 class="text-bangla">কারখানা/প্রতিষ্ঠান/প্রকল্পের নাম : সমন্বিত রিপোর্ট (দৈনিক ভিত্তিক)</h5>
                    <h5 class="text-bangla">বিদ্যমান জনবলের পরিসংখ্যান</h5>
                    <p class="bangla-text">প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}</p>
                </div>
    `;

    for (var m = 0; m < monthsData.length; m++) {
        var monthData = monthsData[m];
        if (!monthData.data || monthData.data.length === 0) continue;
        
        // Process all records for this month and organize by grade and designation
        var gradeDesignationSummary = {};
        var factoryDetails = {};
        var totalSanctioned = 0;
        var totalMale = 0;
        var totalFemale = 0;
        var grandTotal = 0;
        var totalVacant = 0;

        // Process each record
        for (var i = 0; i < monthData.data.length; i++) {
            var record = monthData.data[i];
            var factoryName = record.factory_name || 'অনামী কারখানা';

            var designations = record.designation ? record.designation.split(',') : [];
            var grades = record.grade ? record.grade.split(',') : [];

            var sanctionedPosts = record.sanctioned_post ? record.sanctioned_post.split(',') : [];
            var maleCounts = record.male ? record.male.split(',') : [];
            var femaleCounts = record.female ? record.female.split(',') : [];
            var totalCounts = record.total ? record.total.split(',') : [];

            // Initialize factory details if not exists
            if (!factoryDetails[factoryName]) {
                factoryDetails[factoryName] = [];
            }

            // Process each designation in the record
            var maxLen = designations.length;
            for (var j = 0; j < maxLen; j++) {

                // var grade = grades[j] ? grades[j].trim() : '';
                // var designationName = designations[j] ? designations[j].trim() : '';

                var designationRaw = designations[j] ? designations[j] : (j === 0 ? record.designation : '');
var gradeRaw = grades[j] ? grades[j] : (j === 0 ? record.grade : '');

var designationName = cleanValue(designationRaw);
var grade = cleanValue(gradeRaw);


                var sanctioned = sanctionedPosts[j] ? parseInt(sanctionedPosts[j]) : 0;
                var male = maleCounts[j] ? parseInt(maleCounts[j]) : 0;
                var female = femaleCounts[j] ? parseInt(femaleCounts[j]) : 0;
                var total = totalCounts[j] ? parseInt(totalCounts[j]) : 0;
                var vacant = sanctioned - total;

                // Create unique key for grade-designation combination
                var key = grade + '|' + designationName;
                
                if (!gradeDesignationSummary[key]) {
                    gradeDesignationSummary[key] = {
                        grade: grade,
                        designation: designationName,
                        sanctioned: 0,
                        male: 0,
                        female: 0,
                        total: 0,
                        vacant: 0
                    };
                }
                
                gradeDesignationSummary[key].sanctioned += sanctioned;
                gradeDesignationSummary[key].male += male;
                gradeDesignationSummary[key].female += female;
                gradeDesignationSummary[key].total += total;
                gradeDesignationSummary[key].vacant += vacant;

                // Store factory-wise details
                factoryDetails[factoryName].push({
                    designation: designationName,
                    grade: grade,
                    sanctioned: sanctioned,
                    male: male,
                    female: female,
                    total: total,
                    vacant: vacant
                });

                totalSanctioned += sanctioned;
                totalMale += male;
                totalFemale += female;
                grandTotal += total;
                totalVacant += vacant;
            }
        }

        printContent += `
            <div class="month-section">
                <div class="month-title">
                    <h3 class="bangla-text">মাস: ${convertToBanglaMonth(monthData.month_name)}</h3>
                    <p class="bangla-text">মোট কারখানা: ${englishToBanglaNumber(Object.keys(factoryDetails).length)} | মোট রেকর্ড: ${englishToBanglaNumber(monthData.data.length)}</p>
                </div>

                <h4 class="bengali-title">গ্রেড ও পদভিত্তিক সারসংক্ষেপ (দৈনিক ভিত্তিক)</h4>
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th class="bangla-text">ক্রমিক</th>
                            <th class="bangla-text">পদের নাম</th>
                            <th class="bangla-text">গ্রেড</th>
                            <th class="bangla-text">অনুমোদিত পদ</th>
                            <th class="bangla-text">পুরুষ (কর্মরত)</th>
                            <th class="bangla-text">মহিলা (কর্মরত)</th>
                            <th class="bangla-text">মোট (কর্মরত)</th>
                            <th class="bangla-text">শূন্য পদ</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        // Convert object to array and sort by grade
        var summaryArray = [];
        for (var key in gradeDesignationSummary) {
            if (gradeDesignationSummary.hasOwnProperty(key)) {
                summaryArray.push(gradeDesignationSummary[key]);
            }
        }
        
        summaryArray.sort(function(a, b) {
            var gradeA = parseInt(a.grade.replace('Grade ', '')) || 0;
            var gradeB = parseInt(b.grade.replace('Grade ', '')) || 0;
            return gradeA - gradeB;
        });

        // Add rows to table
        //  <td class="bangla-text" style="text-align: center;">${item.designation || '-'}</td>
        var serial = 1;
        for (var s = 0; s < summaryArray.length; s++) {
            var item = summaryArray[s];
            printContent += `
                <tr>
                    <td class="bangla-number">${englishToBanglaNumber(serial)}</td>
                    <td class="bangla-text" style="text-align: center;">${item.designation}</td>
                    <td class="bangla-text">${convertGradeToBangla(item.grade)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.sanctioned)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.male)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.female)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.total)}</td>
                    <td class="bangla-number">${englishToBanglaNumber(item.vacant)}</td>
                </tr>
            `;
            serial++;
        }

        // Add grand total row
        printContent += `
                        <tr class="total-row">
                            <td colspan="3" class="bangla-text"><strong>সর্বমোট</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalSanctioned)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalMale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalFemale)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(grandTotal)}</strong></td>
                            <td class="bangla-number"><strong>${englishToBanglaNumber(totalVacant)}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="factory-details">
                    <h4 class="bengali-title">কারখানা ভিত্তিক বিস্তারিত বিবরণ (দৈনিক ভিত্তিক)</h4>
        `;

        // Display factory-wise details
        var factoryNames = [];
        for (var fname in factoryDetails) {
            if (factoryDetails.hasOwnProperty(fname)) {
                factoryNames.push(fname);
            }
        }
        
        for (var f = 0; f < factoryNames.length; f++) {
            var factoryName = factoryNames[f];
            var details = factoryDetails[factoryName];
            var factorySanctionedTotal = 0;
            var factoryMaleTotal = 0;
            var factoryFemaleTotal = 0;
            var factoryTotal = 0;
            var factoryVacantTotal = 0;

            printContent += `
                <div style="margin-bottom: 20px;">
                    <h5 class="bangla-text" style="background: #e9ecef; padding: 8px; margin: 10px 0; border-left: 3px solid #28a745;">
                        <strong>কারখানা: ${convertToBanglaFactory(factoryName)}</strong>
                    </h5>
                    <table class="factory-table text-center">
                        <thead>
                            <tr>
                                <th class="bangla-text">ক্রমিক</th>
                                <th class="bangla-text">পদের নাম</th>
                                <th class="bangla-text">গ্রেড</th>
                                <th class="bangla-text">অনুমোদিত পদ</th>
                                <th class="bangla-text">পুরুষ (কর্মরত)</th>
                                <th class="bangla-text">মহিলা (কর্মরত)</th>
                                <th class="bangla-text">মোট (কর্মরত)</th>
                                <th class="bangla-text">শূন্য পদ</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            var factorySerial = 1;
            for (var d = 0; d < details.length; d++) {
                var detail = details[d];
                factorySanctionedTotal += detail.sanctioned;
                factoryMaleTotal += detail.male;
                factoryFemaleTotal += detail.female;
                factoryTotal += detail.total;
                factoryVacantTotal += detail.vacant;

                //<td class="bangla-text">${detail.designation || '-'}</td>

                printContent += `
                    <tr>
                        <td class="bangla-number">${englishToBanglaNumber(factorySerial)}</td>
                        <td class="bangla-text">${detail.designation}</td>
                        <td class="bangla-text">${convertGradeToBangla(detail.grade)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.sanctioned)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.male)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.female)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.total)}</td>
                        <td class="bangla-number">${englishToBanglaNumber(detail.vacant)}</td>
                    </tr>
                `;
                factorySerial++;
            }

            printContent += `
                            <tr class="factory-total-row">
                                <td colspan="3" class="bangla-text" style="text-align: right;"><strong>কারখানা মোট:</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factorySanctionedTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryMaleTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryFemaleTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryTotal)}</strong></td>
                                <td class="bangla-number"><strong>${englishToBanglaNumber(factoryVacantTotal)}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        printContent += `
            <!-- Signature Section -->
                <div class="signature-section">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div style="border-top: 0px solid #000; width: auto; margin: 0 auto; padding-top: 0px;">
                                <strong><small>সিস্টেম জেনারেটেড ডকুমেন্ট। স্বাক্ষরের প্রয়োজন নাই।</small></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="print-footer">
                    <strong>Design & Developed by ICT Division, BCIC.</strong>
                </div> 

                
        `;
    }

 printContent += `
                
                
                <div class="no-print text-center mt-2">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> প্রিন্ট করুন
                    </button>
                    <button class="btn btn-secondary" onclick="window.close()">
                        <i class="fas fa-times me-1"></i> বন্ধ করুন
                    </button>
                    <br>
                    
                </div>
                <div class="text-center mt-1">
                    <small class="text-muted">
                        প্রতিবেদন তৈরির তারিখ: ${convertDateToBangla(new Date().toISOString().split('T')[0])}
                    </small>
                
            </div>
         </div>
            </div>
            
        </body>
        </html>
    `;

    var printWindow = window.open('', '_blank', 'width=1200,height=800,scrollbars=1');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
}