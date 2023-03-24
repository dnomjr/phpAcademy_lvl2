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
        fopen( $filename, 'c');    
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
    function write_data($mkFile, $filename, $time, $name, $late, $welcome)
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
        if (file_exists($filename) && pathinfo($filename, PATHINFO_EXTENSION) === 'json')
        { 
            return json_decode(file_get_contents($filename), true);
        }
       else 
       {
           return file_get_contents($filename);
       }
    }
     
    /**
    * funkcie, ktore suvisia s logovanim studenti.json, prerob do classy na staticke funkcie
    * sprav studenti.json, kde sa budu ukladat studenti ktori prisli
    * ak subor existuje tak loadni stary studenti.json, a pridaj novy zaznam
    * incrementuj pri prichode studenta cislo v jsone, ktore bude reprezentovat     celkovy pocet prichodov
    */

    class Students
    {
        public static function addStudent($filename, $newStudent)
        {    
            if(is_file($filename))
            {
                $students = json_decode(file_get_contents($filename), true);
            }

            if(empty($students))
            {
                $students = [
                    'pocetPrichodov' => 0
                ];
            }
            
            array_push($students, $newStudent);
            $students['pocetPrichodov']++;
            
            file_put_contents( $filename, json_encode($students));
            echo "<hr>";
            echo "Celkovy počet príchodov je " . $students['pocetPrichodov'].".";
            echo "<hr>";
        } 
    }

    /**
     * funkcie, ktore suvisia s logovanim prichody.json, prerob do classy a funkcie pouzivaj tak ze najprv vytvoris instanciu classy (objekt)
     * 
     * sprav prichody.json, ktory bude len pole vsetkych prichodov, a rovnako ho appenduj decodovanim a encodovanim jsonu
     * preiteruj pole z prichody.json, a k meskajucim datumom napis ze ""meskanie""
     */
    Class Arrivals
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
                $arrive = json_decode(file_get_contents($this->file));
            }
            
            if(empty($arrive))
            {
                $arrive = [];
            }
            
            // check for late arrival - w/ private method
            $isLate = $this->checkLate();
            if ($isLate)
            {
                $this->Date = date('d.m.Y, H:i:s') . " meskanie";
            }

            array_push($arrive, $this->Date);
            file_put_contents($this->file, json_encode($arrive));
        }

        /**
         * v classe ktoru pouzivas ako instanciu, vytvor private metodu ktoru pouzijes ako nejaku pomocnu feature pri logovani (napriklad ziskavanie ci nastalo meskanie)
         */
        private function checkLate()
        {
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
