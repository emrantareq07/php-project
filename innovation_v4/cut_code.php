<option value="Deputy Chief Engineer"  <?=$row == 'Deputy Chief Engineer' ? ' selected="selected"' : '';?> >Deputy Chief Engineer</option>
<option value="Additional Chief Engineer"  <?=$row == 'Additional Chief Engineer' ? ' selected="selected"' : '';?> >Additional Chief Engineer</option>
 <option value="General Manager"  <?=$row == 'General Manager' ? ' selected="selected"' : '';?> >General Manager</option>
 <option value="Programmer"  <?=$row == 'Programmer' ? ' selected="selected"' : '';?> >Programmer</option>
 <option value="Assistant Programmer"  <?=$row == 'Assistant Programmer' ? ' selected="selected"' : '';?> >Assistant Programmer</option>           

<option value="Addl. Chief Accountant" <?=$row == 'Addl. Chief Accountant' ? ' selected="selected"' : '';?> >Addl. Chief Accountant</option>
<option value="Deputy General Manager"  <?=$row == 'Deputy General Manager' ? ' selected="selected"' : '';?> >Deputy General Manager</option>
<option value="Manager"  <?=$row == 'Manager' ? ' selected="selected"' : '';?> >Manager</option>
<option value="Deputy Manager"  <?=$row == 'Deputy Manager' ? ' selected="selected"' : '';?> >Deputy Manager</option>
 <option value="Assistant Manager"  <?=$row == 'Assistant Manager' ? ' selected="selected"' : '';?> >Assistant Manager</option>
 <option value="Departmental Head(Commercial)"  <?=$row == 'Departmental Head(Commercial)' ? ' selected="selected"' : '';?> >Departmental Head(Commercial)</option>
 <option value=" Departmental Head(Administration)"  <?=$row == 'Departmental Head(Administration)' ? ' selected="selected"' : '';?> >Departmental Head(Administration)</option>   
  <option value=" Departmental Head(Accounts)"  <?=$row == 'Departmental Head(Accounts)' ? ' selected="selected"' : '';?> >Departmental Head(Accounts)</option> 
  <option value="Sr. System Analyst"  <?=$row == 'Sr. System Analyst' ? ' selected="selected"' : '';?> >Sr. System Analyst </option>
   <option value="Chairman (Grade-1)"  <?=$row == 'Chairman (Grade-1)' ? ' selected="selected"' : '';?> >Chairman (Grade-1) </option>
   <option value="System Analyst"  <?=$row == 'System Analyst' ? ' selected="selected"' : '';?> >System Analyst </option>
   <option value="GM(MTS)/Chief Engineer(MTS)"  <?=$row == 'GM(MTS)/Chief Engineer(MTS)' ? ' selected="selected"' : '';?> >GM(MTS)/Chief Engineer(MTS) </option>

----------------------------

    <select class="form-control" id="designation" name="designation" value="">
      <option value="" disabled selected>--Select--</option>
   <?php

      // require_once('db/db.php');

      $sql = "SELECT designation FROM designation";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_array($result))
      {
        $designation=$row['designation'];
       // echo "<option value='".$row['designation']."'>".$row['designation']."</option>";


        ?>

        
      <option value="Assistant Engineer" <?php echo $designation == 'Assistant Engineer' ? ' selected="selected"' : '';?> >Assistant Engineer</option>
      <option value="Executive Engineer"  <?php echo $designation == 'Executive Engineer' ? ' selected="selected"' : '';?> >Executive Engineer</option>
      


    
    </select>
    <?php
      }
        }

        ?>