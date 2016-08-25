<?php

class KilloWattCost {
 var $date_start;
 var $date_end;
 var $prwth_endeiksh;
 var $deyterh_endeiksh;
 var $sqm;
 var $dhmTelh;
 var $dhmForos;
 var $timhZwnhs;
 var $synPalaiothtas;
 var $synTAP;

 var $timh_kwh_euro_0_2000;
 var $timh_kwh_euro_2000_up;
 var $elaxisth_xrewsh_1F;
 var $elaxisth_xrewsh_3F;

 var $pagio1F;
 var $pagio3F;
 
 var $MPX_Metaforas; 	// €/kVA*ΣΙ/έτος
 var $MMX_Metaforas; 	// €/kWh
 var $MPX_Dianomhs; 	// €/kVA*ΣΙ/έτος
 var $MMX_Dianomhs; 	// €/kWh
 var $loipes_xrewseis;	//(€/kWh)
 var $etmear;		//(€/kWh)
 var $YKW_0;		// (€/kWh)
 var $YKW_1601;		// (€/kWh)
 var $YKW_2002;		// (€/kWh)
 var $YKW_3000;		// (€/kWh)
 var $SI;		//Χρεωστέα Ισχύς: Η συμφωνημένη ισχύς (ΣΙ) της παροχής.

 var $TotalEnergy;
 var $DaysDiff;
 var $pagiaXrewsh;
 var $xrewshEnergias;
 var $elSysMet;
 var $elSysDia;
 var $YKW;
 var $etmearTotal;
 var $loipes_xrewseisTotal;
 var $efkTotal;
 var $axiaHlektr;
 var $eidikoTelosTotal;
 var $FPA;
 var $enanti;
 var $DT;
 var $DF;


 function __construct($enanti, $paroxh, $date_start, $date_end, $prwth_endeiksh, $deyterh_endeiksh, $sqm , $dhmTelh, $dhmForos, $timhZwnhs, $synPalaiothtas, $synTAP)
 {
	 $this->timh_kwh_euro_0_2000 	= 0.09460;
	 $this->timh_kwh_euro_2000_up 	= 0.10252;
	 $this->elaxisth_xrewsh_1F 	= 5.30;
	 $this->elaxisth_xrewsh_3F 	= 8.58;
	 $this->pagio1F 		= 1.52;
	 $this->pagio3F 		= 4.80;
	 $this->MPX_Metaforas 		= 0.14;
	 $this->MMX_Metaforas 		= 0.00541;
	 $this->MPX_Dianomhs		= 0.56;
	 $this->MMX_Dianomhs		= 0.0214;
	 $this->loipes_xrewseis		= 0.00046;
	 $this->etmear			= 0.02487;
	 $this->YKW_0			= 0.00699;
	 $this->YKW_1601		= 0.01570;
	 $this->YKW_2002		= 0.03987;
	 $this->YKW_3000		= 0.04488;
	 $this->EFK			= 0.0022;
	 $this->SI 			= 1;
	 $this->kVa 			= 8;

	 $this->paroxh	 		= $paroxh;
	 $this->enanti	 		= $enanti;
	 $this->date_start 		= $date_start;
	 $this->date_end		= $date_end;
	 $this->prwth_endeiksh		= $prwth_endeiksh;
	 $this->deyterh_endeiksh	= $deyterh_endeiksh;
	 $this->sqm			= $sqm;
	 $this->dhmTelh			= $dhmTelh;
	 $this->dhmForos		= $dhmForos;
	 $this->timhZwnhs		= $timhZwnhs;
	 $this->synPalaiothtas		= $synPalaiothtas;
	 $this->synTAP			= $synTAP;


 }

 function calculateDays(){

	 $start = strtotime($this->date_start);
	 $end = strtotime($this->date_end);
	 $datediff = floor(($end - $start)/(60*60*24));
	 $this->DaysDiff = $datediff;
	 return $this->DaysDiff;
 }

 function calculateEnergy(){

	 $this->TotalEnergy = ($this->deyterh_endeiksh - $this->prwth_endeiksh);
	 return $this->TotalEnergy;
 }


 //Πάγια Χρέωση  ( τιμή  x  συντ. αναγωγής ημερών ): 1,52 €x  (120/120)=  1,52€ 
 function calculatePagiaXrewsh(){

	 $this->calculateDays();

	 if($this->paroxh == 1){
		 $this->pagiaXrewsh = round($this->pagio1F * ($this->DaysDiff/120), 2, PHP_ROUND_HALF_UP);
		 return $this->pagiaXrewsh;
	 } else {
		 $this->pagiaXrewsh = round($this->pagio3F * ($this->DaysDiff/120), 2, PHP_ROUND_HALF_UP);
		 return $this->pagiaXrewsh;
	 }
	 return false;
 }

