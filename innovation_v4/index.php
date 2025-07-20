
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Innovation Database - BCIC.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">  
 	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
 
* {
	font-family: 'Open Sans', sans-serif;
    font-family: 'Tiro Bangla', serif;
	<!--font-family: 'Noto Sans Bengali', sans-serif;
    font-family: 'Nikosh', sans-serif;
    font-family: 'Nikosh', serif;-->
}
.bs-example{
            margin: 10px;
        }

  </style>  
 <link rel="icon" type="image/gif/png" href="images/bcic_logo.png"> 
</head>
<body>
  <div class="bs-example bg-white shadow">
	<div class="container-fluid border shadow" style="width:100%;">	
	<div class="row">    	
	<div class="col-sm-8"> <h2 align="right" class="my-3 text-center text-white bg-success rounded">বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ </h2>
		<p align="right" class="small my-0 pt-0 text-center text-muted">[--Design & Developed By: Md. Tareq Emran, Programmer, ICT Division, BCIC--]</p>
	</div>
	<div class="col-sm-4">                            
      <span class="float-end"><a href="statistics_report.php" class="btn btn-success text-white my-3"> <i class='fas fa-chart-line'></i> Statistics Report </a>
		<a class="btn btn-danger my-3" href="create_pdf_all_inovation.php" target="_blank"> <i class="fa fa-print"></i> Print</a> </span>
			  
    </div>
	</div>

	<?php
	include('db/db.php');
	$res=mysqli_query($conn,"SELECT * FROM innovation_tbl ORDER BY id DESC");
	//$res=mysqli_query($conn,"SELECT id,fiscal_year,title_of_invention,inventors_name,inventors_designation,
	 //inventors_emp_id,proposed_workplace,des_of_invention, imple_status,replicate_eligibility FROM innovation order by id desc");
	?>
	  <table class="table table-striped table-hover pt-2" style="width:100%">
	  	<!-- <table id="user_data" class="table table-bordered table-striped" class="display nowrap" style="width:100%"> -->
		<thead style="background-color: purple;" class="text-white">
			<tr>
				<!--<th>অর্থবছর</th>
				<th>Name</th>
				<th>Title</th>
				<th>Company Name</th>
				<th>Name</th>
				<th>Title</th>
				<th style="display:none;">Address</th>
				<th>City</th>-->
				
				<!-- <th class='text-center pb-4'>#</th> -->
				 <th class='text-center pb-4'>অর্থবছর</th>
				  <th class='text-left pb-4'>সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
				 <th>উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
				 <th class='text-left pb-4'> সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা </th>
				 <th class='text-center pb-4'> বাস্তাবায়নের অবস্থা</th>
				  <th class='text-center pb-4'>রেপ্লিকেট যোগ্যতা</th>
				  <th class='text-center pb-4'> ফলাফল </th>
				  <th class='text-center pb-4'>সেবার লিংক</th>
				  <th class='text-center pb-4'>মন্তব্য</th>
			</tr>
		</thead>
		<tbody>
		
		
			<?php 
			//if (mysqli_num_rows($res) > 0) {
			
			while($row=mysqli_fetch_array($res)){?>
			<tr>
			<!-- <td><?php //echo $row['id']?></td> -->
				<td><?php echo $row['fiscal_year']?></td>
				<td><?php echo $row['title_of_invention']?></td>
				<td><?php echo $row['inventors_name']."<br>".$row['inventors_designation']."<br>".$row['inventors_emp_id']."<br>".$row['proposed_workplace'] ?></td>
				
				
				<!--<td><?php //echo $row['des_of_invention']?></td>
				<td><a href="javascript:void(0)" class="btn btn-success btn-view ml-1" data-id="'.$row['id'].'">  
				<i class="fa fa-eye" style="font-size:17px"></i> View </a></td>
				<td><?php //echo $row['title_of_invention']?>
				<button data-id='<?php //echo $row['id']; ?>' class="userinfo btn btn-success btn-xs">
				<i class="fa fa-eye" style="font-size:20px"></i></button ></td>
				-->
				<td><?php echo $row['title_of_invention']?>
				<a class="view_data ml-1 bg-success text-white" id="<?php echo $row["id"]; ?>" style="text-decoration:none; cursor: pointer;">  
				বিস্তারিত... </a>
				
				<!--<input type="button" name="view" value="বিস্তারিত.." id="<?php //echo $row["id"]; ?>" class="btn btn-info btn-xs view_data my-0" />
				-->
				</td> 
				<td class='text-center pb-4'><?php echo $row['imple_status']?></td>
				<td class='text-center pb-4'><?php echo $row['replicate_eligibility']?></td>
				<td class='text-center pb-4'><?php echo $row['feedback']?></td>
				<td><?php echo $row['service_link']?></td>
				<td><?php echo $row['remarks']?></td>
				
				<!--<td style="display:none;"><?php //echo $row['address']?></td>-->
				
			</tr>
			<?php //}
			} 
			//mysqli_close($conn);
			?>
		</tbody>
	  </table>
	  
