<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "clanciinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$clanci_delete = NULL; // Initialize page object first

class cclanci_delete extends cclanci {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{3D7A0815-42DE-4706-A688-045439C7A049}";

	// Table name
	var $TableName = 'clanci';

	// Page object name
	var $PageObjName = 'clanci_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (clanci)
		if (!isset($GLOBALS["clanci"])) {
			$GLOBALS["clanci"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["clanci"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'clanci', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("clancilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in clanci class, clanciinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->autor1->setDbValue($rs->fields('autor1'));
		if (array_key_exists('EV__autor1', $rs->fields)) {
			$this->autor1->VirtualValue = $rs->fields('EV__autor1'); // Set up virtual field value
		} else {
			$this->autor1->VirtualValue = ""; // Clear value
		}
		$this->institucija1->setDbValue($rs->fields('institucija1'));
		if (array_key_exists('EV__institucija1', $rs->fields)) {
			$this->institucija1->VirtualValue = $rs->fields('EV__institucija1'); // Set up virtual field value
		} else {
			$this->institucija1->VirtualValue = ""; // Clear value
		}
		$this->autor2->setDbValue($rs->fields('autor2'));
		if (array_key_exists('EV__autor2', $rs->fields)) {
			$this->autor2->VirtualValue = $rs->fields('EV__autor2'); // Set up virtual field value
		} else {
			$this->autor2->VirtualValue = ""; // Clear value
		}
		$this->institucija2->setDbValue($rs->fields('institucija2'));
		if (array_key_exists('EV__institucija2', $rs->fields)) {
			$this->institucija2->VirtualValue = $rs->fields('EV__institucija2'); // Set up virtual field value
		} else {
			$this->institucija2->VirtualValue = ""; // Clear value
		}
		$this->autor3->setDbValue($rs->fields('autor3'));
		if (array_key_exists('EV__autor3', $rs->fields)) {
			$this->autor3->VirtualValue = $rs->fields('EV__autor3'); // Set up virtual field value
		} else {
			$this->autor3->VirtualValue = ""; // Clear value
		}
		$this->institucija3->setDbValue($rs->fields('institucija3'));
		if (array_key_exists('EV__institucija3', $rs->fields)) {
			$this->institucija3->VirtualValue = $rs->fields('EV__institucija3'); // Set up virtual field value
		} else {
			$this->institucija3->VirtualValue = ""; // Clear value
		}
		$this->autor4->setDbValue($rs->fields('autor4'));
		if (array_key_exists('EV__autor4', $rs->fields)) {
			$this->autor4->VirtualValue = $rs->fields('EV__autor4'); // Set up virtual field value
		} else {
			$this->autor4->VirtualValue = ""; // Clear value
		}
		$this->institucija4->setDbValue($rs->fields('institucija4'));
		if (array_key_exists('EV__institucija4', $rs->fields)) {
			$this->institucija4->VirtualValue = $rs->fields('EV__institucija4'); // Set up virtual field value
		} else {
			$this->institucija4->VirtualValue = ""; // Clear value
		}
		$this->autor5->setDbValue($rs->fields('autor5'));
		if (array_key_exists('EV__autor5', $rs->fields)) {
			$this->autor5->VirtualValue = $rs->fields('EV__autor5'); // Set up virtual field value
		} else {
			$this->autor5->VirtualValue = ""; // Clear value
		}
		$this->institucija5->setDbValue($rs->fields('institucija5'));
		if (array_key_exists('EV__institucija5', $rs->fields)) {
			$this->institucija5->VirtualValue = $rs->fields('EV__institucija5'); // Set up virtual field value
		} else {
			$this->institucija5->VirtualValue = ""; // Clear value
		}
		$this->autor6->setDbValue($rs->fields('autor6'));
		if (array_key_exists('EV__autor6', $rs->fields)) {
			$this->autor6->VirtualValue = $rs->fields('EV__autor6'); // Set up virtual field value
		} else {
			$this->autor6->VirtualValue = ""; // Clear value
		}
		$this->institucija6->setDbValue($rs->fields('institucija6'));
		if (array_key_exists('EV__institucija6', $rs->fields)) {
			$this->institucija6->VirtualValue = $rs->fields('EV__institucija6'); // Set up virtual field value
		} else {
			$this->institucija6->VirtualValue = ""; // Clear value
		}
		$this->autor7->setDbValue($rs->fields('autor7'));
		if (array_key_exists('EV__autor7', $rs->fields)) {
			$this->autor7->VirtualValue = $rs->fields('EV__autor7'); // Set up virtual field value
		} else {
			$this->autor7->VirtualValue = ""; // Clear value
		}
		$this->institucija7->setDbValue($rs->fields('institucija7'));
		if (array_key_exists('EV__institucija7', $rs->fields)) {
			$this->institucija7->VirtualValue = $rs->fields('EV__institucija7'); // Set up virtual field value
		} else {
			$this->institucija7->VirtualValue = ""; // Clear value
		}
		$this->autor8->setDbValue($rs->fields('autor8'));
		if (array_key_exists('EV__autor8', $rs->fields)) {
			$this->autor8->VirtualValue = $rs->fields('EV__autor8'); // Set up virtual field value
		} else {
			$this->autor8->VirtualValue = ""; // Clear value
		}
		$this->institucija8->setDbValue($rs->fields('institucija8'));
		if (array_key_exists('EV__institucija8', $rs->fields)) {
			$this->institucija8->VirtualValue = $rs->fields('EV__institucija8'); // Set up virtual field value
		} else {
			$this->institucija8->VirtualValue = ""; // Clear value
		}
		$this->autor9->setDbValue($rs->fields('autor9'));
		if (array_key_exists('EV__autor9', $rs->fields)) {
			$this->autor9->VirtualValue = $rs->fields('EV__autor9'); // Set up virtual field value
		} else {
			$this->autor9->VirtualValue = ""; // Clear value
		}
		$this->institucija9->setDbValue($rs->fields('institucija9'));
		if (array_key_exists('EV__institucija9', $rs->fields)) {
			$this->institucija9->VirtualValue = $rs->fields('EV__institucija9'); // Set up virtual field value
		} else {
			$this->institucija9->VirtualValue = ""; // Clear value
		}
		$this->autor10->setDbValue($rs->fields('autor10'));
		if (array_key_exists('EV__autor10', $rs->fields)) {
			$this->autor10->VirtualValue = $rs->fields('EV__autor10'); // Set up virtual field value
		} else {
			$this->autor10->VirtualValue = ""; // Clear value
		}
		$this->institucija10->setDbValue($rs->fields('institucija10'));
		if (array_key_exists('EV__institucija10', $rs->fields)) {
			$this->institucija10->VirtualValue = $rs->fields('EV__institucija10'); // Set up virtual field value
		} else {
			$this->institucija10->VirtualValue = ""; // Clear value
		}
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->autor1->DbValue = $row['autor1'];
		$this->institucija1->DbValue = $row['institucija1'];
		$this->autor2->DbValue = $row['autor2'];
		$this->institucija2->DbValue = $row['institucija2'];
		$this->autor3->DbValue = $row['autor3'];
		$this->institucija3->DbValue = $row['institucija3'];
		$this->autor4->DbValue = $row['autor4'];
		$this->institucija4->DbValue = $row['institucija4'];
		$this->autor5->DbValue = $row['autor5'];
		$this->institucija5->DbValue = $row['institucija5'];
		$this->autor6->DbValue = $row['autor6'];
		$this->institucija6->DbValue = $row['institucija6'];
		$this->autor7->DbValue = $row['autor7'];
		$this->institucija7->DbValue = $row['institucija7'];
		$this->autor8->DbValue = $row['autor8'];
		$this->institucija8->DbValue = $row['institucija8'];
		$this->autor9->DbValue = $row['autor9'];
		$this->institucija9->DbValue = $row['institucija9'];
		$this->autor10->DbValue = $row['autor10'];
		$this->institucija10->DbValue = $row['institucija10'];
		$this->naslov_eng->DbValue = $row['naslov_eng'];
		$this->naslov_mne->DbValue = $row['naslov_mne'];
		$this->sazetak_eng->DbValue = $row['sazetak_eng'];
		$this->sazetak_mne->DbValue = $row['sazetak_mne'];
		$this->keywords_eng->DbValue = $row['keywords_eng'];
		$this->keywords_mne->DbValue = $row['keywords_mne'];
		$this->tip->DbValue = $row['tip'];
		$this->file->DbValue = $row['file'];
		$this->broj->DbValue = $row['broj'];
		$this->str->DbValue = $row['str'];
		$this->hits->DbValue = $row['hits'];
		$this->references->DbValue = $row['references'];
		$this->citation->DbValue = $row['citation'];
		$this->doi->DbValue = $row['doi'];
		$this->udk->DbValue = $row['udk'];
		$this->scopus_id->DbValue = $row['scopus_id'];
		$this->lastip->DbValue = $row['lastip'];
		$this->lastdownloadip->DbValue = $row['lastdownloadip'];
		$this->downloads->DbValue = $row['downloads'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "clancilist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($clanci_delete)) $clanci_delete = new cclanci_delete();

// Page init
$clanci_delete->Page_Init();

// Page main
$clanci_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clanci_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var clanci_delete = new ew_Page("clanci_delete");
clanci_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = clanci_delete.PageID; // For backward compatibility

// Form object
var fclancidelete = new ew_Form("fclancidelete");

// Form_CustomValidate event
fclancidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclancidelete.ValidateRequired = true;
<?php } else { ?>
fclancidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclancidelete.Lists["x_autor1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_autor10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancidelete.Lists["x_institucija10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($clanci_delete->Recordset = $clanci_delete->LoadRecordset())
	$clanci_deleteTotalRecs = $clanci_delete->Recordset->RecordCount(); // Get record count
if ($clanci_deleteTotalRecs <= 0) { // No record found, exit
	if ($clanci_delete->Recordset)
		$clanci_delete->Recordset->Close();
	$clanci_delete->Page_Terminate("clancilist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $clanci_delete->ShowPageHeader(); ?>
<?php
$clanci_delete->ShowMessage();
?>
<form name="fclancidelete" id="fclancidelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="clanci">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($clanci_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_clancidelete" class="ewTable ewTableSeparate">
<?php echo $clanci->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($clanci->id->Visible) { // id ?>
		<td><span id="elh_clanci_id" class="clanci_id"><?php echo $clanci->id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor1->Visible) { // autor1 ?>
		<td><span id="elh_clanci_autor1" class="clanci_autor1"><?php echo $clanci->autor1->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
		<td><span id="elh_clanci_institucija1" class="clanci_institucija1"><?php echo $clanci->institucija1->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor2->Visible) { // autor2 ?>
		<td><span id="elh_clanci_autor2" class="clanci_autor2"><?php echo $clanci->autor2->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
		<td><span id="elh_clanci_institucija2" class="clanci_institucija2"><?php echo $clanci->institucija2->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor3->Visible) { // autor3 ?>
		<td><span id="elh_clanci_autor3" class="clanci_autor3"><?php echo $clanci->autor3->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
		<td><span id="elh_clanci_institucija3" class="clanci_institucija3"><?php echo $clanci->institucija3->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor4->Visible) { // autor4 ?>
		<td><span id="elh_clanci_autor4" class="clanci_autor4"><?php echo $clanci->autor4->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
		<td><span id="elh_clanci_institucija4" class="clanci_institucija4"><?php echo $clanci->institucija4->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor5->Visible) { // autor5 ?>
		<td><span id="elh_clanci_autor5" class="clanci_autor5"><?php echo $clanci->autor5->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
		<td><span id="elh_clanci_institucija5" class="clanci_institucija5"><?php echo $clanci->institucija5->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor6->Visible) { // autor6 ?>
		<td><span id="elh_clanci_autor6" class="clanci_autor6"><?php echo $clanci->autor6->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
		<td><span id="elh_clanci_institucija6" class="clanci_institucija6"><?php echo $clanci->institucija6->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor7->Visible) { // autor7 ?>
		<td><span id="elh_clanci_autor7" class="clanci_autor7"><?php echo $clanci->autor7->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
		<td><span id="elh_clanci_institucija7" class="clanci_institucija7"><?php echo $clanci->institucija7->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor8->Visible) { // autor8 ?>
		<td><span id="elh_clanci_autor8" class="clanci_autor8"><?php echo $clanci->autor8->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
		<td><span id="elh_clanci_institucija8" class="clanci_institucija8"><?php echo $clanci->institucija8->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor9->Visible) { // autor9 ?>
		<td><span id="elh_clanci_autor9" class="clanci_autor9"><?php echo $clanci->autor9->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
		<td><span id="elh_clanci_institucija9" class="clanci_institucija9"><?php echo $clanci->institucija9->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->autor10->Visible) { // autor10 ?>
		<td><span id="elh_clanci_autor10" class="clanci_autor10"><?php echo $clanci->autor10->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
		<td><span id="elh_clanci_institucija10" class="clanci_institucija10"><?php echo $clanci->institucija10->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
		<td><span id="elh_clanci_naslov_eng" class="clanci_naslov_eng"><?php echo $clanci->naslov_eng->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
		<td><span id="elh_clanci_naslov_mne" class="clanci_naslov_mne"><?php echo $clanci->naslov_mne->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->sazetak_eng->Visible) { // sazetak_eng ?>
		<td><span id="elh_clanci_sazetak_eng" class="clanci_sazetak_eng"><?php echo $clanci->sazetak_eng->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->sazetak_mne->Visible) { // sazetak_mne ?>
		<td><span id="elh_clanci_sazetak_mne" class="clanci_sazetak_mne"><?php echo $clanci->sazetak_mne->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
		<td><span id="elh_clanci_keywords_eng" class="clanci_keywords_eng"><?php echo $clanci->keywords_eng->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
		<td><span id="elh_clanci_keywords_mne" class="clanci_keywords_mne"><?php echo $clanci->keywords_mne->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->tip->Visible) { // tip ?>
		<td><span id="elh_clanci_tip" class="clanci_tip"><?php echo $clanci->tip->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->file->Visible) { // file ?>
		<td><span id="elh_clanci_file" class="clanci_file"><?php echo $clanci->file->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->broj->Visible) { // broj ?>
		<td><span id="elh_clanci_broj" class="clanci_broj"><?php echo $clanci->broj->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->str->Visible) { // str ?>
		<td><span id="elh_clanci_str" class="clanci_str"><?php echo $clanci->str->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->hits->Visible) { // hits ?>
		<td><span id="elh_clanci_hits" class="clanci_hits"><?php echo $clanci->hits->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->references->Visible) { // references ?>
		<td><span id="elh_clanci_references" class="clanci_references"><?php echo $clanci->references->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->doi->Visible) { // doi ?>
		<td><span id="elh_clanci_doi" class="clanci_doi"><?php echo $clanci->doi->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->udk->Visible) { // udk ?>
		<td><span id="elh_clanci_udk" class="clanci_udk"><?php echo $clanci->udk->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
		<td><span id="elh_clanci_scopus_id" class="clanci_scopus_id"><?php echo $clanci->scopus_id->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->lastip->Visible) { // lastip ?>
		<td><span id="elh_clanci_lastip" class="clanci_lastip"><?php echo $clanci->lastip->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
		<td><span id="elh_clanci_lastdownloadip" class="clanci_lastdownloadip"><?php echo $clanci->lastdownloadip->FldCaption() ?></span></td>
<?php } ?>
<?php if ($clanci->downloads->Visible) { // downloads ?>
		<td><span id="elh_clanci_downloads" class="clanci_downloads"><?php echo $clanci->downloads->FldCaption() ?></span></td>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$clanci_delete->RecCnt = 0;
$i = 0;
while (!$clanci_delete->Recordset->EOF) {
	$clanci_delete->RecCnt++;
	$clanci_delete->RowCnt++;

	// Set row properties
	$clanci->ResetAttrs();
	$clanci->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$clanci_delete->LoadRowValues($clanci_delete->Recordset);

	// Render row
	$clanci_delete->RenderRow();
?>
	<tr<?php echo $clanci->RowAttributes() ?>>
<?php if ($clanci->id->Visible) { // id ?>
		<td<?php echo $clanci->id->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_id" class="control-group clanci_id">
<span<?php echo $clanci->id->ViewAttributes() ?>>
<?php echo $clanci->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor1->Visible) { // autor1 ?>
		<td<?php echo $clanci->autor1->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor1" class="control-group clanci_autor1">
<span<?php echo $clanci->autor1->ViewAttributes() ?>>
<?php echo $clanci->autor1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
		<td<?php echo $clanci->institucija1->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija1" class="control-group clanci_institucija1">
<span<?php echo $clanci->institucija1->ViewAttributes() ?>>
<?php echo $clanci->institucija1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor2->Visible) { // autor2 ?>
		<td<?php echo $clanci->autor2->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor2" class="control-group clanci_autor2">
<span<?php echo $clanci->autor2->ViewAttributes() ?>>
<?php echo $clanci->autor2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
		<td<?php echo $clanci->institucija2->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija2" class="control-group clanci_institucija2">
<span<?php echo $clanci->institucija2->ViewAttributes() ?>>
<?php echo $clanci->institucija2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor3->Visible) { // autor3 ?>
		<td<?php echo $clanci->autor3->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor3" class="control-group clanci_autor3">
<span<?php echo $clanci->autor3->ViewAttributes() ?>>
<?php echo $clanci->autor3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
		<td<?php echo $clanci->institucija3->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija3" class="control-group clanci_institucija3">
<span<?php echo $clanci->institucija3->ViewAttributes() ?>>
<?php echo $clanci->institucija3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor4->Visible) { // autor4 ?>
		<td<?php echo $clanci->autor4->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor4" class="control-group clanci_autor4">
<span<?php echo $clanci->autor4->ViewAttributes() ?>>
<?php echo $clanci->autor4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
		<td<?php echo $clanci->institucija4->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija4" class="control-group clanci_institucija4">
<span<?php echo $clanci->institucija4->ViewAttributes() ?>>
<?php echo $clanci->institucija4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor5->Visible) { // autor5 ?>
		<td<?php echo $clanci->autor5->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor5" class="control-group clanci_autor5">
<span<?php echo $clanci->autor5->ViewAttributes() ?>>
<?php echo $clanci->autor5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
		<td<?php echo $clanci->institucija5->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija5" class="control-group clanci_institucija5">
<span<?php echo $clanci->institucija5->ViewAttributes() ?>>
<?php echo $clanci->institucija5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor6->Visible) { // autor6 ?>
		<td<?php echo $clanci->autor6->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor6" class="control-group clanci_autor6">
<span<?php echo $clanci->autor6->ViewAttributes() ?>>
<?php echo $clanci->autor6->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
		<td<?php echo $clanci->institucija6->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija6" class="control-group clanci_institucija6">
<span<?php echo $clanci->institucija6->ViewAttributes() ?>>
<?php echo $clanci->institucija6->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor7->Visible) { // autor7 ?>
		<td<?php echo $clanci->autor7->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor7" class="control-group clanci_autor7">
<span<?php echo $clanci->autor7->ViewAttributes() ?>>
<?php echo $clanci->autor7->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
		<td<?php echo $clanci->institucija7->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija7" class="control-group clanci_institucija7">
<span<?php echo $clanci->institucija7->ViewAttributes() ?>>
<?php echo $clanci->institucija7->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor8->Visible) { // autor8 ?>
		<td<?php echo $clanci->autor8->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor8" class="control-group clanci_autor8">
<span<?php echo $clanci->autor8->ViewAttributes() ?>>
<?php echo $clanci->autor8->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
		<td<?php echo $clanci->institucija8->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija8" class="control-group clanci_institucija8">
<span<?php echo $clanci->institucija8->ViewAttributes() ?>>
<?php echo $clanci->institucija8->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor9->Visible) { // autor9 ?>
		<td<?php echo $clanci->autor9->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor9" class="control-group clanci_autor9">
<span<?php echo $clanci->autor9->ViewAttributes() ?>>
<?php echo $clanci->autor9->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
		<td<?php echo $clanci->institucija9->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija9" class="control-group clanci_institucija9">
<span<?php echo $clanci->institucija9->ViewAttributes() ?>>
<?php echo $clanci->institucija9->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->autor10->Visible) { // autor10 ?>
		<td<?php echo $clanci->autor10->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_autor10" class="control-group clanci_autor10">
<span<?php echo $clanci->autor10->ViewAttributes() ?>>
<?php echo $clanci->autor10->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
		<td<?php echo $clanci->institucija10->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_institucija10" class="control-group clanci_institucija10">
<span<?php echo $clanci->institucija10->ViewAttributes() ?>>
<?php echo $clanci->institucija10->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
		<td<?php echo $clanci->naslov_eng->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_naslov_eng" class="control-group clanci_naslov_eng">
<span<?php echo $clanci->naslov_eng->ViewAttributes() ?>>
<?php echo $clanci->naslov_eng->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
		<td<?php echo $clanci->naslov_mne->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_naslov_mne" class="control-group clanci_naslov_mne">
<span<?php echo $clanci->naslov_mne->ViewAttributes() ?>>
<?php echo $clanci->naslov_mne->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->sazetak_eng->Visible) { // sazetak_eng ?>
		<td<?php echo $clanci->sazetak_eng->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_sazetak_eng" class="control-group clanci_sazetak_eng">
<span<?php echo $clanci->sazetak_eng->ViewAttributes() ?>>
<?php echo $clanci->sazetak_eng->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->sazetak_mne->Visible) { // sazetak_mne ?>
		<td<?php echo $clanci->sazetak_mne->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_sazetak_mne" class="control-group clanci_sazetak_mne">
<span<?php echo $clanci->sazetak_mne->ViewAttributes() ?>>
<?php echo $clanci->sazetak_mne->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
		<td<?php echo $clanci->keywords_eng->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_keywords_eng" class="control-group clanci_keywords_eng">
<span<?php echo $clanci->keywords_eng->ViewAttributes() ?>>
<?php echo $clanci->keywords_eng->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
		<td<?php echo $clanci->keywords_mne->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_keywords_mne" class="control-group clanci_keywords_mne">
<span<?php echo $clanci->keywords_mne->ViewAttributes() ?>>
<?php echo $clanci->keywords_mne->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->tip->Visible) { // tip ?>
		<td<?php echo $clanci->tip->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_tip" class="control-group clanci_tip">
<span<?php echo $clanci->tip->ViewAttributes() ?>>
<?php echo $clanci->tip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->file->Visible) { // file ?>
		<td<?php echo $clanci->file->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_file" class="control-group clanci_file">
<span<?php echo $clanci->file->ViewAttributes() ?>>
<?php echo $clanci->file->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->broj->Visible) { // broj ?>
		<td<?php echo $clanci->broj->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_broj" class="control-group clanci_broj">
<span<?php echo $clanci->broj->ViewAttributes() ?>>
<?php echo $clanci->broj->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->str->Visible) { // str ?>
		<td<?php echo $clanci->str->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_str" class="control-group clanci_str">
<span<?php echo $clanci->str->ViewAttributes() ?>>
<?php echo $clanci->str->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->hits->Visible) { // hits ?>
		<td<?php echo $clanci->hits->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_hits" class="control-group clanci_hits">
<span<?php echo $clanci->hits->ViewAttributes() ?>>
<?php echo $clanci->hits->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->references->Visible) { // references ?>
		<td<?php echo $clanci->references->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_references" class="control-group clanci_references">
<span<?php echo $clanci->references->ViewAttributes() ?>>
<?php echo $clanci->references->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->doi->Visible) { // doi ?>
		<td<?php echo $clanci->doi->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_doi" class="control-group clanci_doi">
<span<?php echo $clanci->doi->ViewAttributes() ?>>
<?php echo $clanci->doi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->udk->Visible) { // udk ?>
		<td<?php echo $clanci->udk->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_udk" class="control-group clanci_udk">
<span<?php echo $clanci->udk->ViewAttributes() ?>>
<?php echo $clanci->udk->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
		<td<?php echo $clanci->scopus_id->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_scopus_id" class="control-group clanci_scopus_id">
<span<?php echo $clanci->scopus_id->ViewAttributes() ?>>
<?php echo $clanci->scopus_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->lastip->Visible) { // lastip ?>
		<td<?php echo $clanci->lastip->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_lastip" class="control-group clanci_lastip">
<span<?php echo $clanci->lastip->ViewAttributes() ?>>
<?php echo $clanci->lastip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
		<td<?php echo $clanci->lastdownloadip->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_lastdownloadip" class="control-group clanci_lastdownloadip">
<span<?php echo $clanci->lastdownloadip->ViewAttributes() ?>>
<?php echo $clanci->lastdownloadip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($clanci->downloads->Visible) { // downloads ?>
		<td<?php echo $clanci->downloads->CellAttributes() ?>>
<span id="el<?php echo $clanci_delete->RowCnt ?>_clanci_downloads" class="control-group clanci_downloads">
<span<?php echo $clanci->downloads->ViewAttributes() ?>>
<?php echo $clanci->downloads->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$clanci_delete->Recordset->MoveNext();
}
$clanci_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fclancidelete.Init();
</script>
<?php
$clanci_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$clanci_delete->Page_Terminate();
?>
