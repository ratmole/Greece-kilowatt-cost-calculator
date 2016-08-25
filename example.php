<?php
require_once('./include/kilowattCost.php');
 $paroxh		= 1;
 $date_start 		= "09-03-2016";
 $date_end		= "13-07-2016";
 $prwth_endeiksh	= "28000";
 $deyterh_endeiksh	= "29500";
 $sqm			= "90";
 $dhmTelh		= "0.85";
 $dhmForos		= "0.30";
 $timhZwnhs		= "1650";
 $synPalaiothtas	= "0.60";
 $synTAP		= "0.00035";
 $enanti		= -59.99;


$test = new KilloWattCost($enanti, $paroxh, $date_start, $date_end, $prwth_endeiksh, $deyterh_endeiksh, $sqm , $dhmTelh, $dhmForos, $timhZwnhs, $synPalaiothtas, $synTAP);

echo $test->calculateTotalCost()."\n";
echo $test->calculateKwhPrice()."\n";

?>
