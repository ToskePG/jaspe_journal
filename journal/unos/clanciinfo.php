<?php

// Global variable for table object
$clanci = NULL;

//
// Table class for clanci
//
class cclanci extends cTable {
	var $id;
	var $autor1;
	var $institucija1;
	var $autor2;
	var $institucija2;
	var $autor3;
	var $institucija3;
	var $autor4;
	var $institucija4;
	var $autor5;
	var $institucija5;
	var $autor6;
	var $institucija6;
	var $autor7;
	var $institucija7;
	var $autor8;
	var $institucija8;
	var $autor9;
	var $institucija9;
	var $autor10;
	var $institucija10;
	var $naslov_eng;
	var $naslov_mne;
	var $sazetak_eng;
	var $sazetak_mne;
	var $keywords_eng;
	var $keywords_mne;
	var $tip;
	var $file;
	var $broj;
	var $str;
	var $hits;
	var $references;
	var $citation;
	var $doi;
	var $udk;
	var $scopus_id;
	var $lastip;
	var $lastdownloadip;
	var $downloads;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'clanci';
		$this->TableName = 'clanci';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('clanci', 'clanci', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// autor1
		$this->autor1 = new cField('clanci', 'clanci', 'x_autor1', 'autor1', '`autor1`', '`autor1`', 3, -1, FALSE, '`EV__autor1`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor1'] = &$this->autor1;

		// institucija1
		$this->institucija1 = new cField('clanci', 'clanci', 'x_institucija1', 'institucija1', '`institucija1`', '`institucija1`', 200, -1, FALSE, '`EV__institucija1`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija1'] = &$this->institucija1;

		// autor2
		$this->autor2 = new cField('clanci', 'clanci', 'x_autor2', 'autor2', '`autor2`', '`autor2`', 3, -1, FALSE, '`EV__autor2`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor2'] = &$this->autor2;

		// institucija2
		$this->institucija2 = new cField('clanci', 'clanci', 'x_institucija2', 'institucija2', '`institucija2`', '`institucija2`', 200, -1, FALSE, '`EV__institucija2`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija2'] = &$this->institucija2;

		// autor3
		$this->autor3 = new cField('clanci', 'clanci', 'x_autor3', 'autor3', '`autor3`', '`autor3`', 3, -1, FALSE, '`EV__autor3`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor3'] = &$this->autor3;

		// institucija3
		$this->institucija3 = new cField('clanci', 'clanci', 'x_institucija3', 'institucija3', '`institucija3`', '`institucija3`', 200, -1, FALSE, '`EV__institucija3`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija3'] = &$this->institucija3;

		// autor4
		$this->autor4 = new cField('clanci', 'clanci', 'x_autor4', 'autor4', '`autor4`', '`autor4`', 3, -1, FALSE, '`EV__autor4`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor4->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor4'] = &$this->autor4;

		// institucija4
		$this->institucija4 = new cField('clanci', 'clanci', 'x_institucija4', 'institucija4', '`institucija4`', '`institucija4`', 200, -1, FALSE, '`EV__institucija4`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija4->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija4'] = &$this->institucija4;

		// autor5
		$this->autor5 = new cField('clanci', 'clanci', 'x_autor5', 'autor5', '`autor5`', '`autor5`', 3, -1, FALSE, '`EV__autor5`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor5->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor5'] = &$this->autor5;

		// institucija5
		$this->institucija5 = new cField('clanci', 'clanci', 'x_institucija5', 'institucija5', '`institucija5`', '`institucija5`', 200, -1, FALSE, '`EV__institucija5`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija5->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija5'] = &$this->institucija5;

		// autor6
		$this->autor6 = new cField('clanci', 'clanci', 'x_autor6', 'autor6', '`autor6`', '`autor6`', 3, -1, FALSE, '`EV__autor6`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor6->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor6'] = &$this->autor6;

		// institucija6
		$this->institucija6 = new cField('clanci', 'clanci', 'x_institucija6', 'institucija6', '`institucija6`', '`institucija6`', 200, -1, FALSE, '`EV__institucija6`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija6->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija6'] = &$this->institucija6;

		// autor7
		$this->autor7 = new cField('clanci', 'clanci', 'x_autor7', 'autor7', '`autor7`', '`autor7`', 3, -1, FALSE, '`EV__autor7`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor7->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor7'] = &$this->autor7;

		// institucija7
		$this->institucija7 = new cField('clanci', 'clanci', 'x_institucija7', 'institucija7', '`institucija7`', '`institucija7`', 200, -1, FALSE, '`EV__institucija7`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija7->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija7'] = &$this->institucija7;

		// autor8
		$this->autor8 = new cField('clanci', 'clanci', 'x_autor8', 'autor8', '`autor8`', '`autor8`', 3, -1, FALSE, '`EV__autor8`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor8->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor8'] = &$this->autor8;

		// institucija8
		$this->institucija8 = new cField('clanci', 'clanci', 'x_institucija8', 'institucija8', '`institucija8`', '`institucija8`', 200, -1, FALSE, '`EV__institucija8`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija8->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija8'] = &$this->institucija8;

		// autor9
		$this->autor9 = new cField('clanci', 'clanci', 'x_autor9', 'autor9', '`autor9`', '`autor9`', 3, -1, FALSE, '`EV__autor9`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor9->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor9'] = &$this->autor9;

		// institucija9
		$this->institucija9 = new cField('clanci', 'clanci', 'x_institucija9', 'institucija9', '`institucija9`', '`institucija9`', 200, -1, FALSE, '`EV__institucija9`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija9->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija9'] = &$this->institucija9;

		// autor10
		$this->autor10 = new cField('clanci', 'clanci', 'x_autor10', 'autor10', '`autor10`', '`autor10`', 3, -1, FALSE, '`EV__autor10`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->autor10->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['autor10'] = &$this->autor10;

		// institucija10
		$this->institucija10 = new cField('clanci', 'clanci', 'x_institucija10', 'institucija10', '`institucija10`', '`institucija10`', 200, -1, FALSE, '`EV__institucija10`', TRUE, FALSE, TRUE, 'FORMATTED TEXT');
		$this->institucija10->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institucija10'] = &$this->institucija10;

		// naslov_eng
		$this->naslov_eng = new cField('clanci', 'clanci', 'x_naslov_eng', 'naslov_eng', '`naslov_eng`', '`naslov_eng`', 201, -1, FALSE, '`naslov_eng`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naslov_eng'] = &$this->naslov_eng;

		// naslov_mne
		$this->naslov_mne = new cField('clanci', 'clanci', 'x_naslov_mne', 'naslov_mne', '`naslov_mne`', '`naslov_mne`', 201, -1, FALSE, '`naslov_mne`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['naslov_mne'] = &$this->naslov_mne;

		// sazetak_eng
		$this->sazetak_eng = new cField('clanci', 'clanci', 'x_sazetak_eng', 'sazetak_eng', '`sazetak_eng`', '`sazetak_eng`', 201, -1, FALSE, '`sazetak_eng`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sazetak_eng'] = &$this->sazetak_eng;

		// sazetak_mne
		$this->sazetak_mne = new cField('clanci', 'clanci', 'x_sazetak_mne', 'sazetak_mne', '`sazetak_mne`', '`sazetak_mne`', 201, -1, FALSE, '`sazetak_mne`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['sazetak_mne'] = &$this->sazetak_mne;

		// keywords_eng
		$this->keywords_eng = new cField('clanci', 'clanci', 'x_keywords_eng', 'keywords_eng', '`keywords_eng`', '`keywords_eng`', 201, -1, FALSE, '`keywords_eng`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['keywords_eng'] = &$this->keywords_eng;

		// keywords_mne
		$this->keywords_mne = new cField('clanci', 'clanci', 'x_keywords_mne', 'keywords_mne', '`keywords_mne`', '`keywords_mne`', 201, -1, FALSE, '`keywords_mne`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['keywords_mne'] = &$this->keywords_mne;

		// tip
		$this->tip = new cField('clanci', 'clanci', 'x_tip', 'tip', '`tip`', '`tip`', 200, -1, FALSE, '`tip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tip'] = &$this->tip;

		// file
		$this->file = new cField('clanci', 'clanci', 'x_file', 'file', '`file`', '`file`', 200, -1, FALSE, '`file`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['file'] = &$this->file;

		// broj
		$this->broj = new cField('clanci', 'clanci', 'x_broj', 'broj', '`broj`', '`broj`', 3, -1, FALSE, '`broj`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->broj->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['broj'] = &$this->broj;

		// str
		$this->str = new cField('clanci', 'clanci', 'x_str', 'str', '`str`', '`str`', 200, -1, FALSE, '`str`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['str'] = &$this->str;

		// hits
		$this->hits = new cField('clanci', 'clanci', 'x_hits', 'hits', '`hits`', '`hits`', 3, -1, FALSE, '`hits`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hits->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hits'] = &$this->hits;

		// references
		$this->references = new cField('clanci', 'clanci', 'x_references', 'references', '`references`', '`references`', 201, -1, FALSE, '`references`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['references'] = &$this->references;

		// citation
		$this->citation = new cField('clanci', 'clanci', 'x_citation', 'citation', '`citation`', '`citation`', 201, -1, FALSE, '`citation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['citation'] = &$this->citation;

		// doi
		$this->doi = new cField('clanci', 'clanci', 'x_doi', 'doi', '`doi`', '`doi`', 200, -1, FALSE, '`doi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['doi'] = &$this->doi;

		// udk
		$this->udk = new cField('clanci', 'clanci', 'x_udk', 'udk', '`udk`', '`udk`', 200, -1, FALSE, '`udk`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['udk'] = &$this->udk;

		// scopus_id
		$this->scopus_id = new cField('clanci', 'clanci', 'x_scopus_id', 'scopus_id', '`scopus_id`', '`scopus_id`', 200, -1, FALSE, '`scopus_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['scopus_id'] = &$this->scopus_id;

		// lastip
		$this->lastip = new cField('clanci', 'clanci', 'x_lastip', 'lastip', '`lastip`', '`lastip`', 200, -1, FALSE, '`lastip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['lastip'] = &$this->lastip;

		// lastdownloadip
		$this->lastdownloadip = new cField('clanci', 'clanci', 'x_lastdownloadip', 'lastdownloadip', '`lastdownloadip`', '`lastdownloadip`', 200, -1, FALSE, '`lastdownloadip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['lastdownloadip'] = &$this->lastdownloadip;

		// downloads
		$this->downloads = new cField('clanci', 'clanci', 'x_downloads', 'downloads', '`downloads`', '`downloads`', 3, -1, FALSE, '`downloads`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->downloads->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['downloads'] = &$this->downloads;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`clanci`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlSelectList() { // Select for List page
		return "SELECT * FROM (" .
			"SELECT *, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor1` LIMIT 1) AS `EV__autor1`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija1` LIMIT 1) AS `EV__institucija1`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor2` LIMIT 1) AS `EV__autor2`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija2` LIMIT 1) AS `EV__institucija2`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor3` LIMIT 1) AS `EV__autor3`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija3` LIMIT 1) AS `EV__institucija3`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor4` LIMIT 1) AS `EV__autor4`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija4` LIMIT 1) AS `EV__institucija4`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor5` LIMIT 1) AS `EV__autor5`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija5` LIMIT 1) AS `EV__institucija5`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor6` LIMIT 1) AS `EV__autor6`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija6` LIMIT 1) AS `EV__institucija6`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor7` LIMIT 1) AS `EV__autor7`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija7` LIMIT 1) AS `EV__institucija7`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor8` LIMIT 1) AS `EV__autor8`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija8` LIMIT 1) AS `EV__institucija8`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor9` LIMIT 1) AS `EV__autor9`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija9` LIMIT 1) AS `EV__institucija9`, (SELECT `autor_eng` FROM `autori` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`autor10` LIMIT 1) AS `EV__autor10`, (SELECT `institucija_eng` FROM `institucije` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `clanci`.`institucija10` LIMIT 1) AS `EV__institucija10` FROM `clanci`" .
			") `EW_TMP_TABLE`";
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->SqlSelectList(), $this->SqlWhere(), $this->SqlGroupBy(), 
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
				$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->autor1->AdvancedSearch->SearchValue <> "" ||
			$this->autor1->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor1->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor1->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija1->AdvancedSearch->SearchValue <> "" ||
			$this->institucija1->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija1->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija1->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor2->AdvancedSearch->SearchValue <> "" ||
			$this->autor2->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija2->AdvancedSearch->SearchValue <> "" ||
			$this->institucija2->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija2->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor3->AdvancedSearch->SearchValue <> "" ||
			$this->autor3->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija3->AdvancedSearch->SearchValue <> "" ||
			$this->institucija3->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija3->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor4->AdvancedSearch->SearchValue <> "" ||
			$this->autor4->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor4->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor4->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija4->AdvancedSearch->SearchValue <> "" ||
			$this->institucija4->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija4->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija4->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor5->AdvancedSearch->SearchValue <> "" ||
			$this->autor5->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor5->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor5->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija5->AdvancedSearch->SearchValue <> "" ||
			$this->institucija5->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija5->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija5->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor6->AdvancedSearch->SearchValue <> "" ||
			$this->autor6->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor6->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor6->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija6->AdvancedSearch->SearchValue <> "" ||
			$this->institucija6->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija6->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija6->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor7->AdvancedSearch->SearchValue <> "" ||
			$this->autor7->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor7->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor7->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija7->AdvancedSearch->SearchValue <> "" ||
			$this->institucija7->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija7->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija7->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor8->AdvancedSearch->SearchValue <> "" ||
			$this->autor8->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor8->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor8->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija8->AdvancedSearch->SearchValue <> "" ||
			$this->institucija8->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija8->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija8->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor9->AdvancedSearch->SearchValue <> "" ||
			$this->autor9->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor9->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor9->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija9->AdvancedSearch->SearchValue <> "" ||
			$this->institucija9->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija9->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija9->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->autor10->AdvancedSearch->SearchValue <> "" ||
			$this->autor10->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->autor10->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->autor10->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->institucija10->AdvancedSearch->SearchValue <> "" ||
			$this->institucija10->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->institucija10->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->institucija10->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`clanci`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "clancilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "clancilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("clanciview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("clanciview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "clanciadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("clanciedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("clanciadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("clancidelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->autor1->setDbValue($rs->fields('autor1'));
		$this->institucija1->setDbValue($rs->fields('institucija1'));
		$this->autor2->setDbValue($rs->fields('autor2'));
		$this->institucija2->setDbValue($rs->fields('institucija2'));
		$this->autor3->setDbValue($rs->fields('autor3'));
		$this->institucija3->setDbValue($rs->fields('institucija3'));
		$this->autor4->setDbValue($rs->fields('autor4'));
		$this->institucija4->setDbValue($rs->fields('institucija4'));
		$this->autor5->setDbValue($rs->fields('autor5'));
		$this->institucija5->setDbValue($rs->fields('institucija5'));
		$this->autor6->setDbValue($rs->fields('autor6'));
		$this->institucija6->setDbValue($rs->fields('institucija6'));
		$this->autor7->setDbValue($rs->fields('autor7'));
		$this->institucija7->setDbValue($rs->fields('institucija7'));
		$this->autor8->setDbValue($rs->fields('autor8'));
		$this->institucija8->setDbValue($rs->fields('institucija8'));
		$this->autor9->setDbValue($rs->fields('autor9'));
		$this->institucija9->setDbValue($rs->fields('institucija9'));
		$this->autor10->setDbValue($rs->fields('autor10'));
		$this->institucija10->setDbValue($rs->fields('institucija10'));
		$this->naslov_eng->setDbValue($rs->fields('naslov_eng'));
		$this->naslov_mne->setDbValue($rs->fields('naslov_mne'));
		$this->sazetak_eng->setDbValue($rs->fields('sazetak_eng'));
		$this->sazetak_mne->setDbValue($rs->fields('sazetak_mne'));
		$this->keywords_eng->setDbValue($rs->fields('keywords_eng'));
		$this->keywords_mne->setDbValue($rs->fields('keywords_mne'));
		$this->tip->setDbValue($rs->fields('tip'));
		$this->file->setDbValue($rs->fields('file'));
		$this->broj->setDbValue($rs->fields('broj'));
		$this->str->setDbValue($rs->fields('str'));
		$this->hits->setDbValue($rs->fields('hits'));
		$this->references->setDbValue($rs->fields('references'));
		$this->citation->setDbValue($rs->fields('citation'));
		$this->doi->setDbValue($rs->fields('doi'));
		$this->udk->setDbValue($rs->fields('udk'));
		$this->scopus_id->setDbValue($rs->fields('scopus_id'));
		$this->lastip->setDbValue($rs->fields('lastip'));
		$this->lastdownloadip->setDbValue($rs->fields('lastdownloadip'));
		$this->downloads->setDbValue($rs->fields('downloads'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// autor1
		// institucija1
		// autor2
		// institucija2
		// autor3
		// institucija3
		// autor4
		// institucija4
		// autor5
		// institucija5
		// autor6
		// institucija6
		// autor7
		// institucija7
		// autor8
		// institucija8
		// autor9
		// institucija9
		// autor10
		// institucija10
		// naslov_eng
		// naslov_mne
		// sazetak_eng
		// sazetak_mne
		// keywords_eng
		// keywords_mne
		// tip
		// file
		// broj
		// str
		// hits
		// references
		// citation
		// doi
		// udk
		// scopus_id
		// lastip
		// lastdownloadip
		// downloads
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// autor1
		if ($this->autor1->VirtualValue <> "") {
			$this->autor1->ViewValue = $this->autor1->VirtualValue;
		} else {
			$this->autor1->ViewValue = $this->autor1->CurrentValue;
		if (strval($this->autor1->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor1->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor1, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor1->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor1->ViewValue = $this->autor1->CurrentValue;
			}
		} else {
			$this->autor1->ViewValue = NULL;
		}
		}
		$this->autor1->ViewCustomAttributes = "";

		// institucija1
		if ($this->institucija1->VirtualValue <> "") {
			$this->institucija1->ViewValue = $this->institucija1->VirtualValue;
		} else {
			$this->institucija1->ViewValue = $this->institucija1->CurrentValue;
		if (strval($this->institucija1->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija1->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija1, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija1->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija1->ViewValue = $this->institucija1->CurrentValue;
			}
		} else {
			$this->institucija1->ViewValue = NULL;
		}
		}
		$this->institucija1->ViewCustomAttributes = "";

		// autor2
		if ($this->autor2->VirtualValue <> "") {
			$this->autor2->ViewValue = $this->autor2->VirtualValue;
		} else {
			$this->autor2->ViewValue = $this->autor2->CurrentValue;
		if (strval($this->autor2->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor2->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor2->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor2->ViewValue = $this->autor2->CurrentValue;
			}
		} else {
			$this->autor2->ViewValue = NULL;
		}
		}
		$this->autor2->ViewCustomAttributes = "";

		// institucija2
		if ($this->institucija2->VirtualValue <> "") {
			$this->institucija2->ViewValue = $this->institucija2->VirtualValue;
		} else {
			$this->institucija2->ViewValue = $this->institucija2->CurrentValue;
		if (strval($this->institucija2->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija2->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija2, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija2->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija2->ViewValue = $this->institucija2->CurrentValue;
			}
		} else {
			$this->institucija2->ViewValue = NULL;
		}
		}
		$this->institucija2->ViewCustomAttributes = "";

		// autor3
		if ($this->autor3->VirtualValue <> "") {
			$this->autor3->ViewValue = $this->autor3->VirtualValue;
		} else {
			$this->autor3->ViewValue = $this->autor3->CurrentValue;
		if (strval($this->autor3->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor3->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor3->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor3->ViewValue = $this->autor3->CurrentValue;
			}
		} else {
			$this->autor3->ViewValue = NULL;
		}
		}
		$this->autor3->ViewCustomAttributes = "";

		// institucija3
		if ($this->institucija3->VirtualValue <> "") {
			$this->institucija3->ViewValue = $this->institucija3->VirtualValue;
		} else {
			$this->institucija3->ViewValue = $this->institucija3->CurrentValue;
		if (strval($this->institucija3->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija3->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija3, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija3->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija3->ViewValue = $this->institucija3->CurrentValue;
			}
		} else {
			$this->institucija3->ViewValue = NULL;
		}
		}
		$this->institucija3->ViewCustomAttributes = "";

		// autor4
		if ($this->autor4->VirtualValue <> "") {
			$this->autor4->ViewValue = $this->autor4->VirtualValue;
		} else {
			$this->autor4->ViewValue = $this->autor4->CurrentValue;
		if (strval($this->autor4->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor4->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor4, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor4->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor4->ViewValue = $this->autor4->CurrentValue;
			}
		} else {
			$this->autor4->ViewValue = NULL;
		}
		}
		$this->autor4->ViewCustomAttributes = "";

		// institucija4
		if ($this->institucija4->VirtualValue <> "") {
			$this->institucija4->ViewValue = $this->institucija4->VirtualValue;
		} else {
			$this->institucija4->ViewValue = $this->institucija4->CurrentValue;
		if (strval($this->institucija4->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija4->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija4, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija4->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija4->ViewValue = $this->institucija4->CurrentValue;
			}
		} else {
			$this->institucija4->ViewValue = NULL;
		}
		}
		$this->institucija4->ViewCustomAttributes = "";

		// autor5
		if ($this->autor5->VirtualValue <> "") {
			$this->autor5->ViewValue = $this->autor5->VirtualValue;
		} else {
			$this->autor5->ViewValue = $this->autor5->CurrentValue;
		if (strval($this->autor5->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor5->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor5->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor5->ViewValue = $this->autor5->CurrentValue;
			}
		} else {
			$this->autor5->ViewValue = NULL;
		}
		}
		$this->autor5->ViewCustomAttributes = "";

		// institucija5
		if ($this->institucija5->VirtualValue <> "") {
			$this->institucija5->ViewValue = $this->institucija5->VirtualValue;
		} else {
			$this->institucija5->ViewValue = $this->institucija5->CurrentValue;
		if (strval($this->institucija5->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija5->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija5, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija5->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija5->ViewValue = $this->institucija5->CurrentValue;
			}
		} else {
			$this->institucija5->ViewValue = NULL;
		}
		}
		$this->institucija5->ViewCustomAttributes = "";

		// autor6
		if ($this->autor6->VirtualValue <> "") {
			$this->autor6->ViewValue = $this->autor6->VirtualValue;
		} else {
			$this->autor6->ViewValue = $this->autor6->CurrentValue;
		if (strval($this->autor6->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor6->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor6, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor6->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor6->ViewValue = $this->autor6->CurrentValue;
			}
		} else {
			$this->autor6->ViewValue = NULL;
		}
		}
		$this->autor6->ViewCustomAttributes = "";

		// institucija6
		if ($this->institucija6->VirtualValue <> "") {
			$this->institucija6->ViewValue = $this->institucija6->VirtualValue;
		} else {
			$this->institucija6->ViewValue = $this->institucija6->CurrentValue;
		if (strval($this->institucija6->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija6->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija6, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija6->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija6->ViewValue = $this->institucija6->CurrentValue;
			}
		} else {
			$this->institucija6->ViewValue = NULL;
		}
		}
		$this->institucija6->ViewCustomAttributes = "";

		// autor7
		if ($this->autor7->VirtualValue <> "") {
			$this->autor7->ViewValue = $this->autor7->VirtualValue;
		} else {
			$this->autor7->ViewValue = $this->autor7->CurrentValue;
		if (strval($this->autor7->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor7->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor7, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor7->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor7->ViewValue = $this->autor7->CurrentValue;
			}
		} else {
			$this->autor7->ViewValue = NULL;
		}
		}
		$this->autor7->ViewCustomAttributes = "";

		// institucija7
		if ($this->institucija7->VirtualValue <> "") {
			$this->institucija7->ViewValue = $this->institucija7->VirtualValue;
		} else {
			$this->institucija7->ViewValue = $this->institucija7->CurrentValue;
		if (strval($this->institucija7->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija7->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija7, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija7->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija7->ViewValue = $this->institucija7->CurrentValue;
			}
		} else {
			$this->institucija7->ViewValue = NULL;
		}
		}
		$this->institucija7->ViewCustomAttributes = "";

		// autor8
		if ($this->autor8->VirtualValue <> "") {
			$this->autor8->ViewValue = $this->autor8->VirtualValue;
		} else {
			$this->autor8->ViewValue = $this->autor8->CurrentValue;
		if (strval($this->autor8->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor8->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor8, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor8->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor8->ViewValue = $this->autor8->CurrentValue;
			}
		} else {
			$this->autor8->ViewValue = NULL;
		}
		}
		$this->autor8->ViewCustomAttributes = "";

		// institucija8
		if ($this->institucija8->VirtualValue <> "") {
			$this->institucija8->ViewValue = $this->institucija8->VirtualValue;
		} else {
			$this->institucija8->ViewValue = $this->institucija8->CurrentValue;
		if (strval($this->institucija8->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija8->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija8, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija8->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija8->ViewValue = $this->institucija8->CurrentValue;
			}
		} else {
			$this->institucija8->ViewValue = NULL;
		}
		}
		$this->institucija8->ViewCustomAttributes = "";

		// autor9
		if ($this->autor9->VirtualValue <> "") {
			$this->autor9->ViewValue = $this->autor9->VirtualValue;
		} else {
			$this->autor9->ViewValue = $this->autor9->CurrentValue;
		if (strval($this->autor9->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor9->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor9, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor9->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor9->ViewValue = $this->autor9->CurrentValue;
			}
		} else {
			$this->autor9->ViewValue = NULL;
		}
		}
		$this->autor9->ViewCustomAttributes = "";

		// institucija9
		if ($this->institucija9->VirtualValue <> "") {
			$this->institucija9->ViewValue = $this->institucija9->VirtualValue;
		} else {
			$this->institucija9->ViewValue = $this->institucija9->CurrentValue;
		if (strval($this->institucija9->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija9->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija9, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija9->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija9->ViewValue = $this->institucija9->CurrentValue;
			}
		} else {
			$this->institucija9->ViewValue = NULL;
		}
		}
		$this->institucija9->ViewCustomAttributes = "";

		// autor10
		if ($this->autor10->VirtualValue <> "") {
			$this->autor10->ViewValue = $this->autor10->VirtualValue;
		} else {
			$this->autor10->ViewValue = $this->autor10->CurrentValue;
		if (strval($this->autor10->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->autor10->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->autor10, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `autor_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->autor10->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->autor10->ViewValue = $this->autor10->CurrentValue;
			}
		} else {
			$this->autor10->ViewValue = NULL;
		}
		}
		$this->autor10->ViewCustomAttributes = "";

		// institucija10
		if ($this->institucija10->VirtualValue <> "") {
			$this->institucija10->ViewValue = $this->institucija10->VirtualValue;
		} else {
			$this->institucija10->ViewValue = $this->institucija10->CurrentValue;
		if (strval($this->institucija10->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->institucija10->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->institucija10, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institucija10->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->institucija10->ViewValue = $this->institucija10->CurrentValue;
			}
		} else {
			$this->institucija10->ViewValue = NULL;
		}
		}
		$this->institucija10->ViewCustomAttributes = "";

		// naslov_eng
		$this->naslov_eng->ViewValue = $this->naslov_eng->CurrentValue;
		$this->naslov_eng->ViewCustomAttributes = "";

		// naslov_mne
		$this->naslov_mne->ViewValue = $this->naslov_mne->CurrentValue;
		$this->naslov_mne->ViewCustomAttributes = "";

		// sazetak_eng
		$this->sazetak_eng->ViewValue = $this->sazetak_eng->CurrentValue;
		$this->sazetak_eng->ViewCustomAttributes = "";

		// sazetak_mne
		$this->sazetak_mne->ViewValue = $this->sazetak_mne->CurrentValue;
		$this->sazetak_mne->ViewCustomAttributes = "";

		// keywords_eng
		$this->keywords_eng->ViewValue = $this->keywords_eng->CurrentValue;
		$this->keywords_eng->ViewCustomAttributes = "";

		// keywords_mne
		$this->keywords_mne->ViewValue = $this->keywords_mne->CurrentValue;
		$this->keywords_mne->ViewCustomAttributes = "";

		// tip
		$this->tip->ViewValue = $this->tip->CurrentValue;
		$this->tip->ViewCustomAttributes = "";

		// file
		$this->file->ViewValue = $this->file->CurrentValue;
		$this->file->ViewCustomAttributes = "";

		// broj
		$this->broj->ViewValue = $this->broj->CurrentValue;
		$this->broj->ViewCustomAttributes = "";

		// str
		$this->str->ViewValue = $this->str->CurrentValue;
		$this->str->ViewCustomAttributes = "";

		// hits
		$this->hits->ViewValue = $this->hits->CurrentValue;
		$this->hits->ViewCustomAttributes = "";

		// references
		$this->references->ViewValue = $this->references->CurrentValue;
		$this->references->ViewCustomAttributes = "";

		// citation
		$this->citation->ViewValue = $this->citation->CurrentValue;
		$this->citation->ViewCustomAttributes = "";

		// doi
		$this->doi->ViewValue = $this->doi->CurrentValue;
		$this->doi->ViewCustomAttributes = "";

		// udk
		$this->udk->ViewValue = $this->udk->CurrentValue;
		$this->udk->ViewCustomAttributes = "";

		// scopus_id
		$this->scopus_id->ViewValue = $this->scopus_id->CurrentValue;
		$this->scopus_id->ViewCustomAttributes = "";

		// lastip
		$this->lastip->ViewValue = $this->lastip->CurrentValue;
		$this->lastip->ViewCustomAttributes = "";

		// lastdownloadip
		$this->lastdownloadip->ViewValue = $this->lastdownloadip->CurrentValue;
		$this->lastdownloadip->ViewCustomAttributes = "";

		// downloads
		$this->downloads->ViewValue = $this->downloads->CurrentValue;
		$this->downloads->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// autor1
		$this->autor1->LinkCustomAttributes = "";
		$this->autor1->HrefValue = "";
		$this->autor1->TooltipValue = "";

		// institucija1
		$this->institucija1->LinkCustomAttributes = "";
		$this->institucija1->HrefValue = "";
		$this->institucija1->TooltipValue = "";

		// autor2
		$this->autor2->LinkCustomAttributes = "";
		$this->autor2->HrefValue = "";
		$this->autor2->TooltipValue = "";

		// institucija2
		$this->institucija2->LinkCustomAttributes = "";
		$this->institucija2->HrefValue = "";
		$this->institucija2->TooltipValue = "";

		// autor3
		$this->autor3->LinkCustomAttributes = "";
		$this->autor3->HrefValue = "";
		$this->autor3->TooltipValue = "";

		// institucija3
		$this->institucija3->LinkCustomAttributes = "";
		$this->institucija3->HrefValue = "";
		$this->institucija3->TooltipValue = "";

		// autor4
		$this->autor4->LinkCustomAttributes = "";
		$this->autor4->HrefValue = "";
		$this->autor4->TooltipValue = "";

		// institucija4
		$this->institucija4->LinkCustomAttributes = "";
		$this->institucija4->HrefValue = "";
		$this->institucija4->TooltipValue = "";

		// autor5
		$this->autor5->LinkCustomAttributes = "";
		$this->autor5->HrefValue = "";
		$this->autor5->TooltipValue = "";

		// institucija5
		$this->institucija5->LinkCustomAttributes = "";
		$this->institucija5->HrefValue = "";
		$this->institucija5->TooltipValue = "";

		// autor6
		$this->autor6->LinkCustomAttributes = "";
		$this->autor6->HrefValue = "";
		$this->autor6->TooltipValue = "";

		// institucija6
		$this->institucija6->LinkCustomAttributes = "";
		$this->institucija6->HrefValue = "";
		$this->institucija6->TooltipValue = "";

		// autor7
		$this->autor7->LinkCustomAttributes = "";
		$this->autor7->HrefValue = "";
		$this->autor7->TooltipValue = "";

		// institucija7
		$this->institucija7->LinkCustomAttributes = "";
		$this->institucija7->HrefValue = "";
		$this->institucija7->TooltipValue = "";

		// autor8
		$this->autor8->LinkCustomAttributes = "";
		$this->autor8->HrefValue = "";
		$this->autor8->TooltipValue = "";

		// institucija8
		$this->institucija8->LinkCustomAttributes = "";
		$this->institucija8->HrefValue = "";
		$this->institucija8->TooltipValue = "";

		// autor9
		$this->autor9->LinkCustomAttributes = "";
		$this->autor9->HrefValue = "";
		$this->autor9->TooltipValue = "";

		// institucija9
		$this->institucija9->LinkCustomAttributes = "";
		$this->institucija9->HrefValue = "";
		$this->institucija9->TooltipValue = "";

		// autor10
		$this->autor10->LinkCustomAttributes = "";
		$this->autor10->HrefValue = "";
		$this->autor10->TooltipValue = "";

		// institucija10
		$this->institucija10->LinkCustomAttributes = "";
		$this->institucija10->HrefValue = "";
		$this->institucija10->TooltipValue = "";

		// naslov_eng
		$this->naslov_eng->LinkCustomAttributes = "";
		$this->naslov_eng->HrefValue = "";
		$this->naslov_eng->TooltipValue = "";

		// naslov_mne
		$this->naslov_mne->LinkCustomAttributes = "";
		$this->naslov_mne->HrefValue = "";
		$this->naslov_mne->TooltipValue = "";

		// sazetak_eng
		$this->sazetak_eng->LinkCustomAttributes = "";
		$this->sazetak_eng->HrefValue = "";
		$this->sazetak_eng->TooltipValue = "";

		// sazetak_mne
		$this->sazetak_mne->LinkCustomAttributes = "";
		$this->sazetak_mne->HrefValue = "";
		$this->sazetak_mne->TooltipValue = "";

		// keywords_eng
		$this->keywords_eng->LinkCustomAttributes = "";
		$this->keywords_eng->HrefValue = "";
		$this->keywords_eng->TooltipValue = "";

		// keywords_mne
		$this->keywords_mne->LinkCustomAttributes = "";
		$this->keywords_mne->HrefValue = "";
		$this->keywords_mne->TooltipValue = "";

		// tip
		$this->tip->LinkCustomAttributes = "";
		$this->tip->HrefValue = "";
		$this->tip->TooltipValue = "";

		// file
		$this->file->LinkCustomAttributes = "";
		$this->file->HrefValue = "";
		$this->file->TooltipValue = "";

		// broj
		$this->broj->LinkCustomAttributes = "";
		$this->broj->HrefValue = "";
		$this->broj->TooltipValue = "";

		// str
		$this->str->LinkCustomAttributes = "";
		$this->str->HrefValue = "";
		$this->str->TooltipValue = "";

		// hits
		$this->hits->LinkCustomAttributes = "";
		$this->hits->HrefValue = "";
		$this->hits->TooltipValue = "";

		// references
		$this->references->LinkCustomAttributes = "";
		$this->references->HrefValue = "";
		$this->references->TooltipValue = "";

		// citation
		$this->citation->LinkCustomAttributes = "";
		$this->citation->HrefValue = "";
		$this->citation->TooltipValue = "";

		// doi
		$this->doi->LinkCustomAttributes = "";
		$this->doi->HrefValue = "";
		$this->doi->TooltipValue = "";

		// udk
		$this->udk->LinkCustomAttributes = "";
		$this->udk->HrefValue = "";
		$this->udk->TooltipValue = "";

		// scopus_id
		$this->scopus_id->LinkCustomAttributes = "";
		$this->scopus_id->HrefValue = "";
		$this->scopus_id->TooltipValue = "";

		// lastip
		$this->lastip->LinkCustomAttributes = "";
		$this->lastip->HrefValue = "";
		$this->lastip->TooltipValue = "";

		// lastdownloadip
		$this->lastdownloadip->LinkCustomAttributes = "";
		$this->lastdownloadip->HrefValue = "";
		$this->lastdownloadip->TooltipValue = "";

		// downloads
		$this->downloads->LinkCustomAttributes = "";
		$this->downloads->HrefValue = "";
		$this->downloads->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->autor1->Exportable) $Doc->ExportCaption($this->autor1);
				if ($this->institucija1->Exportable) $Doc->ExportCaption($this->institucija1);
				if ($this->autor2->Exportable) $Doc->ExportCaption($this->autor2);
				if ($this->institucija2->Exportable) $Doc->ExportCaption($this->institucija2);
				if ($this->autor3->Exportable) $Doc->ExportCaption($this->autor3);
				if ($this->institucija3->Exportable) $Doc->ExportCaption($this->institucija3);
				if ($this->autor4->Exportable) $Doc->ExportCaption($this->autor4);
				if ($this->institucija4->Exportable) $Doc->ExportCaption($this->institucija4);
				if ($this->autor5->Exportable) $Doc->ExportCaption($this->autor5);
				if ($this->institucija5->Exportable) $Doc->ExportCaption($this->institucija5);
				if ($this->autor6->Exportable) $Doc->ExportCaption($this->autor6);
				if ($this->institucija6->Exportable) $Doc->ExportCaption($this->institucija6);
				if ($this->autor7->Exportable) $Doc->ExportCaption($this->autor7);
				if ($this->institucija7->Exportable) $Doc->ExportCaption($this->institucija7);
				if ($this->autor8->Exportable) $Doc->ExportCaption($this->autor8);
				if ($this->institucija8->Exportable) $Doc->ExportCaption($this->institucija8);
				if ($this->autor9->Exportable) $Doc->ExportCaption($this->autor9);
				if ($this->institucija9->Exportable) $Doc->ExportCaption($this->institucija9);
				if ($this->autor10->Exportable) $Doc->ExportCaption($this->autor10);
				if ($this->institucija10->Exportable) $Doc->ExportCaption($this->institucija10);
				if ($this->naslov_eng->Exportable) $Doc->ExportCaption($this->naslov_eng);
				if ($this->naslov_mne->Exportable) $Doc->ExportCaption($this->naslov_mne);
				if ($this->sazetak_eng->Exportable) $Doc->ExportCaption($this->sazetak_eng);
				if ($this->sazetak_mne->Exportable) $Doc->ExportCaption($this->sazetak_mne);
				if ($this->keywords_eng->Exportable) $Doc->ExportCaption($this->keywords_eng);
				if ($this->keywords_mne->Exportable) $Doc->ExportCaption($this->keywords_mne);
				if ($this->tip->Exportable) $Doc->ExportCaption($this->tip);
				if ($this->file->Exportable) $Doc->ExportCaption($this->file);
				if ($this->broj->Exportable) $Doc->ExportCaption($this->broj);
				if ($this->str->Exportable) $Doc->ExportCaption($this->str);
				if ($this->hits->Exportable) $Doc->ExportCaption($this->hits);
				if ($this->references->Exportable) $Doc->ExportCaption($this->references);
				if ($this->citation->Exportable) $Doc->ExportCaption($this->citation);
				if ($this->doi->Exportable) $Doc->ExportCaption($this->doi);
				if ($this->udk->Exportable) $Doc->ExportCaption($this->udk);
				if ($this->scopus_id->Exportable) $Doc->ExportCaption($this->scopus_id);
				if ($this->lastip->Exportable) $Doc->ExportCaption($this->lastip);
				if ($this->lastdownloadip->Exportable) $Doc->ExportCaption($this->lastdownloadip);
				if ($this->downloads->Exportable) $Doc->ExportCaption($this->downloads);
			} else {
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->autor1->Exportable) $Doc->ExportCaption($this->autor1);
				if ($this->institucija1->Exportable) $Doc->ExportCaption($this->institucija1);
				if ($this->autor2->Exportable) $Doc->ExportCaption($this->autor2);
				if ($this->institucija2->Exportable) $Doc->ExportCaption($this->institucija2);
				if ($this->autor3->Exportable) $Doc->ExportCaption($this->autor3);
				if ($this->institucija3->Exportable) $Doc->ExportCaption($this->institucija3);
				if ($this->autor4->Exportable) $Doc->ExportCaption($this->autor4);
				if ($this->institucija4->Exportable) $Doc->ExportCaption($this->institucija4);
				if ($this->autor5->Exportable) $Doc->ExportCaption($this->autor5);
				if ($this->institucija5->Exportable) $Doc->ExportCaption($this->institucija5);
				if ($this->autor6->Exportable) $Doc->ExportCaption($this->autor6);
				if ($this->institucija6->Exportable) $Doc->ExportCaption($this->institucija6);
				if ($this->autor7->Exportable) $Doc->ExportCaption($this->autor7);
				if ($this->institucija7->Exportable) $Doc->ExportCaption($this->institucija7);
				if ($this->autor8->Exportable) $Doc->ExportCaption($this->autor8);
				if ($this->institucija8->Exportable) $Doc->ExportCaption($this->institucija8);
				if ($this->autor9->Exportable) $Doc->ExportCaption($this->autor9);
				if ($this->institucija9->Exportable) $Doc->ExportCaption($this->institucija9);
				if ($this->autor10->Exportable) $Doc->ExportCaption($this->autor10);
				if ($this->institucija10->Exportable) $Doc->ExportCaption($this->institucija10);
				if ($this->naslov_eng->Exportable) $Doc->ExportCaption($this->naslov_eng);
				if ($this->naslov_mne->Exportable) $Doc->ExportCaption($this->naslov_mne);
				if ($this->sazetak_eng->Exportable) $Doc->ExportCaption($this->sazetak_eng);
				if ($this->sazetak_mne->Exportable) $Doc->ExportCaption($this->sazetak_mne);
				if ($this->keywords_eng->Exportable) $Doc->ExportCaption($this->keywords_eng);
				if ($this->keywords_mne->Exportable) $Doc->ExportCaption($this->keywords_mne);
				if ($this->tip->Exportable) $Doc->ExportCaption($this->tip);
				if ($this->file->Exportable) $Doc->ExportCaption($this->file);
				if ($this->broj->Exportable) $Doc->ExportCaption($this->broj);
				if ($this->str->Exportable) $Doc->ExportCaption($this->str);
				if ($this->hits->Exportable) $Doc->ExportCaption($this->hits);
				if ($this->references->Exportable) $Doc->ExportCaption($this->references);
				if ($this->doi->Exportable) $Doc->ExportCaption($this->doi);
				if ($this->udk->Exportable) $Doc->ExportCaption($this->udk);
				if ($this->scopus_id->Exportable) $Doc->ExportCaption($this->scopus_id);
				if ($this->lastip->Exportable) $Doc->ExportCaption($this->lastip);
				if ($this->lastdownloadip->Exportable) $Doc->ExportCaption($this->lastdownloadip);
				if ($this->downloads->Exportable) $Doc->ExportCaption($this->downloads);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->autor1->Exportable) $Doc->ExportField($this->autor1);
					if ($this->institucija1->Exportable) $Doc->ExportField($this->institucija1);
					if ($this->autor2->Exportable) $Doc->ExportField($this->autor2);
					if ($this->institucija2->Exportable) $Doc->ExportField($this->institucija2);
					if ($this->autor3->Exportable) $Doc->ExportField($this->autor3);
					if ($this->institucija3->Exportable) $Doc->ExportField($this->institucija3);
					if ($this->autor4->Exportable) $Doc->ExportField($this->autor4);
					if ($this->institucija4->Exportable) $Doc->ExportField($this->institucija4);
					if ($this->autor5->Exportable) $Doc->ExportField($this->autor5);
					if ($this->institucija5->Exportable) $Doc->ExportField($this->institucija5);
					if ($this->autor6->Exportable) $Doc->ExportField($this->autor6);
					if ($this->institucija6->Exportable) $Doc->ExportField($this->institucija6);
					if ($this->autor7->Exportable) $Doc->ExportField($this->autor7);
					if ($this->institucija7->Exportable) $Doc->ExportField($this->institucija7);
					if ($this->autor8->Exportable) $Doc->ExportField($this->autor8);
					if ($this->institucija8->Exportable) $Doc->ExportField($this->institucija8);
					if ($this->autor9->Exportable) $Doc->ExportField($this->autor9);
					if ($this->institucija9->Exportable) $Doc->ExportField($this->institucija9);
					if ($this->autor10->Exportable) $Doc->ExportField($this->autor10);
					if ($this->institucija10->Exportable) $Doc->ExportField($this->institucija10);
					if ($this->naslov_eng->Exportable) $Doc->ExportField($this->naslov_eng);
					if ($this->naslov_mne->Exportable) $Doc->ExportField($this->naslov_mne);
					if ($this->sazetak_eng->Exportable) $Doc->ExportField($this->sazetak_eng);
					if ($this->sazetak_mne->Exportable) $Doc->ExportField($this->sazetak_mne);
					if ($this->keywords_eng->Exportable) $Doc->ExportField($this->keywords_eng);
					if ($this->keywords_mne->Exportable) $Doc->ExportField($this->keywords_mne);
					if ($this->tip->Exportable) $Doc->ExportField($this->tip);
					if ($this->file->Exportable) $Doc->ExportField($this->file);
					if ($this->broj->Exportable) $Doc->ExportField($this->broj);
					if ($this->str->Exportable) $Doc->ExportField($this->str);
					if ($this->hits->Exportable) $Doc->ExportField($this->hits);
					if ($this->references->Exportable) $Doc->ExportField($this->references);
					if ($this->citation->Exportable) $Doc->ExportField($this->citation);
					if ($this->doi->Exportable) $Doc->ExportField($this->doi);
					if ($this->udk->Exportable) $Doc->ExportField($this->udk);
					if ($this->scopus_id->Exportable) $Doc->ExportField($this->scopus_id);
					if ($this->lastip->Exportable) $Doc->ExportField($this->lastip);
					if ($this->lastdownloadip->Exportable) $Doc->ExportField($this->lastdownloadip);
					if ($this->downloads->Exportable) $Doc->ExportField($this->downloads);
				} else {
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->autor1->Exportable) $Doc->ExportField($this->autor1);
					if ($this->institucija1->Exportable) $Doc->ExportField($this->institucija1);
					if ($this->autor2->Exportable) $Doc->ExportField($this->autor2);
					if ($this->institucija2->Exportable) $Doc->ExportField($this->institucija2);
					if ($this->autor3->Exportable) $Doc->ExportField($this->autor3);
					if ($this->institucija3->Exportable) $Doc->ExportField($this->institucija3);
					if ($this->autor4->Exportable) $Doc->ExportField($this->autor4);
					if ($this->institucija4->Exportable) $Doc->ExportField($this->institucija4);
					if ($this->autor5->Exportable) $Doc->ExportField($this->autor5);
					if ($this->institucija5->Exportable) $Doc->ExportField($this->institucija5);
					if ($this->autor6->Exportable) $Doc->ExportField($this->autor6);
					if ($this->institucija6->Exportable) $Doc->ExportField($this->institucija6);
					if ($this->autor7->Exportable) $Doc->ExportField($this->autor7);
					if ($this->institucija7->Exportable) $Doc->ExportField($this->institucija7);
					if ($this->autor8->Exportable) $Doc->ExportField($this->autor8);
					if ($this->institucija8->Exportable) $Doc->ExportField($this->institucija8);
					if ($this->autor9->Exportable) $Doc->ExportField($this->autor9);
					if ($this->institucija9->Exportable) $Doc->ExportField($this->institucija9);
					if ($this->autor10->Exportable) $Doc->ExportField($this->autor10);
					if ($this->institucija10->Exportable) $Doc->ExportField($this->institucija10);
					if ($this->naslov_eng->Exportable) $Doc->ExportField($this->naslov_eng);
					if ($this->naslov_mne->Exportable) $Doc->ExportField($this->naslov_mne);
					if ($this->sazetak_eng->Exportable) $Doc->ExportField($this->sazetak_eng);
					if ($this->sazetak_mne->Exportable) $Doc->ExportField($this->sazetak_mne);
					if ($this->keywords_eng->Exportable) $Doc->ExportField($this->keywords_eng);
					if ($this->keywords_mne->Exportable) $Doc->ExportField($this->keywords_mne);
					if ($this->tip->Exportable) $Doc->ExportField($this->tip);
					if ($this->file->Exportable) $Doc->ExportField($this->file);
					if ($this->broj->Exportable) $Doc->ExportField($this->broj);
					if ($this->str->Exportable) $Doc->ExportField($this->str);
					if ($this->hits->Exportable) $Doc->ExportField($this->hits);
					if ($this->references->Exportable) $Doc->ExportField($this->references);
					if ($this->doi->Exportable) $Doc->ExportField($this->doi);
					if ($this->udk->Exportable) $Doc->ExportField($this->udk);
					if ($this->scopus_id->Exportable) $Doc->ExportField($this->scopus_id);
					if ($this->lastip->Exportable) $Doc->ExportField($this->lastip);
					if ($this->lastdownloadip->Exportable) $Doc->ExportField($this->lastdownloadip);
					if ($this->downloads->Exportable) $Doc->ExportField($this->downloads);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
