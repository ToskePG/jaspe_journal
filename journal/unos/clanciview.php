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

$clanci_view = NULL; // Initialize page object first

class cclanci_view extends cclanci {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{280688C0-E75A-4602-948C-8B7C3B174051}";

	// Table name
	var $TableName = 'clanci';

	// Page object name
	var $PageObjName = 'clanci_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'clanci', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "clancilist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "clancilist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "clancilist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

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
		$this->udk->setDbValue($rs->fields('udk'));
		$this->hits->setDbValue($rs->fields('hits'));
		$this->references->setDbValue($rs->fields('references'));
		$this->citation->setDbValue($rs->fields('citation'));
		$this->doi->setDbValue($rs->fields('doi'));
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
		$this->udk->DbValue = $row['udk'];
		$this->hits->DbValue = $row['hits'];
		$this->references->DbValue = $row['references'];
		$this->citation->DbValue = $row['citation'];
		$this->doi->DbValue = $row['doi'];
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		// udk
		// hits
		// references
		// citation
		// doi
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

			// udk
			$this->udk->ViewValue = $this->udk->CurrentValue;
			$this->udk->ViewCustomAttributes = "";

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

			// udk
			$this->udk->LinkCustomAttributes = "";
			$this->udk->HrefValue = "";
			$this->udk->TooltipValue = "";

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_clanci\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_clanci',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fclanciview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$ExportDoc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "clancilist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($clanci_view)) $clanci_view = new cclanci_view();

// Page init
$clanci_view->Page_Init();

// Page main
$clanci_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clanci_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($clanci->Export == "") { ?>
<script type="text/javascript">

// Page object
var clanci_view = new ew_Page("clanci_view");
clanci_view.PageID = "view"; // Page ID
var EW_PAGE_ID = clanci_view.PageID; // For backward compatibility

// Form object
var fclanciview = new ew_Form("fclanciview");

// Form_CustomValidate event
fclanciview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclanciview.ValidateRequired = true;
<?php } else { ?>
fclanciview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclanciview.Lists["x_autor1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_autor10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciview.Lists["x_institucija10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($clanci->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($clanci->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $clanci_view->ExportOptions->Render("body") ?>
<?php if (!$clanci_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($clanci_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $clanci_view->ShowPageHeader(); ?>
<?php
$clanci_view->ShowMessage();
?>
<form name="fclanciview" id="fclanciview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="clanci">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_clanciview" class="table table-bordered table-striped">
<?php if ($clanci->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_clanci_id"><?php echo $clanci->id->FldCaption() ?></span></td>
		<td<?php echo $clanci->id->CellAttributes() ?>>
<span id="el_clanci_id" class="control-group">
<span<?php echo $clanci->id->ViewAttributes() ?>>
<?php echo $clanci->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor1->Visible) { // autor1 ?>
	<tr id="r_autor1">
		<td><span id="elh_clanci_autor1"><?php echo $clanci->autor1->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor1->CellAttributes() ?>>
<span id="el_clanci_autor1" class="control-group">
<span<?php echo $clanci->autor1->ViewAttributes() ?>>
<?php echo $clanci->autor1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
	<tr id="r_institucija1">
		<td><span id="elh_clanci_institucija1"><?php echo $clanci->institucija1->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija1->CellAttributes() ?>>
<span id="el_clanci_institucija1" class="control-group">
<span<?php echo $clanci->institucija1->ViewAttributes() ?>>
<?php echo $clanci->institucija1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor2->Visible) { // autor2 ?>
	<tr id="r_autor2">
		<td><span id="elh_clanci_autor2"><?php echo $clanci->autor2->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor2->CellAttributes() ?>>
<span id="el_clanci_autor2" class="control-group">
<span<?php echo $clanci->autor2->ViewAttributes() ?>>
<?php echo $clanci->autor2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
	<tr id="r_institucija2">
		<td><span id="elh_clanci_institucija2"><?php echo $clanci->institucija2->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija2->CellAttributes() ?>>
<span id="el_clanci_institucija2" class="control-group">
<span<?php echo $clanci->institucija2->ViewAttributes() ?>>
<?php echo $clanci->institucija2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor3->Visible) { // autor3 ?>
	<tr id="r_autor3">
		<td><span id="elh_clanci_autor3"><?php echo $clanci->autor3->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor3->CellAttributes() ?>>
<span id="el_clanci_autor3" class="control-group">
<span<?php echo $clanci->autor3->ViewAttributes() ?>>
<?php echo $clanci->autor3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
	<tr id="r_institucija3">
		<td><span id="elh_clanci_institucija3"><?php echo $clanci->institucija3->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija3->CellAttributes() ?>>
<span id="el_clanci_institucija3" class="control-group">
<span<?php echo $clanci->institucija3->ViewAttributes() ?>>
<?php echo $clanci->institucija3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor4->Visible) { // autor4 ?>
	<tr id="r_autor4">
		<td><span id="elh_clanci_autor4"><?php echo $clanci->autor4->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor4->CellAttributes() ?>>
<span id="el_clanci_autor4" class="control-group">
<span<?php echo $clanci->autor4->ViewAttributes() ?>>
<?php echo $clanci->autor4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
	<tr id="r_institucija4">
		<td><span id="elh_clanci_institucija4"><?php echo $clanci->institucija4->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija4->CellAttributes() ?>>
<span id="el_clanci_institucija4" class="control-group">
<span<?php echo $clanci->institucija4->ViewAttributes() ?>>
<?php echo $clanci->institucija4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor5->Visible) { // autor5 ?>
	<tr id="r_autor5">
		<td><span id="elh_clanci_autor5"><?php echo $clanci->autor5->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor5->CellAttributes() ?>>
<span id="el_clanci_autor5" class="control-group">
<span<?php echo $clanci->autor5->ViewAttributes() ?>>
<?php echo $clanci->autor5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
	<tr id="r_institucija5">
		<td><span id="elh_clanci_institucija5"><?php echo $clanci->institucija5->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija5->CellAttributes() ?>>
<span id="el_clanci_institucija5" class="control-group">
<span<?php echo $clanci->institucija5->ViewAttributes() ?>>
<?php echo $clanci->institucija5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor6->Visible) { // autor6 ?>
	<tr id="r_autor6">
		<td><span id="elh_clanci_autor6"><?php echo $clanci->autor6->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor6->CellAttributes() ?>>
<span id="el_clanci_autor6" class="control-group">
<span<?php echo $clanci->autor6->ViewAttributes() ?>>
<?php echo $clanci->autor6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
	<tr id="r_institucija6">
		<td><span id="elh_clanci_institucija6"><?php echo $clanci->institucija6->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija6->CellAttributes() ?>>
<span id="el_clanci_institucija6" class="control-group">
<span<?php echo $clanci->institucija6->ViewAttributes() ?>>
<?php echo $clanci->institucija6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor7->Visible) { // autor7 ?>
	<tr id="r_autor7">
		<td><span id="elh_clanci_autor7"><?php echo $clanci->autor7->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor7->CellAttributes() ?>>
<span id="el_clanci_autor7" class="control-group">
<span<?php echo $clanci->autor7->ViewAttributes() ?>>
<?php echo $clanci->autor7->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
	<tr id="r_institucija7">
		<td><span id="elh_clanci_institucija7"><?php echo $clanci->institucija7->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija7->CellAttributes() ?>>
<span id="el_clanci_institucija7" class="control-group">
<span<?php echo $clanci->institucija7->ViewAttributes() ?>>
<?php echo $clanci->institucija7->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor8->Visible) { // autor8 ?>
	<tr id="r_autor8">
		<td><span id="elh_clanci_autor8"><?php echo $clanci->autor8->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor8->CellAttributes() ?>>
<span id="el_clanci_autor8" class="control-group">
<span<?php echo $clanci->autor8->ViewAttributes() ?>>
<?php echo $clanci->autor8->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
	<tr id="r_institucija8">
		<td><span id="elh_clanci_institucija8"><?php echo $clanci->institucija8->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija8->CellAttributes() ?>>
<span id="el_clanci_institucija8" class="control-group">
<span<?php echo $clanci->institucija8->ViewAttributes() ?>>
<?php echo $clanci->institucija8->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor9->Visible) { // autor9 ?>
	<tr id="r_autor9">
		<td><span id="elh_clanci_autor9"><?php echo $clanci->autor9->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor9->CellAttributes() ?>>
<span id="el_clanci_autor9" class="control-group">
<span<?php echo $clanci->autor9->ViewAttributes() ?>>
<?php echo $clanci->autor9->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
	<tr id="r_institucija9">
		<td><span id="elh_clanci_institucija9"><?php echo $clanci->institucija9->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija9->CellAttributes() ?>>
<span id="el_clanci_institucija9" class="control-group">
<span<?php echo $clanci->institucija9->ViewAttributes() ?>>
<?php echo $clanci->institucija9->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->autor10->Visible) { // autor10 ?>
	<tr id="r_autor10">
		<td><span id="elh_clanci_autor10"><?php echo $clanci->autor10->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor10->CellAttributes() ?>>
<span id="el_clanci_autor10" class="control-group">
<span<?php echo $clanci->autor10->ViewAttributes() ?>>
<?php echo $clanci->autor10->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
	<tr id="r_institucija10">
		<td><span id="elh_clanci_institucija10"><?php echo $clanci->institucija10->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija10->CellAttributes() ?>>
<span id="el_clanci_institucija10" class="control-group">
<span<?php echo $clanci->institucija10->ViewAttributes() ?>>
<?php echo $clanci->institucija10->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
	<tr id="r_naslov_eng">
		<td><span id="elh_clanci_naslov_eng"><?php echo $clanci->naslov_eng->FldCaption() ?></span></td>
		<td<?php echo $clanci->naslov_eng->CellAttributes() ?>>
<span id="el_clanci_naslov_eng" class="control-group">
<span<?php echo $clanci->naslov_eng->ViewAttributes() ?>>
<?php echo $clanci->naslov_eng->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
	<tr id="r_naslov_mne">
		<td><span id="elh_clanci_naslov_mne"><?php echo $clanci->naslov_mne->FldCaption() ?></span></td>
		<td<?php echo $clanci->naslov_mne->CellAttributes() ?>>
<span id="el_clanci_naslov_mne" class="control-group">
<span<?php echo $clanci->naslov_mne->ViewAttributes() ?>>
<?php echo $clanci->naslov_mne->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->sazetak_eng->Visible) { // sazetak_eng ?>
	<tr id="r_sazetak_eng">
		<td><span id="elh_clanci_sazetak_eng"><?php echo $clanci->sazetak_eng->FldCaption() ?></span></td>
		<td<?php echo $clanci->sazetak_eng->CellAttributes() ?>>
<span id="el_clanci_sazetak_eng" class="control-group">
<span<?php echo $clanci->sazetak_eng->ViewAttributes() ?>>
<?php echo $clanci->sazetak_eng->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->sazetak_mne->Visible) { // sazetak_mne ?>
	<tr id="r_sazetak_mne">
		<td><span id="elh_clanci_sazetak_mne"><?php echo $clanci->sazetak_mne->FldCaption() ?></span></td>
		<td<?php echo $clanci->sazetak_mne->CellAttributes() ?>>
<span id="el_clanci_sazetak_mne" class="control-group">
<span<?php echo $clanci->sazetak_mne->ViewAttributes() ?>>
<?php echo $clanci->sazetak_mne->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
	<tr id="r_keywords_eng">
		<td><span id="elh_clanci_keywords_eng"><?php echo $clanci->keywords_eng->FldCaption() ?></span></td>
		<td<?php echo $clanci->keywords_eng->CellAttributes() ?>>
<span id="el_clanci_keywords_eng" class="control-group">
<span<?php echo $clanci->keywords_eng->ViewAttributes() ?>>
<?php echo $clanci->keywords_eng->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
	<tr id="r_keywords_mne">
		<td><span id="elh_clanci_keywords_mne"><?php echo $clanci->keywords_mne->FldCaption() ?></span></td>
		<td<?php echo $clanci->keywords_mne->CellAttributes() ?>>
<span id="el_clanci_keywords_mne" class="control-group">
<span<?php echo $clanci->keywords_mne->ViewAttributes() ?>>
<?php echo $clanci->keywords_mne->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->tip->Visible) { // tip ?>
	<tr id="r_tip">
		<td><span id="elh_clanci_tip"><?php echo $clanci->tip->FldCaption() ?></span></td>
		<td<?php echo $clanci->tip->CellAttributes() ?>>
<span id="el_clanci_tip" class="control-group">
<span<?php echo $clanci->tip->ViewAttributes() ?>>
<?php echo $clanci->tip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->file->Visible) { // file ?>
	<tr id="r_file">
		<td><span id="elh_clanci_file"><?php echo $clanci->file->FldCaption() ?></span></td>
		<td<?php echo $clanci->file->CellAttributes() ?>>
<span id="el_clanci_file" class="control-group">
<span<?php echo $clanci->file->ViewAttributes() ?>>
<?php echo $clanci->file->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->broj->Visible) { // broj ?>
	<tr id="r_broj">
		<td><span id="elh_clanci_broj"><?php echo $clanci->broj->FldCaption() ?></span></td>
		<td<?php echo $clanci->broj->CellAttributes() ?>>
<span id="el_clanci_broj" class="control-group">
<span<?php echo $clanci->broj->ViewAttributes() ?>>
<?php echo $clanci->broj->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->str->Visible) { // str ?>
	<tr id="r_str">
		<td><span id="elh_clanci_str"><?php echo $clanci->str->FldCaption() ?></span></td>
		<td<?php echo $clanci->str->CellAttributes() ?>>
<span id="el_clanci_str" class="control-group">
<span<?php echo $clanci->str->ViewAttributes() ?>>
<?php echo $clanci->str->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->udk->Visible) { // udk ?>
	<tr id="r_udk">
		<td><span id="elh_clanci_udk"><?php echo $clanci->udk->FldCaption() ?></span></td>
		<td<?php echo $clanci->udk->CellAttributes() ?>>
<span id="el_clanci_udk" class="control-group">
<span<?php echo $clanci->udk->ViewAttributes() ?>>
<?php echo $clanci->udk->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->hits->Visible) { // hits ?>
	<tr id="r_hits">
		<td><span id="elh_clanci_hits"><?php echo $clanci->hits->FldCaption() ?></span></td>
		<td<?php echo $clanci->hits->CellAttributes() ?>>
<span id="el_clanci_hits" class="control-group">
<span<?php echo $clanci->hits->ViewAttributes() ?>>
<?php echo $clanci->hits->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->references->Visible) { // references ?>
	<tr id="r_references">
		<td><span id="elh_clanci_references"><?php echo $clanci->references->FldCaption() ?></span></td>
		<td<?php echo $clanci->references->CellAttributes() ?>>
<span id="el_clanci_references" class="control-group">
<span<?php echo $clanci->references->ViewAttributes() ?>>
<?php echo $clanci->references->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->citation->Visible) { // citation ?>
	<tr id="r_citation">
		<td><span id="elh_clanci_citation"><?php echo $clanci->citation->FldCaption() ?></span></td>
		<td<?php echo $clanci->citation->CellAttributes() ?>>
<span id="el_clanci_citation" class="control-group">
<span<?php echo $clanci->citation->ViewAttributes() ?>>
<?php echo $clanci->citation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->doi->Visible) { // doi ?>
	<tr id="r_doi">
		<td><span id="elh_clanci_doi"><?php echo $clanci->doi->FldCaption() ?></span></td>
		<td<?php echo $clanci->doi->CellAttributes() ?>>
<span id="el_clanci_doi" class="control-group">
<span<?php echo $clanci->doi->ViewAttributes() ?>>
<?php echo $clanci->doi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
	<tr id="r_scopus_id">
		<td><span id="elh_clanci_scopus_id"><?php echo $clanci->scopus_id->FldCaption() ?></span></td>
		<td<?php echo $clanci->scopus_id->CellAttributes() ?>>
<span id="el_clanci_scopus_id" class="control-group">
<span<?php echo $clanci->scopus_id->ViewAttributes() ?>>
<?php echo $clanci->scopus_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->lastip->Visible) { // lastip ?>
	<tr id="r_lastip">
		<td><span id="elh_clanci_lastip"><?php echo $clanci->lastip->FldCaption() ?></span></td>
		<td<?php echo $clanci->lastip->CellAttributes() ?>>
<span id="el_clanci_lastip" class="control-group">
<span<?php echo $clanci->lastip->ViewAttributes() ?>>
<?php echo $clanci->lastip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
	<tr id="r_lastdownloadip">
		<td><span id="elh_clanci_lastdownloadip"><?php echo $clanci->lastdownloadip->FldCaption() ?></span></td>
		<td<?php echo $clanci->lastdownloadip->CellAttributes() ?>>
<span id="el_clanci_lastdownloadip" class="control-group">
<span<?php echo $clanci->lastdownloadip->ViewAttributes() ?>>
<?php echo $clanci->lastdownloadip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($clanci->downloads->Visible) { // downloads ?>
	<tr id="r_downloads">
		<td><span id="elh_clanci_downloads"><?php echo $clanci->downloads->FldCaption() ?></span></td>
		<td<?php echo $clanci->downloads->CellAttributes() ?>>
<span id="el_clanci_downloads" class="control-group">
<span<?php echo $clanci->downloads->ViewAttributes() ?>>
<?php echo $clanci->downloads->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
fclanciview.Init();
</script>
<?php
$clanci_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($clanci->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$clanci_view->Page_Terminate();
?>
