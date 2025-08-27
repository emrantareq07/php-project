<section>
  <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Notices</h2>
          <p>Notice Board</p>
        </div>
<div class="card border-warning text-white bg-success mb-3" >
  <div class="card-header"></div>
  <div class="card-body">
    <h5 class="card-title">
    <?php
    include('db/db.php');
    $res=mysqli_query($conn,"SELECT * FROM notice_board ORDER BY id DESC LIMIT 6");
    //$res=mysqli_query($conn,"SELECT id,fiscal_year,title_of_invention,inventors_name,inventors_designation,
     //inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility FROM innovation order by id desc");
    while($row=mysqli_fetch_array($res)){
    ?>   
      <ul id="lidesign" class="fa-ul">
      <li class=""><i class="fa-li fa fa-solid fa-check-square" style="font-size:30px;color:lightblue;"></i>
        <a target="_blank" href="pdf_view.php?id=<?php echo $row['id'] ?>"><?php echo $row['notice']?></a></li>
      </ul>
      <?php //}
      } 
      //mysqli_close($conn);
      ?>
       </h5>
      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>    
  </div>
</div>
</div>
</section>