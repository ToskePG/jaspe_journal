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

$clanci_edit = NULL; // Initialize page object first

class cclanci_edit extends cclanci {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{3D7A0815-42DE-4706-A688-045439C7A049}";

	// Table name
	var $TableName = 'clanci';

	// Page object name
	var $PageObjName = 'clanci_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("clancilist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("clancilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->autor1->FldIsDetailKey) {
			$this->autor1->setFormValue($objForm->GetValue("x_autor1"));
		}
		if (!$this->institucija1->FldIsDetailKey) {
			$this->institucija1->setFormValue($objForm->GetValue("x_institucija1"));
		}
		if (!$this->autor2->FldIsDetailKey) {
			$this->autor2->setFormValue($objForm->GetValue("x_autor2"));
		}
		if (!$this->institucija2->FldIsDetailKey) {
			$this->institucija2->setFormValue($objForm->GetValue("x_institucija2"));
		}
		if (!$this->autor3->FldIsDetailKey) {
			$this->autor3->setFormValue($objForm->GetValue("x_autor3"));
		}
		if (!$this->institucija3->FldIsDetailKey) {
			$this->institucija3->setFormValue($objForm->GetValue("x_institucija3"));
		}
		if (!$this->autor4->FldIsDetailKey) {
			$this->autor4->setFormValue($objForm->GetValue("x_autor4"));
		}
		if (!$this->institucija4->FldIsDetailKey) {
			$this->institucija4->setFormValue($objForm->GetValue("x_institucija4"));
		}
		if (!$this->autor5->FldIsDetailKey) {
			$this->autor5->setFormValue($objForm->GetValue("x_autor5"));
		}
		if (!$this->institucija5->FldIsDetailKey) {
			$this->institucija5->setFormValue($objForm->GetValue("x_institucija5"));
		}
		if (!$this->autor6->FldIsDetailKey) {
			$this->autor6->setFormValue($objForm->GetValue("x_autor6"));
		}
		if (!$this->institucija6->FldIsDetailKey) {
			$this->institucija6->setFormValue($objForm->GetValue("x_institucija6"));
		}
		if (!$this->autor7->FldIsDetailKey) {
			$this->autor7->setFormValue($objForm->GetValue("x_autor7"));
		}
		if (!$this->institucija7->FldIsDetailKey) {
			$this->institucija7->setFormValue($objForm->GetValue("x_institucija7"));
		}
		if (!$this->autor8->FldIsDetailKey) {
			$this->autor8->setFormValue($objForm->GetValue("x_autor8"));
		}
		if (!$this->institucija8->FldIsDetailKey) {
			$this->institucija8->setFormValue($objForm->GetValue("x_institucija8"));
		}
		if (!$this->autor9->FldIsDetailKey) {
			$this->autor9->setFormValue($objForm->GetValue("x_autor9"));
		}
		if (!$this->institucija9->FldIsDetailKey) {
			$this->institucija9->setFormValue($objForm->GetValue("x_institucija9"));
		}
		if (!$this->autor10->FldIsDetailKey) {
			$this->autor10->setFormValue($objForm->GetValue("x_autor10"));
		}
		if (!$this->institucija10->FldIsDetailKey) {
			$this->institucija10->setFormValue($objForm->GetValue("x_institucija10"));
		}
		if (!$this->naslov_eng->FldIsDetailKey) {
			$this->naslov_eng->setFormValue($objForm->GetValue("x_naslov_eng"));
		}
		if (!$this->naslov_mne->FldIsDetailKey) {
			$this->naslov_mne->setFormValue($objForm->GetValue("x_naslov_mne"));
		}
		if (!$this->sazetak_eng->FldIsDetailKey) {
			$this->sazetak_eng->setFormValue($objForm->GetValue("x_sazetak_eng"));
		}
		if (!$this->sazetak_mne->FldIsDetailKey) {
			$this->sazetak_mne->setFormValue($objForm->GetValue("x_sazetak_mne"));
		}
		if (!$this->keywords_eng->FldIsDetailKey) {
			$this->keywords_eng->setFormValue($objForm->GetValue("x_keywords_eng"));
		}
		if (!$this->keywords_mne->FldIsDetailKey) {
			$this->keywords_mne->setFormValue($objForm->GetValue("x_keywords_mne"));
		}
		if (!$this->tip->FldIsDetailKey) {
			$this->tip->setFormValue($objForm->GetValue("x_tip"));
		}
		if (!$this->file->FldIsDetailKey) {
			$this->file->setFormValue($objForm->GetValue("x_file"));
		}
		if (!$this->broj->FldIsDetailKey) {
			$this->broj->setFormValue($objForm->GetValue("x_broj"));
		}
		if (!$this->str->FldIsDetailKey) {
			$this->str->setFormValue($objForm->GetValue("x_str"));
		}
		if (!$this->hits->FldIsDetailKey) {
			$this->hits->setFormValue($objForm->GetValue("x_hits"));
		}
		if (!$this->references->FldIsDetailKey) {
			$this->references->setFormValue($objForm->GetValue("x_references"));
		}
		if (!$this->citation->FldIsDetailKey) {
			$this->citation->setFormValue($objForm->GetValue("x_citation"));
		}
		if (!$this->doi->FldIsDetailKey) {
			$this->doi->setFormValue($objForm->GetValue("x_doi"));
		}
		if (!$this->udk->FldIsDetailKey) {
			$this->udk->setFormValue($objForm->GetValue("x_udk"));
		}
		if (!$this->scopus_id->FldIsDetailKey) {
			$this->scopus_id->setFormValue($objForm->GetValue("x_scopus_id"));
		}
		if (!$this->lastip->FldIsDetailKey) {
			$this->lastip->setFormValue($objForm->GetValue("x_lastip"));
		}
		if (!$this->lastdownloadip->FldIsDetailKey) {
			$this->lastdownloadip->setFormValue($objForm->GetValue("x_lastdownloadip"));
		}
		if (!$this->downloads->FldIsDetailKey) {
			$this->downloads->setFormValue($objForm->GetValue("x_downloads"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->autor1->CurrentValue = $this->autor1->FormValue;
		$this->institucija1->CurrentValue = $this->institucija1->FormValue;
		$this->autor2->CurrentValue = $this->autor2->FormValue;
		$this->institucija2->CurrentValue = $this->institucija2->FormValue;
		$this->autor3->CurrentValue = $this->autor3->FormValue;
		$this->institucija3->CurrentValue = $this->institucija3->FormValue;
		$this->autor4->CurrentValue = $this->autor4->FormValue;
		$this->institucija4->CurrentValue = $this->institucija4->FormValue;
		$this->autor5->CurrentValue = $this->autor5->FormValue;
		$this->institucija5->CurrentValue = $this->institucija5->FormValue;
		$this->autor6->CurrentValue = $this->autor6->FormValue;
		$this->institucija6->CurrentValue = $this->institucija6->FormValue;
		$this->autor7->CurrentValue = $this->autor7->FormValue;
		$this->institucija7->CurrentValue = $this->institucija7->FormValue;
		$this->autor8->CurrentValue = $this->autor8->FormValue;
		$this->institucija8->CurrentValue = $this->institucija8->FormValue;
		$this->autor9->CurrentValue = $this->autor9->FormValue;
		$this->institucija9->CurrentValue = $this->institucija9->FormValue;
		$this->autor10->CurrentValue = $this->autor10->FormValue;
		$this->institucija10->CurrentValue = $this->institucija10->FormValue;
		$this->naslov_eng->CurrentValue = $this->naslov_eng->FormValue;
		$this->naslov_mne->CurrentValue = $this->naslov_mne->FormValue;
		$this->sazetak_eng->CurrentValue = $this->sazetak_eng->FormValue;
		$this->sazetak_mne->CurrentValue = $this->sazetak_mne->FormValue;
		$this->keywords_eng->CurrentValue = $this->keywords_eng->FormValue;
		$this->keywords_mne->CurrentValue = $this->keywords_mne->FormValue;
		$this->tip->CurrentValue = $this->tip->FormValue;
		$this->file->CurrentValue = $this->file->FormValue;
		$this->broj->CurrentValue = $this->broj->FormValue;
		$this->str->CurrentValue = $this->str->FormValue;
		$this->hits->CurrentValue = $this->hits->FormValue;
		$this->references->CurrentValue = $this->references->FormValue;
		$this->citation->CurrentValue = $this->citation->FormValue;
		$this->doi->CurrentValue = $this->doi->FormValue;
		$this->udk->CurrentValue = $this->udk->FormValue;
		$this->scopus_id->CurrentValue = $this->scopus_id->FormValue;
		$this->lastip->CurrentValue = $this->lastip->FormValue;
		$this->lastdownloadip->CurrentValue = $this->lastdownloadip->FormValue;
		$this->downloads->CurrentValue = $this->downloads->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// autor1
			$this->autor1->EditCustomAttributes = "";
			$this->autor1->EditValue = ew_HtmlEncode($this->autor1->CurrentValue);
			$this->autor1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor1->FldCaption()));

			// institucija1
			$this->institucija1->EditCustomAttributes = "";
			$this->institucija1->EditValue = ew_HtmlEncode($this->institucija1->CurrentValue);
			$this->institucija1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija1->FldCaption()));

			// autor2
			$this->autor2->EditCustomAttributes = "";
			$this->autor2->EditValue = ew_HtmlEncode($this->autor2->CurrentValue);
			$this->autor2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor2->FldCaption()));

			// institucija2
			$this->institucija2->EditCustomAttributes = "";
			$this->institucija2->EditValue = ew_HtmlEncode($this->institucija2->CurrentValue);
			$this->institucija2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija2->FldCaption()));

			// autor3
			$this->autor3->EditCustomAttributes = "";
			$this->autor3->EditValue = ew_HtmlEncode($this->autor3->CurrentValue);
			$this->autor3->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor3->FldCaption()));

			// institucija3
			$this->institucija3->EditCustomAttributes = "";
			$this->institucija3->EditValue = ew_HtmlEncode($this->institucija3->CurrentValue);
			$this->institucija3->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija3->FldCaption()));

			// autor4
			$this->autor4->EditCustomAttributes = "";
			$this->autor4->EditValue = ew_HtmlEncode($this->autor4->CurrentValue);
			$this->autor4->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor4->FldCaption()));

			// institucija4
			$this->institucija4->EditCustomAttributes = "";
			$this->institucija4->EditValue = ew_HtmlEncode($this->institucija4->CurrentValue);
			$this->institucija4->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija4->FldCaption()));

			// autor5
			$this->autor5->EditCustomAttributes = "";
			$this->autor5->EditValue = ew_HtmlEncode($this->autor5->CurrentValue);
			$this->autor5->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor5->FldCaption()));

			// institucija5
			$this->institucija5->EditCustomAttributes = "";
			$this->institucija5->EditValue = ew_HtmlEncode($this->institucija5->CurrentValue);
			$this->institucija5->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija5->FldCaption()));

			// autor6
			$this->autor6->EditCustomAttributes = "";
			$this->autor6->EditValue = ew_HtmlEncode($this->autor6->CurrentValue);
			$this->autor6->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor6->FldCaption()));

			// institucija6
			$this->institucija6->EditCustomAttributes = "";
			$this->institucija6->EditValue = ew_HtmlEncode($this->institucija6->CurrentValue);
			$this->institucija6->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija6->FldCaption()));

			// autor7
			$this->autor7->EditCustomAttributes = "";
			$this->autor7->EditValue = ew_HtmlEncode($this->autor7->CurrentValue);
			$this->autor7->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor7->FldCaption()));

			// institucija7
			$this->institucija7->EditCustomAttributes = "";
			$this->institucija7->EditValue = ew_HtmlEncode($this->institucija7->CurrentValue);
			$this->institucija7->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija7->FldCaption()));

			// autor8
			$this->autor8->EditCustomAttributes = "";
			$this->autor8->EditValue = ew_HtmlEncode($this->autor8->CurrentValue);
			$this->autor8->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor8->FldCaption()));

			// institucija8
			$this->institucija8->EditCustomAttributes = "";
			$this->institucija8->EditValue = ew_HtmlEncode($this->institucija8->CurrentValue);
			$this->institucija8->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija8->FldCaption()));

			// autor9
			$this->autor9->EditCustomAttributes = "";
			$this->autor9->EditValue = ew_HtmlEncode($this->autor9->CurrentValue);
			$this->autor9->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor9->FldCaption()));

			// institucija9
			$this->institucija9->EditCustomAttributes = "";
			$this->institucija9->EditValue = ew_HtmlEncode($this->institucija9->CurrentValue);
			$this->institucija9->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija9->FldCaption()));

			// autor10
			$this->autor10->EditCustomAttributes = "";
			$this->autor10->EditValue = ew_HtmlEncode($this->autor10->CurrentValue);
			$this->autor10->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->autor10->FldCaption()));

			// institucija10
			$this->institucija10->EditCustomAttributes = "";
			$this->institucija10->EditValue = ew_HtmlEncode($this->institucija10->CurrentValue);
			$this->institucija10->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->institucija10->FldCaption()));

			// naslov_eng
			$this->naslov_eng->EditCustomAttributes = "";
			$this->naslov_eng->EditValue = $this->naslov_eng->CurrentValue;
			$this->naslov_eng->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->naslov_eng->FldCaption()));

			// naslov_mne
			$this->naslov_mne->EditCustomAttributes = "";
			$this->naslov_mne->EditValue = $this->naslov_mne->CurrentValue;
			$this->naslov_mne->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->naslov_mne->FldCaption()));

			// sazetak_eng
			$this->sazetak_eng->EditCustomAttributes = "";
			$this->sazetak_eng->EditValue = $this->sazetak_eng->CurrentValue;
			$this->sazetak_eng->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->sazetak_eng->FldCaption()));

			// sazetak_mne
			$this->sazetak_mne->EditCustomAttributes = "";
			$this->sazetak_mne->EditValue = $this->sazetak_mne->CurrentValue;
			$this->sazetak_mne->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->sazetak_mne->FldCaption()));

			// keywords_eng
			$this->keywords_eng->EditCustomAttributes = "";
			$this->keywords_eng->EditValue = $this->keywords_eng->CurrentValue;
			$this->keywords_eng->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->keywords_eng->FldCaption()));

			// keywords_mne
			$this->keywords_mne->EditCustomAttributes = "";
			$this->keywords_mne->EditValue = $this->keywords_mne->CurrentValue;
			$this->keywords_mne->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->keywords_mne->FldCaption()));

			// tip
			$this->tip->EditCustomAttributes = "";
			$this->tip->EditValue = ew_HtmlEncode($this->tip->CurrentValue);
			$this->tip->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tip->FldCaption()));

			// file
			$this->file->EditCustomAttributes = "";
			$this->file->EditValue = ew_HtmlEncode($this->file->CurrentValue);
			$this->file->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->file->FldCaption()));

			// broj
			$this->broj->EditCustomAttributes = "";
			$this->broj->EditValue = ew_HtmlEncode($this->broj->CurrentValue);
			$this->broj->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->broj->FldCaption()));

			// str
			$this->str->EditCustomAttributes = "";
			$this->str->EditValue = ew_HtmlEncode($this->str->CurrentValue);
			$this->str->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->str->FldCaption()));

			// hits
			$this->hits->EditCustomAttributes = "";
			$this->hits->EditValue = ew_HtmlEncode($this->hits->CurrentValue);
			$this->hits->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hits->FldCaption()));

			// references
			$this->references->EditCustomAttributes = "";
			$this->references->EditValue = $this->references->CurrentValue;
			$this->references->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->references->FldCaption()));

			// citation
			$this->citation->EditCustomAttributes = "";
			$this->citation->EditValue = $this->citation->CurrentValue;
			$this->citation->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->citation->FldCaption()));

			// doi
			$this->doi->EditCustomAttributes = "";
			$this->doi->EditValue = ew_HtmlEncode($this->doi->CurrentValue);
			$this->doi->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->doi->FldCaption()));

			// udk
			$this->udk->EditCustomAttributes = "";
			$this->udk->EditValue = ew_HtmlEncode($this->udk->CurrentValue);
			$this->udk->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->udk->FldCaption()));

			// scopus_id
			$this->scopus_id->EditCustomAttributes = "";
			$this->scopus_id->EditValue = ew_HtmlEncode($this->scopus_id->CurrentValue);
			$this->scopus_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->scopus_id->FldCaption()));

			// lastip
			$this->lastip->EditCustomAttributes = "";
			$this->lastip->EditValue = ew_HtmlEncode($this->lastip->CurrentValue);
			$this->lastip->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->lastip->FldCaption()));

			// lastdownloadip
			$this->lastdownloadip->EditCustomAttributes = "";
			$this->lastdownloadip->EditValue = ew_HtmlEncode($this->lastdownloadip->CurrentValue);
			$this->lastdownloadip->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->lastdownloadip->FldCaption()));

			// downloads
			$this->downloads->EditCustomAttributes = "";
			$this->downloads->EditValue = ew_HtmlEncode($this->downloads->CurrentValue);
			$this->downloads->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->downloads->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// autor1
			$this->autor1->HrefValue = "";

			// institucija1
			$this->institucija1->HrefValue = "";

			// autor2
			$this->autor2->HrefValue = "";

			// institucija2
			$this->institucija2->HrefValue = "";

			// autor3
			$this->autor3->HrefValue = "";

			// institucija3
			$this->institucija3->HrefValue = "";

			// autor4
			$this->autor4->HrefValue = "";

			// institucija4
			$this->institucija4->HrefValue = "";

			// autor5
			$this->autor5->HrefValue = "";

			// institucija5
			$this->institucija5->HrefValue = "";

			// autor6
			$this->autor6->HrefValue = "";

			// institucija6
			$this->institucija6->HrefValue = "";

			// autor7
			$this->autor7->HrefValue = "";

			// institucija7
			$this->institucija7->HrefValue = "";

			// autor8
			$this->autor8->HrefValue = "";

			// institucija8
			$this->institucija8->HrefValue = "";

			// autor9
			$this->autor9->HrefValue = "";

			// institucija9
			$this->institucija9->HrefValue = "";

			// autor10
			$this->autor10->HrefValue = "";

			// institucija10
			$this->institucija10->HrefValue = "";

			// naslov_eng
			$this->naslov_eng->HrefValue = "";

			// naslov_mne
			$this->naslov_mne->HrefValue = "";

			// sazetak_eng
			$this->sazetak_eng->HrefValue = "";

			// sazetak_mne
			$this->sazetak_mne->HrefValue = "";

			// keywords_eng
			$this->keywords_eng->HrefValue = "";

			// keywords_mne
			$this->keywords_mne->HrefValue = "";

			// tip
			$this->tip->HrefValue = "";

			// file
			$this->file->HrefValue = "";

			// broj
			$this->broj->HrefValue = "";

			// str
			$this->str->HrefValue = "";

			// hits
			$this->hits->HrefValue = "";

			// references
			$this->references->HrefValue = "";

			// citation
			$this->citation->HrefValue = "";

			// doi
			$this->doi->HrefValue = "";

			// udk
			$this->udk->HrefValue = "";

			// scopus_id
			$this->scopus_id->HrefValue = "";

			// lastip
			$this->lastip->HrefValue = "";

			// lastdownloadip
			$this->lastdownloadip->HrefValue = "";

			// downloads
			$this->downloads->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->autor1->FldIsDetailKey && !is_null($this->autor1->FormValue) && $this->autor1->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->autor1->FldCaption());
		}
		if (!$this->institucija1->FldIsDetailKey && !is_null($this->institucija1->FormValue) && $this->institucija1->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->institucija1->FldCaption());
		}
		if (!$this->naslov_eng->FldIsDetailKey && !is_null($this->naslov_eng->FormValue) && $this->naslov_eng->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->naslov_eng->FldCaption());
		}
		if (!$this->naslov_mne->FldIsDetailKey && !is_null($this->naslov_mne->FormValue) && $this->naslov_mne->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->naslov_mne->FldCaption());
		}
		if (!$this->sazetak_eng->FldIsDetailKey && !is_null($this->sazetak_eng->FormValue) && $this->sazetak_eng->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->sazetak_eng->FldCaption());
		}
		if (!$this->sazetak_mne->FldIsDetailKey && !is_null($this->sazetak_mne->FormValue) && $this->sazetak_mne->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->sazetak_mne->FldCaption());
		}
		if (!$this->keywords_eng->FldIsDetailKey && !is_null($this->keywords_eng->FormValue) && $this->keywords_eng->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->keywords_eng->FldCaption());
		}
		if (!$this->keywords_mne->FldIsDetailKey && !is_null($this->keywords_mne->FormValue) && $this->keywords_mne->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->keywords_mne->FldCaption());
		}
		if (!$this->tip->FldIsDetailKey && !is_null($this->tip->FormValue) && $this->tip->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tip->FldCaption());
		}
		if (!$this->file->FldIsDetailKey && !is_null($this->file->FormValue) && $this->file->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->file->FldCaption());
		}
		if (!$this->broj->FldIsDetailKey && !is_null($this->broj->FormValue) && $this->broj->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->broj->FldCaption());
		}
		if (!ew_CheckInteger($this->broj->FormValue)) {
			ew_AddMessage($gsFormError, $this->broj->FldErrMsg());
		}
		if (!$this->str->FldIsDetailKey && !is_null($this->str->FormValue) && $this->str->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->str->FldCaption());
		}
		if (!$this->hits->FldIsDetailKey && !is_null($this->hits->FormValue) && $this->hits->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hits->FldCaption());
		}
		if (!ew_CheckInteger($this->hits->FormValue)) {
			ew_AddMessage($gsFormError, $this->hits->FldErrMsg());
		}
		if (!$this->downloads->FldIsDetailKey && !is_null($this->downloads->FormValue) && $this->downloads->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->downloads->FldCaption());
		}
		if (!ew_CheckInteger($this->downloads->FormValue)) {
			ew_AddMessage($gsFormError, $this->downloads->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// autor1
			$this->autor1->SetDbValueDef($rsnew, $this->autor1->CurrentValue, 0, $this->autor1->ReadOnly);

			// institucija1
			$this->institucija1->SetDbValueDef($rsnew, $this->institucija1->CurrentValue, "", $this->institucija1->ReadOnly);

			// autor2
			$this->autor2->SetDbValueDef($rsnew, $this->autor2->CurrentValue, NULL, $this->autor2->ReadOnly);

			// institucija2
			$this->institucija2->SetDbValueDef($rsnew, $this->institucija2->CurrentValue, NULL, $this->institucija2->ReadOnly);

			// autor3
			$this->autor3->SetDbValueDef($rsnew, $this->autor3->CurrentValue, NULL, $this->autor3->ReadOnly);

			// institucija3
			$this->institucija3->SetDbValueDef($rsnew, $this->institucija3->CurrentValue, NULL, $this->institucija3->ReadOnly);

			// autor4
			$this->autor4->SetDbValueDef($rsnew, $this->autor4->CurrentValue, NULL, $this->autor4->ReadOnly);

			// institucija4
			$this->institucija4->SetDbValueDef($rsnew, $this->institucija4->CurrentValue, NULL, $this->institucija4->ReadOnly);

			// autor5
			$this->autor5->SetDbValueDef($rsnew, $this->autor5->CurrentValue, NULL, $this->autor5->ReadOnly);

			// institucija5
			$this->institucija5->SetDbValueDef($rsnew, $this->institucija5->CurrentValue, NULL, $this->institucija5->ReadOnly);

			// autor6
			$this->autor6->SetDbValueDef($rsnew, $this->autor6->CurrentValue, NULL, $this->autor6->ReadOnly);

			// institucija6
			$this->institucija6->SetDbValueDef($rsnew, $this->institucija6->CurrentValue, NULL, $this->institucija6->ReadOnly);

			// autor7
			$this->autor7->SetDbValueDef($rsnew, $this->autor7->CurrentValue, NULL, $this->autor7->ReadOnly);

			// institucija7
			$this->institucija7->SetDbValueDef($rsnew, $this->institucija7->CurrentValue, NULL, $this->institucija7->ReadOnly);

			// autor8
			$this->autor8->SetDbValueDef($rsnew, $this->autor8->CurrentValue, NULL, $this->autor8->ReadOnly);

			// institucija8
			$this->institucija8->SetDbValueDef($rsnew, $this->institucija8->CurrentValue, NULL, $this->institucija8->ReadOnly);

			// autor9
			$this->autor9->SetDbValueDef($rsnew, $this->autor9->CurrentValue, NULL, $this->autor9->ReadOnly);

			// institucija9
			$this->institucija9->SetDbValueDef($rsnew, $this->institucija9->CurrentValue, NULL, $this->institucija9->ReadOnly);

			// autor10
			$this->autor10->SetDbValueDef($rsnew, $this->autor10->CurrentValue, NULL, $this->autor10->ReadOnly);

			// institucija10
			$this->institucija10->SetDbValueDef($rsnew, $this->institucija10->CurrentValue, NULL, $this->institucija10->ReadOnly);

			// naslov_eng
			$this->naslov_eng->SetDbValueDef($rsnew, $this->naslov_eng->CurrentValue, "", $this->naslov_eng->ReadOnly);

			// naslov_mne
			$this->naslov_mne->SetDbValueDef($rsnew, $this->naslov_mne->CurrentValue, "", $this->naslov_mne->ReadOnly);

			// sazetak_eng
			$this->sazetak_eng->SetDbValueDef($rsnew, $this->sazetak_eng->CurrentValue, "", $this->sazetak_eng->ReadOnly);

			// sazetak_mne
			$this->sazetak_mne->SetDbValueDef($rsnew, $this->sazetak_mne->CurrentValue, "", $this->sazetak_mne->ReadOnly);

			// keywords_eng
			$this->keywords_eng->SetDbValueDef($rsnew, $this->keywords_eng->CurrentValue, "", $this->keywords_eng->ReadOnly);

			// keywords_mne
			$this->keywords_mne->SetDbValueDef($rsnew, $this->keywords_mne->CurrentValue, "", $this->keywords_mne->ReadOnly);

			// tip
			$this->tip->SetDbValueDef($rsnew, $this->tip->CurrentValue, NULL, $this->tip->ReadOnly);

			// file
			$this->file->SetDbValueDef($rsnew, $this->file->CurrentValue, "", $this->file->ReadOnly);

			// broj
			$this->broj->SetDbValueDef($rsnew, $this->broj->CurrentValue, 0, $this->broj->ReadOnly);

			// str
			$this->str->SetDbValueDef($rsnew, $this->str->CurrentValue, NULL, $this->str->ReadOnly);

			// hits
			$this->hits->SetDbValueDef($rsnew, $this->hits->CurrentValue, NULL, $this->hits->ReadOnly);

			// references
			$this->references->SetDbValueDef($rsnew, $this->references->CurrentValue, NULL, $this->references->ReadOnly);

			// citation
			$this->citation->SetDbValueDef($rsnew, $this->citation->CurrentValue, NULL, $this->citation->ReadOnly);

			// doi
			$this->doi->SetDbValueDef($rsnew, $this->doi->CurrentValue, NULL, $this->doi->ReadOnly);

			// udk
			$this->udk->SetDbValueDef($rsnew, $this->udk->CurrentValue, NULL, $this->udk->ReadOnly);

			// scopus_id
			$this->scopus_id->SetDbValueDef($rsnew, $this->scopus_id->CurrentValue, NULL, $this->scopus_id->ReadOnly);

			// lastip
			$this->lastip->SetDbValueDef($rsnew, $this->lastip->CurrentValue, NULL, $this->lastip->ReadOnly);

			// lastdownloadip
			$this->lastdownloadip->SetDbValueDef($rsnew, $this->lastdownloadip->CurrentValue, NULL, $this->lastdownloadip->ReadOnly);

			// downloads
			$this->downloads->SetDbValueDef($rsnew, $this->downloads->CurrentValue, 0, $this->downloads->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "clancilist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($clanci_edit)) $clanci_edit = new cclanci_edit();

// Page init
$clanci_edit->Page_Init();

// Page main
$clanci_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clanci_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var clanci_edit = new ew_Page("clanci_edit");
clanci_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = clanci_edit.PageID; // For backward compatibility

// Form object
var fclanciedit = new ew_Form("fclanciedit");

// Validate form
fclanciedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_autor1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->autor1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_institucija1");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->institucija1->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_naslov_eng");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->naslov_eng->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_naslov_mne");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->naslov_mne->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_sazetak_eng");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->sazetak_eng->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_sazetak_mne");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->sazetak_mne->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_keywords_eng");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->keywords_eng->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_keywords_mne");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->keywords_mne->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tip");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->tip->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_file");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->file->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_broj");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->broj->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_broj");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($clanci->broj->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_str");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->str->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_hits");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->hits->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_hits");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($clanci->hits->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_downloads");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($clanci->downloads->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_downloads");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($clanci->downloads->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fclanciedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclanciedit.ValidateRequired = true;
<?php } else { ?>
fclanciedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclanciedit.Lists["x_autor1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_autor10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclanciedit.Lists["x_institucija10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $clanci_edit->ShowPageHeader(); ?>
<?php
$clanci_edit->ShowMessage();
?>
<form name="fclanciedit" id="fclanciedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="clanci">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_clanciedit" class="table table-bordered table-striped">
<?php if ($clanci->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_clanci_id"><?php echo $clanci->id->FldCaption() ?></span></td>
		<td<?php echo $clanci->id->CellAttributes() ?>>
<span id="el_clanci_id" class="control-group">
<span<?php echo $clanci->id->ViewAttributes() ?>>
<?php echo $clanci->id->EditValue ?></span>
</span>
<input type="hidden" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($clanci->id->CurrentValue) ?>">
<?php echo $clanci->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor1->Visible) { // autor1 ?>
	<tr id="r_autor1">
		<td><span id="elh_clanci_autor1"><?php echo $clanci->autor1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->autor1->CellAttributes() ?>>
<span id="el_clanci_autor1" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor1->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor1->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor1" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_autor1" id="sv_x_autor1" value="<?php echo $clanci->autor1->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor1->PlaceHolder ?>"<?php echo $clanci->autor1->EditAttributes() ?>>&nbsp;<span id="em_x_autor1" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor1" style="display: inline; z-index: 8980"></div>
</span>
<input type="hidden" data-field="x_autor1" name="x_autor1" id="x_autor1" value="<?php echo $clanci->autor1->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor1, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor1" id="q_x_autor1" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor1", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor1") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor1"] = oas;
</script>
&nbsp;<a id="aol_x_autor1" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor1',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor1->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor1, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor1" id="s_x_autor1" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
	<tr id="r_institucija1">
		<td><span id="elh_clanci_institucija1"><?php echo $clanci->institucija1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->institucija1->CellAttributes() ?>>
<span id="el_clanci_institucija1" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija1->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija1->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija1" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_institucija1" id="sv_x_institucija1" value="<?php echo $clanci->institucija1->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija1->PlaceHolder ?>"<?php echo $clanci->institucija1->EditAttributes() ?>>&nbsp;<span id="em_x_institucija1" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija1" style="display: inline; z-index: 8970"></div>
</span>
<input type="hidden" data-field="x_institucija1" name="x_institucija1" id="x_institucija1" value="<?php echo $clanci->institucija1->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija1, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija1" id="q_x_institucija1" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija1", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija1") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija1"] = oas;
</script>
&nbsp;<a id="aol_x_institucija1" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija1',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija1->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija1, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija1" id="s_x_institucija1" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija1->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor2->Visible) { // autor2 ?>
	<tr id="r_autor2">
		<td><span id="elh_clanci_autor2"><?php echo $clanci->autor2->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor2->CellAttributes() ?>>
<span id="el_clanci_autor2" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor2->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor2->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor2" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_autor2" id="sv_x_autor2" value="<?php echo $clanci->autor2->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor2->PlaceHolder ?>"<?php echo $clanci->autor2->EditAttributes() ?>>&nbsp;<span id="em_x_autor2" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor2" style="display: inline; z-index: 8960"></div>
</span>
<input type="hidden" data-field="x_autor2" name="x_autor2" id="x_autor2" value="<?php echo $clanci->autor2->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor2, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor2" id="q_x_autor2" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor2", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor2") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor2"] = oas;
</script>
&nbsp;<a id="aol_x_autor2" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor2',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor2->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor2, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor2" id="s_x_autor2" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
	<tr id="r_institucija2">
		<td><span id="elh_clanci_institucija2"><?php echo $clanci->institucija2->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija2->CellAttributes() ?>>
