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


/**
* funkcie, ktore suvisia s logovanim studenti.json, prerob do classy na staticke funkcie
* sprav studenti.json, kde sa budu ukladat studenti ktori prisli
* ak subor existuje tak loadni stary studenti.json, a pridaj novy zaznam
* incrementuj pri prichode studenta cislo v jsone, ktore bude reprezentovat     celkovy pocet prichodov
 */

 class Studenti
 {
    public static function addStudent($filename, $newStudent)
    {    
        if(is_file($filename))
        {
            $arrayStudents = json_decode(file_get_contents($filename), true);
        }
    
        if(empty($arrayStudents))
        {
            $arrayStudents = [
                'pocetPrichodov' => 0
            ];
        }
        
        array_push($arrayStudents, $newStudent);
        $arrayStudents['pocetPrichodov']++;
        
        file_put_contents( $filename, json_encode($arrayStudents), true);
        echo "<hr>";
        echo "Celkovy počet príchodov je " . $arrayStudents['pocetPrichodov'].".";
    } 
 }

/**
 * vypis obsah mapy studentov po decodovani pomocou print_r 
 */
function loadJson($filename)
{
    echo "<hr>";
    return json_decode(file_get_contents($filename), true);
}


/**
 * funkcie, ktore suvisia s logovanim prichody.json, prerob do classy a funkcie pouzivaj tak ze najprv vytvoris instanciu classy (objekt)
 * 
 * sprav prichody.json, ktory bude len pole vsetkych prichodov, a rovnako ho appenduj decodovanim a encodovanim jsonu
 * preiteruj pole z prichody.json, a k meskajucim datumom napis ze ""meskanie""
 */

Class Prichody
{
    public $file;
    public $Date;
    public $Late;

    function addDate($filename, $arriveDate, $tooLate)
    {
    $this->file = $filename;
    $this->Date = $arriveDate;
    $this->Late = $tooLate;

    if(is_file($this->file))
    {
        $arrayArrive = json_decode(file_get_contents($this->file));
    }
    
    if(empty($arrayArrive))
    {
        $arrayArrive = [];
    }
    
    // check for late arrival - w/ private method
    $isLate = $this->checkLate($this->Late, $this->Date);
    if ($isLate) {
        $this->Date = date('d.m.Y, H:i:s') . " meskanie";
    }

    array_push($arrayArrive, $this->Date);
    file_put_contents($this->file, json_encode($arrayArrive));
    }

    /**
     * v classe ktoru pouzivas ako instanciu, vytvor private metodu ktoru pouzijes ako nejaku pomocnu feature pri logovani (napriklad ziskavanie ci nastalo meskanie)
     */
    private function checkLate($tooLate, $arriveDate)
    {
        $this->Late = $tooLate;
        $this->Date = $arriveDate;

        if ($this->Late == $this->Date)
        {
            return true;
        } 
        else
        {
            return false;
        }
    }
}
?>
