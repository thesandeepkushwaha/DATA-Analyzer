<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Round 1</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style>
#mySideoutput a {
  position: absolute;
  right: -80px;
  transition: 0.3s;
  padding: 15px;
  width: 100px;
  text-decoration: none;
  font-size: 20px;
  color: white;
  border-radius: 5px 0 0 5px;
}

#mySideoutput a:hover {
  right: 0;
}

#output {
  top: 20px;
  background-color: #4CAF50;
}


/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 80%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}
</style>
</head>
<body>
<?php
// Start the session
session_start();
?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="#"><b>DATA Analyzer</b></a>
  </nav>
  <div class="container-fluid">
  <div class="row">
    <div class="col-sm-3">
    <h2>Select Format</h2>
    <div class="jumbotron">
  
    <form method="POST" action="index.php">
    <input type="checkbox" name="mysql" value="" id="sql_checkbox" >MySql<br>
    <input type="text" name="servername" placeholder="" value="localhost" class="form-control"><br>
   <input type="text" name="username" placeholder="" value="root" class="form-control"><br>
     <input type="text" name="password" placeholder="Password" value="" class="form-control"><br>
     <div id="sql_box" style="display:none;">
<input type="submit" name="database" class= "button">
</div>
</form>
<hr>

  <input type="checkbox" name="mysql" value="" id="csv_checkbox"> CSV<br>
  <div id="csv_box" style="display:none;">
  <form method="POST" action="" enctype="multipart/form-data">
  <input type="file" name="file" value="" class="form-control" id="fileUpload"> <br>
  <input type="submit" name="submit_csv">
  </div>
</form>
</div>

</div>

<div class="col-sm-3">
    <h2>Select Source</h2>
    <div class="jumbotron">
<?php


if (isset($_POST['submit_csv'])){
   echo '<h2>CSV FILE</h2> <br /><br /><br />' ;
   
    echo " " . realpath('./' . $_FILES['file']['name']) . "<br>";
    }

if(isset($_POST['database']))

{
    $_SESSION["server"] = trim($_POST['servername']);
    $_SESSION["username"] =trim($_POST['username']);
    $_SESSION["password"] = trim($_POST['password']);
    
// Usage without mysql_list_dbs()

$link = mysql_connect( $_SESSION["server"],  $_SESSION["username"],  $_SESSION["password"] );
$res = mysql_query("SHOW DATABASES");
    ?>
       <form method="POST" action="index.php">
    <select name="dbname" class="dropdown-item"> 
    <option>Select Database</option>
    <?php while ($row = mysql_fetch_assoc($res)) { ?>
   <option  value = "<?php echo($row['Database'])?>" ><?php echo($row['Database']) ?></option>
    <?php } ?>
   </select>
   <input type="submit" name="dbname_submit">
   </form>
<?php
}
?>
 
    
 
<?php
if(isset($_POST['dbname_submit']))
{
//$dbname=$_POST['dbname'];
$_SESSION["dbname"] =($_POST['dbname']);
if (!mysql_connect( $_SESSION["server"],  $_SESSION["username"],  $_SESSION["password"] )) {
    echo 'Could not connect to mysql';
    exit;
}

$sql = "SHOW TABLES FROM $_SESSION[dbname]";
$result = mysql_query($sql);

if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}
?>
<form method="POST" action="index.php">
<h2>Select Table</h2>
<?php while ($row = mysql_fetch_row($result)) { 
    ?>
   <input id="abc" type="checkbox" name="table_name[]" value="<?php echo "$row[0]" ?>"> <?php echo "$row[0]" ?><br>
 
    <?php 
}?>
  <input type="submit" name="table_submit">
</form>
<?php
//mysql_free_result($result);
}

?>
    </div>
    </div>
    <div class="col-sm-6">
    <h2>Visualizer</h2>   
   <div class="jumbotron">
   <div>
   <p id="table_v"></p>
   <?php

if (isset($_POST['submit_csv'])){
   // $csv_file= $_POST['file'];
    echo(' <input type="submit" value="File">    <br><br><br>
    <input type="submit" value="transform"> <br><br><br>
    <input type="submit" value="Output as mysql"><br><br><br>
    <form method="POST" action="index.php">  
    <input type="submit" name="run" value="Run the mapping"></form>  '     );
}
        if(isset($_POST['table_submit'])){
    //to run PHP script on submit
// Loop to store and display values of individual checked checkbox.
?><form method="POST" action="index.php"><?php
foreach ($_POST['table_name'] as $value) {
    // Do something with each valid friend entry ...
    if ($value)
     {?>
        
        <input type="submit"  name="table[]" value="<?php echo "$value" ?>"><br>
        </form>
        <?php }
}?>
</form>
        <?php
        }
        ?>
   </div>
<div>

<?php
if(isset($_POST['table']))
{
echo "<p>Verify the Primary Key for Joining.<p>";
// Query to get columns from table
  // Query to check connection
  //$mysqli = new mysqli('localhost', 'root', '', 'aapka_painter');
  $mysqli = new mysqli( $_SESSION["server"],  $_SESSION["username"],  $_SESSION["password"], $_SESSION["dbname"]);
if (!$mysqli)
 {
  echo 'Could not connect to mysql';
  exit;
}
echo '<form name="join">';
foreach ($_POST['table'] as $tablename) {
$sql = "SHOW COLUMNS FROM $tablename";
$res = $mysqli->query($sql);
echo '<select name="Select_key[]">'; // Open your drop down box

while($row = $res->fetch_assoc()){
    $columns[] = $row['Field'];

}
    foreach ($columns as $value) {

    echo '<option value="'.strip_tags($value).'">'.$value.'</option>';

}


echo '</select>';

}
echo '</form>';
}

?>
<?
if(isset($_POST['join']))
{
  foreach ($columns as $value) {
   $sql = "SELECT *
            FROM $tablename 
            JOIN $tablename on $tablename. = c.id
            ORDER by p.id
            ";
  }
}
?>

</div>
   
   
   </div></div>
   </div>
 </div>


 <div id="mySideoutput" class="sideoutput">
  <a href="#" id="output">Output</a>
  
</div>

<!-- Trigger/Open The Modal -->


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <!-- <h2></h2> -->
    </div>
    <div class="modal-body">
    <?
    if (isset($_POST['run'])){
    
    echo "<table>\n\n";
    $f = fopen("orders.csv", "r");
    while (($line = fgetcsv($f)) !== false) {
            echo "<tr>";
            foreach ($line as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>\n";
    }
    fclose($f);
    echo "\n</table>";
    
    }
    ?>
      <p>Some other text...</p>
    </div>
    <div class="modal-footer">
      <!-- <h3></h3> -->
    </div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("output");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}



  $(document).ready(function()    
        {
    $("#sql_checkbox").change(function(){
        if($("#sql_checkbox").prop("checked", true)){
            $("#csv_checkbox").prop("checked", false);
                $("#sql_box").show();
                $("#csv_box").hide();
            }
        });      
        $("#csv_checkbox").change(function(){
            if($("#csv_checkbox").prop("checked", true)){
                $("#sql_checkbox").prop("checked", false);
                $("#csv_box").show();
                $("#sql_box").hide();
            }
        });
});

function getFilePath(){
     $('input[type=file]').change(function () {
         var filePath=$('#fileUpload').val();
         console.log(filePath); 
     });
}
</script>
</body>
</html>