<span id="el_clanci_institucija2" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija2->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija2->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija2" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_institucija2" id="sv_x_institucija2" value="<?php echo $clanci->institucija2->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija2->PlaceHolder ?>"<?php echo $clanci->institucija2->EditAttributes() ?>>&nbsp;<span id="em_x_institucija2" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija2" style="display: inline; z-index: 8950"></div>
</span>
<input type="hidden" data-field="x_institucija2" name="x_institucija2" id="x_institucija2" value="<?php echo $clanci->institucija2->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija2, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija2" id="q_x_institucija2" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija2", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija2") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija2"] = oas;
</script>
&nbsp;<a id="aol_x_institucija2" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija2',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija2->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija2, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija2" id="s_x_institucija2" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija2->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor3->Visible) { // autor3 ?>
	<tr id="r_autor3">
		<td><span id="elh_clanci_autor3"><?php echo $clanci->autor3->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor3->CellAttributes() ?>>
<span id="el_clanci_autor3" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor3->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor3->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor3" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_autor3" id="sv_x_autor3" value="<?php echo $clanci->autor3->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor3->PlaceHolder ?>"<?php echo $clanci->autor3->EditAttributes() ?>>&nbsp;<span id="em_x_autor3" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor3" style="display: inline; z-index: 8940"></div>
</span>
<input type="hidden" data-field="x_autor3" name="x_autor3" id="x_autor3" value="<?php echo $clanci->autor3->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor3, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor3" id="q_x_autor3" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor3", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor3") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor3"] = oas;
</script>
&nbsp;<a id="aol_x_autor3" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor3',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor3->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor3, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor3" id="s_x_autor3" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
	<tr id="r_institucija3">
		<td><span id="elh_clanci_institucija3"><?php echo $clanci->institucija3->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija3->CellAttributes() ?>>