 //Χρέωση Ενέργειας  ( kWh  x  τιμή κλίμακας ): 1.100 x  0,09460 €= 104,06€
 function calculateXrewshEnergias(){

	 $this->calculateEnergy();

	 if($this->TotalEnergy <= 2000){
		$this->xrewshEnergias = round(($this->TotalEnergy * $this->timh_kwh_euro_0_2000), 2, PHP_ROUND_HALF_UP);
		return $this->xrewshEnergias;
	 } else {
		 $this->xrewshEnergias = round(($this->TotalEnergy * $this->timh_kwh_euro_2000_up), 2, PHP_ROUND_HALF_UP);
		 return $this->xrewshEnergias;
	
	 }
	 return false;
 }

//[ ΜΠΧ (€ / kVA & έτος ) x kVA x Ημέρες / 365]  + [ kWh x ΜΜΧ (€ / kWh) ]  =  
 function calculateElSysMet(){
	$this->elSysMet = round((($this->MPX_Metaforas * $this->kVa * $this->DaysDiff/365) + ($this->TotalEnergy*$this->MMX_Metaforas)), 2, PHP_ROUND_HALF_UP);
	return $this->elSysMet;
 }

//[ ΜΠΧ (€ / kVA & έτος ) x kVA x Ημέρες / 365] + [ kWh  x  ΜΜΧ (€ / kWh) / συνφ ] =            
 function calculateElSysDia(){
	$this->elSysDia = round((($this->MPX_Dianomhs * $this->kVa *$this->DaysDiff/365) + ($this->TotalEnergy*$this->MMX_Dianomhs/$this->SI)), 2, PHP_ROUND_HALF_UP);
	return $this->elSysDia;
 }
//ΥΠΗΡΕΣΙΕΣ ΚΟΙΝΗΣ ΩΦΕΛΕΙΑΣ:(kWh x τιμή) 
 function calculateYKW(){

	if ($this->TotalEnergy > 3000)
		$this->YKW = round(($this->TotalEnergy * $this->YKW_3000), 2, PHP_ROUND_HALF_UP);
	else if (($this->TotalEnergy >= 2001) && ($this->TotalEnergy <= 3000))
		$this->YKW = round(($this->TotalEnergy * $this->YKW_2002), 2, PHP_ROUND_HALF_UP);
	else if (($this->TotalEnergy >= 1601) && ($this->TotalEnergy <= 2000))
		$this->YKW = round(($this->TotalEnergy * $this->YKW_1601), 2, PHP_ROUND_HALF_UP);
	else if (($this->TotalEnergy >= 0) && ($this->TotalEnergy <= 1600))
		$this->YKW = round(($this->TotalEnergy * $this->YKW_0), 2, PHP_ROUND_HALF_UP);

	return $this->YKW;
 }

 //ΕΙΔΙΚΟ ΤΕΛΟΣ ΜΕΙΩΣΗΣ ΕΚΠΟΜΠΩΝ ΑΕΡΙΩΝ ΡΥΠΩΝ / ΕΤΜΕΑΡ: (kWh x τιμή)
 function calculateETMEAR(){

	$this->etmearTotal = round(($this->TotalEnergy * $this->etmear), 2, PHP_ROUND_HALF_UP);
	return $this->etmearTotal;
 }

 //ΛΟΙΠΕΣ ΧΡΕΩΣΕΙΣ:(kWh x τιμή)
 function calculateLoipesXrewseis(){

	$this->loipes_xrewseisTotal = round(($this->TotalEnergy * $this->loipes_xrewseis), 2, PHP_ROUND_HALF_UP);
	return $this->loipes_xrewseisTotal;
 }

 function calculateEFK(){

	$this->efkTotal = round(($this->TotalEnergy * $this->EFK), 2, PHP_ROUND_HALF_UP);
	return $this->efkTotal;
 }

 function calculateEidikoTelos(){
	$this->eidikoTelosTotal = round((($this->axiaHlektr - $this->etmearTotal + $this->efkTotal)*5/1000), 2, PHP_ROUND_HALF_UP);
	return $this->eidikoTelosTotal;
 }

 function calculateFPA(){
	$this->FPA = round((($this->axiaHlektr + $this->efkTotal)*13/100), 2, PHP_ROUND_HALF_UP);
	return $this->FPA;
 }

 function calculateDT(){
	$this->DT = round((($this->sqm*$this->dhmTelh)*(round(($this->DaysDiff/2),0,PHP_ROUND_HALF_UP)/365)), 2, PHP_ROUND_HALF_UP);
	return $this->DT;
 }

