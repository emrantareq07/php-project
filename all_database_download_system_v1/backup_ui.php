<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Database Backup System</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    background: #f5f7fa;
}
.card {
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}
.progress {
    height: 22px;
    border-radius: 12px;
}
#statusBox {
    background: #eaf3ff;
    padding: 12px;
    border-radius: 10px;
    display: none;
}
</style>
</head>

<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card p-4">
                <h3 class="text-center mb-3">
                    <i class="fa fa-database text-primary"></i> Full Database Backup
                </h3>

                <p class="text-center text-muted">
                    Click the button below to generate a ZIP backup of all databases.
                </p>

                <div id="statusBox" class="mb-3">
                    <b>Status:</b> <span id="statusText">Starting...</span>
                </div>

                <div class="progress mb-3" style="display:none;" id="progressWrapper">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                        role="progressbar" style="width: 0%">0%</div>
                </div>

                <button id="backupBtn" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="fa fa-download"></i> Start Backup
                </button>

            </div>

        </div>
    </div>
</div>

<!-- JS -->
<script>
document.getElementById("backupBtn").addEventListener("click", startBackup);

function startBackup() {
    let btn = document.getElementById("backupBtn");
    let bar = document.getElementById("progressBar");
    let statusBox = document.getElementById("statusBox");
    let statusText = document.getElementById("statusText");
    let progressWrapper = document.getElementById("progressWrapper");

    btn.disabled = true;
    btn.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Processing...";

    statusBox.style.display = "block";
    progressWrapper.style.display = "block";

    // Fake animation while waiting (looks professional)
    let fakeProgress = 0;
    let progressInterval = setInterval(() => {
        fakeProgress += 10;
        if (fakeProgress > 90) fakeProgress = 90; // stop at 90%
        bar.style.width = fakeProgress + "%";
        bar.innerHTML = fakeProgress + "%";
    }, 300);

    statusText.innerHTML = "Connecting...";

    fetch("backup.php?action=backup")
    .then(res => res.json())
    .then(data => {

        clearInterval(progressInterval);

        if (data.success) {
            bar.style.width = "100%";
            bar.innerHTML = "100%";

            statusText.innerHTML = "Backup completed successfully! Downloading ZIP...";

            // Download file
            setTimeout(() => { window.location = data.file; }, 1200);

            btn.innerHTML = "<i class='fa fa-check'></i> Completed";
        } else {
            statusText.innerHTML = "❌ Error: " + data.message;
            bar.classList.remove("bg-success");
            bar.classList.add("bg-danger");
            bar.style.width = "100%";
            bar.innerHTML = "Failed";

            btn.disabled = false;
            btn.innerHTML = "Start Backup";
        }
    })
    .catch(err => {
        clearInterval(progressInterval);
        statusText.innerHTML = "❌ Failed: " + err;
        btn.disabled = false;
        btn.innerHTML = "Start Backup";
    });
}
</script>

</body>
</html>