<span id="el_clanci_institucija3" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija3->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija3->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija3" style="white-space: nowrap; z-index: 8930">
	<input type="text" name="sv_x_institucija3" id="sv_x_institucija3" value="<?php echo $clanci->institucija3->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija3->PlaceHolder ?>"<?php echo $clanci->institucija3->EditAttributes() ?>>&nbsp;<span id="em_x_institucija3" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija3" style="display: inline; z-index: 8930"></div>
</span>
<input type="hidden" data-field="x_institucija3" name="x_institucija3" id="x_institucija3" value="<?php echo $clanci->institucija3->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija3, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija3" id="q_x_institucija3" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija3", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija3") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija3"] = oas;
</script>
&nbsp;<a id="aol_x_institucija3" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija3',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija3->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija3, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija3" id="s_x_institucija3" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija3->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor4->Visible) { // autor4 ?>
	<tr id="r_autor4">
		<td><span id="elh_clanci_autor4"><?php echo $clanci->autor4->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor4->CellAttributes() ?>>
<span id="el_clanci_autor4" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor4->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor4->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor4" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_autor4" id="sv_x_autor4" value="<?php echo $clanci->autor4->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor4->PlaceHolder ?>"<?php echo $clanci->autor4->EditAttributes() ?>>&nbsp;<span id="em_x_autor4" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor4" style="display: inline; z-index: 8920"></div>
</span>
<input type="hidden" data-field="x_autor4" name="x_autor4" id="x_autor4" value="<?php echo $clanci->autor4->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor4, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor4" id="q_x_autor4" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor4", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor4") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor4"] = oas;
</script>
&nbsp;<a id="aol_x_autor4" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor4',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor4->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor4, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor4" id="s_x_autor4" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
	<tr id="r_institucija4">
		<td><span id="elh_clanci_institucija4"><?php echo $clanci->institucija4->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija4->CellAttributes() ?>>
