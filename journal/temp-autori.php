<?php
	$broj_autora = 0;
	for($i=1;$i<=5;$i++){
		//if($a_result["autor".$i]!=""){
			$autor_id[$i]=$i;
			$institucija_id[$i]=$i+100;
			if($i==3) $institucija_id[$i] = "101,103";
			
			$broj_autora++;
		//}
	}

	$ins_counter = 0;

	$aut_string = "";
	$ins_string = "";

	for($i=1;$i<=$broj_autora;$i++){
		$institucija_postoji = 0;
		for($j=1;$j<$i;$j++){
			if($institucija_id[$i] == $institucija_id[$j]){
				$institucija_postoji = $j;
				break;
			}
		}
		if($i>1) $aut_string = $aut_string . ", ";
		if($institucija_postoji == 0){
			$ins_counter++;
			$indeksi_institucija[$i] = $ins_counter;
			$ins_string = $ins_string ."<sup>". $ins_counter . "</sup><em>INS No. " . $institucija_id[$i] . "</em><br />";
			$aut_string = $aut_string . "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">AUT No." . $autor_id[$i] . "</a></em><sup>" . $ins_counter . "</sup>";
		}else{
			$indeksi_institucija[$i] = $indeksi_institucija[$institucija_postoji];
			$aut_string = $aut_string . "<em><a href=\"/?sekcija=articles&alc=autor&alv=".$autor_id[$i]."\">AUT No." . $autor_id[$i]. "</a></em><sup>" . $indeksi_institucija[$i] . "</sup>";
		}
	}
	
	echo "<p>".$aut_string."</p>";
	echo "<p>".$ins_string."</p>";

?>

