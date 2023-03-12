<?php

/**
 * Vypis aktualny datum a cas naformatovany
 *
 * @return string
 */
function actual_date()
{
    return date('d.m.Y, H:i:s');
}


/**
 * Vytvori novy subor, ak uz existuje tak vrati false.
 *
 * @param string $filename
 * @return boolean
 */
function mkfile( $filename )
{
    if ( ! is_file( $filename ))
    {
        fclose(fopen( $filename, 'x'));
        return true;
    }
        return false;
}


/**
 * Ukladaj aktualny datum a cas do suboru (ak uz v subore existuje datum a cas, novy cas sa pripise), kazdy zaznam daj na novy riadok
 *
 * Ak prisiel student po 8:00, tak dopis do logu za cas string "meskanie"
 * 
 * Ak pride student medzi 20-24, tak vyhod chybu cez die, ze nemoze sa dany prichod zapisat
 * 
 * Dorob aby sa pri prichode logovalo aj meno studenta
 * 
 * @param string $log_file
 * @param string $time
 * @return string
 */
function write_data($filename, $time, $name, $late, $welcome)
{
    if ($late)
    {
        return file_put_contents($filename, "$time || Hello $name. You are too late!" . PHP_EOL, FILE_APPEND); 
    }

    elseif ($welcome)
    {
        return file_put_contents($filename, "$time || Hello $name. You are welcome!" . PHP_EOL, FILE_APPEND);
    }

    else
    {
        die ("Hello $name. You are too late! Door between 20:00 and 23:59 are closed");
    } 
}
    

/**
 * Getuj obsah log suboru a vypis ho
 *
 * @param string $log_file
 * @return string
 */
function read_data($filename)
{
    return file_get_contents( $filename );
}

?>