<span id="el_clanci_institucija4" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija4->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija4->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija4" style="white-space: nowrap; z-index: 8910">
	<input type="text" name="sv_x_institucija4" id="sv_x_institucija4" value="<?php echo $clanci->institucija4->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija4->PlaceHolder ?>"<?php echo $clanci->institucija4->EditAttributes() ?>>&nbsp;<span id="em_x_institucija4" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija4" style="display: inline; z-index: 8910"></div>
</span>
<input type="hidden" data-field="x_institucija4" name="x_institucija4" id="x_institucija4" value="<?php echo $clanci->institucija4->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija4, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija4" id="q_x_institucija4" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija4", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija4") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija4"] = oas;
</script>
&nbsp;<a id="aol_x_institucija4" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija4',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija4->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija4, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija4" id="s_x_institucija4" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija4->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor5->Visible) { // autor5 ?>
	<tr id="r_autor5">
		<td><span id="elh_clanci_autor5"><?php echo $clanci->autor5->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor5->CellAttributes() ?>>
<span id="el_clanci_autor5" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor5->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor5->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor5" style="white-space: nowrap; z-index: 8900">
	<input type="text" name="sv_x_autor5" id="sv_x_autor5" value="<?php echo $clanci->autor5->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor5->PlaceHolder ?>"<?php echo $clanci->autor5->EditAttributes() ?>>&nbsp;<span id="em_x_autor5" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor5" style="display: inline; z-index: 8900"></div>
