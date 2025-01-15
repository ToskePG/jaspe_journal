<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "ewmysql10.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "brojeviinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$brojevi_add = NULL; // Initialize page object first

class cbrojevi_add extends cbrojevi {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{3D7A0815-42DE-4706-A688-045439C7A049}";

	// Table name
	var $TableName = 'brojevi';

	// Page object name
	var $PageObjName = 'brojevi_add';

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

		// Table object (brojevi)
		if (!isset($GLOBALS["brojevi"])) {
			$GLOBALS["brojevi"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["brojevi"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'brojevi', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("brojevilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "brojeviview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->broj->CurrentValue = NULL;
		$this->broj->OldValue = $this->broj->CurrentValue;
		$this->period_eng->CurrentValue = NULL;
		$this->period_eng->OldValue = $this->period_eng->CurrentValue;
		$this->period_mne->CurrentValue = NULL;
		$this->period_mne->OldValue = $this->period_mne->CurrentValue;
		$this->godina->CurrentValue = NULL;
		$this->godina->OldValue = $this->godina->CurrentValue;
		$this->vol->CurrentValue = NULL;
		$this->vol->OldValue = $this->vol->CurrentValue;
		$this->no->CurrentValue = NULL;
		$this->no->OldValue = $this->no->CurrentValue;
		$this->file->CurrentValue = NULL;
		$this->file->OldValue = $this->file->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->broj->FldIsDetailKey) {
			$this->broj->setFormValue($objForm->GetValue("x_broj"));
		}
		if (!$this->period_eng->FldIsDetailKey) {
			$this->period_eng->setFormValue($objForm->GetValue("x_period_eng"));
		}
		if (!$this->period_mne->FldIsDetailKey) {
			$this->period_mne->setFormValue($objForm->GetValue("x_period_mne"));
		}
		if (!$this->godina->FldIsDetailKey) {
			$this->godina->setFormValue($objForm->GetValue("x_godina"));
		}
		if (!$this->vol->FldIsDetailKey) {
			$this->vol->setFormValue($objForm->GetValue("x_vol"));
		}
		if (!$this->no->FldIsDetailKey) {
			$this->no->setFormValue($objForm->GetValue("x_no"));
		}
		if (!$this->file->FldIsDetailKey) {
			$this->file->setFormValue($objForm->GetValue("x_file"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->broj->CurrentValue = $this->broj->FormValue;
		$this->period_eng->CurrentValue = $this->period_eng->FormValue;
		$this->period_mne->CurrentValue = $this->period_mne->FormValue;
		$this->godina->CurrentValue = $this->godina->FormValue;
		$this->vol->CurrentValue = $this->vol->FormValue;
		$this->no->CurrentValue = $this->no->FormValue;
		$this->file->CurrentValue = $this->file->FormValue;
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
		$this->broj->setDbValue($rs->fields('broj'));
		$this->period_eng->setDbValue($rs->fields('period_eng'));
		$this->period_mne->setDbValue($rs->fields('period_mne'));
		$this->godina->setDbValue($rs->fields('godina'));
		$this->vol->setDbValue($rs->fields('vol'));
		$this->no->setDbValue($rs->fields('no'));
		$this->file->setDbValue($rs->fields('file'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->broj->DbValue = $row['broj'];
		$this->period_eng->DbValue = $row['period_eng'];
		$this->period_mne->DbValue = $row['period_mne'];
		$this->godina->DbValue = $row['godina'];
		$this->vol->DbValue = $row['vol'];
		$this->no->DbValue = $row['no'];
		$this->file->DbValue = $row['file'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		// broj
		// period_eng
		// period_mne
		// godina
		// vol
		// no
		// file

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// broj
			$this->broj->ViewValue = $this->broj->CurrentValue;
			$this->broj->ViewCustomAttributes = "";

			// period_eng
			$this->period_eng->ViewValue = $this->period_eng->CurrentValue;
			$this->period_eng->ViewCustomAttributes = "";

			// period_mne
			$this->period_mne->ViewValue = $this->period_mne->CurrentValue;
			$this->period_mne->ViewCustomAttributes = "";

			// godina
			$this->godina->ViewValue = $this->godina->CurrentValue;
			$this->godina->ViewCustomAttributes = "";

			// vol
			$this->vol->ViewValue = $this->vol->CurrentValue;
			$this->vol->ViewCustomAttributes = "";

			// no
			$this->no->ViewValue = $this->no->CurrentValue;
			$this->no->ViewCustomAttributes = "";

			// file
			$this->file->ViewValue = $this->file->CurrentValue;
			$this->file->ViewCustomAttributes = "";

			// broj
			$this->broj->LinkCustomAttributes = "";
			$this->broj->HrefValue = "";
			$this->broj->TooltipValue = "";

			// period_eng
			$this->period_eng->LinkCustomAttributes = "";
			$this->period_eng->HrefValue = "";
			$this->period_eng->TooltipValue = "";

			// period_mne
			$this->period_mne->LinkCustomAttributes = "";
			$this->period_mne->HrefValue = "";
			$this->period_mne->TooltipValue = "";

			// godina
			$this->godina->LinkCustomAttributes = "";
			$this->godina->HrefValue = "";
			$this->godina->TooltipValue = "";

			// vol
			$this->vol->LinkCustomAttributes = "";
			$this->vol->HrefValue = "";
			$this->vol->TooltipValue = "";

			// no
			$this->no->LinkCustomAttributes = "";
			$this->no->HrefValue = "";
			$this->no->TooltipValue = "";

			// file
			$this->file->LinkCustomAttributes = "";
			$this->file->HrefValue = "";
			$this->file->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// broj
			$this->broj->EditCustomAttributes = "";
			$this->broj->EditValue = ew_HtmlEncode($this->broj->CurrentValue);
			$this->broj->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->broj->FldCaption()));

			// period_eng
			$this->period_eng->EditCustomAttributes = "";
			$this->period_eng->EditValue = ew_HtmlEncode($this->period_eng->CurrentValue);
			$this->period_eng->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->period_eng->FldCaption()));

			// period_mne
			$this->period_mne->EditCustomAttributes = "";
			$this->period_mne->EditValue = ew_HtmlEncode($this->period_mne->CurrentValue);
			$this->period_mne->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->period_mne->FldCaption()));

			// godina
			$this->godina->EditCustomAttributes = "";
			$this->godina->EditValue = ew_HtmlEncode($this->godina->CurrentValue);
			$this->godina->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->godina->FldCaption()));

			// vol
			$this->vol->EditCustomAttributes = "";
			$this->vol->EditValue = ew_HtmlEncode($this->vol->CurrentValue);
			$this->vol->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vol->FldCaption()));

			// no
			$this->no->EditCustomAttributes = "";
			$this->no->EditValue = ew_HtmlEncode($this->no->CurrentValue);
			$this->no->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no->FldCaption()));

			// file
			$this->file->EditCustomAttributes = "";
			$this->file->EditValue = ew_HtmlEncode($this->file->CurrentValue);
			$this->file->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->file->FldCaption()));

			// Edit refer script
			// broj

			$this->broj->HrefValue = "";

			// period_eng
			$this->period_eng->HrefValue = "";

			// period_mne
			$this->period_mne->HrefValue = "";

			// godina
			$this->godina->HrefValue = "";

			// vol
			$this->vol->HrefValue = "";

			// no
			$this->no->HrefValue = "";

			// file
			$this->file->HrefValue = "";
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
		if (!$this->broj->FldIsDetailKey && !is_null($this->broj->FormValue) && $this->broj->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->broj->FldCaption());
		}
		if (!$this->period_eng->FldIsDetailKey && !is_null($this->period_eng->FormValue) && $this->period_eng->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->period_eng->FldCaption());
		}
		if (!$this->period_mne->FldIsDetailKey && !is_null($this->period_mne->FormValue) && $this->period_mne->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->period_mne->FldCaption());
		}
		if (!$this->godina->FldIsDetailKey && !is_null($this->godina->FormValue) && $this->godina->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->godina->FldCaption());
		}
		if (!ew_CheckInteger($this->godina->FormValue)) {
			ew_AddMessage($gsFormError, $this->godina->FldErrMsg());
		}
		if (!$this->vol->FldIsDetailKey && !is_null($this->vol->FormValue) && $this->vol->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vol->FldCaption());
		}
		if (!ew_CheckInteger($this->vol->FormValue)) {
			ew_AddMessage($gsFormError, $this->vol->FldErrMsg());
		}
		if (!$this->no->FldIsDetailKey && !is_null($this->no->FormValue) && $this->no->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no->FldCaption());
		}
		if (!ew_CheckInteger($this->no->FormValue)) {
			ew_AddMessage($gsFormError, $this->no->FldErrMsg());
		}
		if (!$this->file->FldIsDetailKey && !is_null($this->file->FormValue) && $this->file->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->file->FldCaption());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// broj
		$this->broj->SetDbValueDef($rsnew, $this->broj->CurrentValue, "", FALSE);

		// period_eng
		$this->period_eng->SetDbValueDef($rsnew, $this->period_eng->CurrentValue, "", FALSE);

		// period_mne
		$this->period_mne->SetDbValueDef($rsnew, $this->period_mne->CurrentValue, "", FALSE);

		// godina
		$this->godina->SetDbValueDef($rsnew, $this->godina->CurrentValue, 0, FALSE);

		// vol
		$this->vol->SetDbValueDef($rsnew, $this->vol->CurrentValue, "", FALSE);

		// no
		$this->no->SetDbValueDef($rsnew, $this->no->CurrentValue, "", FALSE);

		// file
		$this->file->SetDbValueDef($rsnew, $this->file->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "brojevilist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($brojevi_add)) $brojevi_add = new cbrojevi_add();

// Page init
$brojevi_add->Page_Init();

// Page main
$brojevi_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$brojevi_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var brojevi_add = new ew_Page("brojevi_add");
brojevi_add.PageID = "add"; // Page ID
var EW_PAGE_ID = brojevi_add.PageID; // For backward compatibility

// Form object
var fbrojeviadd = new ew_Form("fbrojeviadd");

// Validate form
fbrojeviadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_broj");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->broj->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_period_eng");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->period_eng->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_period_mne");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->period_mne->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_godina");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->godina->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_godina");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brojevi->godina->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vol");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->vol->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vol");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brojevi->vol->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->no->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($brojevi->no->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_file");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($brojevi->file->FldCaption()) ?>");

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
fbrojeviadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbrojeviadd.ValidateRequired = true;
<?php } else { ?>
fbrojeviadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $brojevi_add->ShowPageHeader(); ?>
<?php
$brojevi_add->ShowMessage();
?>
<form name="fbrojeviadd" id="fbrojeviadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="brojevi">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_brojeviadd" class="table table-bordered table-striped">
<?php if ($brojevi->broj->Visible) { // broj ?>
	<tr id="r_broj">
		<td><span id="elh_brojevi_broj"><?php echo $brojevi->broj->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->broj->CellAttributes() ?>>
<span id="el_brojevi_broj" class="control-group">
<input type="text" data-field="x_broj" name="x_broj" id="x_broj" size="30" maxlength="255" placeholder="<?php echo $brojevi->broj->PlaceHolder ?>" value="<?php echo $brojevi->broj->EditValue ?>"<?php echo $brojevi->broj->EditAttributes() ?>>
</span>
<?php echo $brojevi->broj->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->period_eng->Visible) { // period_eng ?>
	<tr id="r_period_eng">
		<td><span id="elh_brojevi_period_eng"><?php echo $brojevi->period_eng->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->period_eng->CellAttributes() ?>>
<span id="el_brojevi_period_eng" class="control-group">
<input type="text" data-field="x_period_eng" name="x_period_eng" id="x_period_eng" size="30" maxlength="255" placeholder="<?php echo $brojevi->period_eng->PlaceHolder ?>" value="<?php echo $brojevi->period_eng->EditValue ?>"<?php echo $brojevi->period_eng->EditAttributes() ?>>
</span>
<?php echo $brojevi->period_eng->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->period_mne->Visible) { // period_mne ?>
	<tr id="r_period_mne">
		<td><span id="elh_brojevi_period_mne"><?php echo $brojevi->period_mne->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->period_mne->CellAttributes() ?>>
<span id="el_brojevi_period_mne" class="control-group">
<input type="text" data-field="x_period_mne" name="x_period_mne" id="x_period_mne" size="30" maxlength="255" placeholder="<?php echo $brojevi->period_mne->PlaceHolder ?>" value="<?php echo $brojevi->period_mne->EditValue ?>"<?php echo $brojevi->period_mne->EditAttributes() ?>>
</span>
<?php echo $brojevi->period_mne->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->godina->Visible) { // godina ?>
	<tr id="r_godina">
		<td><span id="elh_brojevi_godina"><?php echo $brojevi->godina->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->godina->CellAttributes() ?>>
<span id="el_brojevi_godina" class="control-group">
<input type="text" data-field="x_godina" name="x_godina" id="x_godina" size="30" placeholder="<?php echo $brojevi->godina->PlaceHolder ?>" value="<?php echo $brojevi->godina->EditValue ?>"<?php echo $brojevi->godina->EditAttributes() ?>>
</span>
<?php echo $brojevi->godina->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->vol->Visible) { // vol ?>
	<tr id="r_vol">
		<td><span id="elh_brojevi_vol"><?php echo $brojevi->vol->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->vol->CellAttributes() ?>>
<span id="el_brojevi_vol" class="control-group">
<input type="text" data-field="x_vol" name="x_vol" id="x_vol" size="30" maxlength="16" placeholder="<?php echo $brojevi->vol->PlaceHolder ?>" value="<?php echo $brojevi->vol->EditValue ?>"<?php echo $brojevi->vol->EditAttributes() ?>>
</span>
<?php echo $brojevi->vol->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->no->Visible) { // no ?>
	<tr id="r_no">
		<td><span id="elh_brojevi_no"><?php echo $brojevi->no->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->no->CellAttributes() ?>>
<span id="el_brojevi_no" class="control-group">
<input type="text" data-field="x_no" name="x_no" id="x_no" size="30" maxlength="16" placeholder="<?php echo $brojevi->no->PlaceHolder ?>" value="<?php echo $brojevi->no->EditValue ?>"<?php echo $brojevi->no->EditAttributes() ?>>
</span>
<?php echo $brojevi->no->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($brojevi->file->Visible) { // file ?>
	<tr id="r_file">
		<td><span id="elh_brojevi_file"><?php echo $brojevi->file->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $brojevi->file->CellAttributes() ?>>
<span id="el_brojevi_file" class="control-group">
<input type="text" data-field="x_file" name="x_file" id="x_file" size="30" maxlength="255" placeholder="<?php echo $brojevi->file->PlaceHolder ?>" value="<?php echo $brojevi->file->EditValue ?>"<?php echo $brojevi->file->EditAttributes() ?>>
</span>
<?php echo $brojevi->file->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fbrojeviadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$brojevi_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$brojevi_add->Page_Terminate();
?>