 function calculateDF(){
	$this->DF = round((($this->sqm*$this->dhmForos)*(round(($this->DaysDiff/2),0,PHP_ROUND_HALF_UP)/365)), 2, PHP_ROUND_HALF_UP);
	return $this->DF;
 }

 function calculateTAP(){
	$this->TAP = round(( $this->sqm * $this->timhZwnhs * $this->synPalaiothtas * $this->synTAP * round(($this->DaysDiff/2),0,PHP_ROUND_HALF_UP) / 365) , 2, PHP_ROUND_HALF_UP);
	return $this->TAP;
 }

 function calculateERT(){
	$this->ERT = round(( 36 * $this->DaysDiff / 365) , 2, PHP_ROUND_HALF_UP);
	return $this->ERT;
 }

 function calculateKwhPrice(){
	$this->KwhPrice = $this->TOTAL/$this->TotalEnergy;
	return $this->KwhPrice;
 }



 function calculateTotalCost(){
	
	 $this->calculatePagiaXrewsh();
	 $this->calculateXrewshEnergias();
	 $this->calculateElSysMet();
	 $this->calculateElSysDia();
	 $this->calculateYKW();
	 $this->calculateETMEAR();
	 $this->calculateLoipesXrewseis();
	 $this->calculateEFK();
	
	 $this->axiaHlektr = round((($this->pagiaXrewsh+$this->xrewshEnergias)+ ($this->elSysMet+$this->elSysDia+$this->YKW+$this->etmearTotal+$this->loipes_xrewseisTotal)), 2, PHP_ROUND_HALF_UP);
	 $this->axiaHlektr = $this->axiaHlektr + $this->enanti; 

	 $this->calculateEidikoTelos();
	 $this->calculateFPA();
	 $this->calculateDT();
	 $this->calculateDF();
	 $this->calculateTAP();

	 $this->synoloDhmou = ($this->DT + $this->DF + $this->TAP);

	 $this->calculateERT();

	 $this->TOTAL = ($this->axiaHlektr + $this->efkTotal + $this->eidikoTelosTotal + $this->FPA + $this->synoloDhmou + $this->ERT);

/*
	 echo "Pagia Xrewsh: ".$this->pagiaXrewsh."\n";	
	 echo "Days: ".$this->DaysDiff."\n";	
	 echo "Xrewsh Energeias: ".$this->xrewshEnergias."\n";	
	 echo "--Xrewsh Promithias Reymatos: ".($this->pagiaXrewsh+$this->xrewshEnergias)."\n";	
	 echo "Ellhniko Systhma metaforas: ".$this->elSysMet."\n";
	 echo "Ellhniko Systhma dianomhs: ".$this->elSysDia."\n";
	 echo "YKW: ".$this->YKW."\n";
	 echo "ETMEAR: ".$this->etmearTotal."\n";
	 echo "Loipes Xrewseis: ".$this->loipes_xrewseisTotal."\n";
	 echo "---ΡΥΘΜΙΖΟΜΕΝΕΣ ΧΡΕΩΣΕΙΣ: ".($this->elSysMet+$this->elSysDia+$this->YKW+$this->etmearTotal+$this->loipes_xrewseisTotal)."\n";
	 echo "---ΑΞΙΑ ΗΛΕΚΤΡΙΚΟΥ ΡΕΥΜΑΤΟΣ:  (ΧΡΕΩΣΗ ΠΡΟΜΗΘΕΙΑΣ ΡΕΥΜΑΤΟΣ + ΡΥΘΜΙΖΟΜΕΝΕΣ ΧΡΕΩΣΕΙΣ): ".($this->axiaHlektr)."\n";

	 echo "EFK: ".$this->efkTotal."\n";
	 echo "Eidiko Telos: ".$this->eidikoTelosTotal."\n";
	 echo "FPA: ".$this->FPA."\n\n";
	 echo "---ΣΥΝΟΛΟ ΑΞΙΑΣ ΗΛΕΚΤΡΙΚΟΥ ΡΕΥΜΑΤΟΣ + ΕΦΚ + ΕΙΔΙΚΟ ΤΕΛΟΣ + FPA: ".($this->axiaHlektr + $this->efkTotal + $this->eidikoTelosTotal + $this->FPA )."\n";
	 echo "DT: ".$this->DT."\n";
	 echo "DF: ".$this->DF."\n";
	 echo "TAP: ".$this->TAP."\n";
	 echo "Synolo gia dhmo: : ".$this->synoloDhmou."\n";
	 echo "ERT: ".$this->ERT."\n\n\n";


	 echo "TOTAL: ".($this->TOTAL)."\n";
*/
	 return $this->TOTAL;
 }


}

?>
