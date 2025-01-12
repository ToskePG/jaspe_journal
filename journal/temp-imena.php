<?php
/*****************************************************************************************
Full Name					Name in Reference List				Name in In-Text Citation
Diego J. Rivera-Gutierrez	Rivera-Gutierrez, D. J. (2016).		(Rivera-Gutierrez, 2016)
Rena Torres Cacoullos		Torres Cacoullos, R. (2012).		(Torres Cacoullos, 2012)
Ulrica von Thiele Schwarz	von Thiele Schwarz, U. (2015).		(von Thiele Schwarz, 2015)
Simone de Beauvoir			de Beauvoir, S. (1944).				(de Beauvoir, 1944)
Herbert M. Turner III		Turner, H. M., III. (2013).			(Turner, 2013)
*****************************************************************************************/

include "name-parser.php";

$autors = Array();
$autors[] = "Diego J. Rivera-Gutierrez";
$autors[] = "Rena Torres Cacoullos";
$autors[] = "Milena J. Popovic Nikolic";
$autors[] = "Milena Popovic Nikolic";
$autors[] = "Ana Marija Popovic Nikolic";
$autors[] = "Ulrica von Thiele Schwarz";
$autors[] = "Simone de Beauvoir";
$autors[] = "Herbert M. Turner III";
$autors[] = "Marko I. N. Markovic Jr.";
$autors[] = "Petar I.N. Petrovic Sr.";

$autor_components = Array();
foreach($autors as $a){
	$parser = new FullNameParser();
	$autor_components[] = $parser->parse_name($a);
}
echo  '<pre>';
print_r($autor_components);
echo  '</pre>';






?>





