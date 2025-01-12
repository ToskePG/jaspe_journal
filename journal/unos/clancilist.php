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

$clanci_list = NULL; // Initialize page object first

class cclanci_list extends cclanci {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{280688C0-E75A-4602-948C-8B7C3B174051}";

	// Table name
	var $TableName = 'clanci';

	// Page object name
	var $PageObjName = 'clanci_list';

	// Grid form hidden field names
	var $FormName = 'fclancilist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "clanciadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "clancidelete.php";
		$this->MultiUpdateUrl = "clanciupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'clanci', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->naslov_eng, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->naslov_mne, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sazetak_eng, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->sazetak_mne, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->keywords_eng, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->keywords_mne, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->tip, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->file, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->str, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->udk, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->references, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->citation, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->doi, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->scopus_id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->lastip, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->lastdownloadip, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->autor1); // autor1
			$this->UpdateSort($this->institucija1); // institucija1
			$this->UpdateSort($this->autor2); // autor2
			$this->UpdateSort($this->institucija2); // institucija2
			$this->UpdateSort($this->autor3); // autor3
			$this->UpdateSort($this->institucija3); // institucija3
			$this->UpdateSort($this->autor4); // autor4
			$this->UpdateSort($this->institucija4); // institucija4
			$this->UpdateSort($this->autor5); // autor5
			$this->UpdateSort($this->institucija5); // institucija5
			$this->UpdateSort($this->autor6); // autor6
			$this->UpdateSort($this->institucija6); // institucija6
			$this->UpdateSort($this->autor7); // autor7
			$this->UpdateSort($this->institucija7); // institucija7
			$this->UpdateSort($this->autor8); // autor8
			$this->UpdateSort($this->institucija8); // institucija8
			$this->UpdateSort($this->autor9); // autor9
			$this->UpdateSort($this->institucija9); // institucija9
			$this->UpdateSort($this->autor10); // autor10
			$this->UpdateSort($this->institucija10); // institucija10
			$this->UpdateSort($this->naslov_eng); // naslov_eng
			$this->UpdateSort($this->naslov_mne); // naslov_mne
			$this->UpdateSort($this->keywords_eng); // keywords_eng
			$this->UpdateSort($this->keywords_mne); // keywords_mne
			$this->UpdateSort($this->tip); // tip
			$this->UpdateSort($this->file); // file
			$this->UpdateSort($this->broj); // broj
			$this->UpdateSort($this->str); // str
			$this->UpdateSort($this->udk); // udk
			$this->UpdateSort($this->hits); // hits
			$this->UpdateSort($this->references); // references
			$this->UpdateSort($this->citation); // citation
			$this->UpdateSort($this->doi); // doi
			$this->UpdateSort($this->scopus_id); // scopus_id
			$this->UpdateSort($this->lastip); // lastip
			$this->UpdateSort($this->lastdownloadip); // lastdownloadip
			$this->UpdateSort($this->downloads); // downloads
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->id->setSort("");
				$this->autor1->setSort("");
				$this->institucija1->setSort("");
				$this->autor2->setSort("");
				$this->institucija2->setSort("");
				$this->autor3->setSort("");
				$this->institucija3->setSort("");
				$this->autor4->setSort("");
				$this->institucija4->setSort("");
				$this->autor5->setSort("");
				$this->institucija5->setSort("");
				$this->autor6->setSort("");
				$this->institucija6->setSort("");
				$this->autor7->setSort("");
				$this->institucija7->setSort("");
				$this->autor8->setSort("");
				$this->institucija8->setSort("");
				$this->autor9->setSort("");
				$this->institucija9->setSort("");
				$this->autor10->setSort("");
				$this->institucija10->setSort("");
				$this->naslov_eng->setSort("");
				$this->naslov_mne->setSort("");
				$this->keywords_eng->setSort("");
				$this->keywords_mne->setSort("");
				$this->tip->setSort("");
				$this->file->setSort("");
				$this->broj->setSort("");
				$this->str->setSort("");
				$this->udk->setSort("");
				$this->hits->setSort("");
				$this->references->setSort("");
				$this->citation->setSort("");
				$this->doi->setSort("");
				$this->scopus_id->setSort("");
				$this->lastip->setSort("");
				$this->lastdownloadip->setSort("");
				$this->downloads->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'></label>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fclancilist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$item->Body = "<a id=\"emf_clanci\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_clanci',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fclancilist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($clanci_list)) $clanci_list = new cclanci_list();

