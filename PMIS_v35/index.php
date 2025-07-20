<!DOCTYPE html>
<html>
<head>
    <title>Redirect Page</title>
    <script type="text/javascript">
        // Define the function to redirect the page
        function redirect() {
            // Specify the URL of the destination page
            var destination = "login/index.php";
            // Redirect to the destination page
            window.location.href = destination;
        }

        // Call the redirect function after a specified time (in milliseconds)
        setTimeout(redirect, 100); // Redirect after 3 seconds (3000 milliseconds)
    </script>
</head>
<body>
    <!-- Optional: You can provide a message or content to display before redirecting -->
    
</body>
</html>
