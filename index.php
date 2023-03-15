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
    date_default_timezone_set('Europe/Bratislava');
    $date = actual_date(); //actual date & time
    
    echo "Actual date and time is ".$date; 

/**
* pridaj form s inputom meno studenta, ktory sa bude submitovat cez POST - OK

* dorob aby sa pri prichode logovalo aj meno studenta - OK

* sprav aby to akceptovalo meno aj cez url adresu ze ?meno=jozko
*/

    $file = 'log.txt';

    mkfile('log.txt'); // create new file for logging arrivals
    /* form w/ student name & submit set on GET*/
    echo '<form action="" method="get">
          <label for="name">Your first & last name: </label>
          <input type="text" name="name">
          <input type="submit" value="Submit!" name="submit">
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


   
/**
 * sprav studenti.json, kde sa budu ukladat studenti ktori prisli - OK

* ak subor existuje tak loadni stary studenti.json, a pridaj novy zaznam - OK

* incrementuj pri prichode studenta cislo v jsone, ktore bude reprezentovat celkovy pocet prichodov - OK

* vypis obsah mapy studentov po decodovani pomocou print_r - OK
 */

    $json_students = 'studenti.json'; // create new json file for student names
    if(is_file($json_students)) //if json file exist
    {
        $students = json_decode(read_data($json_students), true); //save to $students json file content and load old file
    
        if(empty($students))   //if array is empty, then create new array w/ arrivals
        {
            $students = [
                'arrivals' => 0
            ] ;
        }

        if(isset($_GET['name']))
        {
        array_push($students, $_GET['name']); //push student names to array
        $students['arrivals']++; // increment number of arrivals when student come
        }
    }
    
    file_put_contents($json_students, json_encode($students), true); //save students and number of arrivals to json file
    echo "<hr>";

    print_r($students); //write content of json file after decode

    echo "<hr>";
    echo "Celkový počet príchodov študentov je " . $students['arrivals'].".";



/**
 * sprav prichody.json, ktory bude len pole vsetkych prichodov, a rovnako ho appenduj decodovanim a encodovanim jsonu
 */
    $json_arrivals = 'prichody.json';
    if(is_file($json_arrivals))
    {
        $arrivals = json_decode(read_data($json_arrivals));
    }
    else
    {
        $arrivals = [];
    }
    
    if(empty($arrivals)) 
    {
        $arrivals = [];
    }
    
    if(isset($_GET['submit']))
    {
        array_push($arrivals, $date); 
    }
   
    echo "<hr>";

    file_put_contents($json_arrivals, json_encode($arrivals));
    print_r($arrivals);
?>


</body>
</html>