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

HtmlEditorConfig::get("basic")->setOptions(array(
    "friendly_name" => "basic editor",
    "priority" => 0,
    "mode" => "none",
    "editor_selector" => "htmleditor",
    "auto_resize" => true,
    "theme" => "advanced",
    "skin" => "default",
    // Remove the bottom status bar
    "theme_advanced_statusbar_location" => "none"
));

// Clear the default buttons
HtmlEditorConfig::get("basic")->setButtonsForLine(1, array());
HtmlEditorConfig::get("basic")->setButtonsForLine(2, array());
HtmlEditorConfig::get("basic")->setButtonsForLine(3, array());

// Add desired buttons
HtmlEditorConfig::get("basic")->setButtonsForLine(1, "bold", "italic");
