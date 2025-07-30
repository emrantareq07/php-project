    <?php
  require_once("includes/header.php");
  //require_once("includes/hero.php");
  ?>

  <main id="main">

    <!-- ======= Photo Gallery Section ======= -->
    <section class="scrolling/photo_gallery">
      <div class="container mt-3" data-aos="fade-up">

        <div class="row">

         <div class="section-title mt-4 pt-4 mb-0">
               <h2>Photo Gallery</h2>
               <h4 class="mt-2 pt-3 mb-0">  </h4>
           </div>
        </div>


           <div class="gallery">
             <?php require_once 'db/db.php';
            $query = "SELECT * FROM photo_gallary where sub_catagory='16th December 2021' ORDER BY id DESC"; 
            $result = mysqli_query($conn, $query);
     
            while ($data = mysqli_fetch_assoc($result)) {
            ?>
                <!-- <li style="list-style: none"> <a href="<?php //echo $data['title']; ?>"><?php //echo $data['catagory']; ?></a></li>
               
               <h4><a class="rounded img-thumnail" href="<?php //echo $data['title']; ?>"><?php //echo $data['catagory']; ?></a></h4> -->
            <!--   <ul id="lidesign" class="fa-ul">

            <li class=""><i class="fa-li fa fa-solid fa-check-square" style="font-size:30px;color:lightblue;"></i>
              <a target="_blank" href="pdf_view.php?id=<?php //echo $data['id'] ?>"><?php //echo $data['title']?></a></li>
            
            </ul> -->


                <img src="./uploads/<?php echo $data['photo']; ?>" height="200" width="200" class="rounded img-thumnail" >
     
            <?php
            }
            ?>

        </div>
        


        </div> 

       </section>   <!-- ======= End Photo Gallery Section ======= -->




  </main><!-- End #main -->
  <?php
  require_once("includes/footer.php");
  ?>