</span>
<input type="hidden" data-field="x_autor5" name="x_autor5" id="x_autor5" value="<?php echo $clanci->autor5->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor5, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor5" id="q_x_autor5" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor5", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor5") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor5"] = oas;
</script>
&nbsp;<a id="aol_x_autor5" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor5',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor5->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor5, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor5" id="s_x_autor5" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
	<tr id="r_institucija5">
		<td><span id="elh_clanci_institucija5"><?php echo $clanci->institucija5->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija5->CellAttributes() ?>>
<span id="el_clanci_institucija5" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija5->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija5->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija5" style="white-space: nowrap; z-index: 8890">
	<input type="text" name="sv_x_institucija5" id="sv_x_institucija5" value="<?php echo $clanci->institucija5->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija5->PlaceHolder ?>"<?php echo $clanci->institucija5->EditAttributes() ?>>&nbsp;<span id="em_x_institucija5" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija5" style="display: inline; z-index: 8890"></div>
</span>
<input type="hidden" data-field="x_institucija5" name="x_institucija5" id="x_institucija5" value="<?php echo $clanci->institucija5->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija5, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija5" id="q_x_institucija5" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija5", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija5") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija5"] = oas;
</script>
&nbsp;<a id="aol_x_institucija5" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija5',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija5->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija5, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija5" id="s_x_institucija5" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor6->Visible) { // autor6 ?>
	<tr id="r_autor6">
		<td><span id="elh_clanci_autor6"><?php echo $clanci->autor6->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor6->CellAttributes() ?>>
