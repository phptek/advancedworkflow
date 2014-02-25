<?php
/**
 * A workflow action that notifies users attached to the workflow path that they have a task awaiting them.
 *
 * @license    BSD License (http://silverstripe.org/bsd-license/)
 * @package    advancedworkflow
 * @subpackage actions
 */
class WorkflowBasicHTMLEditorField extends HtmlEditorField {
	
	/**
	 * 
	 * @param type $config
	 * @param type $name
	 * @param type $title
	 * @param type $rows
	 * @param type $cols
	 * @param type $value
	 * @param type $form
	 */
	public function __construct($config, $name, $title = null, $rows = 30, $cols = 20, $value = '', $form = null) {
		// Skip the HtmlEditorField's constructor
		TextareaField::__construct($name, $title, $rows, $cols, $value, $form);

		$this->addExtraClass('typography');
		$this->addExtraClass("htmleditor$config");

		self::include_js($config);
	}	

	/**
	 * 
	 * @param type $configName
	 */
	public static function include_js($configName) {
		Requirements::javascript(MCE_ROOT . 'tiny_mce_src.js');

		$config = HtmlEditorConfig::get($configName);
		$config->setOption('mode', 'none');
		$config->setOption('editor_selector', "htmleditor$configName");

		$js = <<<EOT
Behaviour.register({
    'textarea.htmleditor$configName' : {
        initialize : function() {
            if(typeof tinyMCE != 'undefined'){
                    var oldsettings = tinyMCE.settings;
                    ".$config->generateJS()."
                                        tinyMCE.execCommand('mceAddControl', true, this.id);
                                        tinyMCE.settings = oldsettings;
                                        
                    this.isChanged = function() {
                        return tinyMCE.getInstanceById(this.id).isDirty();
                    }
                    this.resetChanged = function() {
                        inst = tinyMCE.getInstanceById(this.id);
                        if (inst) inst.startContent = tinymce.trim(inst.getContent({format : 'raw', no_events : 1}));
                    }
			}
        }
    }
});
EOT;
		Requirements::customScript($js, "htmlEditorConfig-$configName");
	}
}
