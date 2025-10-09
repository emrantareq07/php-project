<?php
require 'db/db.php';
?>
<?php include 'includes/header.php'; ?>

<style>
/* small UI polish */
.contact-card {
  transition: transform 0.18s, box-shadow 0.18s, background-color 1s;
  border-left: 4px solid #0d6efd;
  cursor: default;
}
.contact-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); }

/* highlight effect for new cards */
.contact-highlight {
  background-color: #fff3cd !important; /* soft yellow */
  animation: fadeHighlight 3s forwards;
}
@keyframes fadeHighlight {
  from { background-color: #fff3cd; }
  to { background-color: white; }
}

#contactsList .card { margin-bottom: .9rem; }
#loadingSpinner { display: none; }
.required-field::after { content: "*"; color: red; margin-left: 4px; }

/* fade-out for messages */
.fade-out {
  opacity: 1;
  transition: opacity 1s ease-out;
}
.fade-out.hide {
  opacity: 0;
}
</style>

<div class="container my-4">
  <!-- Button to open modal -->
      <div class="col-12 col-md-6 ">
      <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#publicModal">
          <i class="fa fa-comments-o"></i> Add Contact
        </button>
      <!-- Admin Login Button -->
      <a href="includes/login.php" class="btn btn-outline-primary" >
        <i class="fa fa-lock"></i> Admin Login
      </a>
    </div>

  <!-- Modal with Contact Submission Form -->

  <div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="publicForm" class="row g-3 p-3" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title text-muted text-uppercase float-end">Add Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="px-3 mt-2" id="publicMsgWrap"><span id="publicMsg" class="me-auto text-center d-block"></span></div>
          <div class="modal-body">
            <div class="row g-3">
            <div class="col-12 col-md-6">
            <label class="form-label required-field">Name</label><input autocomplete="on" type="text" name="name" class="form-control" required></div>
            <div class="col-12 col-md-6"><label class="form-label required-field">Mobile</label>
              <!-- <input autocomplete="on" name="mobile" class="form-control" required inputmode="tel"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "11"> -->

              <input 
                  autocomplete="on" 
                  name="mobile" 
                  class="form-control" 
                  required 
                  inputmode="tel" 
                  pattern="[0-9]*" 
                  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                  maxlength="11"
              >

            </div>
            <div class="col-12 col-md-6"><label class="form-label">Alternate Mobile</label>
              <!-- <input autocomplete="on" name="alt_mobile" class="form-control" inputmode="tel"> -->
              <input 
                  autocomplete="on" 
                  name="alt_mobile" 
                  class="form-control" 
                  required 
                  inputmode="tel" 
                  pattern="[0-9]*" 
                  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                  maxlength="11"
              >
            </div>
            <div class="col-12 col-md-6"><label class="form-label">Email</label><input autocomplete="on" name="email" type="email" class="form-control"></div>
            <div class="col-12 col-md-6"><label class="form-label required-field">Occupation</label><input autocomplete="on" type="text" name="occupation" class="form-control" required></div>
            <div class="col-12 col-md-6"><label class="form-label">Jobplace</label><input name="jobplace" autocomplete="on" type="text" class="form-control"></div>
            <div class="col-12 col-md-6"><label class="form-label ">Blood Group</label>
              <!-- <input type="text" name="blood_group" class="form-control" required> -->
              <select name="blood_group" class="form-select">
              <option value="" selected disabled>Select Blood Group</option>
              <option value="A+">A+</option>
              <option value="A-">A-</option>
              <option value="B+">B+</option>
              <option value="B-">B-</option>
              <option value="O+">O+</option>
              <option value="O-">O-</option>
              <option value="AB+">AB+</option>
              <option value="AB-">AB-</option>
            </select>
            </div>
            <div class="col-12 col-md-6"><label class="form-label">Profile Image</label><input type="file" name="image" class="form-control" accept="image/*">
              <div class="mt-2">
                <img id="edit_preview" src="" alt="Preview" width="80" height="80" class="rounded-circle border d-none">
              </div>
            </div>
            <div class="col-12"><label class="form-label required-field">Address</label><textarea autocomplete="on" name="address" class="form-control" rows="1" required></textarea></div>
          </div>
        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <h4 class="mt-4">Friend Contacts</h4>
  <div class="input-group mb-3">
    <span class="input-group-text bg-white"><i class="fa fa-search"></i></span>
    <input type="text" id="searchBox" autofocus class="form-control" placeholder="Search by name, address, occupation, email...">
    <button class="btn btn-outline-secondary" id="clearSearchBtn" type="button">
      <i class="fa fa-times"></i>
    </button>
  </div>

  <div id="contactsList" class="list-group mb-2"></div>
  <div class="text-center py-3" id="loadingSpinner" aria-hidden="true">
    <div class="spinner-border text-primary" role="status"></div>
    <div class="mt-2 small">Loading more contacts...</div>
  </div>
  <div id="loadMoreTrigger" style="height:1px;"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const publicForm = document.getElementById('publicForm');
  const publicMsg = document.getElementById('publicMsg');
  const modalEl = document.getElementById('publicModal');
  const contactsList = document.getElementById('contactsList');
  const spinner = document.getElementById('loadingSpinner');
  const searchBox = document.getElementById('searchBox');
  const clearBtn = document.getElementById('clearSearchBtn');
  const submitBtn = publicForm.querySelector('button[type="submit"]');

  function showMsg(type, text){
    publicMsg.className='fade-out';
    publicMsg.innerHTML=`<div class="alert alert-${type} mb-0">${text}</div>`;
    setTimeout(()=>publicMsg.classList.add('hide'),3000);
    setTimeout(()=>{publicMsg.innerHTML=''; publicMsg.className='';},4000);
  }

  function debounce(fn, wait=300){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),wait); }; }

  /* Submit form */
  publicForm.addEventListener('submit', async (e)=>{
    e.preventDefault();
    submitBtn.disabled=true;
    showMsg('info','Sending…');

    try{
      const fd = new FormData(publicForm);
      const res = await fetch('includes/request_save.php',{method:'POST',body:fd});
      const txt = await res.text();

      // reset form
      publicForm.reset();
      showMsg('success',txt);

      const name = fd.get('name');
      const mobile = fd.get('mobile');
      const address = fd.get('address');
      const blood_group = fd.get('blood_group');
      const imageFile = publicForm.querySelector('input[name="image"]').files[0];
      let imgHTML = '';
      if(imageFile){
        const reader = new FileReader();
        reader.onload=function(e){ imgHTML = `<img src="${e.target.result}" class="rounded-circle me-2" style="width:50px;height:50px;">`; insertPendingCard(); }
        reader.readAsDataURL(imageFile);
      } else insertPendingCard();

      function insertPendingCard(){
          const tempId = `pending-${Date.now()}`;
    let imageURL = imageFile && imageFile.name ? URL.createObjectURL(imageFile) : "https://via.placeholder.com/60";
    const pendingHtml = `
      <div class="list-group-item card contact-card contact-highlight" data-temp-id="${tempId}">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <img src="${imageURL}" class="rounded-circle me-3" width="60" height="60" style="object-fit:cover;">
            <div>
              <h6 class="card-title mb-1">${name}</h6>
              <p class="mb-1 small text-muted">${mobile}</p>
              <p class="mb-1">${address}</p>
              <span class="badge bg-warning text-dark"><i class="fa fa-clock-o"></i> Pending Approval</span>
              ${blood_group ? `<span class="badge bg-danger ms-1">${blood_group}</span>` : ''}
            </div>
          </div>
        </div>
      </div>
    `;
      contactsList.insertAdjacentHTML('afterbegin',pendingHtml);
      // Auto close modal
          setTimeout(()=>{ const modal = bootstrap.Modal.getInstance(modalEl); if(modal) modal.hide(); }, 2000);
      }

        // const tempId = `pending-${Date.now()}`;
        // const pendingHtml = `
        //   <div class="list-group-item card contact-card contact-highlight" data-temp-id="${tempId}">
        //     <div class="card-body d-flex justify-content-between align-items-center">
        //       <div class="d-flex align-items-center">
        //         ${imgHTML}
        //         <div>
        //           <h6 class="card-title mb-1">${name}</h6>
        //           <p class="mb-1 small text-muted">${mobile}</p>
        //           <p class="mb-1">Blood Group: ${blood_group}</p>
        //           <p class="mb-1">${address}</p>
        //           <span class="badge bg-warning text-dark">Pending Approval</span>
        //         </div>
        //       </div>
        //     </div>
        //   </div>
        // `;
      //   contactsList.insertAdjacentHTML('afterbegin',pendingHtml);
      // }

    }catch(e){
      showMsg('danger','Error submitting request.');
      console.error(e);
    }finally{ submitBtn.disabled=false; }
  });

  modalEl.addEventListener('show.bs.modal',()=>{ publicForm.reset(); publicMsg.innerHTML=''; });
  modalEl.addEventListener('hidden.bs.modal',()=>{ publicForm.reset(); publicMsg.innerHTML=''; });

  /* Search & Clear */
  let searchQuery=""; let offset=0, limit=10, loading=false, allLoaded=false;
  const handleSearch=debounce((e)=>{ searchQuery=e.target.value.trim(); loadContacts(searchQuery,true); },250);
  searchBox.addEventListener('input',handleSearch);
  clearBtn.addEventListener('click',()=>{ searchBox.value=""; searchQuery=""; loadContacts("",true); searchBox.focus(); });

  async function loadContacts(query="", reset=false){
    if(loading||(allLoaded&&!reset)) return;
    loading=true; spinner.style.display='block';
    if(reset){offset=0; allLoaded=false; contactsList.innerHTML='';}
    try{
      const res = await fetch(`includes/get_public_contacts.php?q=${encodeURIComponent(query)}&limit=${limit}&offset=${offset}`);
      const html = await res.text();
      if(!html.trim()){ if(offset===0) contactsList.innerHTML='<div class="alert alert-warning text-center">No contacts found.</div>'; allLoaded=true; }
      else { contactsList.insertAdjacentHTML('beforeend',html); offset+=limit; }
    }catch(e){console.error(e);}finally{ spinner.style.display='none'; loading=false; }
  }
  loadContacts();
  window.addEventListener('scroll',()=>{ if((window.innerHeight+window.scrollY)>=document.body.offsetHeight-140) loadContacts(searchQuery); });

  /* Auto-refresh new/approved contacts */
