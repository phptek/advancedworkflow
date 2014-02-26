<?php
/**
 * @license BSD License (http://silverstripe.org/bsd-license/)
 * @package advancedworkflow
 */
define('ADVANCED_WORKFLOW_DIR', basename(dirname(__FILE__)));

if(ADVANCED_WORKFLOW_DIR != 'advancedworkflow') {
	throw new Exception(
		"The advanced workflow module must be in a directory named 'advancedworkflow', not " . ADVANCED_WORKFLOW_DIR
	);
}

LeftAndMain::require_css(ADVANCED_WORKFLOW_DIR . '/css/AdvancedWorkflowAdmin.css');

HtmlEditorConfig::get('basic')->setOptions(array(
	'friendly_name' => 'Basic',
	'priority' => '0',
	'mode' => 'none',					// Initialized through LeftAndMain.EditFor.js logic
    "editor_selector" => "htmleditor",	// Used by TinyMCE as the element identifier
    "auto_resize" => true,
    "theme" => "advanced",
    "skin" => "default",
    "theme_advanced_statusbar_location" => "none"	
));

// @todo http://www.tinymce.com/wiki.php/buttons/controls
HtmlEditorConfig::get('basic')->removeButtons('underline', 'strikethrough', 'html');
HtmlEditorConfig::get("basic")->setButtonsForLine(1, "bold", "italic");
HtmlEditorConfig::get('basic')->setButtonsForLine(2, array());
HtmlEditorConfig::get('basic')->setButtonsForLine(3, array());
HtmlEditorConfig::get('basic')->disablePlugins('contextmenu', 'table', 'emotions', 'paste');

