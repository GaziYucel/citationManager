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
/*jshint esversion: 6 */

/**
 * @desc Main OptimetaCitations Class
 */
class OptimetaCitations {
    /**
     * @desc Gets CSRF Token
     * @returns string
     */
    getCsrfToken(){
        // ojs version 3.2.1
        if(typeof $ !== "undefined" && typeof $.pkp !== "undefined" &&
            typeof $.pkp.currentUser !== "undefined" &&
            typeof $.pkp.currentUser.csrfToken !== "undefined"){
            return $.pkp.currentUser.csrfToken;
        }

        // ojs version 3.3.0
        if(typeof pkp !== "undefined" && typeof pkp.currentUser !== "undefined" &&
            typeof pkp.currentUser.csrfToken !== "undefined"){
            return pkp.currentUser.csrfToken;
        }

        return "";
    }

    /**
     * @desc Gets Helper Array
     * @param baseArray array
     * @returns array
     */
    getHelperArray(baseArray){
        let helperArray = JSON.parse(JSON.stringify(baseArray));
        for(let i = 0;i < baseArray.length; i++){
            for(let key of Object.keys(helperArray[i])){
                helperArray[i]["_edit_" + key] = false;
            }
            helperArray[i]["editRow"] = false;
        }
        return helperArray;
    }

    /**
     * @desc Check if string is json
     * @param str string
     * @returns boolean
     */
    isStringJson(str) {
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
    findPathInArray (obj, key) {
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
}