setInterval(async () => {
  try {
    const res = await fetch(`includes/get_public_contacts.php?q=${encodeURIComponent(searchQuery)}&limit=5&offset=0`);
    const html = await res.text();
    if (!html.trim()) return;

    const temp = document.createElement('div');
    temp.innerHTML = html;
    const newCards = Array.from(temp.querySelectorAll('.contact-card'));

    const existing = new Set(
      Array.from(contactsList.querySelectorAll('.contact-card')).map(c => {
        const name = c.querySelector('h6')?.innerText.trim() || '';
        const href = c.querySelector('a.btn-success')?.getAttribute('href') || '';
        return name + '|' + href;
      })
    );

    for (let i = newCards.length - 1; i >= 0; i--) {
      const c = newCards[i];
      const id = (c.querySelector('h6')?.innerText.trim() || '') + '|' +
                 (c.querySelector('a.btn-success')?.getAttribute('href') || '');

      if (!existing.has(id)) {
        // Remove any pending card
        const newName = c.querySelector("h6")?.innerText.trim() || "";
        const newMobile = c.querySelector("p.small")?.innerText.trim() || "";
        contactsList.querySelectorAll(".contact-card[data-temp-id]").forEach(p => {
          const pName = p.querySelector("h6")?.innerText.trim() || "";
          const pMobile = p.querySelector("p.small")?.innerText.trim() || "";
          if (pName === newName && pMobile === newMobile) {
            p.remove(); // remove pending
          }
        });

        // Insert the approved/new card
        c.classList.add('contact-highlight');
        contactsList.insertAdjacentElement('afterbegin', c);

        // Green highlight for approved contacts
        c.style.transition = "background-color 1s";
        c.style.backgroundColor = "#d4edda"; // light green

        // Remove highlight after 2s
        setTimeout(() => { 
          c.style.backgroundColor = ''; 
          c.classList.remove('contact-highlight'); // remove soft yellow class if any
        }, 1000);
      }
    }

    // Remove soft-yellow highlight from cards that are now approved
    contactsList.querySelectorAll(".contact-card.contact-highlight[data-temp-id]").forEach(p => {
      const name = p.querySelector("h6")?.innerText.trim() || "";
      const mobile = p.querySelector("p.small")?.innerText.trim() || "";
      const existsApproved = newCards.some(c => {
        const cName = c.querySelector("h6")?.innerText.trim() || "";
        const cMobile = c.querySelector("p.small")?.innerText.trim() || "";
        return cName === name && cMobile === mobile;
      });
      if (existsApproved) {
        p.remove(); // remove pending card
      }
    });

  } catch (e) {
    console.error(e);
  }
}, 1000);


});
</script>
