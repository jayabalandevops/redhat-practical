<!DOCTYPE html>
<html>
<body>

<?php
$firstname = $_POST[‘firstname’];
$lastname = $_POST[‘lastname’];
$dob = $_POST[‘dob’];
$fp = fopen(”formdata.txt”, “a”);
$savestring = $firstname . “,” . $lastname . “,” . $dob . “n”;
fwrite($fp, $savestring);
fclose($fp);
echo “<h1>You data has been saved in a text file!</h1>”;
?>

</body>
</html>