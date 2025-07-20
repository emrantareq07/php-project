<!DOCTYPE html>
<html>
<head>
    <title>Print Example</title>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
    <h1>Print Data from Database</h1>

    <!-- Print Button -->
    <!-- <button id="printButton">Print</button> -->
<a href="#" id="printButton"  class="btn btn-danger "><span class="glyphicon glyphicon-print" aria-hidden="true"></span>  Print </a>
    <!-- JavaScript to handle the button click -->
    <script>
        document.getElementById("printButton").addEventListener("click", function() {
            // When the "Print" button is clicked, trigger the browser's print dialog
            var printWindow = window.open('', '', 'width=' + window.innerWidth + ', height=' + window.innerHeight + ', resizable=yes, scrollbars=yes');
            var contentToPrint = "<h2>Data from Database</h2>";

            // Fetch the data and add it to the contentToPrint variable
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "test_4.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    contentToPrint += xhr.responseText;

                    // Write the content to the new window and initiate printing
                    printWindow.document.open();
                    printWindow.document.write(contentToPrint);
                    printWindow.document.close();
                    printWindow.print();
                    printWindow.close();
                }
            };
            xhr.send();
        });
    </script>
</body>
</html>
