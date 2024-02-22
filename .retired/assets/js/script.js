/**
 * @file assets/js/script.js
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class CitationManagerPlugin
 * @brief Main javascript for the plugin.
 */

/**
 * Gets CSRF Token
 * @returns string
 */
function citationManagerGetCsrfToken() {
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
 * Gets citations raw value
 * @returns {string}
 */
function citationManagerGetCitationsRaw() {
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
 * Gets Helper Array
 * @param baseArray array
 * @returns array
 */
function citationManagerGetHelperArray(baseArray) {
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
 * Check if string is json
 * @param str string
 * @returns boolean
 */
function citationManagerIsStringJson(str) {
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
function citationManagerFindPathInArray(obj, key) {
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

/**
 * Method to wait for an element to appear and call function
 * @param selector
 * @param callback
 * @param checkFrequency
 * @param timeOut
 * @example waitForElementToDisplay(".class1 div.class2", function () { console.log("callback function"); }, 1000, 9000);
 */
function citationManagerWaitForElementToDisplay(selector, callback, checkFrequency, timeOut) {
    let startTime = Date.now();
    (function loopSearch() {
        if (document.querySelector(selector) != null) {
            callback();
        } else {
            setTimeout(function () {
                if (timeOut && Date.now() - startTime > timeOut)
                    return;
                loopSearch();
            }, checkFrequency);
        }
    })();
}
