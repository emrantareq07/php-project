<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employee ID Generator V2</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
  
  <script>

  function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }

//
$(".tes").keyup(function () {
    if (this.value.length == this.maxLength) {
      $(this).next('.tes').focus();
    }
});

// $("#tes").keyup(function () {
    // if (this.value.length == this.maxLength) {
      // $(this).next('#tes').focus();
    // }
// });
// var elts = document.getElementsByClassName('test')
// Array.from(elts).forEach(function(elt){
  // elt.addEventListener("keyup", function(event) {
    // // Number 13 is the "Enter" key on the keyboard
    // if (event.keyCode === 13 || elt.value.length == 3) {
      // // Focus on the next sibling
      // elt.nextElementSibling.focus()
    // }
  // });
// })
// $(".inputs").keyup(function () {
    // if (this.value.length == this.maxLength) {
      // $(this).next('.inputs').focus();
    // }
// });

// $(".inputs").keyup(function () {
//     if (this.value.length == this.maxLength) {
//       $(this).next('.inputs').focus();
//     }
// });
</script>
</head>
<body>


<div class="container mt-5 ">
  <h2 class="text-uppercase text-center text-muted">Employee ID Generator V2</h2>
  <p class="text-uppercase text-center text-muted">BCIC Officers/Staff Employee ID Generator</p>
  <h1 class="text-center text-uppercase text-success">Employee ID is: <b class="text-danger"> 
<?php
if(isset($_POST['submit'])){
$first_digit=$_POST['first_digit'];
$second_digit=$_POST['second_digit'];
$third_digit=$_POST['third_digit'];
$fourth_digit=$_POST['fourth_digit'];

//2nd step
$first_digit_2nd_step=$first_digit;
$second_digit_2nd_step=(int) $second_digit*2;
$third_digit_2nd_step=$third_digit;
$fourth_digit_2nd_step=(int) $fourth_digit*2;

//}

// $first_digit_2nd_step = '';
// $owsecond_digit_2nd_stepner = '';
// $third_digit_2nd_step = '';
// $fourth_digit_2nd_step = '';

function sum($num) {
    $sum = 0;
    for ($i = 0; $i < strlen($num); $i++){
        $sum += $num[$i];
    }
    return $sum;
}
  
// Driver Code
//$num = "121";
$num = @"$second_digit_2nd_step";
// echo sum($num);
$sum_2nd_digit=sum($num);
// echo "<br>";

function sum1($num1) {
    $sum = 0;
    for ($i = 0; $i < strlen($num1); $i++){
        $sum += $num1[$i];
    }
    return $sum;
}
  
// Driver Code
//$num = "121";
$num1 = @"$fourth_digit_2nd_step";
$sum_4th_digit=sum1($num1);

@$result=$first_digit_2nd_step+$sum_2nd_digit+$third_digit+$sum_4th_digit;
 // echo $result;

if($result<=10){
  $last_digit=10-$result;
  // echo $last_digit;
}


elseif($result<=20){
  $last_digit=20-$result;
  // echo $last_digit;
}

elseif($result<=30){
  $last_digit=30-$result;
  // echo $last_digit;
}

elseif($result<=40){
  $last_digit=40-$result;
  // echo $last_digit;
}

echo $first_digit.$second_digit.$third_digit.$fourth_digit.'-'.$last_digit;

}

?>
</b>
  </h1>
<form action="index.php" method="POST">
<div class="row">
  <div class="col-sm-4"></div>

  <div class="col-sm-4 text-center">
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<input class="tes" type="text" maxlength="1" size="7" name="first_digit" autofocus required onkeypress="return isNumberKey(event)"/>
<input class="tes" type="text" maxlength="1" size="7" name="second_digit" required onkeypress="return isNumberKey(event)"/>
<input class="tes" type="text" maxlength="1" size="7" name="third_digit" required onkeypress="return isNumberKey(event)"/>
<input class="tes" type="text" maxlength="1" size="7" name="fourth_digit" required onkeypress="return isNumberKey(event)"/>

  
<!-- <input class=" form-control" type="text" placeholder="Enter 1st Digit" name="first_digit" required autofocus onkeypress="return isNumberKey(event)" maxlength="1" /><br/>

<input class=" form-control" type="text" placeholder="Enter 2nd Digit" name="second_digit" required onkeypress="return isNumberKey(event)" maxlength="1" /><br/>
<input class=" form-control" type="text" placeholder="Enter 3rd Digit" name="third_digit" required onkeypress="return isNumberKey(event)" maxlength="1"/><br/>
<input class=" form-control" type="text" placeholder="Enter 4th Digit" name="fourth_digit" required onkeypress="return isNumberKey(event)" maxlength="1" /><br/> -->

   </div>
 <div class="col-sm-4"></div> 
</div> 
 
  <br/>
   <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
      <div class="d-grid gap-2">
        
        <button type="submit" name="submit" class="btn btn-primary text-uppercase">Create EMP ID</button>
      </div>
        
   </div>
   <div class="col-sm-4"></div>
  </div>
  </form>


<!--   <form action="index.php" method="POST">
    <div class="row">
      <div class="col">
        <input type="text" class="form-control"  class="inputs" id="0" placeholder="Enter 1st Digit" name="first_digit" required autofocus onkeypress="return isNumberKey(event)" maxlength="1">
      </div>
      <div class="col">
        <input type="text" class="form-control" class="inputs" id="1" placeholder="Enter 2nd Digit" name="second_digit" required onkeypress="return isNumberKey(event)" maxlength="1">
      </div>
	        <div class="col">
        <input type="text" class="form-control" class="inputs" id="2" placeholder="Enter 3rd Digit" name="third_digit" required onkeypress="return isNumberKey(event)" maxlength="1">
      </div>
      <div class="col">
        <input type="text" class="form-control" class="inputs" id="3" placeholder="Enter 4th Digit" name="fourth_digit" required onkeypress="return isNumberKey(event)" maxlength="1">
      </div> -->
<!-- 	        <div class="col">
        <input type="text" class="form-control" placeholder="Enter email" name="email">
      </div>
      <div class="col">
        <input type="password" class="form-control" placeholder="Enter password" name="pswd">
      </div> -->
  <!--   </div>
	<hr>
	 <div class="row">
      <div class="col"></div>
     <div class="col"></div>
            <div class="col">
        <button type="submit" name="submit" class="btn btn-primary">Create EMP ID</button>
      </div>
      <div class="col"></div>
	 </div>
	
  </form> -->
<!-- </div> -->



</body>
</html>
<script>

var container = document.getElementsByTagName("input");
// for loop to iterate through elements
for (let i = 0; i < container.length; i++) {
  // create function event for keyup in input fields
  container[i].onkeyup = function(e) {
    //create variable for events source element
    var target = e.srcElement;
    //create variable for the max length attribute in the input field
    var maxLength = parseInt(target.attributes["maxlength"].value, 10);
    //create variable for the length of the targets value in the input
    var myLength = target.value.length;
    //conditional that sees if the elements value is equal tot he maxlength value 
    //or if the enter key is pressed
    if (e.keyCode === 13 || myLength >= maxLength) {
      //set variable for next
      var next = target;
      //loop for the next element of the target
      while (next = next.nextElementSibling) {
        //conditional if the next is not present, so last element we break the code
        if (next == null)
          break;
        //conditional to for the next element that is actually an input tag
        if (next.tagName.toLowerCase() == "input") {
          next.focus();
          break;
        }
      }
    }
  }
}
</script>