// Page init
$clanci_list->Page_Init();

// Page main
$clanci_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$clanci_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($clanci->Export == "") { ?>
<script type="text/javascript">

// Page object
var clanci_list = new ew_Page("clanci_list");
clanci_list.PageID = "list"; // Page ID
var EW_PAGE_ID = clanci_list.PageID; // For backward compatibility

// Form object
var fclancilist = new ew_Form("fclancilist");
fclancilist.FormKeyCountName = '<?php echo $clanci_list->FormKeyCountName ?>';

// Form_CustomValidate event
fclancilist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fclancilist.ValidateRequired = true;
<?php } else { ?>
fclancilist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fclancilist.Lists["x_autor1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija3"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija4"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija5"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija6"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija7"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija8"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija9"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_autor10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_autor_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fclancilist.Lists["x_institucija10"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institucija_eng","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fclancilistsrch = new ew_Form("fclancilistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($clanci->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($clanci_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $clanci_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$clanci_list->TotalRecs = $clanci->SelectRecordCount();
	} else {
		if ($clanci_list->Recordset = $clanci_list->LoadRecordset())
			$clanci_list->TotalRecs = $clanci_list->Recordset->RecordCount();
	}
	$clanci_list->StartRec = 1;
	if ($clanci_list->DisplayRecs <= 0 || ($clanci->Export <> "" && $clanci->ExportAll)) // Display all records
		$clanci_list->DisplayRecs = $clanci_list->TotalRecs;
	if (!($clanci->Export <> "" && $clanci->ExportAll))
		$clanci_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$clanci_list->Recordset = $clanci_list->LoadRecordset($clanci_list->StartRec-1, $clanci_list->DisplayRecs);
$clanci_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($clanci->Export == "" && $clanci->CurrentAction == "") { ?>
<form name="fclancilistsrch" id="fclancilistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fclancilistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fclancilistsrch_SearchGroup" href="#fclancilistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fclancilistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fclancilistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="clanci">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($clanci_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $clanci_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($clanci_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($clanci_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($clanci_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php } ?>
<?php $clanci_list->ShowPageHeader(); ?>
<?php
$clanci_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<?php if ($clanci->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($clanci->CurrentAction <> "gridadd" && $clanci->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($clanci_list->Pager)) $clanci_list->Pager = new cPrevNextPager($clanci_list->StartRec, $clanci_list->DisplayRecs, $clanci_list->TotalRecs) ?>
<?php if ($clanci_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($clanci_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($clanci_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $clanci_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($clanci_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($clanci_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $clanci_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $clanci_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $clanci_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $clanci_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($clanci_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
<?php if ($clanci_list->TotalRecs > 0) { ?>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
<input type="hidden" name="t" value="clanci">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="input-small" onchange="this.form.submit();">
<option value="10"<?php if ($clanci_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($clanci_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($clanci_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($clanci_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($clanci->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</td>
<?php } ?>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($clanci_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
</div>
<?php } ?>
<form name="fclancilist" id="fclancilist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="clanci">
<div id="gmp_clanci" class="ewGridMiddlePanel">
<?php if ($clanci_list->TotalRecs > 0) { ?>
<table id="tbl_clancilist" class="ewTable ewTableSeparate">
<?php echo $clanci->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$clanci_list->RenderListOptions();

// Render list options (header, left)
$clanci_list->ListOptions->Render("header", "left");
?>
<?php if ($clanci->id->Visible) { // id ?>
	<?php if ($clanci->SortUrl($clanci->id) == "") { ?>
		<td><div id="elh_clanci_id" class="clanci_id"><div class="ewTableHeaderCaption"><?php echo $clanci->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->id) ?>',1);"><div id="elh_clanci_id" class="clanci_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor1->Visible) { // autor1 ?>
	<?php if ($clanci->SortUrl($clanci->autor1) == "") { ?>
		<td><div id="elh_clanci_autor1" class="clanci_autor1"><div class="ewTableHeaderCaption"><?php echo $clanci->autor1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor1) ?>',1);"><div id="elh_clanci_autor1" class="clanci_autor1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
	<?php if ($clanci->SortUrl($clanci->institucija1) == "") { ?>
		<td><div id="elh_clanci_institucija1" class="clanci_institucija1"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija1) ?>',1);"><div id="elh_clanci_institucija1" class="clanci_institucija1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor2->Visible) { // autor2 ?>
	<?php if ($clanci->SortUrl($clanci->autor2) == "") { ?>
		<td><div id="elh_clanci_autor2" class="clanci_autor2"><div class="ewTableHeaderCaption"><?php echo $clanci->autor2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor2) ?>',1);"><div id="elh_clanci_autor2" class="clanci_autor2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
	<?php if ($clanci->SortUrl($clanci->institucija2) == "") { ?>
		<td><div id="elh_clanci_institucija2" class="clanci_institucija2"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija2) ?>',1);"><div id="elh_clanci_institucija2" class="clanci_institucija2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor3->Visible) { // autor3 ?>
	<?php if ($clanci->SortUrl($clanci->autor3) == "") { ?>
		<td><div id="elh_clanci_autor3" class="clanci_autor3"><div class="ewTableHeaderCaption"><?php echo $clanci->autor3->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor3) ?>',1);"><div id="elh_clanci_autor3" class="clanci_autor3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
	<?php if ($clanci->SortUrl($clanci->institucija3) == "") { ?>
		<td><div id="elh_clanci_institucija3" class="clanci_institucija3"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija3->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija3) ?>',1);"><div id="elh_clanci_institucija3" class="clanci_institucija3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor4->Visible) { // autor4 ?>
	<?php if ($clanci->SortUrl($clanci->autor4) == "") { ?>
		<td><div id="elh_clanci_autor4" class="clanci_autor4"><div class="ewTableHeaderCaption"><?php echo $clanci->autor4->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor4) ?>',1);"><div id="elh_clanci_autor4" class="clanci_autor4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
	<?php if ($clanci->SortUrl($clanci->institucija4) == "") { ?>
		<td><div id="elh_clanci_institucija4" class="clanci_institucija4"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija4->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija4) ?>',1);"><div id="elh_clanci_institucija4" class="clanci_institucija4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor5->Visible) { // autor5 ?>
	<?php if ($clanci->SortUrl($clanci->autor5) == "") { ?>
		<td><div id="elh_clanci_autor5" class="clanci_autor5"><div class="ewTableHeaderCaption"><?php echo $clanci->autor5->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor5) ?>',1);"><div id="elh_clanci_autor5" class="clanci_autor5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
	<?php if ($clanci->SortUrl($clanci->institucija5) == "") { ?>
		<td><div id="elh_clanci_institucija5" class="clanci_institucija5"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija5->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija5) ?>',1);"><div id="elh_clanci_institucija5" class="clanci_institucija5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor6->Visible) { // autor6 ?>
	<?php if ($clanci->SortUrl($clanci->autor6) == "") { ?>
		<td><div id="elh_clanci_autor6" class="clanci_autor6"><div class="ewTableHeaderCaption"><?php echo $clanci->autor6->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor6) ?>',1);"><div id="elh_clanci_autor6" class="clanci_autor6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
	<?php if ($clanci->SortUrl($clanci->institucija6) == "") { ?>
		<td><div id="elh_clanci_institucija6" class="clanci_institucija6"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija6->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija6) ?>',1);"><div id="elh_clanci_institucija6" class="clanci_institucija6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor7->Visible) { // autor7 ?>
	<?php if ($clanci->SortUrl($clanci->autor7) == "") { ?>
		<td><div id="elh_clanci_autor7" class="clanci_autor7"><div class="ewTableHeaderCaption"><?php echo $clanci->autor7->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor7) ?>',1);"><div id="elh_clanci_autor7" class="clanci_autor7">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor7->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
	<?php if ($clanci->SortUrl($clanci->institucija7) == "") { ?>
		<td><div id="elh_clanci_institucija7" class="clanci_institucija7"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija7->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija7) ?>',1);"><div id="elh_clanci_institucija7" class="clanci_institucija7">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija7->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor8->Visible) { // autor8 ?>
	<?php if ($clanci->SortUrl($clanci->autor8) == "") { ?>
		<td><div id="elh_clanci_autor8" class="clanci_autor8"><div class="ewTableHeaderCaption"><?php echo $clanci->autor8->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor8) ?>',1);"><div id="elh_clanci_autor8" class="clanci_autor8">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor8->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
	<?php if ($clanci->SortUrl($clanci->institucija8) == "") { ?>
		<td><div id="elh_clanci_institucija8" class="clanci_institucija8"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija8->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija8) ?>',1);"><div id="elh_clanci_institucija8" class="clanci_institucija8">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija8->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor9->Visible) { // autor9 ?>
	<?php if ($clanci->SortUrl($clanci->autor9) == "") { ?>
		<td><div id="elh_clanci_autor9" class="clanci_autor9"><div class="ewTableHeaderCaption"><?php echo $clanci->autor9->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor9) ?>',1);"><div id="elh_clanci_autor9" class="clanci_autor9">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor9->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
	<?php if ($clanci->SortUrl($clanci->institucija9) == "") { ?>
		<td><div id="elh_clanci_institucija9" class="clanci_institucija9"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija9->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija9) ?>',1);"><div id="elh_clanci_institucija9" class="clanci_institucija9">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija9->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->autor10->Visible) { // autor10 ?>
	<?php if ($clanci->SortUrl($clanci->autor10) == "") { ?>
		<td><div id="elh_clanci_autor10" class="clanci_autor10"><div class="ewTableHeaderCaption"><?php echo $clanci->autor10->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->autor10) ?>',1);"><div id="elh_clanci_autor10" class="clanci_autor10">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->autor10->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->autor10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->autor10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
	<?php if ($clanci->SortUrl($clanci->institucija10) == "") { ?>
		<td><div id="elh_clanci_institucija10" class="clanci_institucija10"><div class="ewTableHeaderCaption"><?php echo $clanci->institucija10->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->institucija10) ?>',1);"><div id="elh_clanci_institucija10" class="clanci_institucija10">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->institucija10->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->institucija10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->institucija10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
	<?php if ($clanci->SortUrl($clanci->naslov_eng) == "") { ?>
		<td><div id="elh_clanci_naslov_eng" class="clanci_naslov_eng"><div class="ewTableHeaderCaption"><?php echo $clanci->naslov_eng->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->naslov_eng) ?>',1);"><div id="elh_clanci_naslov_eng" class="clanci_naslov_eng">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->naslov_eng->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->naslov_eng->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->naslov_eng->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
	<?php if ($clanci->SortUrl($clanci->naslov_mne) == "") { ?>
		<td><div id="elh_clanci_naslov_mne" class="clanci_naslov_mne"><div class="ewTableHeaderCaption"><?php echo $clanci->naslov_mne->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->naslov_mne) ?>',1);"><div id="elh_clanci_naslov_mne" class="clanci_naslov_mne">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->naslov_mne->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->naslov_mne->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->naslov_mne->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
	<?php if ($clanci->SortUrl($clanci->keywords_eng) == "") { ?>
		<td><div id="elh_clanci_keywords_eng" class="clanci_keywords_eng"><div class="ewTableHeaderCaption"><?php echo $clanci->keywords_eng->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->keywords_eng) ?>',1);"><div id="elh_clanci_keywords_eng" class="clanci_keywords_eng">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->keywords_eng->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->keywords_eng->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->keywords_eng->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
	<?php if ($clanci->SortUrl($clanci->keywords_mne) == "") { ?>
		<td><div id="elh_clanci_keywords_mne" class="clanci_keywords_mne"><div class="ewTableHeaderCaption"><?php echo $clanci->keywords_mne->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->keywords_mne) ?>',1);"><div id="elh_clanci_keywords_mne" class="clanci_keywords_mne">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->keywords_mne->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->keywords_mne->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->keywords_mne->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->tip->Visible) { // tip ?>
	<?php if ($clanci->SortUrl($clanci->tip) == "") { ?>
		<td><div id="elh_clanci_tip" class="clanci_tip"><div class="ewTableHeaderCaption"><?php echo $clanci->tip->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->tip) ?>',1);"><div id="elh_clanci_tip" class="clanci_tip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->tip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->tip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->tip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->file->Visible) { // file ?>
	<?php if ($clanci->SortUrl($clanci->file) == "") { ?>
		<td><div id="elh_clanci_file" class="clanci_file"><div class="ewTableHeaderCaption"><?php echo $clanci->file->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->file) ?>',1);"><div id="elh_clanci_file" class="clanci_file">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->file->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->file->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->file->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->broj->Visible) { // broj ?>
	<?php if ($clanci->SortUrl($clanci->broj) == "") { ?>
		<td><div id="elh_clanci_broj" class="clanci_broj"><div class="ewTableHeaderCaption"><?php echo $clanci->broj->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->broj) ?>',1);"><div id="elh_clanci_broj" class="clanci_broj">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->broj->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->broj->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->broj->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->str->Visible) { // str ?>
	<?php if ($clanci->SortUrl($clanci->str) == "") { ?>
		<td><div id="elh_clanci_str" class="clanci_str"><div class="ewTableHeaderCaption"><?php echo $clanci->str->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->str) ?>',1);"><div id="elh_clanci_str" class="clanci_str">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->str->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->str->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->str->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->udk->Visible) { // udk ?>
	<?php if ($clanci->SortUrl($clanci->udk) == "") { ?>
		<td><div id="elh_clanci_udk" class="clanci_udk"><div class="ewTableHeaderCaption"><?php echo $clanci->udk->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->udk) ?>',1);"><div id="elh_clanci_udk" class="clanci_udk">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->udk->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->udk->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->udk->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->hits->Visible) { // hits ?>
	<?php if ($clanci->SortUrl($clanci->hits) == "") { ?>
		<td><div id="elh_clanci_hits" class="clanci_hits"><div class="ewTableHeaderCaption"><?php echo $clanci->hits->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->hits) ?>',1);"><div id="elh_clanci_hits" class="clanci_hits">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->hits->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->hits->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->hits->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->references->Visible) { // references ?>
	<?php if ($clanci->SortUrl($clanci->references) == "") { ?>
		<td><div id="elh_clanci_references" class="clanci_references"><div class="ewTableHeaderCaption"><?php echo $clanci->references->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->references) ?>',1);"><div id="elh_clanci_references" class="clanci_references">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->references->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->references->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->references->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->citation->Visible) { // citation ?>
	<?php if ($clanci->SortUrl($clanci->citation) == "") { ?>
		<td><div id="elh_clanci_citation" class="clanci_citation"><div class="ewTableHeaderCaption"><?php echo $clanci->citation->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->citation) ?>',1);"><div id="elh_clanci_citation" class="clanci_citation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->citation->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->citation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->citation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->doi->Visible) { // doi ?>
	<?php if ($clanci->SortUrl($clanci->doi) == "") { ?>
		<td><div id="elh_clanci_doi" class="clanci_doi"><div class="ewTableHeaderCaption"><?php echo $clanci->doi->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->doi) ?>',1);"><div id="elh_clanci_doi" class="clanci_doi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->doi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->doi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->doi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
	<?php if ($clanci->SortUrl($clanci->scopus_id) == "") { ?>
		<td><div id="elh_clanci_scopus_id" class="clanci_scopus_id"><div class="ewTableHeaderCaption"><?php echo $clanci->scopus_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->scopus_id) ?>',1);"><div id="elh_clanci_scopus_id" class="clanci_scopus_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->scopus_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->scopus_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->scopus_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->lastip->Visible) { // lastip ?>
	<?php if ($clanci->SortUrl($clanci->lastip) == "") { ?>
		<td><div id="elh_clanci_lastip" class="clanci_lastip"><div class="ewTableHeaderCaption"><?php echo $clanci->lastip->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->lastip) ?>',1);"><div id="elh_clanci_lastip" class="clanci_lastip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->lastip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->lastip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->lastip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
	<?php if ($clanci->SortUrl($clanci->lastdownloadip) == "") { ?>
		<td><div id="elh_clanci_lastdownloadip" class="clanci_lastdownloadip"><div class="ewTableHeaderCaption"><?php echo $clanci->lastdownloadip->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->lastdownloadip) ?>',1);"><div id="elh_clanci_lastdownloadip" class="clanci_lastdownloadip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->lastdownloadip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($clanci->lastdownloadip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->lastdownloadip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($clanci->downloads->Visible) { // downloads ?>
	<?php if ($clanci->SortUrl($clanci->downloads) == "") { ?>
		<td><div id="elh_clanci_downloads" class="clanci_downloads"><div class="ewTableHeaderCaption"><?php echo $clanci->downloads->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $clanci->SortUrl($clanci->downloads) ?>',1);"><div id="elh_clanci_downloads" class="clanci_downloads">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $clanci->downloads->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($clanci->downloads->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($clanci->downloads->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$clanci_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($clanci->ExportAll && $clanci->Export <> "") {
	$clanci_list->StopRec = $clanci_list->TotalRecs;
} else {

	// Set the last record to display
	if ($clanci_list->TotalRecs > $clanci_list->StartRec + $clanci_list->DisplayRecs - 1)
		$clanci_list->StopRec = $clanci_list->StartRec + $clanci_list->DisplayRecs - 1;
	else
		$clanci_list->StopRec = $clanci_list->TotalRecs;
}
$clanci_list->RecCnt = $clanci_list->StartRec - 1;
if ($clanci_list->Recordset && !$clanci_list->Recordset->EOF) {
	$clanci_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $clanci_list->StartRec > 1)
		$clanci_list->Recordset->Move($clanci_list->StartRec - 1);
} elseif (!$clanci->AllowAddDeleteRow && $clanci_list->StopRec == 0) {
	$clanci_list->StopRec = $clanci->GridAddRowCount;
}

// Initialize aggregate
$clanci->RowType = EW_ROWTYPE_AGGREGATEINIT;
$clanci->ResetAttrs();
$clanci_list->RenderRow();
while ($clanci_list->RecCnt < $clanci_list->StopRec) {
	$clanci_list->RecCnt++;
	if (intval($clanci_list->RecCnt) >= intval($clanci_list->StartRec)) {
		$clanci_list->RowCnt++;

		// Set up key count
		$clanci_list->KeyCount = $clanci_list->RowIndex;

		// Init row class and style
		$clanci->ResetAttrs();
		$clanci->CssClass = "";
		if ($clanci->CurrentAction == "gridadd") {
		} else {
			$clanci_list->LoadRowValues($clanci_list->Recordset); // Load row values
		}
		$clanci->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$clanci->RowAttrs = array_merge($clanci->RowAttrs, array('data-rowindex'=>$clanci_list->RowCnt, 'id'=>'r' . $clanci_list->RowCnt . '_clanci', 'data-rowtype'=>$clanci->RowType));

		// Render row
		$clanci_list->RenderRow();

		// Render list options
		$clanci_list->RenderListOptions();
?>
	<tr<?php echo $clanci->RowAttributes() ?>>
<?php

// Render list options (body, left)
$clanci_list->ListOptions->Render("body", "left", $clanci_list->RowCnt);
?>
	<?php if ($clanci->id->Visible) { // id ?>
		<td<?php echo $clanci->id->CellAttributes() ?>>
<span<?php echo $clanci->id->ViewAttributes() ?>>
<?php echo $clanci->id->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor1->Visible) { // autor1 ?>
		<td<?php echo $clanci->autor1->CellAttributes() ?>>
<span<?php echo $clanci->autor1->ViewAttributes() ?>>
<?php echo $clanci->autor1->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija1->Visible) { // institucija1 ?>
		<td<?php echo $clanci->institucija1->CellAttributes() ?>>
<span<?php echo $clanci->institucija1->ViewAttributes() ?>>
<?php echo $clanci->institucija1->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor2->Visible) { // autor2 ?>
		<td<?php echo $clanci->autor2->CellAttributes() ?>>
<span<?php echo $clanci->autor2->ViewAttributes() ?>>
<?php echo $clanci->autor2->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija2->Visible) { // institucija2 ?>
		<td<?php echo $clanci->institucija2->CellAttributes() ?>>
<span<?php echo $clanci->institucija2->ViewAttributes() ?>>
<?php echo $clanci->institucija2->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor3->Visible) { // autor3 ?>
		<td<?php echo $clanci->autor3->CellAttributes() ?>>
<span<?php echo $clanci->autor3->ViewAttributes() ?>>
<?php echo $clanci->autor3->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija3->Visible) { // institucija3 ?>
		<td<?php echo $clanci->institucija3->CellAttributes() ?>>
<span<?php echo $clanci->institucija3->ViewAttributes() ?>>
<?php echo $clanci->institucija3->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor4->Visible) { // autor4 ?>
		<td<?php echo $clanci->autor4->CellAttributes() ?>>
<span<?php echo $clanci->autor4->ViewAttributes() ?>>
<?php echo $clanci->autor4->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija4->Visible) { // institucija4 ?>
		<td<?php echo $clanci->institucija4->CellAttributes() ?>>
<span<?php echo $clanci->institucija4->ViewAttributes() ?>>
<?php echo $clanci->institucija4->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor5->Visible) { // autor5 ?>
		<td<?php echo $clanci->autor5->CellAttributes() ?>>
<span<?php echo $clanci->autor5->ViewAttributes() ?>>
<?php echo $clanci->autor5->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija5->Visible) { // institucija5 ?>
		<td<?php echo $clanci->institucija5->CellAttributes() ?>>
<span<?php echo $clanci->institucija5->ViewAttributes() ?>>
<?php echo $clanci->institucija5->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor6->Visible) { // autor6 ?>
		<td<?php echo $clanci->autor6->CellAttributes() ?>>
<span<?php echo $clanci->autor6->ViewAttributes() ?>>
<?php echo $clanci->autor6->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija6->Visible) { // institucija6 ?>
		<td<?php echo $clanci->institucija6->CellAttributes() ?>>
<span<?php echo $clanci->institucija6->ViewAttributes() ?>>
<?php echo $clanci->institucija6->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor7->Visible) { // autor7 ?>
		<td<?php echo $clanci->autor7->CellAttributes() ?>>
<span<?php echo $clanci->autor7->ViewAttributes() ?>>
<?php echo $clanci->autor7->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija7->Visible) { // institucija7 ?>
		<td<?php echo $clanci->institucija7->CellAttributes() ?>>
<span<?php echo $clanci->institucija7->ViewAttributes() ?>>
<?php echo $clanci->institucija7->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor8->Visible) { // autor8 ?>
		<td<?php echo $clanci->autor8->CellAttributes() ?>>
<span<?php echo $clanci->autor8->ViewAttributes() ?>>
<?php echo $clanci->autor8->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija8->Visible) { // institucija8 ?>
		<td<?php echo $clanci->institucija8->CellAttributes() ?>>
<span<?php echo $clanci->institucija8->ViewAttributes() ?>>
<?php echo $clanci->institucija8->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor9->Visible) { // autor9 ?>
		<td<?php echo $clanci->autor9->CellAttributes() ?>>
<span<?php echo $clanci->autor9->ViewAttributes() ?>>
<?php echo $clanci->autor9->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija9->Visible) { // institucija9 ?>
		<td<?php echo $clanci->institucija9->CellAttributes() ?>>
<span<?php echo $clanci->institucija9->ViewAttributes() ?>>
<?php echo $clanci->institucija9->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->autor10->Visible) { // autor10 ?>
		<td<?php echo $clanci->autor10->CellAttributes() ?>>
<span<?php echo $clanci->autor10->ViewAttributes() ?>>
<?php echo $clanci->autor10->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->institucija10->Visible) { // institucija10 ?>
		<td<?php echo $clanci->institucija10->CellAttributes() ?>>
<span<?php echo $clanci->institucija10->ViewAttributes() ?>>
<?php echo $clanci->institucija10->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->naslov_eng->Visible) { // naslov_eng ?>
		<td<?php echo $clanci->naslov_eng->CellAttributes() ?>>
<span<?php echo $clanci->naslov_eng->ViewAttributes() ?>>
<?php echo $clanci->naslov_eng->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->naslov_mne->Visible) { // naslov_mne ?>
		<td<?php echo $clanci->naslov_mne->CellAttributes() ?>>
<span<?php echo $clanci->naslov_mne->ViewAttributes() ?>>
<?php echo $clanci->naslov_mne->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->keywords_eng->Visible) { // keywords_eng ?>
		<td<?php echo $clanci->keywords_eng->CellAttributes() ?>>
<span<?php echo $clanci->keywords_eng->ViewAttributes() ?>>
<?php echo $clanci->keywords_eng->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->keywords_mne->Visible) { // keywords_mne ?>
		<td<?php echo $clanci->keywords_mne->CellAttributes() ?>>
<span<?php echo $clanci->keywords_mne->ViewAttributes() ?>>
<?php echo $clanci->keywords_mne->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->tip->Visible) { // tip ?>
		<td<?php echo $clanci->tip->CellAttributes() ?>>
<span<?php echo $clanci->tip->ViewAttributes() ?>>
<?php echo $clanci->tip->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->file->Visible) { // file ?>
		<td<?php echo $clanci->file->CellAttributes() ?>>
<span<?php echo $clanci->file->ViewAttributes() ?>>
<?php echo $clanci->file->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->broj->Visible) { // broj ?>
		<td<?php echo $clanci->broj->CellAttributes() ?>>
<span<?php echo $clanci->broj->ViewAttributes() ?>>
<?php echo $clanci->broj->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->str->Visible) { // str ?>
		<td<?php echo $clanci->str->CellAttributes() ?>>
<span<?php echo $clanci->str->ViewAttributes() ?>>
<?php echo $clanci->str->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->udk->Visible) { // udk ?>
		<td<?php echo $clanci->udk->CellAttributes() ?>>
<span<?php echo $clanci->udk->ViewAttributes() ?>>
<?php echo $clanci->udk->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->hits->Visible) { // hits ?>
		<td<?php echo $clanci->hits->CellAttributes() ?>>
<span<?php echo $clanci->hits->ViewAttributes() ?>>
<?php echo $clanci->hits->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->references->Visible) { // references ?>
		<td<?php echo $clanci->references->CellAttributes() ?>>
<span<?php echo $clanci->references->ViewAttributes() ?>>
<?php echo $clanci->references->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->citation->Visible) { // citation ?>
		<td<?php echo $clanci->citation->CellAttributes() ?>>
<span<?php echo $clanci->citation->ViewAttributes() ?>>
<?php echo $clanci->citation->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->doi->Visible) { // doi ?>
		<td<?php echo $clanci->doi->CellAttributes() ?>>
<span<?php echo $clanci->doi->ViewAttributes() ?>>
<?php echo $clanci->doi->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->scopus_id->Visible) { // scopus_id ?>
		<td<?php echo $clanci->scopus_id->CellAttributes() ?>>
<span<?php echo $clanci->scopus_id->ViewAttributes() ?>>
<?php echo $clanci->scopus_id->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->lastip->Visible) { // lastip ?>
		<td<?php echo $clanci->lastip->CellAttributes() ?>>
<span<?php echo $clanci->lastip->ViewAttributes() ?>>
<?php echo $clanci->lastip->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->lastdownloadip->Visible) { // lastdownloadip ?>
		<td<?php echo $clanci->lastdownloadip->CellAttributes() ?>>
<span<?php echo $clanci->lastdownloadip->ViewAttributes() ?>>
<?php echo $clanci->lastdownloadip->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($clanci->downloads->Visible) { // downloads ?>
		<td<?php echo $clanci->downloads->CellAttributes() ?>>
<span<?php echo $clanci->downloads->ViewAttributes() ?>>
<?php echo $clanci->downloads->ListViewValue() ?></span>
<a id="<?php echo $clanci_list->PageObjName . "_row_" . $clanci_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$clanci_list->ListOptions->Render("body", "right", $clanci_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($clanci->CurrentAction <> "gridadd")
		$clanci_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($clanci->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($clanci_list->Recordset)
	$clanci_list->Recordset->Close();
?>
<?php if ($clanci_list->TotalRecs > 0) { ?>
<?php if ($clanci->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($clanci->CurrentAction <> "gridadd" && $clanci->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($clanci_list->Pager)) $clanci_list->Pager = new cPrevNextPager($clanci_list->StartRec, $clanci_list->DisplayRecs, $clanci_list->TotalRecs) ?>
<?php if ($clanci_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
	<?php echo $Language->Phrase("Page") ?>&nbsp;
<div class="input-prepend input-append">
<!--first page button-->
	<?php if ($clanci_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->FirstButton->Start ?>"><i class="icon-step-backward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-backward"></i></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($clanci_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->PrevButton->Start ?>"><i class="icon-prev"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-prev"></i></a>
	<?php } ?>
<!--current page number-->
	<input class="input-mini" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $clanci_list->Pager->CurrentPage ?>">
<!--next page button-->
	<?php if ($clanci_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->NextButton->Start ?>"><i class="icon-play"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-play"></i></a>
	<?php } ?>
<!--last page button-->
	<?php if ($clanci_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-small" href="<?php echo $clanci_list->PageUrl() ?>start=<?php echo $clanci_list->Pager->LastButton->Start ?>"><i class="icon-step-forward"></i></a>
	<?php } else { ?>
	<a class="btn btn-small" disabled="disabled"><i class="icon-step-forward"></i></a>
	<?php } ?>
</div>
	&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $clanci_list->Pager->PageCount ?>
</td>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $clanci_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $clanci_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $clanci_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($clanci_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
<?php } ?>
</td>
<?php if ($clanci_list->TotalRecs > 0) { ?>
<td>
	&nbsp;&nbsp;&nbsp;&nbsp;
<input type="hidden" name="t" value="clanci">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="input-small" onchange="this.form.submit();">
<option value="10"<?php if ($clanci_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="20"<?php if ($clanci_list->DisplayRecs == 20) { ?> selected="selected"<?php } ?>>20</option>
<option value="50"<?php if ($clanci_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($clanci_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($clanci->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</td>
<?php } ?>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($clanci_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
<?php } ?>
</td></tr></table>
<?php if ($clanci->Export == "") { ?>
<script type="text/javascript">
fclancilistsrch.Init();
fclancilist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$clanci_list->ShowPageFooter();
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
$clanci_list->Page_Terminate();
?>
