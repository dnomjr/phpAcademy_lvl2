<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level2</title>
</head>
<body>
<?php include "functions.php" ?> 


<?php 
   $date = actual_date(); //actual date & time
    echo "Actual date and time is ".$date; 
    echo "<hr>";

    mkfile('log.txt'); // create new file for logging arrivals
    $file = 'log.txt';

    /* form w/ student name & submit set on GET*/
    echo '<form action="" method="get">
          <label for="name">Your first & last name: </label>
          <input type="text" name="name">
          <input type="submit" value="Submit!">
          </form>';

          /*arrivals*/
    if (isset($_GET['name']) ) 
    {
        $their_name = $_GET['name']; //student name

        $delay = (date('H:i:s') > date('08:00:00') && date('H:i:s') < date('20:00:00'));
        $ontime = (date('H:i:s') >= date('00:00:00') && date('H:i:s') <= date('08:00:00'));

        write_data($file, $date, $their_name, $delay, $ontime); // put data to exist file
    }
    
    echo nl2br(read_data( $file )); // get data from log.txt to index.php
   

    $json = 'studenti.json'; // create new json file for student names
       
    if(is_file($json)) //if json file exist
        {
        $array = json_decode(file_get_contents($json)); //save to $array json file content and load old file
    
        if(empty($array))
        {
            $array = []; 
        }

        if(isset($_GET['name']))
        {
        array_push($array, $_GET['name']);
        }
        }

    file_put_contents($json, json_encode($array));
    print_r($array);

?>


</body>
</html>