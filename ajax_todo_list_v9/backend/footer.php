
<!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
   
 <script src="ajax/ajax.js"></script>
 <script type="text/javascript">
    $(document).on('click','.update',function(e) {
    var id=$(this).attr("data-id");
    var date=$(this).attr("data-date");
    var time=$(this).attr("data-time");
    var subject=$(this).attr("data-subject");
    var focal_point=$(this).attr("data-focal_point");
    var president=$(this).attr("data-president");    
    var zoom_id=$(this).attr("data-zoom_id");
    var zoom_passcode=$(this).attr("data-zoom_passcode");
    var zoom_link=$(this).attr("data-zoom_link");
    var place=$(this).attr("data-place");
    var status_s=$(this).attr("data-status");
    
    $('#id_u').val(id);
    $('#date_u').val(date);
    $('#time_u').val(time);   
    $('#subject_u').val(subject);
    $('#focal_point_u').val(focal_point);
    $('#president_u').val(president);
    $('#zoom_id_u').val(zoom_id);
    $('#zoom_passcode_u').val(zoom_passcode);
    $('#zoom_link_u').val(zoom_link);
    $('#place_u').val(place);
    $('#status_u').val(status_s);
  });

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
      'use strict'      
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.querySelectorAll('.needs-validation')      
      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function (user_form) {
          user_form.addEventListener('submit', function (event) {
            if (!user_form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            user_form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>

   <script>
function applyFontSizes() {
  // Get all elements that need to be styled
  var elements = document.querySelectorAll('.text');

  elements.forEach(function(element) {
    // Check if the text contains Bangla characters
    if (/[\u0980-\u09FF]/.test(element.textContent)) {
      element.classList.add('bangla-text');
    } else {
      element.classList.add('english-text');
    }
  });
}

// Call the function after the page has loaded
document.addEventListener('DOMContentLoaded', applyFontSizes);
</script>



</body>
</html>