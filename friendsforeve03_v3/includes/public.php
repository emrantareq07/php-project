<?php
require '../db/db.php';
?>
<?php include 'header.php'; ?>

<div class="container my-4">  
  <!-- Button to open modal -->
  <button class="btn btn-outline-primary me-2 " data-bs-toggle="modal" data-bs-target="#publicModal">
    <i class="fa fa-comments-o"></i> Add Contact
  </button>

  <!-- Modal with Contact Submission Form -->
  <div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="publicForm" class="row g-3 p-3">
          <div class="modal-header">
            <h5 class="modal-title text-muted text-uppercase float-end">Add Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Mobile</label><input name="mobile" class="form-control" required inputmode="tel"></div>
            <div class="col-md-6"><label class="form-label">Alternate Mobile</label><input name="alt_mobile" class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Occupation</label><input name="occupation" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Jobplace</label><input name="jobplace" class="form-control"></div>
            <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
          </div>
          <div class="modal-footer">
            <span id="publicMsg" class="me-auto"></span>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Approved Contacts List -->
<h4 class="mt-4">Contacts</h4>

<div class="input-group mb-3">
  <span class="input-group-text">🔍</span>
  <input type="text" id="searchBox" class="form-control" placeholder="Search by name, address, occupation, email...">
</div>

<div class="list-group" id="contactsList">
  <div class="text-center py-4" id="loadingSpinner" style="display:none;">
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-2">Loading contacts...</div>
  </div>
</div>

</div>

<!-- <div class="list-group"> -->
  <?php
    // $stmt = $conn->query("SELECT * FROM friends WHERE status='approved' ORDER BY name ASC");
    // while ($r = $stmt->fetch_assoc()):
    //   $primary = htmlspecialchars($r['mobile']);
    //   $alt = htmlspecialchars($r['alt_mobile']);
    //   $smsNumber = $primary ?: $alt;
  ?>
   <!--  <div class="list-group-item list-group-item-action contact-row d-flex justify-content-between align-items-center" onclick="window.location.href='tel:<?//= $primary ?>'">
      <div>
        <div class="fw-semibold"><?//= htmlspecialchars($r['name']) ?></div>
        <small class="text-muted d-block"><?//= htmlspecialchars($r['occupation']) ?> — <?//= htmlspecialchars($r['jobplace']) ?></small>
        <small class="d-block"><?//= nl2br(htmlspecialchars($r['address'])) ?></small>
      </div>
      <div class="text-end">
        <div><a href="tel:<?= $primary ?>" class="btn btn-sm btn-outline-success mb-1"><i class="fa fa-phone"></i> Call</a></div>
        <div><a href="sms:<?= $smsNumber ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-comments-o"></i> Message</a></div>
      </div>
    </div> -->
  <?php //endwhile; ?>
  <!-- </div> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- <script>
document.getElementById("publicForm").addEventListener("submit", function(e){
  e.preventDefault();
  let form = this;
  let formData = new FormData(form);
  fetch("request_save.php", { method: "POST", body: formData })
    .then(r => r.text())
    .then(res => {
      document.getElementById("publicMsg").innerHTML =
        '<div class="alert alert-info alert-dismissible fade show mb-0" role="alert">'
        + res +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
      form.reset();
    });
});
</script> -->
<script>
document.getElementById("publicForm").addEventListener("submit", function(e){
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);
    fetch("request_save.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(res => {
        document.getElementById("publicMsg").innerHTML =
            '<div class="alert alert-info mb-0">'+res+'</div>';
        form.reset();
    })
    .catch(err => {
        document.getElementById("publicMsg").innerHTML =
            '<div class="alert alert-danger mb-0">Error submitting request.</div>';
        console.error(err);
    });
});
</script>

<!-- <script>
function loadContacts(){
    fetch("get_public_contacts.php")
      .then(r => r.text())
      .then(html => {
        document.getElementById("contactsList").innerHTML = html;
      })
      .catch(err => console.error("Error loading contacts:", err));
}

// Load contacts on page load
loadContacts();

// Refresh every 30 seconds
setInterval(loadContacts, 30000);

// After successful request submission, reload contacts
document.getElementById("publicForm").addEventListener("submit", function(e){
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);
    fetch("request_save.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(res => {
        document.getElementById("publicMsg").innerHTML =
            '<div class="alert alert-info mb-0">'+res+'</div>';
        form.reset();
        loadContacts(); // Refresh contacts after submit
    })
    .catch(err => {
        document.getElementById("publicMsg").innerHTML =
            '<div class="alert alert-danger mb-0">Error submitting request.</div>';
        console.error(err);
    });
});
</script> -->
<script>
let searchQuery = "";

// Load contacts (with optional search query)
function loadContacts(query = ""){
    searchQuery = query; // save last query
    fetch("get_public_contacts.php?q=" + encodeURIComponent(query))
      .then(r => r.text())
      .then(html => {
        document.getElementById("contactsList").innerHTML = html;
      })
      .catch(err => console.error("Error loading contacts:", err));
}

// Load contacts initially
loadContacts();

// Live search event
document.getElementById("searchBox").addEventListener("keyup", function(){
    loadContacts(this.value);
});

// Auto-refresh every 10 seconds, keeping current search filter
setInterval(() => {
    loadContacts(searchQuery);
}, 10000);
</script>



</body>
</html>
