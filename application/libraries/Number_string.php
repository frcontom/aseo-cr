<?php
//
//namespace NumberToString;
use Luecano\NumeroALetras\NumeroALetras;

class Number_string
{

    function __construct() {
        require 'vendor/autoload.php'; // Línea no necesaria si se usa frameworks como Laravel
    }


    function numberToMoney($value = 0, $currenty = 'DÓLARES'){
        $formatter = new NumeroALetras();
        return $formatter->toMoney($value, 2, $currenty, 'CENTAVOS');
    }

}