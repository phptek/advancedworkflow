/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ }),
/* 1 */
/***/ (function(module, exports) {

module.exports = i18n;

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_bundles_advanced_workflow_cms_js__ = __webpack_require__(3);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_bundles_advancedworkflow_management_js__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_bundles_WorkflowField_js__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3_bundles_WorkflowGridField_js__ = __webpack_require__(6);





/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);


__WEBPACK_IMPORTED_MODULE_0_jquery___default.a.entwine('ss', function ($) {
  $('.cms-edit-form').find('.Actions, .btn-toolbar').find('#ActionMenus_WorkflowOptions .action').entwine({
    onclick: function onclick(e) {
      var transitionId = this.attr('data-transitionid');
      var buttonName = this.attr('name');

      buttonName = buttonName.replace(/-\d+/, '');
      this.attr('name', buttonName);

      $('input[name=TransitionID]').val(transitionId);

      this._super(e);
    }
  });

  $('.cms-edit-form').find('.Actions, .btn-toolbar').find('.action.start-workflow').entwine({
    onmouseup: function onmouseup(e) {
      $('input[name=TriggeredWorkflowID]').val(this.data('workflow'));

      var name = this.attr('name');
      this.attr('name', name.replace(/-\d+/, ''));
      this._super(e);
    }
  });
});

/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_i18n__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_i18n___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_i18n__);



__WEBPACK_IMPORTED_MODULE_0_jquery___default.a.entwine('ss', function ($) {
  $('.advancedWorkflowTransition').entwine({
    onclick: function onclick(e) {
      e.preventDefault();

      var comments = prompt('Comments');
      var instanceId = this.parents('ul').attr('data-instance-id');
      var transitionId = this.attr('data-transition-id');
      var securityId = $('[name=SecurityID]').val();
      if (!securityId) {
        alert('Invalid SecurityID field!');
        return false;
      }

      $.post('AdvancedWorkflowActionController/transition', {
        SecurityID: securityId,
        comments: comments,
        transition: transitionId,
        id: instanceId
      }, function (data) {
        if (data) {
          var parsedData = $.parseJSON(data);

          if (parsedData.success) {
            location.href = parsedData.link;
          } else {
            alert(__WEBPACK_IMPORTED_MODULE_1_i18n___default.a._t('Workflow.ProcessError'));
          }
        }
      });

      return false;
    }
  });
});

