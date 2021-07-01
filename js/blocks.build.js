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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wpcloudy_index_js__ = __webpack_require__(/*! ./wpcloudy/index.js */ 1);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__wpcloudy_index_js___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__wpcloudy_index_js__);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi93cGNsb3VkeS9pbmRleC5qcyc7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2tzLmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTsiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/*!*******************************!*\
  !*** ./src/wpcloudy/index.js ***!
  \*******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("var __ = wp.i18n.__; // Importer la fonction __() d'internationalisation des chaines\n\nvar registerBlockType = wp.blocks.registerBlockType; // Importe la fonction registerBlockType() de la librairie globale wp.blocks\n\n// Fonction WordPress pour déclarer un bloc\n\nregisterBlockType('gutenberg-wpcloudy/wpcloudy', // Nom du bloc sous forme de slug avec son préfixe (wp est bien sûr réservé)\n{\n\ttitle: __(\"Weather\"), // Titre du bloc lisible par un humain\n\tdescription: __(\"WP Cloudy widget\"), // Description qui apparait dans l'inspecteur\n\ticon: 'cloud', // Dashicon sans le préfixe 'dashicons-' → https://developer.wordpress.org/resource/dashicons/\n\tcategory: 'widgets', // Catégorie (common, formatting, layout widgets, embed)\n\tkeywords: [// Mots clés pour améliorer la recherche de blocs\n\t__('weather'), __('wp cloudy'), __('forecast')],\n\n\t// La partie affichée dans l'administration de WordPress\n\tedit: function edit(props) {\n\t\treturn wp.element.createElement(\n\t\t\t\"div\",\n\t\t\tnull,\n\t\t\twp.element.createElement(\n\t\t\t\t\"p\",\n\t\t\t\tnull,\n\t\t\t\t\"Salut ! Je suis le backend\"\n\t\t\t)\n\t\t);\n\t},\n\n\t// La partie enregistrée en base et affichée en front\n\tsave: function save(props) {\n\t\treturn wp.element.createElement(\n\t\t\t\"div\",\n\t\t\tnull,\n\t\t\twp.element.createElement(\n\t\t\t\t\"p\",\n\t\t\t\tnull,\n\t\t\t\t\"Salut, je suis le frontend\"\n\t\t\t)\n\t\t);\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy93cGNsb3VkeS9pbmRleC5qcz9hMTMxIl0sInNvdXJjZXNDb250ZW50IjpbInZhciBfXyA9IHdwLmkxOG4uX187IC8vIEltcG9ydGVyIGxhIGZvbmN0aW9uIF9fKCkgZCdpbnRlcm5hdGlvbmFsaXNhdGlvbiBkZXMgY2hhaW5lc1xuXG52YXIgcmVnaXN0ZXJCbG9ja1R5cGUgPSB3cC5ibG9ja3MucmVnaXN0ZXJCbG9ja1R5cGU7IC8vIEltcG9ydGUgbGEgZm9uY3Rpb24gcmVnaXN0ZXJCbG9ja1R5cGUoKSBkZSBsYSBsaWJyYWlyaWUgZ2xvYmFsZSB3cC5ibG9ja3NcblxuLy8gRm9uY3Rpb24gV29yZFByZXNzIHBvdXIgZMOpY2xhcmVyIHVuIGJsb2NcblxucmVnaXN0ZXJCbG9ja1R5cGUoJ2d1dGVuYmVyZy13cGNsb3VkeS93cGNsb3VkeScsIC8vIE5vbSBkdSBibG9jIHNvdXMgZm9ybWUgZGUgc2x1ZyBhdmVjIHNvbiBwcsOpZml4ZSAod3AgZXN0IGJpZW4gc8O7ciByw6lzZXJ2w6kpXG57XG5cdHRpdGxlOiBfXyhcIldlYXRoZXJcIiksIC8vIFRpdHJlIGR1IGJsb2MgbGlzaWJsZSBwYXIgdW4gaHVtYWluXG5cdGRlc2NyaXB0aW9uOiBfXyhcIldQIENsb3VkeSB3aWRnZXRcIiksIC8vIERlc2NyaXB0aW9uIHF1aSBhcHBhcmFpdCBkYW5zIGwnaW5zcGVjdGV1clxuXHRpY29uOiAnY2xvdWQnLCAvLyBEYXNoaWNvbiBzYW5zIGxlIHByw6lmaXhlICdkYXNoaWNvbnMtJyDihpIgaHR0cHM6Ly9kZXZlbG9wZXIud29yZHByZXNzLm9yZy9yZXNvdXJjZS9kYXNoaWNvbnMvXG5cdGNhdGVnb3J5OiAnd2lkZ2V0cycsIC8vIENhdMOpZ29yaWUgKGNvbW1vbiwgZm9ybWF0dGluZywgbGF5b3V0IHdpZGdldHMsIGVtYmVkKVxuXHRrZXl3b3JkczogWy8vIE1vdHMgY2zDqXMgcG91ciBhbcOpbGlvcmVyIGxhIHJlY2hlcmNoZSBkZSBibG9jc1xuXHRfXygnd2VhdGhlcicpLCBfXygnd3AgY2xvdWR5JyksIF9fKCdmb3JlY2FzdCcpXSxcblxuXHQvLyBMYSBwYXJ0aWUgYWZmaWNow6llIGRhbnMgbCdhZG1pbmlzdHJhdGlvbiBkZSBXb3JkUHJlc3Ncblx0ZWRpdDogZnVuY3Rpb24gZWRpdChwcm9wcykge1xuXHRcdHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG5cdFx0XHRcImRpdlwiLFxuXHRcdFx0bnVsbCxcblx0XHRcdHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdFx0XCJwXCIsXG5cdFx0XHRcdG51bGwsXG5cdFx0XHRcdFwiU2FsdXQgISBKZSBzdWlzIGxlIGJhY2tlbmRcIlxuXHRcdFx0KVxuXHRcdCk7XG5cdH0sXG5cblx0Ly8gTGEgcGFydGllIGVucmVnaXN0csOpZSBlbiBiYXNlIGV0IGFmZmljaMOpZSBlbiBmcm9udFxuXHRzYXZlOiBmdW5jdGlvbiBzYXZlKHByb3BzKSB7XG5cdFx0cmV0dXJuIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdFwiZGl2XCIsXG5cdFx0XHRudWxsLFxuXHRcdFx0d3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KFxuXHRcdFx0XHRcInBcIixcblx0XHRcdFx0bnVsbCxcblx0XHRcdFx0XCJTYWx1dCwgamUgc3VpcyBsZSBmcm9udGVuZFwiXG5cdFx0XHQpXG5cdFx0KTtcblx0fVxufSk7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvd3BjbG91ZHkvaW5kZXguanNcbi8vIG1vZHVsZSBpZCA9IDFcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///1\n");

/***/ })
/******/ ]);