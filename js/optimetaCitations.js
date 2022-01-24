/**
 * @file plugins/generic/optimetaCitations/js/optimetaCitations.js
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OptimetaCitationsPlugin
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief Main javascript for the plugin. *
 */

/**
 *
 * @returns {string}
 */
function optimetaCitationsGetCsrfToken(){

	// ojs version 3.2.1
	if(typeof $ !== 'undefined' && typeof $.pkp !== 'undefined' && typeof $.pkp.currentUser !== 'undefined' && typeof $.pkp.currentUser.csrfToken !== 'undefined'){
		return $.pkp.currentUser.csrfToken;
	}

	// ojs version 3.3.0
	if(typeof pkp !== 'undefined' && typeof pkp.currentUser !== 'undefined' && typeof pkp.currentUser.csrfToken !== 'undefined'){
		return pkp.currentUser.csrfToken;
	}

	return '';
}

/**
 *
 * @param baseArray
 * @returns {*}
 */
function optimetaCitationsGetHelperArray(baseArray){
	let helperArray = JSON.parse(JSON.stringify(baseArray));
	for(let i = 0;i < baseArray.length; i++){
		helperArray[i].editRow = false;
	}
	return helperArray;
}

/**
 *
 * @param str
 * @returns {boolean}
 */
function optimetaCitationsIsStringJson(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}
