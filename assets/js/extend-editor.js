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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./_dev/js/guten-block/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./_dev/js/guten-block/i18n.js":
/*!*************************************!*\
  !*** ./_dev/js/guten-block/i18n.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("wp.i18n.setLocaleData({\n  '': {}\n}, 'pin-master');\n\n//# sourceURL=webpack:///./_dev/js/guten-block/i18n.js?");

/***/ }),

/***/ "./_dev/js/guten-block/images-extend/fields.js":
/*!*****************************************************!*\
  !*** ./_dev/js/guten-block/images-extend/fields.js ***!
  \*****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n\n\n/**\n * Internal block libraries\n */\nvar __ = wp.i18n.__;\nvar InspectorControls = wp.editor.InspectorControls;\nvar Fragment = wp.element.Fragment;\nvar _wp$components = wp.components,\n    CheckboxControl = _wp$components.CheckboxControl,\n    PanelBody = _wp$components.PanelBody,\n    TextControl = _wp$components.TextControl,\n    TextareaControl = _wp$components.TextareaControl;\nvar createHigherOrderComponent = wp.compose.createHigherOrderComponent;\nvar _wp = wp,\n    hooks = _wp.hooks,\n    media = _wp.media;\nvar pastIdProps = {};\nvar registerFields = createHigherOrderComponent(function (BlockEdit) {\n  return function (props) {\n    if ('core/image' !== props.name) {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(BlockEdit, props);\n    }\n\n    var attributes = props.attributes,\n        setAttributes = props.setAttributes; // When the last known 'id' prop was known and not an image ID,\n    // see if there's some Pinterest Text\n    // or Repin value to take from the Media Library.\n\n    if (typeof pastIdProps[props.clientId] !== 'undefined' && !pastIdProps[props.clientId] && attributes.id) {\n      var attachment = wp.media.attachment(attributes.id);\n      setAttributes({\n        pinterestDesc: attachment.attributes.wppml_pinterest_desc,\n        pinterestRepinId: attachment.attributes.wppml_pinterest_repin_id\n      });\n    } // Track last props state for the next cyle.\n\n\n    pastIdProps[props.clientId] = attributes.id || false;\n\n    if (!attributes.id) {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(BlockEdit, props);\n    }\n\n    var pinterestDesc = attributes.pinterestDesc,\n        pinterestRepinId = attributes.pinterestRepinId,\n        pinterestNoPin = attributes.pinterestNoPin;\n    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(Fragment, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(InspectorControls, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(PanelBody, {\n      title: __('WP Pin Master', 'pin-master')\n    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(TextareaControl, {\n      defaultValue: pinterestDesc,\n      onChange: function onChange(value) {\n        return setAttributes({\n          pinterestDesc: value\n        });\n      },\n      label: __('Pinterest - Description', 'pin-master'),\n      placeholder: ''\n    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(TextControl, {\n      defaultValue: pinterestRepinId,\n      onChange: function onChange(value) {\n        return setAttributes({\n          pinterestRepinId: value\n        });\n      },\n      label: __('Pinterest - Repin ID', 'pin-master')\n    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(CheckboxControl, {\n      checked: pinterestNoPin,\n      onChange: function onChange(value) {\n        return setAttributes({\n          pinterestNoPin: value\n        });\n      },\n      label: __('No Pin - Disable Pinning', 'pin-master')\n    }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(BlockEdit, props));\n  };\n});\nwp.hooks.addFilter('editor.BlockEdit', 'codextune/pin-master', registerFields);\n\n//# sourceURL=webpack:///./_dev/js/guten-block/images-extend/fields.js?");

/***/ }),

/***/ "./_dev/js/guten-block/images-extend/save.js":
/*!***************************************************!*\
  !*** ./_dev/js/guten-block/images-extend/save.js ***!
  \***************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n\n\n/**\n* Save the value\n*\n* @return void \n*/\nvar _lodash = lodash,\n    isEmpty = _lodash.isEmpty;\nvar _wp$element = wp.element,\n    RawHTML = _wp$element.RawHTML,\n    renderToString = _wp$element.renderToString;\nvar _wp = wp,\n    hooks = _wp.hooks;\n\nvar SavePint = function SavePint(element, blockType, attributes) {\n  if ('core/image' !== blockType.name) {\n    return element;\n  }\n\n  var pinterestDesc = attributes.pinterestDesc,\n      pinterestRepinId = attributes.pinterestRepinId,\n      pinterestNoPin = attributes.pinterestNoPin;\n  var imgProps = [];\n\n  if (!isEmpty(pinterestDesc)) {\n    imgProps.push({\n      attribute: 'data-pin-description',\n      className: '',\n      value: pinterestDesc\n    });\n  }\n\n  if (!isEmpty(pinterestRepinId)) {\n    imgProps.push({\n      attribute: 'data-pin-id',\n      className: '',\n      value: pinterestRepinId\n    });\n  }\n\n  if (pinterestNoPin) {\n    imgProps.push({\n      attribute: 'data-pin-nopin',\n      className: 'nopin',\n      value: 'true'\n    });\n  } // If empty, no need to modify.\n\n\n  if (isEmpty(imgProps)) {\n    return element;\n  }\n\n  var elementAsString = renderToString(element);\n  imgProps.forEach(function (_ref) {\n    var attribute = _ref.attribute,\n        value = _ref.value,\n        className = _ref.className;\n    // Allow limited html to prevent breaking out.\n    value = value.replace(/&/g, '&amp;').replace(/</g, '').replace(/>/g, '').replace(/\\\"/g, '');\n    elementAsString = elementAsString.replace('<img ', \"<img \".concat(attribute, \"=\\\"\").concat(value, \"\\\" \")).replace(' class=\"wp-image-', \" class=\\\"\".concat(className, \" wp-image-\"));\n  });\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(RawHTML, null, elementAsString);\n};\n\nwp.hooks.addFilter('blocks.getSaveElement', 'codextune/pin-master', SavePint);\n\n//# sourceURL=webpack:///./_dev/js/guten-block/images-extend/save.js?");

/***/ }),