/***/ }),
/* 5 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_i18n__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_i18n___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_i18n__);
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };




__WEBPACK_IMPORTED_MODULE_0_jquery___default.a.entwine('workflow', function ($) {
  $('.workflow-field').entwine({
    Loading: null,
    Dialog: null,
    onmatch: function onmatch() {
      var self = this;

      this.setLoading(this.find('.workflow-field-loading'));
      this.setDialog(this.find('.workflow-field-dialog'));

      this.getDialog().data('workflow-field', this).dialog({
        autoOpen: false,
        width: 800,
        height: 600,
        modal: true,
        dialogClass: 'workflow-field-editor-dialog'
      });

      this.getDialog().on('click', 'button', function (event) {
        $(event.currentTarget).addClass('disabled');
      });

      this.getDialog().on('submit', 'form', function (event) {
        $(event.currentTarget).ajaxSubmit(function (response) {
          if ($(response).is('.workflow-field')) {
            self.getDialog().empty().dialog('close');
            self.replaceWith(response);
          } else {
            self.getDialog().html(response);
          }
        });

        return false;
      });
    },
    onunmatch: function onunmatch() {
      $('.workflow-field-editor-dialog').remove();
    },
    showDialog: function showDialog(url) {
      var dlg = this.getDialog();

      dlg.empty().dialog('open');
      dlg.parent().addClass('loading');

      $.get(url).done(function (body) {
        dlg.html(body).parent().removeClass('loading');
      });
    },
    loading: function loading(toggle) {
      this.getLoading().toggle(typeof toggle === 'undefined' || toggle);
    }
  });

  $('.workflow-field .workflow-field-actions').entwine({
    onmatch: function onmatch() {
      $('.workflow-field .workflow-field-action-disabled').on('click', function () {
        return false;
      });
      this.sortable({
        axis: 'y',
        containment: this,
        placeholder: 'ui-state-highlight workflow-placeholder',
        handle: '.workflow-field-action-drag',
        tolerance: 'pointer',
        update: function update() {
          var actions = $(this).find('.workflow-field-action');
          var field = $(this).closest('.workflow-field');
          var link = field.data('sort-link');
          var ids = actions.each(function (ind, ele) {
            return $(ele).data('id');
          });
          var data = {
            'id[]': ids,
            class: 'Symbiote\\AdvancedWorkflow\\DataObjects\\WorkflowAction',
            SecurityID: field.data('securityid')
          };

          field.loading();
          $.post(link, data).done(function () {
            field.loading(false);
          });
        }
      });
    }
  });

  $('.workflow-field .workflow-field-action-transitions').entwine({
    onmatch: function onmatch() {
      this.sortable({
        axis: 'y',
        containment: this,
        handle: '.workflow-field-action-drag',
        tolerance: 'pointer',
        update: function update() {
          var trans = $(this).find('li');
          var field = $(this).closest('.workflow-field');
          var link = field.data('sort-link');
          var ids = trans.map(function () {
            return $('li').data('id');
          });
          var data = {
            'id[]': ids.get(),
            class: 'Symbiote\\AdvancedWorkflow\\DataObjects\\WorkflowTransition',
            parent: $(this).closest('.workflow-field-action').data('id'),
            SecurityID: field.data('securityid')
          };

          field.loading();
          $.post(link, data).done(function () {
            field.loading(false);
          });
        }
      });
    }
  });

  $('.workflow-field .workflow-field-create-class').entwine({
    onmatch: function onmatch() {
      this.chosen().addClass('has-chnz');
    },
    onchange: function onchange() {
      this.siblings('.workflow-field-do-create').toggleClass('disabled', !this.val());
    }
  });

  $('.workflow-field .workflow-field-do-create').entwine({
    onclick: function onclick() {
      var sel = this.siblings('.workflow-field-create-class');
      var field = this.closest('.workflow-field');

      if (sel.val()) {
        field.showDialog(sel.val());
      }

      return false;
    }
  });

  $('.workflow-field .workflow-field-open-dialog').entwine({
    onclick: function onclick() {
      this.closest('.workflow-field').showDialog(this.prop('href'));
      return false;
    }
  });

  $('.workflow-field .workflow-field-delete').entwine({
    onclick: function onclick() {
      if (confirm(__WEBPACK_IMPORTED_MODULE_1_i18n___default.a._t('Workflow.DeleteQuestion'))) {
        var data = {
          SecurityID: this.data('securityid')
        };

        $.post(this.prop('href'), data).done(function (body) {
          $('.workflow-field').replaceWith(body);
        });
      }

      return false;
    }
  });

  $('#Root_PublishingSchedule').entwine({
    onclick: function onclick() {
      if (_typeof($.fn.timepicker()) !== 'object' || !$('input.hasTimePicker').length > 0) {
        return false;
      }

      var field = $('input.hasTimePicker');
      var defaultTime = function defaultTime() {
        var date = new Date();
        return date.getHours() + ':' + date.getMinutes();
      };
      var pickerOpts = {
        useLocalTimezone: true,
        defaultValue: defaultTime,
        controlType: 'select',
        timeFormat: 'HH:mm'
      };

      field.timepicker(pickerOpts);
      return false;
    },
    onmatch: function onmatch() {
      var self = this;
      var publishDate = this.find('input[name="PublishOnDate[date]"]');
      var publishTime = this.find('input[name="PublishOnDate[time]"]');
      var parent = publishDate.parent().parent();

      if (!$('#Form_EditForm_action_publish').attr('disabled')) {
        self.checkEmbargo($(publishDate).val(), $(publishTime).val(), parent);

        publishDate.change(function () {
          self.checkEmbargo($(publishDate).val(), $(publishTime).val(), parent);
        });

        publishTime.change(function () {
          self.checkEmbargo($(publishDate).val(), $(publishTime).val(), parent);
        });
      }

      this._super();
    },
    linkScheduled: function linkScheduled(parent) {
      $('#workflow-schedule').click(function () {
        var tabID = parent.closest('.ui-tabs-panel.tab').attr('id');
        $('#tab-' + tabID).trigger('click');
        return false;
      });
    },
    checkEmbargo: function checkEmbargo(publishDate, publishTime, parent) {
      $('.Actions #embargo-message').remove();

      var noPublishDate = publishDate === undefined || publishDate.length === 0;
      var noPublishTime = publishTime === undefined || publishTime.length === 0;

      if (noPublishDate && noPublishTime) {
        $('#Form_EditForm_action_publish').removeClass('embargo');
        $('#Form_EditForm_action_publish').prev('button').removeClass('ui-corner-right');
      } else {
        var message = '';

        $('#Form_EditForm_action_publish').addClass('embargo');
        $('#Form_EditForm_action_publish').prev('button').addClass('ui-corner-right');

        if (publishDate === '') {
          message = __WEBPACK_IMPORTED_MODULE_1_i18n___default.a.sprintf(__WEBPACK_IMPORTED_MODULE_1_i18n___default.a._t('Workflow.EMBARGOMESSAGETIME'), publishTime);
        } else if (publishTime === '') {
          message = __WEBPACK_IMPORTED_MODULE_1_i18n___default.a.sprintf(__WEBPACK_IMPORTED_MODULE_1_i18n___default.a._t('Workflow.EMBARGOMESSAGEDATE'), publishDate);
        } else {
          message = __WEBPACK_IMPORTED_MODULE_1_i18n___default.a.sprintf(__WEBPACK_IMPORTED_MODULE_1_i18n___default.a._t('Workflow.EMBARGOMESSAGEDATETIME'), publishDate, publishTime);
        }

        message = message.replace('<a>', '<a href="#" id="workflow-schedule">');

        $('.Actions #ActionMenus').after('<p class="edit-info" id="embargo-message">' + message + '</p>');

        this.linkScheduled(parent);
      }

      return false;
    }
  });
});

__WEBPACK_IMPORTED_MODULE_0_jquery___default.a.entwine('ss', function ($) {
  $('.importSpec').entwine({
    onmatch: function onmatch() {
      this.hide();
    }
  });

  $('#Form_ImportForm_error').entwine({
    onmatch: function onmatch() {
      this.html(this.html().replace('CSV', ''));
    }
  });

  $('.ss-gridfield .action.no-ajax.export-link').entwine({
    onclick: function onclick() {
      window.location.href = $.path.makeUrlAbsolute(this.attr('href'));

      return false;
    }
  });
});

/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);


__WEBPACK_IMPORTED_MODULE_0_jquery___default.a.entwine('ss', function ($) {
  $('.ss-gridfield .ss-gridfield-item').entwine({
    onmatch: function onmatch() {
      var row = this.closest('tr');

      if (this.find('.col-buttons.disabled').length) {
        row.addClass('disabled').on('click', function (e) {
          if (e.target.nodeName === 'A' && e.target.className.match(/edit-link/) === null) {
            return true;
          }
          return false;
        });

        this.find('a.edit-link').attr('title', '');
      }
    }
  });

  $('.AdvancedWorkflowAdmin .ss-gridfield-item.disabled').entwine({
    onmouseover: function onmouseover() {
      this.css('cursor', 'default');
    }
  });

  $('.ss-gridfield .ss-gridfield-item td.col-Title a').entwine({
    onclick: function onclick(e) {
      e.stopPropagation();
    }
  });

  $('.ss-gridfield .col-buttons .action.gridfield-button-delete, .cms-edit-form .Actions button.action.action-delete').entwine({
    onclick: function onclick(e) {
      this._super(e);
      $('.cms-container').reloadCurrentPanel();
      e.preventDefault();
    }
  });
});

/***/ })
/******/ ]);
//# sourceMappingURL=advancedworkflow.js.map