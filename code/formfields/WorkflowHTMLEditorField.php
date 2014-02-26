<?php
/**
 * A TinyMCE-powered WYSIWYG HTML editor field with image and link insertion and tracking capabilities. Editor fields
 * are created from <textarea> tags, which are then converted with JavaScript.
 *
 * @author Russell Michell <russell@silverstripe.com>
 * @author Hamish Friedlander <hamish@silverstripe.com>
 * @author Stig Lindvist <stig@silverstripe.com>
 * @package advancedworkflow
 * @todo
 * - Requirements::customScript() isn't working and the original TinyMCE config is still injected into the DOM
 * - Need to ensure that the global JS var 'ssTinyMceConfig' taken from generated JS, takes on a JSON version of _config.php
 */
class WorkflowHtmlEditorField extends HtmlEditorField {
	
	public static $config_name = '';
	
	/**
	 * Includes the JavaScript neccesary for this field to work using the {@link Requirements} system.
	 */
	public static function include_js() {
		//parent::include_js();
		$configObj = HtmlEditorConfig::get(self::$config_name);
//		var_dump($configObj->generateJS());
//		die;
		
		Requirements::customScript($configObj->generateJS(), 'htmlEditorConfig');
	}
	
	/**
	 * @see TextareaField::__construct()
	 */
	public function __construct($configName, $name, $title = null, $value = '') {
		self::$config_name = $configName;
		
		TextareaField::__construct($name, $title, $value);
		
		$this->addExtraClass('typography');
		
		$editorCSSHook = HtmlEditorConfig::get(self::$config_name)->getOption('editor_selector');
		$this->addExtraClass($editorCSSHook);
		
		self::include_js();
	}
}
