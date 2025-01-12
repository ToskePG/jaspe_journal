<?php
	$qstrana = mysql_query('select * from `strane` where `id` = "'.$page_id.'" limit 1') or die(mysql_error());

/*while ($row = mysql_fetch_assoc($result)) {
    echo $row["userid"];
    echo $row["fullname"];
    echo $row["userstatus"];
}
*/

	$row = mysql_fetch_assoc($qstrana);
    echo "<h3>".$row["headline"]."</h3>";
    echo $row["content"];


?>