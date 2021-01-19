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
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/wharehouse.js":
/*!************************************!*\
  !*** ./resources/js/wharehouse.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.changeFilter = function () {
  window.filter.themes = [];
  window.filter.mission = [];
  document.querySelectorAll('input[name="checkFilter"]').forEach(function (el) {
    if (el.checked) {
      window.filter[el.getAttribute('theme')].push(el.value);
    }
  });
  buildScreen(1);
};

window.clearFilter = function () {
  document.querySelectorAll('input[name="checkFilter"]').forEach(function (el) {
    el.checked = false;
  });
  window.filter = {
    title: '',
    themes: [],
    mission: []
  };
  buildScreen(1);
}; // рендерит кнопки страниц
//             currentPage:result.currentPage, 
//             pageQuantity:result.pageQuantity


window.renderPagination = function (data) {
  if (data.pageQuantity <= 1) return;
  var buttonsHtml = '';

  for (var index = 1; index <= data.pageQuantity; index++) {
    buttonsHtml += '<li><button ';
    if (index == data.currentPage) buttonsHtml += 'class="active-pagination" ';
    buttonsHtml += 'page="' + index + '" ';
    buttonsHtml += 'onclick="buildScreen(' + index + ')">';
    buttonsHtml += index;
    buttonsHtml += '</button></li>';
  }

  if (data.currentPage < data.pageQuantity) {
    buttonsHtml += '<li><button onclick="buildScreen(0, 1)">Следующая</button></li>';
  }

  var table = document.querySelector('.pagination-block');
  var pagination = document.querySelector('ul.pagination');

  if (pagination === null) {
    pagination = document.createElement('ul');
  }

  pagination.className = 'pagination';
  pagination.setAttribute('maxPage', data.pageQuantity);
  pagination.innerHTML = buttonsHtml;
  table.appendChild(pagination);
}; // ввод в поисковое поле


window.newSearch = function () {
  var reset = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
  if (typeof cloneInputText === "function") cloneInputText();
  var titleFilter = event.target.value;
  if (reset) titleFilter = '';
  window.filter.title = titleFilter;
  buildScreen(1, false);
}; // получает экран товаров - page страницу
// shift - нажата кнопка Следующая


window.buildScreen = function (page) {
  var shift = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  if (shift != false) page = Number(document.querySelector('.active-pagination').getAttribute('page')) + shift;
  ajax('getWharehouse', {
    page: page,
    filter: window.filter
  }, function (result) {
    renderPage(result.data);
    renderPagination({
      currentPage: result.currentPage,
      pageQuantity: result.pageQuantity
    });
  });
};

document.addEventListener('DOMContentLoaded', function () {
  clearFilter();
});

/***/ }),

/***/ 2:
/*!******************************************!*\
  !*** multi ./resources/js/wharehouse.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/katunin/Documents/bells/resources/js/wharehouse.js */"./resources/js/wharehouse.js");


/***/ })

/******/ });