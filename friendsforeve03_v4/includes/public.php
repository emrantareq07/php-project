<?php
require '../db/db.php';
?>
<?php include 'header.php'; ?>

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
  <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#publicModal">
    <i class="fa fa-comments-o"></i> Add Contact
  </button>

  <!-- Modal with Contact Submission Form -->
  <div class="modal fade" id="publicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form id="publicForm" class="row g-3 p-3" autocomplete="off">
          <div class="modal-header">
            <h5 class="modal-title text-muted text-uppercase float-end">Add Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <!-- message area -->
          <div class="px-3 mt-2" id="publicMsgWrap"><span id="publicMsg" class="me-auto text-center d-block"></span></div>

          <div class="modal-body">
            <div class="col-md-6"><label class="form-label required-field">Name</label><input type="text" name="name" class="form-control" required autocomplete="on"></div>
            <div class="col-md-6"><label class="form-label required-field">Mobile</label><input name="mobile" class="form-control" required inputmode="tel" autocomplete="on"></div>
            <div class="col-md-6"><label class="form-label">Alternate Mobile</label><input name="alt_mobile" class="form-control" inputmode="tel" autocomplete="on"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" autocomplete="on"></div>
            <div class="col-md-6"><label class="form-label required-field">Occupation</label><input type="text" name="occupation" class="form-control" required autocomplete="on"></div>
            <div class="col-md-6"><label class="form-label">Jobplace</label><input name="jobplace" type="text" class="form-control" autocomplete="on"></div>
            <div class="col-12"><label class="form-label required-field">Address</label><textarea name="address" class="form-control" rows="1" required autocomplete="on"></textarea></div>
          </div>
          <div class="modal-footer">
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
    <input type="text" id="searchBox" autofocus class="form-control" placeholder="Search by name, address, occupation, email...">
    <button class="btn btn-outline-secondary" id="clearSearchBtn" type="button">
      <i class="fa fa-times"></i>
    </button>
  </div>

  <!-- Contacts container -->
  <div id="contactsList" class="list-group mb-2"></div>

  <!-- spinner -->
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
  const nameInput = publicForm.querySelector('input[name="name"]');
  const submitBtn = publicForm.querySelector('button[type="submit"]');
  const searchBox = document.getElementById('searchBox');
  const clearBtn = document.getElementById('clearSearchBtn');
  const contactsList = document.getElementById('contactsList');
  const spinner = document.getElementById('loadingSpinner');

  function showMsg(type, text) {
    publicMsg.className = 'fade-out';
    publicMsg.innerHTML = `<div class="alert alert-${type} mb-0"><i class="fa fa-${type==='success'?'check-circle':'info-circle'}"></i> ${text}</div>`;
    setTimeout(()=> publicMsg.classList.add('hide'), 3000); // fade
    setTimeout(()=> { publicMsg.innerHTML=''; publicMsg.className=''; }, 4000);
  }

  function debounce(fn, wait=300){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),wait); }; }

  /* submit form */
  publicForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    submitBtn.disabled = true;
    showMsg('info', 'Sending…');
    try {
      const fd = new FormData(publicForm);
      const res = await fetch('request_save.php',{method:'POST',body:fd});
      const txt = await res.text();
      publicForm.reset();
      nameInput.focus();
      showMsg('success', txt);
    } catch {
      showMsg('danger','Error submitting request.');
    } finally { submitBtn.disabled=false; }
  });

  modalEl.addEventListener('show.bs.modal', ()=>{ publicForm.reset(); publicMsg.innerHTML=''; setTimeout(()=>nameInput.focus(),150); });
  modalEl.addEventListener('hidden.bs.modal', ()=>{ publicForm.reset(); publicMsg.innerHTML=''; });

  /* search */
  let searchQuery=""; let offset=0,limit=10,loading=false,allLoaded=false;
  const handleSearch=debounce((e)=>{searchQuery=e.target.value.trim();loadContacts(searchQuery,true);},250);
  searchBox.addEventListener('input',handleSearch);
  clearBtn.addEventListener('click',()=>{searchBox.value="";searchQuery="";loadContacts("",true);searchBox.focus();});

  async function loadContacts(query="",reset=false){
    if(loading||(allLoaded&&!reset))return;
    loading=true; spinner.style.display='block';
    if(reset){offset=0;allLoaded=false;contactsList.innerHTML='';}
    try{
      const res=await fetch(`get_public_contacts.php?q=${encodeURIComponent(query)}&limit=${limit}&offset=${offset}`);
      const html=await res.text();
      if(!html.trim()){ if(offset===0) contactsList.innerHTML='<div class="alert alert-warning text-center">No contacts found.</div>'; allLoaded=true; }
      else { contactsList.insertAdjacentHTML('beforeend',html); offset+=limit; }
    }catch(e){console.error(e);}finally{spinner.style.display='none';loading=false;}
  }
  loadContacts();
  window.addEventListener('scroll',()=>{ if((window.innerHeight+window.scrollY)>=document.body.offsetHeight-140) loadContacts(searchQuery); });

  /* auto-refresh highlight */
  setInterval(async()=>{
    try{
      const res=await fetch(`get_public_contacts.php?q=${encodeURIComponent(searchQuery)}&limit=5&offset=0`);
      const html=await res.text();
      if(!html.trim())return;
      const temp=document.createElement('div');temp.innerHTML=html;
      const newCards=Array.from(temp.querySelectorAll('.contact-card'));
      const existing=new Set(Array.from(contactsList.querySelectorAll('.contact-card')).map(c=>{
        const name=c.querySelector('h6')?.innerText.trim()||'';const href=c.querySelector('.btn-success')?.getAttribute('href')||'';return name+'|'+href;
      }));
      for(let i=newCards.length-1;i>=0;i--){
        const c=newCards[i];
        const id=(c.querySelector('h6')?.innerText.trim()||'')+'|'+(c.querySelector('.btn-success')?.getAttribute('href')||'');
        if(!existing.has(id)){ c.classList.add('contact-highlight'); contactsList.insertAdjacentHTML('afterbegin',c.outerHTML); }
      }
    }catch(e){console.error(e);}
  },15000);
});
</script>
