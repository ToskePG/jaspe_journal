<?php

//	$sajt = "http://www.mjssm.me";
	$sajt = "http://www.sportmont.ucg.ac.me";
//	$sajt = "http://www.jaspe.ac.me";

	$strane = array (
		'/',
		'/?sekcija=page&amp;p=21',
		'/?sekcija=page&amp;p=22',
		'/?sekcija=page&amp;p=24',
		'/?sekcija=page&amp;p=26',
		'/?sekcija=page&amp;p=5',
		'/?sekcija=page&amp;p=23',
		'/download/Authorship%20Statement.pdf',
		'/download/Declaration%20of%20Potential%20Conflict%20of%20Interest.pdf',
		'/?sekcija=articles&amp;alc=past&amp;alv=1',
		'/?sekcija=page&amp;p=51',
		'/?sekcija=page&amp;p=62',
		'/?sekcija=page&amp;p=dl-authors',
		'/?sekcija=page&amp;p=7',
		'/?sekcija=page&amp;p=8',
		'/rss/'
	);
	
/////////////////////	header("Content-Type: application/rss+xml; charset=UTF-8");

	Class SimpleXMLElementExtended extends SimpleXMLElement {

	  /**
	   * Adds a child with $value inside CDATA
	   * @param unknown $name
	   * @param unknown $value
	   */
	  public function addChildWithCDATA($name, $value = NULL) {
	    $new_child = $this->addChild($name);

	    if ($new_child !== NULL) {
	      $node = dom_import_simplexml($new_child);
	      $no   = $node->ownerDocument;
	      $node->appendChild($no->createCDATASection($value));
	    }

	    return $new_child;
	  }
	}
	
	$xml = new SimpleXMLElementExtended('<urlset/>');
	$xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");

	foreach ($strane as $strana) {
		$url = $xml->addChild("url");
		$url->addChild("loc", $sajt . $strana);
	}

	include "db.php";

 	$sitemap_result = mysql_query("SELECT * FROM clanci ORDER BY id") or die(mysql_error());

	while ($row = mysql_fetch_assoc($sitemap_result)) {
		$url = $xml->addChild("url");
		$url->addChild("loc", $sajt . "/?sekcija=article&amp;artid=" . $row['id']);
	}

	$xml->asXML("sitemap.xml");
	
	$dom = new DOMDocument();
	$dom->loadXML($xml->asXML());
	$dom->formatOutput = true;
	$formattedXML = $dom->saveXML();

///////////////	echo $formattedXML;
	
?>

<?/*

"Note that although addChild() escapes "<" and ">", it does not escape the ampersand "&"."


$xmlelement->value = 'my value < > &';

instead of:

$xmlelement->addChild('value', 'my value < > &');
*/
?>