<span id="el_clanci_autor6" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor6->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor6->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor6" style="white-space: nowrap; z-index: 8880">
	<input type="text" name="sv_x_autor6" id="sv_x_autor6" value="<?php echo $clanci->autor6->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor6->PlaceHolder ?>"<?php echo $clanci->autor6->EditAttributes() ?>>&nbsp;<span id="em_x_autor6" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor6" style="display: inline; z-index: 8880"></div>
</span>
<input type="hidden" data-field="x_autor6" name="x_autor6" id="x_autor6" value="<?php echo $clanci->autor6->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor6, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor6" id="q_x_autor6" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor6", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor6") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor6"] = oas;
</script>
&nbsp;<a id="aol_x_autor6" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor6',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor6->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor6, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor6" id="s_x_autor6" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor6->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
	<tr id="r_institucija6">
		<td><span id="elh_clanci_institucija6"><?php echo $clanci->institucija6->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija6->CellAttributes() ?>>
<span id="el_clanci_institucija6" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija6->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija6->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija6" style="white-space: nowrap; z-index: 8870">
	<input type="text" name="sv_x_institucija6" id="sv_x_institucija6" value="<?php echo $clanci->institucija6->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija6->PlaceHolder ?>"<?php echo $clanci->institucija6->EditAttributes() ?>>&nbsp;<span id="em_x_institucija6" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija6" style="display: inline; z-index: 8870"></div>
</span>
<input type="hidden" data-field="x_institucija6" name="x_institucija6" id="x_institucija6" value="<?php echo $clanci->institucija6->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija6, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija6" id="q_x_institucija6" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija6", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija6") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija6"] = oas;
</script>
&nbsp;<a id="aol_x_institucija6" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija6',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija6->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija6, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija6" id="s_x_institucija6" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija6->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor7->Visible) { // autor7 ?>
	<tr id="r_autor7">
		<td><span id="elh_clanci_autor7"><?php echo $clanci->autor7->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor7->CellAttributes() ?>>
<span id="el_clanci_autor7" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor7->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor7->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor7" style="white-space: nowrap; z-index: 8860">
	<input type="text" name="sv_x_autor7" id="sv_x_autor7" value="<?php echo $clanci->autor7->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor7->PlaceHolder ?>"<?php echo $clanci->autor7->EditAttributes() ?>>&nbsp;<span id="em_x_autor7" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor7" style="display: inline; z-index: 8860"></div>
</span>
<input type="hidden" data-field="x_autor7" name="x_autor7" id="x_autor7" value="<?php echo $clanci->autor7->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor7, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor7" id="q_x_autor7" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor7", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor7") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor7"] = oas;
</script>
&nbsp;<a id="aol_x_autor7" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor7',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor7->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor7, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor7" id="s_x_autor7" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor7->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
	<tr id="r_institucija7">
		<td><span id="elh_clanci_institucija7"><?php echo $clanci->institucija7->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija7->CellAttributes() ?>>
<span id="el_clanci_institucija7" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija7->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija7->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija7" style="white-space: nowrap; z-index: 8850">
	<input type="text" name="sv_x_institucija7" id="sv_x_institucija7" value="<?php echo $clanci->institucija7->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija7->PlaceHolder ?>"<?php echo $clanci->institucija7->EditAttributes() ?>>&nbsp;<span id="em_x_institucija7" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija7" style="display: inline; z-index: 8850"></div>
</span>
<input type="hidden" data-field="x_institucija7" name="x_institucija7" id="x_institucija7" value="<?php echo $clanci->institucija7->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija7, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija7" id="q_x_institucija7" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija7", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija7") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija7"] = oas;
</script>
&nbsp;<a id="aol_x_institucija7" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija7',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija7->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija7, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija7" id="s_x_institucija7" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija7->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor8->Visible) { // autor8 ?>
	<tr id="r_autor8">
		<td><span id="elh_clanci_autor8"><?php echo $clanci->autor8->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor8->CellAttributes() ?>>
<span id="el_clanci_autor8" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor8->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor8->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor8" style="white-space: nowrap; z-index: 8840">
	<input type="text" name="sv_x_autor8" id="sv_x_autor8" value="<?php echo $clanci->autor8->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor8->PlaceHolder ?>"<?php echo $clanci->autor8->EditAttributes() ?>>&nbsp;<span id="em_x_autor8" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor8" style="display: inline; z-index: 8840"></div>
</span>
<input type="hidden" data-field="x_autor8" name="x_autor8" id="x_autor8" value="<?php echo $clanci->autor8->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor8, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor8" id="q_x_autor8" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor8", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor8") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor8"] = oas;
</script>
&nbsp;<a id="aol_x_autor8" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor8',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor8->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor8, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor8" id="s_x_autor8" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor8->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
	<tr id="r_institucija8">
		<td><span id="elh_clanci_institucija8"><?php echo $clanci->institucija8->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija8->CellAttributes() ?>>
<span id="el_clanci_institucija8" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija8->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija8->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija8" style="white-space: nowrap; z-index: 8830">
	<input type="text" name="sv_x_institucija8" id="sv_x_institucija8" value="<?php echo $clanci->institucija8->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija8->PlaceHolder ?>"<?php echo $clanci->institucija8->EditAttributes() ?>>&nbsp;<span id="em_x_institucija8" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija8" style="display: inline; z-index: 8830"></div>
</span>
<input type="hidden" data-field="x_institucija8" name="x_institucija8" id="x_institucija8" value="<?php echo $clanci->institucija8->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija8, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija8" id="q_x_institucija8" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija8", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija8") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija8"] = oas;
</script>
&nbsp;<a id="aol_x_institucija8" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija8',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija8->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija8, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija8" id="s_x_institucija8" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija8->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor9->Visible) { // autor9 ?>
	<tr id="r_autor9">
		<td><span id="elh_clanci_autor9"><?php echo $clanci->autor9->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor9->CellAttributes() ?>>
<span id="el_clanci_autor9" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor9->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor9->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor9" style="white-space: nowrap; z-index: 8820">
	<input type="text" name="sv_x_autor9" id="sv_x_autor9" value="<?php echo $clanci->autor9->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor9->PlaceHolder ?>"<?php echo $clanci->autor9->EditAttributes() ?>>&nbsp;<span id="em_x_autor9" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor9" style="display: inline; z-index: 8820"></div>
</span>
<input type="hidden" data-field="x_autor9" name="x_autor9" id="x_autor9" value="<?php echo $clanci->autor9->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor9, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor9" id="q_x_autor9" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor9", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor9") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor9"] = oas;
</script>
&nbsp;<a id="aol_x_autor9" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor9',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor9->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor9, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor9" id="s_x_autor9" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor9->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
	<tr id="r_institucija9">
		<td><span id="elh_clanci_institucija9"><?php echo $clanci->institucija9->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija9->CellAttributes() ?>>
<span id="el_clanci_institucija9" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija9->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija9->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija9" style="white-space: nowrap; z-index: 8810">
	<input type="text" name="sv_x_institucija9" id="sv_x_institucija9" value="<?php echo $clanci->institucija9->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija9->PlaceHolder ?>"<?php echo $clanci->institucija9->EditAttributes() ?>>&nbsp;<span id="em_x_institucija9" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija9" style="display: inline; z-index: 8810"></div>
