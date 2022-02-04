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
	if(typeof $ !== 'undefined' && typeof $.pkp !== 'undefined' &&
        typeof $.pkp.currentUser !== 'undefined' &&
        typeof $.pkp.currentUser.csrfToken !== 'undefined'){
		return $.pkp.currentUser.csrfToken;
	}

	// ojs version 3.3.0
	if(typeof pkp !== 'undefined' && typeof pkp.currentUser !== 'undefined' &&
        typeof pkp.currentUser.csrfToken !== 'undefined'){
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

/**
 *
 * @param ob
 * @param key
 * @returns {string}
 */
function optimetaCitationsFindPathInArray (ob, key) {
    const path = [];
    const keyExists = (obj) => {
        if (!obj || (typeof obj !== "object" && !Array.isArray(obj))) {
            return false;
        }
        else if (obj.hasOwnProperty(key)) {
            return true;
        }
        else if (Array.isArray(obj)) {
            let parentKey = path.length ? path.pop() : "";

            for (let i = 0; i < obj.length; i++) {
                path.push(`${parentKey}[${i}]`);
                const result = keyExists(obj[i], key);
                if (result) {
                    return result;
                }
                path.pop();
            }
        }
        else {
            for (const k in obj) {
                path.push(k);
                const result = keyExists(obj[k], key);
                if (result) {
                    return result;
                }
                path.pop();
            }
        }
        return false;
    };

    keyExists(ob);

    return path.join(".");
}
