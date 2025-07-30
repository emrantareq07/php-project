
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ID Card Generator</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
      }
      h1 {
        color: #171f27;
        text-align: center;
      }
      #idcard-preview {
        width: 100%;
        height: 550px;
        border: 1px solid #ddd;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin: 20px 0;
      }
      .controls {
        text-align: center;
        margin-top: 20px;
      }
      button {
        background-color: #171f27;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
      }
      button:hover {
        background-color: #2a3a4a;
      }
      .loading {
        display: none;
        text-align: center;
        margin: 20px 0;
        color: #555;
      }
    </style>
  </head>
  <body>
    <h1>Employee ID Card Generator</h1>
    <div class="loading" id="loading">Generating ID Cards... Please wait</div>
   <!--  <iframe
      src="about:blank"
      frameborder="0"
      id="idcard-preview"
      title="Id Card Preview"
    ></iframe> -->
  <iframe src="about:blank" frameborder="0" id="idcard-preview" title="Id Card Preview"></iframe>
  <div class="controls">
    <button type="button" onclick="downloadIdCard()">Download PDF</button>
  </div>

  <!-- JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="base64-uris.js"></script>
  <script src="scripts.js"></script>

  <script>
    document.getElementById('loading').style.display = 'block';

    // Assume you pass ID in the URL like id_card_generator.html?id=5
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id') || 0;

    fetch('id_card_pdf.php?id=' + id)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        const employees = data.map(emp => ({
          ...emp,
          image: emp.image || 'https://www.pngitem.com/pimgs/m/30-307416_profile-icon-png-image-free-download-searchpng-employee.png'
        }));
        return generateIdCard(employees);
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('loading').textContent = 'Error loading data. Please check console.';
      })
      .finally(() => {
        document.getElementById('loading').style.display = 'none';
      });
  </script>
</body>
</html>
