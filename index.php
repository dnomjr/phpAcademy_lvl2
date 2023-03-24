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
        ?>

        <form action="" method="get">
            <label for="name">Your first & last name: </label>
            <input type="text" name="name">
            <input type="submit" value="Submit!" name="submit">
        </form>    

        <?php
        /**
        * dorob aby sa pri prichode logovalo aj meno studenta
        * sprav aby to akceptovalo meno aj cez url adresu ze ?meno=jozko
        */
            $file = 'log.txt';

            /*arrivals*/
            if (isset($_GET['name'])) 
            {
                $studentName = $_GET['name']; //student name variable

                $delay = (date('H:i:s') > date('08:00:00') && date('H:i:s') < date('23:00:00'));
                $ontime = (date('H:i:s') >= date('00:00:00') && date('H:i:s') <= date('08:00:00'));

                write_data(mkfile($file), $file, $date, $studentName, $delay, $ontime); // put data to exist file
            }
            
            if(is_file($file))
            {
                echo nl2br(read_data( $file )); // get data from log.txt to index.php
            }

            /** STUDENTI.JSON */
            $jsonStudents = 'studenti.json';

            if(isset($studentName))
            {
                mkfile($jsonStudents);
                echo(Students::addStudent($jsonStudents, $studentName));
                print_r(read_data($jsonStudents));
            }

            echo '<hr>';

            /** PRICHODY.JSON */
            $jsonArrivals = 'prichody.json';
            
            if(isset($_GET['submit']))
            {
                mkfile($jsonArrivals);
                $arrive = new Arrivals();
                $arrive->addDate($jsonArrivals, $date, $delay);
                print_r(read_data($jsonArrivals));
            }
        ?>
    </body>
</html>