</span>
<input type="hidden" data-field="x_institucija9" name="x_institucija9" id="x_institucija9" value="<?php echo $clanci->institucija9->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija9, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija9" id="q_x_institucija9" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija9", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija9") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija9"] = oas;
</script>
&nbsp;<a id="aol_x_institucija9" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija9',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija9->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija9, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija9" id="s_x_institucija9" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija9->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->autor10->Visible) { // autor10 ?>
	<tr id="r_autor10">
		<td><span id="elh_clanci_autor10"><?php echo $clanci->autor10->FldCaption() ?></span></td>
		<td<?php echo $clanci->autor10->CellAttributes() ?>>
<span id="el_clanci_autor10" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->autor10->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->autor10->EditAttrs["onchange"] = "";
?>
<span id="as_x_autor10" style="white-space: nowrap; z-index: 8800">
	<input type="text" name="sv_x_autor10" id="sv_x_autor10" value="<?php echo $clanci->autor10->EditValue ?>" size="30" placeholder="<?php echo $clanci->autor10->PlaceHolder ?>"<?php echo $clanci->autor10->EditAttributes() ?>>&nbsp;<span id="em_x_autor10" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_autor10" style="display: inline; z-index: 8800"></div>
</span>
<input type="hidden" data-field="x_autor10" name="x_autor10" id="x_autor10" value="<?php echo $clanci->autor10->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld` FROM `autori`";
$sWhereWrk = "`autor_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor10, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_autor10" id="q_x_autor10" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_autor10", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_autor10") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_autor10"] = oas;
</script>
&nbsp;<a id="aol_x_autor10" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_autor10',url:'autoriaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->autor10->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `autor_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `autori`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->autor10, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `autor_eng` ASC";
?>
<input type="hidden" name="s_x_autor10" id="s_x_autor10" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->autor10->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
	<tr id="r_institucija10">
		<td><span id="elh_clanci_institucija10"><?php echo $clanci->institucija10->FldCaption() ?></span></td>
		<td<?php echo $clanci->institucija10->CellAttributes() ?>>
<span id="el_clanci_institucija10" class="control-group">
<?php
	$wrkonchange = trim(" " . @$clanci->institucija10->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$clanci->institucija10->EditAttrs["onchange"] = "";
?>
<span id="as_x_institucija10" style="white-space: nowrap; z-index: 8790">
	<input type="text" name="sv_x_institucija10" id="sv_x_institucija10" value="<?php echo $clanci->institucija10->EditValue ?>" size="30" placeholder="<?php echo $clanci->institucija10->PlaceHolder ?>"<?php echo $clanci->institucija10->EditAttributes() ?>>&nbsp;<span id="em_x_institucija10" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_institucija10" style="display: inline; z-index: 8790"></div>
</span>
<input type="hidden" data-field="x_institucija10" name="x_institucija10" id="x_institucija10" value="<?php echo $clanci->institucija10->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld` FROM `institucije`";
$sWhereWrk = "`institucija_eng` LIKE '{query_value}%'";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija10, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_institucija10" id="q_x_institucija10" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_institucija10", fclanciedit, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_institucija10") + ar[i] : "";
	return dv;
}
fclanciedit.AutoSuggests["x_institucija10"] = oas;
</script>
&nbsp;<a id="aol_x_institucija10" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_institucija10',url:'institucijeaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $clanci->institucija10->FldCaption() ?></a>
<?php
$sSqlWrk = "SELECT `id`, `institucija_eng` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institucije`";
$sWhereWrk = "{filter}";

