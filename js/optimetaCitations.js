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
 * @desc Gets CSRF Token
 * @returns string
 */
function optimetaCitationsGetCsrfToken() {
    // ojs version 3.2.1
    if (typeof $ !== "undefined" && typeof $.pkp !== "undefined" &&
        typeof $.pkp.currentUser !== "undefined" &&
        typeof $.pkp.currentUser.csrfToken !== "undefined") {
        return $.pkp.currentUser.csrfToken;
    }

    // ojs version 3.3.0
    if (typeof pkp !== "undefined" && typeof pkp.currentUser !== "undefined" &&
        typeof pkp.currentUser.csrfToken !== "undefined") {
        return pkp.currentUser.csrfToken;
    }

    return "";
}

/**
 * @desc Gets citations raw value
 * @returns {string}
 */
function optimetaCitationsGetCitationsRaw() {
    let element = null;
    let citationsRaw = "";

    // submission wizard
    element = document.getElementsByName("citationsRaw")[0];
    if (typeof element !== 'undefined' && element !== null &&
        typeof element.value !== "undefined" && element.value !== null) {
        citationsRaw = element.value;
    }

    // submission edit
    element = document.getElementById("citations-citationsRaw-control");
    if (typeof element !== 'undefined' && element !== null &&
        typeof element.value !== "undefined" && element.value !== null) {
        citationsRaw = element.value;
    }

    return citationsRaw;
}

/**
 * @desc Gets Helper Array
 * @param baseArray array
 * @returns array
 */
function optimetaCitationsGetHelperArray(baseArray) {
    let helperArray = JSON.parse(JSON.stringify(baseArray));
    for (let i = 0; i < baseArray.length; i++) {
        for (let key of Object.keys(helperArray[i])) {
            helperArray[i]["_edit_" + key] = false;
        }
        helperArray[i].editRow = false;
    }
    return helperArray;
}

/**
 * @desc Check if string is json
 * @param str string
 * @returns boolean
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
 * @param obj object
 * @param key string
 * @returns string
 */
function optimetaCitationsFindPathInArray(obj, key) {
    const path = [];
    const keyExists = (obj) => {
        if (!obj || (typeof obj !== "object" && !Array.isArray(obj))) {
            return false;
        } else if (obj.hasOwnProperty(key)) {
            return true;
        } else if (Array.isArray(obj)) {
            let parentKey = path.length ? path.pop() : "";

            for (let i = 0; i < obj.length; i++) {
                path.push(`${parentKey}[${i}]`);
                const result = keyExists(obj[i], key);
                if (result) {
                    return result;
                }
                path.pop();
            }
        } else {
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
