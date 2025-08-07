<?php
$date = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Reload Example</title>
    <style>
        /* Center the reloading message */
        #reload-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: red;
            font-weight: bold;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        // Show the reloading message and reload the page every 10 seconds
        setTimeout(() => {
            const reloadMessage = document.getElementById('reload-message');
            reloadMessage.style.display = 'block'; // Show the message
            setTimeout(() => {
                location.reload();
            }, 2000); // Wait 2 seconds before reloading
        }, 10000); // 10 seconds = 10000 milliseconds
    </script>
</head>
<body>
    <p>Today's Date: <?php echo $date; ?></p>
    <p>Yesterday's Date: <?php echo $yesterday; ?></p>

    <!-- Hidden reload message -->
    <div id="reload-message">Reloading...</div>
</body>
</html>