// Call Lookup selecting
$clanci->Lookup_Selecting($clanci->institucija10, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY `institucija_eng` ASC";
?>
<input type="hidden" name="s_x_institucija10" id="s_x_institucija10" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&t0=3">
</span>
<?php echo $clanci->institucija10->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
	<tr id="r_naslov_eng">
		<td><span id="elh_clanci_naslov_eng"><?php echo $clanci->naslov_eng->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->naslov_eng->CellAttributes() ?>>
<span id="el_clanci_naslov_eng" class="control-group">
<textarea data-field="x_naslov_eng" name="x_naslov_eng" id="x_naslov_eng" cols="35" rows="4" placeholder="<?php echo $clanci->naslov_eng->PlaceHolder ?>"<?php echo $clanci->naslov_eng->EditAttributes() ?>><?php echo $clanci->naslov_eng->EditValue ?></textarea>
</span>
<?php echo $clanci->naslov_eng->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
	<tr id="r_naslov_mne">
		<td><span id="elh_clanci_naslov_mne"><?php echo $clanci->naslov_mne->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->naslov_mne->CellAttributes() ?>>
<span id="el_clanci_naslov_mne" class="control-group">
<textarea data-field="x_naslov_mne" name="x_naslov_mne" id="x_naslov_mne" cols="35" rows="4" placeholder="<?php echo $clanci->naslov_mne->PlaceHolder ?>"<?php echo $clanci->naslov_mne->EditAttributes() ?>><?php echo $clanci->naslov_mne->EditValue ?></textarea>
</span>
<?php echo $clanci->naslov_mne->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->sazetak_eng->Visible) { // sazetak_eng ?>
	<tr id="r_sazetak_eng">
		<td><span id="elh_clanci_sazetak_eng"><?php echo $clanci->sazetak_eng->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->sazetak_eng->CellAttributes() ?>>
<span id="el_clanci_sazetak_eng" class="control-group">
<textarea data-field="x_sazetak_eng" name="x_sazetak_eng" id="x_sazetak_eng" cols="35" rows="4" placeholder="<?php echo $clanci->sazetak_eng->PlaceHolder ?>"<?php echo $clanci->sazetak_eng->EditAttributes() ?>><?php echo $clanci->sazetak_eng->EditValue ?></textarea>
</span>
<?php echo $clanci->sazetak_eng->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->sazetak_mne->Visible) { // sazetak_mne ?>
	<tr id="r_sazetak_mne">
		<td><span id="elh_clanci_sazetak_mne"><?php echo $clanci->sazetak_mne->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->sazetak_mne->CellAttributes() ?>>
<span id="el_clanci_sazetak_mne" class="control-group">
<textarea data-field="x_sazetak_mne" name="x_sazetak_mne" id="x_sazetak_mne" cols="35" rows="4" placeholder="<?php echo $clanci->sazetak_mne->PlaceHolder ?>"<?php echo $clanci->sazetak_mne->EditAttributes() ?>><?php echo $clanci->sazetak_mne->EditValue ?></textarea>
</span>
<?php echo $clanci->sazetak_mne->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
	<tr id="r_keywords_eng">
		<td><span id="elh_clanci_keywords_eng"><?php echo $clanci->keywords_eng->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->keywords_eng->CellAttributes() ?>>
<span id="el_clanci_keywords_eng" class="control-group">
<textarea data-field="x_keywords_eng" name="x_keywords_eng" id="x_keywords_eng" cols="35" rows="4" placeholder="<?php echo $clanci->keywords_eng->PlaceHolder ?>"<?php echo $clanci->keywords_eng->EditAttributes() ?>><?php echo $clanci->keywords_eng->EditValue ?></textarea>
</span>
<?php echo $clanci->keywords_eng->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
	<tr id="r_keywords_mne">
		<td><span id="elh_clanci_keywords_mne"><?php echo $clanci->keywords_mne->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->keywords_mne->CellAttributes() ?>>
<span id="el_clanci_keywords_mne" class="control-group">
<textarea data-field="x_keywords_mne" name="x_keywords_mne" id="x_keywords_mne" cols="35" rows="4" placeholder="<?php echo $clanci->keywords_mne->PlaceHolder ?>"<?php echo $clanci->keywords_mne->EditAttributes() ?>><?php echo $clanci->keywords_mne->EditValue ?></textarea>
</span>
<?php echo $clanci->keywords_mne->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->tip->Visible) { // tip ?>
	<tr id="r_tip">
		<td><span id="elh_clanci_tip"><?php echo $clanci->tip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->tip->CellAttributes() ?>>
<span id="el_clanci_tip" class="control-group">
<input type="text" data-field="x_tip" name="x_tip" id="x_tip" size="30" maxlength="255" placeholder="<?php echo $clanci->tip->PlaceHolder ?>" value="<?php echo $clanci->tip->EditValue ?>"<?php echo $clanci->tip->EditAttributes() ?>>
</span>
<?php echo $clanci->tip->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->file->Visible) { // file ?>
	<tr id="r_file">
		<td><span id="elh_clanci_file"><?php echo $clanci->file->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->file->CellAttributes() ?>>
<span id="el_clanci_file" class="control-group">
<input type="text" data-field="x_file" name="x_file" id="x_file" size="30" maxlength="255" placeholder="<?php echo $clanci->file->PlaceHolder ?>" value="<?php echo $clanci->file->EditValue ?>"<?php echo $clanci->file->EditAttributes() ?>>
</span>
<?php echo $clanci->file->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->broj->Visible) { // broj ?>
	<tr id="r_broj">
		<td><span id="elh_clanci_broj"><?php echo $clanci->broj->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->broj->CellAttributes() ?>>
<span id="el_clanci_broj" class="control-group">
<input type="text" data-field="x_broj" name="x_broj" id="x_broj" size="30" placeholder="<?php echo $clanci->broj->PlaceHolder ?>" value="<?php echo $clanci->broj->EditValue ?>"<?php echo $clanci->broj->EditAttributes() ?>>
</span>
<?php echo $clanci->broj->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->str->Visible) { // str ?>
	<tr id="r_str">
		<td><span id="elh_clanci_str"><?php echo $clanci->str->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->str->CellAttributes() ?>>
<span id="el_clanci_str" class="control-group">
<input type="text" data-field="x_str" name="x_str" id="x_str" size="30" maxlength="32" placeholder="<?php echo $clanci->str->PlaceHolder ?>" value="<?php echo $clanci->str->EditValue ?>"<?php echo $clanci->str->EditAttributes() ?>>
</span>
<?php echo $clanci->str->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->hits->Visible) { // hits ?>
	<tr id="r_hits">
		<td><span id="elh_clanci_hits"><?php echo $clanci->hits->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->hits->CellAttributes() ?>>
<span id="el_clanci_hits" class="control-group">
<input type="text" data-field="x_hits" name="x_hits" id="x_hits" size="30" placeholder="<?php echo $clanci->hits->PlaceHolder ?>" value="<?php echo $clanci->hits->EditValue ?>"<?php echo $clanci->hits->EditAttributes() ?>>
</span>
<?php echo $clanci->hits->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->references->Visible) { // references ?>
	<tr id="r_references">
		<td><span id="elh_clanci_references"><?php echo $clanci->references->FldCaption() ?></span></td>
		<td<?php echo $clanci->references->CellAttributes() ?>>
<span id="el_clanci_references" class="control-group">
<textarea data-field="x_references" name="x_references" id="x_references" cols="35" rows="4" placeholder="<?php echo $clanci->references->PlaceHolder ?>"<?php echo $clanci->references->EditAttributes() ?>><?php echo $clanci->references->EditValue ?></textarea>
</span>
<?php echo $clanci->references->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->citation->Visible) { // citation ?>
	<tr id="r_citation">
		<td><span id="elh_clanci_citation"><?php echo $clanci->citation->FldCaption() ?></span></td>
		<td<?php echo $clanci->citation->CellAttributes() ?>>
<span id="el_clanci_citation" class="control-group">
<textarea data-field="x_citation" name="x_citation" id="x_citation" cols="35" rows="4" placeholder="<?php echo $clanci->citation->PlaceHolder ?>"<?php echo $clanci->citation->EditAttributes() ?>><?php echo $clanci->citation->EditValue ?></textarea>
</span>
<?php echo $clanci->citation->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->doi->Visible) { // doi ?>
	<tr id="r_doi">
		<td><span id="elh_clanci_doi"><?php echo $clanci->doi->FldCaption() ?></span></td>
		<td<?php echo $clanci->doi->CellAttributes() ?>>
<span id="el_clanci_doi" class="control-group">
<input type="text" data-field="x_doi" name="x_doi" id="x_doi" size="30" maxlength="255" placeholder="<?php echo $clanci->doi->PlaceHolder ?>" value="<?php echo $clanci->doi->EditValue ?>"<?php echo $clanci->doi->EditAttributes() ?>>
</span>
<?php echo $clanci->doi->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->udk->Visible) { // udk ?>
	<tr id="r_udk">
		<td><span id="elh_clanci_udk"><?php echo $clanci->udk->FldCaption() ?></span></td>
		<td<?php echo $clanci->udk->CellAttributes() ?>>
<span id="el_clanci_udk" class="control-group">
<input type="text" data-field="x_udk" name="x_udk" id="x_udk" size="30" maxlength="255" placeholder="<?php echo $clanci->udk->PlaceHolder ?>" value="<?php echo $clanci->udk->EditValue ?>"<?php echo $clanci->udk->EditAttributes() ?>>
</span>
<?php echo $clanci->udk->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
	<tr id="r_scopus_id">
		<td><span id="elh_clanci_scopus_id"><?php echo $clanci->scopus_id->FldCaption() ?></span></td>
		<td<?php echo $clanci->scopus_id->CellAttributes() ?>>
<span id="el_clanci_scopus_id" class="control-group">
<input type="text" data-field="x_scopus_id" name="x_scopus_id" id="x_scopus_id" size="30" maxlength="32" placeholder="<?php echo $clanci->scopus_id->PlaceHolder ?>" value="<?php echo $clanci->scopus_id->EditValue ?>"<?php echo $clanci->scopus_id->EditAttributes() ?>>
</span>
<?php echo $clanci->scopus_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->lastip->Visible) { // lastip ?>
	<tr id="r_lastip">
		<td><span id="elh_clanci_lastip"><?php echo $clanci->lastip->FldCaption() ?></span></td>
		<td<?php echo $clanci->lastip->CellAttributes() ?>>
<span id="el_clanci_lastip" class="control-group">
<input type="text" data-field="x_lastip" name="x_lastip" id="x_lastip" size="30" maxlength="16" placeholder="<?php echo $clanci->lastip->PlaceHolder ?>" value="<?php echo $clanci->lastip->EditValue ?>"<?php echo $clanci->lastip->EditAttributes() ?>>
</span>
<?php echo $clanci->lastip->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
	<tr id="r_lastdownloadip">
		<td><span id="elh_clanci_lastdownloadip"><?php echo $clanci->lastdownloadip->FldCaption() ?></span></td>
		<td<?php echo $clanci->lastdownloadip->CellAttributes() ?>>
<span id="el_clanci_lastdownloadip" class="control-group">
<input type="text" data-field="x_lastdownloadip" name="x_lastdownloadip" id="x_lastdownloadip" size="30" maxlength="16" placeholder="<?php echo $clanci->lastdownloadip->PlaceHolder ?>" value="<?php echo $clanci->lastdownloadip->EditValue ?>"<?php echo $clanci->lastdownloadip->EditAttributes() ?>>
</span>
<?php echo $clanci->lastdownloadip->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($clanci->downloads->Visible) { // downloads ?>
	<tr id="r_downloads">
		<td><span id="elh_clanci_downloads"><?php echo $clanci->downloads->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $clanci->downloads->CellAttributes() ?>>
<span id="el_clanci_downloads" class="control-group">
<input type="text" data-field="x_downloads" name="x_downloads" id="x_downloads" size="30" placeholder="<?php echo $clanci->downloads->PlaceHolder ?>" value="<?php echo $clanci->downloads->EditValue ?>"<?php echo $clanci->downloads->EditAttributes() ?>>
</span>
<?php echo $clanci->downloads->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fclanciedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$clanci_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$clanci_edit->Page_Terminate();
?>
