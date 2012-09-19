<?php

/* 
 * Maciej Małecki
 * smt116(at)gmail.com
 * http://github.com/smt116/jquery.form/
 * 
 * MIT License http://www.opensource.org/licenses/mit-license.php
 */

error_reporting(NULL);

/**
 * Klasa odpowiedzialna za przekazanie informacji z php do js 
 */
final class json {

    /**
     * @param array $array tablica do przekazania do js
     */
    function __construct($array) {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/json");
        echo json_encode($array);
    }

}

