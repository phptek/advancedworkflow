<?php
/**
 * Extends the File class for some exported-workflow, post-upload logic. 
 * We're able to leverage existing YAML parse/creation logic to create new workflow definitions from previousely
 * exported workflow definitions.
 * 
 * @see {@link WorkflowDefinitionExporter}
 * 
 * This class is pretty much biased toward YAML, as that's what SilverStripe uses for config and that's what already
 * exists in the module for workflow creation logic. However, it's been left open enough to suit adding non-YAML formats
 * if required.
 * 
 * At the moment, exported data is uploaded to the 'assets' area (due to restrictions on Upload targets in {@link UploadField}).
 * 
 * Issues:
 * 
 * - In the case of YML and its being the lingua-franca of SilverStripe config, it doesn't belong in the assets directory
 * - When User relations are exported on transitions/actions, email addresses are included
 * 
 * Solutions:
 * 
 * - Maintain the data as configuration data in system (filesystem) config somewhere (but how to put it there post-upload)?
 * - Dynamically create a non-file DataObject with Title & Content fields, with data from uploaded file. Then run $file->delete()
 * - Assume YML in assets is actually OK provided:
 *	1). It's non-editable; and
 *	2). Sensitive data (User email addresses) once used, are obfuscated viz: 9c3f5204e5381639@ec21c0605327fb61721fbbd4; and
 *	3). We chown it to be non readable by the webserver, post-upload
 * 
 * Notes:
 * 
 * - .yml is actually already disabled by default by SilverStripe's default .htaccess.
 * 
 * @author russell@silverstripe.com
 * @package advancedworkflow
 */
class FileWorkflowExportExtension extends DataExtension {
	
	/**
	 * A field in which to store the raw content string for viewing in Files admin
	 * @var array
	 */
	public static $db = array(
		"ContentRaw" => "Text"
	);
	
	/**
	 * Simply allows users to view the Content field of this imported workflow definition, in the Files admin.
	 * 
	 * @param \FieldList $fields
	 * @return \FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields) {
		if (!$this->owner->ID) {
			return $fields;
		}
		
		parent::updateCMSFields($fields);
		
		$tab = $fields->fieldByName('Root') ? $fields->findOrMakeTab('Root.Main') : $fields;
		$contentField = new LiteralField('ViewableContent', '<pre>'.$this->contentShow('yml').'</pre>');
		$tab->push($contentField);
	}

	/**
	 * Trigger format-specific, post-upload logic for imported, WorkflowDefinition exports.
	 */
	public function onAfterWrite() {
		parent::onAfterWrite();
		$this->formatMarshall();
	}
	
	/**
	 * Perform different tasks dependent on uploaded file format.
	 * 
	 * @param string $format
	 * @return void
	 */
	protected function formatMarshall() {
		$format = Config::inst()->get('WorkflowDefinitionExporter', 'exportFormat');
		switch($format) {
			case 'YML':
			default:
				$this->contentUpdate();
				break;
		}
	}
	
	/**
	 * Reload the Content fields with latest uploaded data.
	 * 
	 * @return void
	 * @todo send controller request for updated $items data for "Choose Template" dropdown menu on WorkflowDefinition
	 */
	protected function contentUpdate() {
		// Parse the uploaded YAML into a PHP array
		$filename = BASE_PATH.'/'.$this->owner->Filename;
		if(file_exists($filename) && is_readable($filename)) {
			$parseImportedYaml = singleton('WorkflowDefinitionImporter')->parseImport($filename);
			// Update the PHP array content of the owner-object
			$oldContentArr = $this->owner->Content;
			$newContentArr = serialize($parseImportedYaml);

			// It's changed, so update it
			if(!$oldContentArr || ($oldContentArr !== $newContentArr)) {
				$this->owner->Content = $newContentArr;
				// Use same detection logic to determine updating ContentRaw field
				$this->owner->ContentRaw = file_get_contents($filename);
				$this->owner->Title = $this->generateUploadName($this->owner->Content);
				$this->owner->write();
			}
		}
		// We want to remove the file from the filesystem or totslly obfuscate it
	}
	
	/**
	 * @param string $format null (PHP array) or 'yml'|'array'
	 * @return string
	 */
	public function contentShow($format = null) {
		if(!$format || $format == 'array') {
			return unserialize($this->owner->Content);
		}
		return $this->owner->ContentRaw;
	}	
	
	/**
	 * Generate a string as a title from the related WorkflowTemplate object, for assigning to $this->owner->Name
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function generateUploadName($content) {
		$content = unserialize($content);
		$template = Injector::Inst()->createWithArgs('WorkflowTemplate', $content['Injector']['ExportedWorkflow']['constructor']);
		return $template->getName();
	}	
}
