<?php
/**
 * Allows workflow definitions to be exported from one SilverStripe install, ready for import into another.
 * 
 * By default, YAML is used for export as it's native to SilverStripe's config system and we're using {@link WorkflowTemplate}
 * for some of the import-specific heavy lifting, which is already heavily predicated on YAML.
 * 
 * Additional formats can be applied by copying the template file and naming it for your desired format e.g. XML, 
 * to become: templates/Includes/WorkflowDefinitionExport_XML.ss.
 * To use the your new format, just change the "exportFormat" and "exportMimeType" vars in _config/workflowconfig.yml.
 * 
 * @author  russell@silverstripe.com
 * @license BSD License (http://silverstripe.org/bsd-license/)
 * @package advancedworkflow
 * @todo
 *	- See @todo on FileWorkflowExportExtension. (YML in assets)
 *	- Re-order Workflow Definition UI, so dropdown is at the top
 *	- Modify docs
 */
class WorkflowDefinitionExporter {
	
	/**
	 * The base filename of the file to the exported
	 * 
	 * @var string
	 */
	public static $export_filename_prefix = 'workflow-definition-export';
	/**
	 *
	 * @var \Member
	 */
	protected $member;
	/**
	 * 
	 * @var \WorkflowDefinition
	 */
	protected $workflowDefinition;
	/**
	 * 
	 * @var array
	 */
	protected $formatMap = array();
	
	/**
	 * 
	 * @param number $definitionID
	 * @return void
	 */
	public function __construct($definitionID) {
		$this->member = Member::currentUser();
		$this->workflowDefinition = DataObject::get_by_id('WorkflowDefinition', $definitionID);
		$this->setFormatMap();
	}
	
	/**
	 * Class methods
	 * -------------
	 */
	
	/**
	 * Runs the export 
	 * 
	 * @return string $template
	 */
	public function export() {
		$templateData = new ArrayData(array(
			'ExportMetaData' => $this->ExportMetaData(),
			'ExportActions' => $this->workflowDefinition->Actions(),
			'ExportUsers' => $this->workflowDefinition->Users(),
			'ExportGroups' => $this->workflowDefinition->Groups() 
		));
		$formatMap = $this->getFormatMap();
		try {
			$template = $this->format($formatMap['suffix'], $templateData);
		}
		catch(Exception $e) {
			user_error($e->getMessage());
		}
		return $template;
	}
	
	/**
	 * Format the exported data as per the passed $format. To add support for other formats,
	 * simply adapt templates/Includes/WorkflowDefinitionExport_YML.ss as required.
	 * 
	 * @param string $format
	 * @param \ArrayData $templateData
	 * @return void
	 */
	public function format($format, $templateData) {
		$tplFlType = strtoupper($format);
		$tplFlName = 'WorkflowDefinitonExport_'.$tplFlType;
		$tplFlPath = BASE_PATH.'/'.ADVANCED_WORKFLOW_DIR.'/templates/includes/'.$tplFlName.'.ss';
		
		if(!file_exists($tplFlPath)) {
			throw new Exception('Sorry; '.$tplFlType.' format is not supported at the moment.');
		}
		
		$viewer = SSViewer::execute_template($tplFlName, $templateData);
		// Temporary until we find the source of the replacement in SSViewer
		$processed = str_replace('&amp;', '&', $viewer);
		// Clean-up newline "gaps" that SSViewer leaves behind from the placement of control structures
		return preg_replace("#^\R+|^[\t\s]*\R+#m", '', $processed);
	}	
	
	/**
	 * Sets the suffix/mime map according to $format
	 * 
	 * @param string $format
	 * @return void
	 */
	public function setFormatMap() {
		$exportConfigFormat = Config::Inst()->get('WorkflowDefinitionExporter', 'exportFormat');
		$frameworkMimes = array_flip(Config::Inst()->get('HTTP', 'MimeTypes'));
		// Locate an authoratative mime first, before 'inventing' our own from config (viz: YML)
		if(!in_array(strtolower($exportConfigFormat), $frameworkMimes)) {
			$exportConfigMimeType = Config::Inst()->get('WorkflowDefinitionExporter', 'exportMimeType');
		}
		$this->formatMap['suffix'] = strtolower($exportConfigFormat);
		$this->formatMap['mime'] = $exportConfigMimeType;
	}
	
	/**
	 * @return array
	 */
	public function getFormatMap() {
		return $this->formatMap;
	}
	
	/**
	 * Returns the size of the current export in bytes.
	 * Used for pushing data to the browser to prompt for download
	 * 
	 * @param string $str
	 * @return number $bytes
	 */
	public function getExportSize($str) {
		return mb_strlen($str, 'latin1');
	}
	
	/**
	 * Template methods
	 * ----------------
	 */

	/**
	 * Generate template vars for metadata
	 * 
	 * @return ArrayData
	 */
	public function ExportMetaData() {
		return new ArrayData(array(
			'ExportHost' => preg_replace("#http(s)?://#", '', Director::protocolAndHost()),
			'ExportDate' => date('d/m/Y H:i:s'),
			'ExportUser' => $this->member->FirstName.' '.$this->member->Surname,
			'ExportVersionFramework' => $this->ssVersion(),
			'ExportWorkflowDefName' => $this->workflowDefinition->Title,
			'ExportRemindDays' => $this->workflowDefinition->RemindDays,
			'ExportSort' => $this->workflowDefinition->Sort
		));
	}
	
	/*
	 * Try different ways of obtaining the current SilverStripe version for YAML output.
	 * 
	 * @return string
	 */
	private function ssVersion() {
		if($version = singleton('SapphireInfo')->Version() != _t('LeftAndMain.VersionUnknown')) {
			return $version;
		}
		return singleton('LeftAndMain')->CMSVersion();
	}
	
	/**
	 * Prompt the client for file download.
	 * We're "overriding" SS_HTTPRequest::send_file() for more robust cross-browser support
	 * 
	 * @param array $filedata
	 * @return \SS_HTTPResponse $response
	 */
	public function sendFile($filedata) {
		$response = new SS_HTTPResponse($filedata['body']);
		if(preg_match("#MSIE\s(6|7|8)?\.0#",$_SERVER['HTTP_USER_AGENT'])) {
			// IE headers
			$response->addHeader("Cache-Control","public");
			$response->addHeader("Content-Disposition","attachment; filename=\"".basename($filedata['name'])."\"");
			$response->addHeader("Content-Type","application/force-download");
			$response->addHeader("Content-Type","application/octet-stream");
			$response->addHeader("Content-Type","application/download");
			$response->addHeader("Content-Type",$filedata['mime']);
			$response->addHeader("Content-Description","File Transfer");
			$response->addHeader("Content-Length",$filedata['size']);	
		}
		else {
			// Everyone else
			$response->addHeader("Content-Type", $filedata['mime']."; name=\"".addslashes($filedata['name'])."\"");
			$response->addHeader("Content-disposition", "attachment; filename=".addslashes($filedata['name']));
			$response->addHeader("Content-Length",$filedata['size']);
		}
		return $response;
	}
	
	/**
	 * Convert a value to be suitable for a YML file.
	 * 
	 * @param string $val String to escape
	 * @return string
	 */
	public static function raw2yaml($val) {
		if(Config::inst()->get('WorkflowDefinitionExporter', 'exportFormat') == 'YML') {
			return str_replace(':', ';', $val);
		}
		return $val;
	}
}