/***/ "./_dev/js/guten-block/images-extend/settings.js":
/*!*******************************************************!*\
  !*** ./_dev/js/guten-block/images-extend/settings.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function getBlockSettings(settings, blockName) {\n  if ('core/image' !== blockName) {\n    return settings;\n  }\n\n  var _lodash = lodash,\n      merge = _lodash.merge;\n  var imageAttr = ['src', 'alt', 'data-pin-description', 'data-pin-id', 'data-pin-nopin'];\n  var pintAttr = {\n    pinterestDesc: {\n      type: 'string',\n      source: 'attribute',\n      selector: 'img',\n      attribute: 'data-pin-description',\n      default: ''\n    },\n    pinterestRepinId: {\n      type: 'string',\n      source: 'attribute',\n      selector: 'img',\n      attribute: 'data-pin-id',\n      default: ''\n    },\n    pinterestNoPin: {\n      type: 'boolean',\n      source: 'attribute',\n      selector: 'img',\n      attribute: 'data-pin-nopin',\n      default: ''\n    }\n  }; // Register data pinterest attributes in Image Block.\n\n  settings.attributes = Object.assign(settings.attributes, pintAttr); // raw <img> HTML transformation.\n\n  settings.transforms.from[0] = merge(settings.transforms.from[0], {\n    schema: {\n      figure: {\n        children: {\n          a: {\n            children: {\n              img: {\n                attributes: imageAttr\n              }\n            }\n          },\n          img: {\n            attributes: imageAttr\n          }\n        }\n      }\n    }\n  }); // [caption] shortcode transformation\n\n  settings.transforms.from[2].attributes = Object.assign(settings.transforms.from[2].attributes, pintAttr);\n  return settings;\n}\n\nwp.hooks.addFilter('blocks.registerBlockType', 'codextune/pin-master', getBlockSettings);\n\n//# sourceURL=webpack:///./_dev/js/guten-block/images-extend/settings.js?");

/***/ }),

/***/ "./_dev/js/guten-block/index.js":
/*!**************************************!*\
  !*** ./_dev/js/guten-block/index.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _i18n_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./i18n.js */ \"./_dev/js/guten-block/i18n.js\");\n/* harmony import */ var _i18n_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_i18n_js__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _images_extend_settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./images-extend/settings */ \"./_dev/js/guten-block/images-extend/settings.js\");\n/* harmony import */ var _images_extend_settings__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_images_extend_settings__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _images_extend_fields__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./images-extend/fields */ \"./_dev/js/guten-block/images-extend/fields.js\");\n/* harmony import */ var _images_extend_save__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./images-extend/save */ \"./_dev/js/guten-block/images-extend/save.js\");\n\n/**\n * Import extends fields of gutenberg image\n */\n\n\n\n\n\n//# sourceURL=webpack:///./_dev/js/guten-block/index.js?");

/***/ }),

/***/ "@wordpress/element":
/*!*****************************!*\
  !*** external "wp.element" ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("module.exports = wp.element;\n\n//# sourceURL=webpack:///external_%22wp.element%22?");

/***/ })

/******/ });