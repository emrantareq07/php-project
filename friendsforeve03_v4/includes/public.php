<?php
require '../db/db.php';
?>
<?php include 'header.php'; ?>

<style>
/* small UI polish */
.contact-card {
  transition: transform 0.18s, box-shadow 0.18s;
  border-left: 4px solid #0d6efd;
  cursor: default;
}
.contact-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
#contactsList .card { margin-bottom: .9rem; }
#loadingSpinner { display: none; }
.required-field::after { content: "*"; color: red; margin-left: 4px; }
</style>

<div class="container my-4">
  <!-- Button to open modal -->
  <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#publicModal">
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
          <span id="publicMsg" class="me-auto text-center"></span>
          <div class="modal-body">
            <div class="col-md-6"><label class="form-label required-field">Name</label><input name="name" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label required-field">Mobile</label><input name="mobile" class="form-control" required inputmode="tel"></div>
            <div class="col-md-6"><label class="form-label">Alternate Mobile</label><input name="alt_mobile" class="form-control" inputmode="tel"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control"></div>
            <div class="col-md-6"><label class="form-label required-field">Occupation</label><input name="occupation" class="form-control"></div>
            <div class="col-md-6"><label class="form-label">Jobplace</label><input name="jobplace" class="form-control"></div>
            <div class="col-12"><label class="form-label required-field">Address</label><textarea name="address" class="form-control" rows="2" required></textarea></div>
          </div>
          <div class="modal-footer">
            <!-- <span id="publicMsg" class="me-auto"></span> -->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Contacts header + search -->
  <h4 class="mt-4">Friend Contacts</h4>

  <div class="input-group mb-3">
    <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
    <input type="text" id="searchBox" class="form-control" placeholder="Search by name, address, occupation, email...">
    <button class="btn btn-outline-secondary" id="clearSearchBtn" type="button">
      <i class="fa fa-times"></i>
    </button>
  </div>

  <!-- Contacts container (IMPORTANT) -->
  <div id="contactsList" class="list-group mb-2"></div>

  <!-- spinner -->
  <div class="text-center py-3" id="loadingSpinner" aria-hidden="true">
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-2 small">Loading more contacts...</div>
  </div>

  <!-- sentinel used by IntersectionObserver -->
  <div id="loadMoreTrigger" style="height:1px;"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* form submit (unchanged) */
document.getElementById("publicForm").addEventListener("submit", function(e){
  e.preventDefault();
  let form = this;
  let formData = new FormData(form);
  fetch("request_save.php", { method: "POST", body: formData })
    .then(r => r.text())
    .then(res => {
      document.getElementById("publicMsg").innerHTML = '<div class="alert alert-info mb-0">'+res+'</div>';
      form.reset();
      // refresh the list so newly added entries (if auto-approved) show up:
      loadContacts(searchQuery, true);
    })
    .catch(err => {
      document.getElementById("publicMsg").innerHTML = '<div class="alert alert-danger mb-0">Error submitting request.</div>';
      console.error(err);
    });
});

/* Lazy-loading + search */
let searchQuery = "";
let offset = 0;
const limit = 10;
let loading = false;
let allLoaded = false;

const contactsList = document.getElementById('contactsList');
const spinner = document.getElementById('loadingSpinner');
const sentinel = document.getElementById('loadMoreTrigger');
const searchBox = document.getElementById('searchBox');
const clearBtn = document.getElementById('clearSearchBtn');

/* debounce helper */
function debounce(fn, wait=300){
  let t;
  return (...args) => { clearTimeout(t); t = setTimeout(()=>fn(...args), wait); };
}

async function loadContacts(query = "", reset = false){
  if (loading) return;
  if (allLoaded && !reset) return;
  loading = true;
  spinner.style.display = 'block';

  if (reset) {
    contactsList.innerHTML = "";
    offset = 0;
    allLoaded = false;
  }

  try {
    const url = `get_public_contacts.php?q=${encodeURIComponent(query)}&limit=${limit}&offset=${offset}`;
    const res = await fetch(url);
    if (!res.ok) {
      console.error('Server returned', res.status);
      // show a friendly error element
      if (offset === 0) {
        contactsList.innerHTML = '<div class="alert alert-danger text-center">Server error while loading contacts.</div>';
      }
      allLoaded = true;
      return;
    }
    const html = await res.text();
    if (html.trim() === "") {
      // server returned no more items
      if (offset === 0) {
        contactsList.innerHTML = '<div class="alert alert-warning text-center">No contacts found.</div>';
      }
      allLoaded = true;
    } else {
      contactsList.insertAdjacentHTML("beforeend", html);
      offset += limit;
    }
  } catch (err) {
    console.error('Fetch failed', err);
    if (offset === 0) contactsList.innerHTML = '<div class="alert alert-danger text-center">Network error.</div>';
    allLoaded = true;
  } finally {
    loading = false;
    spinner.style.display = allLoaded ? 'none' : 'block';
  }
}

/* IntersectionObserver to auto-load when sentinel becomes visible */
const io = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting && !loading && !allLoaded) {
      loadContacts(searchQuery, false);
    }
  });
}, { root: null, threshold: 0.1 });
io.observe(sentinel);

/* initial load (reset = true) */
loadContacts("", true);

/* search with debounce */
const debouncedSearch = debounce(function(e){
  searchQuery = e.target.value.trim();
  loadContacts(searchQuery, true);
}, 250);
searchBox.addEventListener('input', debouncedSearch);

/* clear button */
clearBtn.addEventListener('click', function(){
  searchBox.value = "";
  searchQuery = "";
  loadContacts("", true);
});
// Auto-refresh every 10 seconds, keeping current search filter
setInterval(() => {
    loadContacts(searchQuery, true);
}, 10000);
</script>

</body>
</html>
