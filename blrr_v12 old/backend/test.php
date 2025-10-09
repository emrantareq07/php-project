<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chosen with Bootstrap 5</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chosen CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
</head>
<body>
    
    <div class="container mt-5">
        <h2>Chosen Select Example with Bootstrap 5</h2>
        <form>
            <div class="mb-3">
                <label for="destination_u" class="form-label">গন্তব্য (এক/একাধিক নির্বাচন করুন):</label>
                <select id="destination_u" class="form-control" name="destination[]" multiple>
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                </select>
            </div>
        </form>
    </div>
    
    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chosen JS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#destination_u').chosen({
                no_results_text: "Oops, nothing found!",
                width: "100%"
            });
        });
    </script>
    
</body>
</html>