<div id="dataModal" class="modal fade" tabindex="-1" role="dialog">  
      <div class="modal-dialog modal-xl" role="document">  
           <div class="modal-content">  
                <div class="modal-header">  <h4 class="modal-title">সেবা/আইডিয়া/উদ্ভাবনের বর্নণা </h4> 
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>  
                      
                </div>  
                <div class="modal-body" id="employee_detail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>  	  
<!--View Innovation Details-->

	<!--<div class="modal fade" id="empModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Details</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
        </div>-->


   </div>
   </div>

    <!-- 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
	-->
	
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>	 -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" ></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="DataTables/datatables.js"></script>
<script type='text/javascript'>
  $(document).ready( function () {
		 $('.table').DataTable({
		// 	 "order": [[1, "desc"]], //"2" is my date array position
		// 	 // lengthMenu: [
  //           // [ 10, 25, 50,-1],
  //           // [ 10, 25, 50, 'All'],

  //       //],
		// "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]], "iDisplayLength" : 10,
  //     dom: 'Blfrtip',
  //     fixedHeader: true,
		// });
  // });

  // $('#user_data').DataTable({
  lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'All'],
        ],

  "processing":true,
  // "serverSide":true,
  "order":[],
  // "scrollY": '50vh',
  //       "scrollCollapse": true,
  //       "paging": false,
  // "ajax":{
  //  url:"fetch.php",
  //  type:"POST"
  // },
  "columnDefs":[
   {
    "targets":[0, 3, 4],
    "orderable":false,
   },
  ],
      // dom: 'Blfrtip',
      // fixedHeader: true

      // columnDefs: [
      //       {
      //           targets: -1,
      //           data: null,
      //           // defaultContent: '<button>Click!</button>',
      //       },
      //   ],
 });

      // $('button').click(function(){  
      	$(document).on('click', '.view_data', function(){
           var employee_id = $(this).attr("id");  
           $.ajax({  
                url:"select.php",  
                method:"post",  
                data:{employee_id:employee_id},  
                success:function(data){  
                     $('#employee_detail').html(data);  
                     $('#dataModal').modal("show");  
                }  
           });  
      });

 });
//
// $(document).ready(function(){  
//       $('.view_data').click(function(){  
//            var employee_id = $(this).attr("id");  
//            $.ajax({  
//                 url:"select.php",  
//                 method:"post",  
//                 data:{employee_id:employee_id},  
//                 success:function(data){  
//                      $('#employee_detail').html(data);  
//                      $('#dataModal').modal("show");  
//                 }  
//            });  
//       });  
//  });  
 
// $(document).ready(function(){
	// $('.userinfo').click(function(){
		// var userid = $(this).data('id');
		// $.ajax({
			// url: 'ajaxfile.php',
			// type: 'post',
			// data: {userid: userid},
			// success: function(response){ 
				// $('.modal-body').html(response); 
				// $('#empModal').modal('show'); 
			// }
		// });
	// });
// });
</script>

</body>
</html>
