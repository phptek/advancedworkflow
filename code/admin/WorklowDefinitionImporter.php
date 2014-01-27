<?php
/**
 * Workflow definition import-specific logic. @see {@link WorkflowDefinitionExporter}.
 * 
 * @author  russell@silverstripe.com
 * @license BSD License (http://silverstripe.org/bsd-license/)
 * @package advancedworkflow
 */
class WorkflowDefinitionImporter {
	
	/**
	 * Generates an array of WorkflowTemplate Objects of all uploaded workflows to show in the dropdown "Choose Template" 
	 * on WorkflowDefiniton.
	 * 
	 * @param string $name. If set, a single-value array comprising a WorkflowTemplate object who's first 'constructor' param matches $name
	 *						is returned.
	 * @return WorkflowTemplate $template | array $importedDefs
	 */
	public function getImportedWorkflows($name = null) {
		$imports = DataObject::get('File')
			->filter('Filename:PartialMatch', WorkflowDefinitionExporter::$export_filename_prefix);		
		
		$importedDefs = array();
		foreach($imports as $import) {
			if(!$import->Content) {
				continue;
			}
			$structure = unserialize($import->Content);
			$struct = $structure['Injector']['ExportedWorkflow'];
			// @todo this doesn't feel right...how to _properly_ wrap WorkflowTemplate with Injector??
			$template = Injector::inst()->get('WorkflowTemplate', true, $struct['constructor']);
			$template->setStructure($struct['properties']['structure']);
			if($name) {
				if($struct['constructor'][0] == trim($name)) {
					return $template;
				}
				continue;
			}
			$importedDefs[] = $template;
		}
		return $importedDefs;
	}
	
	/**
	 * Handle finding a yml file. Parse the file by splitting it into header/fragment pairs,
	 * and normalising some of the header values.
	 * 
	 * @see {@link SS_ConfigManifest} from where this logic was taken.
	 * @param string $source
	 * @return array $yamlAsArray
	 */
	public function parseImport($source) {
		if(is_file($source)) {
			// @todo why can't we get at $this->owner->Content??
			$source = file_get_contents($source);
		}

		// Use the Zend copy of this script to prevent class conflicts when RailsYaml is included
		require_once('thirdparty/zend_translate_railsyaml/library/Translate/Adapter/thirdparty/sfYaml/lib/sfYamlParser.php');
		$parser = new sfYamlParser();
		
		// Make sure the linefeeds are all converted to \n, PCRE '$' will not match anything else.
		$source = str_replace(array("\r\n", "\r"), "\n", $source);
		// YAML parsers really should handle this properly themselves, but neither spyc nor symfony-yaml do. So we
		// follow in their vein and just do what we need, not what the spec says
		$parts = preg_split('/^---$/m', $source, -1, PREG_SPLIT_NO_EMPTY);

		// If we got an odd number of parts the config, file doesn't have a header for every document
		if (count($parts) != 2) {
			user_error("The file does not have an equal number of headers and config blocks");
		}

		return $parser->parse($parts[1]);
	}		
}
