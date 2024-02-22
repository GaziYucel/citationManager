pkp.registry.init(
    'app',
    "WorkflowPage",
    {
        "menu": {
            "submissions": {
                "name": "Submissions",
                "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/submissions",
                "isCurrent": false
            },
            "issues": {
                "name": "Issues",
                "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/manageIssues",
                "isCurrent": false
            },
            "dois": {
                "name": "DOIs",
                "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/dois",
                "isCurrent": false
            },
            "settings": {
                "name": "Settings",
                "submenu": {
                    "context": {
                        "name": "Journal",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/settings\/context",
                        "isCurrent": false
                    },
                    "website": {
                        "name": "Website",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/settings\/website",
                        "isCurrent": false
                    },
                    "workflow": {
                        "name": "Workflow",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/settings\/workflow",
                        "isCurrent": false
                    },
                    "distribution": {
                        "name": "Distribution",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/settings\/distribution",
                        "isCurrent": false
                    },
                    "access": {
                        "name": "Users & Roles",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/settings\/access",
                        "isCurrent": false
                    }
                }
            },
            "statistics": {
                "name": "Statistics",
                "submenu": {
                    "publications": {
                        "name": "Articles",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/publications\/publications",
                        "isCurrent": false
                    },
                    "issues": {
                        "name": "Issues",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/issues\/issues",
                        "isCurrent": false
                    },
                    "context": {
                        "name": "Journal",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/context\/context",
                        "isCurrent": false
                    },
                    "editorial": {
                        "name": "Editorial Activity",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/editorial\/editorial",
                        "isCurrent": false
                    },
                    "users": {
                        "name": "Users",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/users\/users",
                        "isCurrent": false
                    },
                    "reports": {
                        "name": "Reports",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/stats\/reports",
                        "isCurrent": false
                    }
                }
            },
            "tools": {
                "name": "Tools",
                "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/management\/tools",
                "isCurrent": false
            },
            "admin": {
                "name": "Administration",
                "url": "http:\/\/localhost\/ojs340\/index.php\/index\/admin",
                "isCurrent": false
            }
        },
        "tinyMCE": {"skinUrl": "http:\/\/localhost\/ojs340\/lib\/pkp\/styles\/tinymce"},
        "tasksUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/page\/page\/tasks",
        "unreadTasksCount": 0,
        "activityLogLabel": "Activity Log & Notes",
        "canAccessPublication": true,
        "canEditPublication": true,
        "components": {
            "contributors": {
                "id": "contributors",
                "isSidebarVisible": false,
                "items": [{
                    "affiliation": {"en": "University of Cape Town", "fr_CA": ""},
                    "biography": {"en": "", "fr_CA": ""},
                    "country": "ZA",
                    "email": "amwandenga@mailinator.com",
                    "familyName": {"en": "Mwandenga Version 2", "fr_CA": ""},
                    "fullName": "Alan Mwandenga Version 2",
                    "givenName": {"en": "Alan", "fr_CA": ""},
                    "id": 5,
                    "includeInBrowse": true,
                    "locale": "en",
                    "orcid": null,
                    "preferredPublicName": {"en": "", "fr_CA": ""},
                    "publicationId": 2,
                    "seq": 0,
                    "url": null,
                    "userGroupId": 14,
                    "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
                }, {
                    "affiliation": {"en": "", "fr_CA": ""},
                    "biography": {"en": "", "fr_CA": ""},
                    "country": "BB",
                    "email": "notanemailamansour@mailinator.com",
                    "familyName": {"en": "Mansour", "fr_CA": ""},
                    "fullName": "Amina Mansour",
                    "givenName": {"en": "Amina", "fr_CA": ""},
                    "id": 6,
                    "includeInBrowse": true,
                    "locale": "en",
                    "orcid": null,
                    "preferredPublicName": {"en": "", "fr_CA": ""},
                    "publicationId": 2,
                    "seq": 1,
                    "url": null,
                    "userGroupId": 14,
                    "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
                }, {
                    "affiliation": {"en": "", "fr_CA": ""},
                    "biography": {"en": "", "fr_CA": ""},
                    "country": "ZA",
                    "email": "nriouf@mailinator.com",
                    "familyName": {"en": "Riouf", "fr_CA": ""},
                    "fullName": "Nicolas Riouf",
                    "givenName": {"en": "Nicolas", "fr_CA": ""},
                    "id": 7,
                    "includeInBrowse": true,
                    "locale": "en",
                    "orcid": null,
                    "preferredPublicName": {"en": "", "fr_CA": ""},
                    "publicationId": 2,
                    "seq": 2,
                    "url": null,
                    "userGroupId": 14,
                    "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
                }],
                "title": "Contributors",
                "canEditPublication": true,
                "publicationApiUrlFormat": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/__publicationId__",
                "form": {
                    "id": "contributor",
                    "method": "POST",
                    "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/__publicationId__\/contributors",
                    "fields": [{
                        "name": "givenName",
                        "component": "field-text",
                        "label": "Given Name",
                        "groupId": "default",
                        "isRequired": true,
                        "isMultilingual": true,
                        "value": {"fr_CA": "", "en": ""},
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "familyName",
                        "component": "field-text",
                        "label": "Family Name",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": true,
                        "value": {"fr_CA": "", "en": ""},
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "preferredPublicName",
                        "component": "field-text",
                        "label": "Preferred Public Name",
                        "description": "Please provide the full name as the author should be identified on the published work. Example: Dr. Alan P. Mwandenga",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": true,
                        "value": {"fr_CA": "", "en": ""},
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "email",
                        "component": "field-text",
                        "label": "Email",
                        "groupId": "default",
                        "isRequired": true,
                        "isMultilingual": false,
                        "value": null,
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "country",
                        "component": "field-select",
                        "label": "Country",
                        "groupId": "default",
                        "isRequired": true,
                        "isMultilingual": false,
                        "value": null,
                        "options": [{"value": "AF", "label": "Afghanistan"}, {
                            "value": "AL",
                            "label": "Albania"
                        }, {"value": "DZ", "label": "Algeria"}, {
                            "value": "AS",
                            "label": "American Samoa"
                        }, {"value": "AD", "label": "Andorra"}, {"value": "AO", "label": "Angola"}, {
                            "value": "AI",
                            "label": "Anguilla"
                        }, {"value": "AQ", "label": "Antarctica"}, {
                            "value": "AG",
                            "label": "Antigua and Barbuda"
                        }, {"value": "AR", "label": "Argentina"}, {"value": "AM", "label": "Armenia"}, {
                            "value": "AW",
                            "label": "Aruba"
                        }, {"value": "AU", "label": "Australia"}, {"value": "AT", "label": "Austria"}, {
                            "value": "AZ",
                            "label": "Azerbaijan"
                        }, {"value": "BS", "label": "Bahamas"}, {"value": "BH", "label": "Bahrain"}, {
                            "value": "BD",
                            "label": "Bangladesh"
                        }, {"value": "BB", "label": "Barbados"}, {"value": "BY", "label": "Belarus"}, {
                            "value": "BE",
                            "label": "Belgium"
                        }, {"value": "BZ", "label": "Belize"}, {"value": "BJ", "label": "Benin"}, {
                            "value": "BM",
                            "label": "Bermuda"
                        }, {"value": "BT", "label": "Bhutan"}, {
                            "value": "BO",
                            "label": "Bolivia, Plurinational State of"
                        }, {"value": "BQ", "label": "Bonaire, Sint Eustatius and Saba"}, {
                            "value": "BA",
                            "label": "Bosnia and Herzegovina"
                        }, {"value": "BW", "label": "Botswana"}, {
                            "value": "BV",
                            "label": "Bouvet Island"
                        }, {"value": "BR", "label": "Brazil"}, {
                            "value": "IO",
                            "label": "British Indian Ocean Territory"
                        }, {"value": "BN", "label": "Brunei Darussalam"}, {
                            "value": "BG",
                            "label": "Bulgaria"
                        }, {"value": "BF", "label": "Burkina Faso"}, {
                            "value": "BI",
                            "label": "Burundi"
                        }, {"value": "CV", "label": "Cabo Verde"}, {"value": "KH", "label": "Cambodia"}, {
                            "value": "CM",
                            "label": "Cameroon"
                        }, {"value": "CA", "label": "Canada"}, {
                            "value": "KY",
                            "label": "Cayman Islands"
                        }, {"value": "CF", "label": "Central African Republic"}, {
                            "value": "TD",
                            "label": "Chad"
                        }, {"value": "CL", "label": "Chile"}, {"value": "CN", "label": "China"}, {
                            "value": "CX",
                            "label": "Christmas Island"
                        }, {"value": "CC", "label": "Cocos (Keeling) Islands"}, {
                            "value": "CO",
                            "label": "Colombia"
                        }, {"value": "KM", "label": "Comoros"}, {"value": "CG", "label": "Congo"}, {
                            "value": "CD",
                            "label": "Congo, The Democratic Republic of the"
                        }, {"value": "CK", "label": "Cook Islands"}, {
                            "value": "CR",
                            "label": "Costa Rica"
                        }, {"value": "HR", "label": "Croatia"}, {"value": "CU", "label": "Cuba"}, {
                            "value": "CW",
                            "label": "Cura\u00e7ao"
                        }, {"value": "CY", "label": "Cyprus"}, {"value": "CZ", "label": "Czechia"}, {
                            "value": "CI",
                            "label": "C\u00f4te d'Ivoire"
                        }, {"value": "DK", "label": "Denmark"}, {"value": "DJ", "label": "Djibouti"}, {
                            "value": "DM",
                            "label": "Dominica"
                        }, {"value": "DO", "label": "Dominican Republic"}, {
                            "value": "EC",
                            "label": "Ecuador"
                        }, {"value": "EG", "label": "Egypt"}, {"value": "SV", "label": "El Salvador"}, {
                            "value": "GQ",
                            "label": "Equatorial Guinea"
                        }, {"value": "ER", "label": "Eritrea"}, {"value": "EE", "label": "Estonia"}, {
                            "value": "SZ",
                            "label": "Eswatini"
                        }, {"value": "ET", "label": "Ethiopia"}, {
                            "value": "FK",
                            "label": "Falkland Islands (Malvinas)"
                        }, {"value": "FO", "label": "Faroe Islands"}, {"value": "FJ", "label": "Fiji"}, {
                            "value": "FI",
                            "label": "Finland"
                        }, {"value": "FR", "label": "France"}, {
                            "value": "GF",
                            "label": "French Guiana"
                        }, {"value": "PF", "label": "French Polynesia"}, {
                            "value": "TF",
                            "label": "French Southern Territories"
                        }, {"value": "GA", "label": "Gabon"}, {"value": "GM", "label": "Gambia"}, {
                            "value": "GE",
                            "label": "Georgia"
                        }, {"value": "DE", "label": "Germany"}, {"value": "GH", "label": "Ghana"}, {
                            "value": "GI",
                            "label": "Gibraltar"
                        }, {"value": "GR", "label": "Greece"}, {"value": "GL", "label": "Greenland"}, {
                            "value": "GD",
                            "label": "Grenada"
                        }, {"value": "GP", "label": "Guadeloupe"}, {"value": "GU", "label": "Guam"}, {
                            "value": "GT",
                            "label": "Guatemala"
                        }, {"value": "GG", "label": "Guernsey"}, {"value": "GN", "label": "Guinea"}, {
                            "value": "GW",
                            "label": "Guinea-Bissau"
                        }, {"value": "GY", "label": "Guyana"}, {"value": "HT", "label": "Haiti"}, {
                            "value": "HM",
                            "label": "Heard Island and McDonald Islands"
                        }, {"value": "VA", "label": "Holy See (Vatican City State)"}, {
                            "value": "HN",
                            "label": "Honduras"
                        }, {"value": "HK", "label": "Hong Kong"}, {"value": "HU", "label": "Hungary"}, {
                            "value": "IS",
                            "label": "Iceland"
                        }, {"value": "IN", "label": "India"}, {"value": "ID", "label": "Indonesia"}, {
                            "value": "IR",
                            "label": "Iran, Islamic Republic of"
                        }, {"value": "IQ", "label": "Iraq"}, {"value": "IE", "label": "Ireland"}, {
                            "value": "IM",
                            "label": "Isle of Man"
                        }, {"value": "IL", "label": "Israel"}, {"value": "IT", "label": "Italy"}, {
                            "value": "JM",
                            "label": "Jamaica"
                        }, {"value": "JP", "label": "Japan"}, {"value": "JE", "label": "Jersey"}, {
                            "value": "JO",
                            "label": "Jordan"
                        }, {"value": "KZ", "label": "Kazakhstan"}, {"value": "KE", "label": "Kenya"}, {
                            "value": "KI",
                            "label": "Kiribati"
                        }, {"value": "KP", "label": "Korea, Democratic People's Republic of"}, {
                            "value": "KR",
                            "label": "Korea, Republic of"
                        }, {"value": "KW", "label": "Kuwait"}, {"value": "KG", "label": "Kyrgyzstan"}, {
                            "value": "LA",
                            "label": "Lao People's Democratic Republic"
                        }, {"value": "LV", "label": "Latvia"}, {"value": "LB", "label": "Lebanon"}, {
                            "value": "LS",
                            "label": "Lesotho"
                        }, {"value": "LR", "label": "Liberia"}, {"value": "LY", "label": "Libya"}, {
                            "value": "LI",
                            "label": "Liechtenstein"
                        }, {"value": "LT", "label": "Lithuania"}, {
                            "value": "LU",
                            "label": "Luxembourg"
                        }, {"value": "MO", "label": "Macao"}, {"value": "MG", "label": "Madagascar"}, {
                            "value": "MW",
                            "label": "Malawi"
                        }, {"value": "MY", "label": "Malaysia"}, {"value": "MV", "label": "Maldives"}, {
                            "value": "ML",
                            "label": "Mali"
                        }, {"value": "MT", "label": "Malta"}, {
                            "value": "MH",
                            "label": "Marshall Islands"
                        }, {"value": "MQ", "label": "Martinique"}, {
                            "value": "MR",
                            "label": "Mauritania"
                        }, {"value": "MU", "label": "Mauritius"}, {"value": "YT", "label": "Mayotte"}, {
                            "value": "MX",
                            "label": "Mexico"
                        }, {"value": "FM", "label": "Micronesia, Federated States of"}, {
                            "value": "MD",
                            "label": "Moldova, Republic of"
                        }, {"value": "MC", "label": "Monaco"}, {"value": "MN", "label": "Mongolia"}, {
                            "value": "ME",
                            "label": "Montenegro"
                        }, {"value": "MS", "label": "Montserrat"}, {"value": "MA", "label": "Morocco"}, {
                            "value": "MZ",
                            "label": "Mozambique"
                        }, {"value": "MM", "label": "Myanmar"}, {"value": "NA", "label": "Namibia"}, {
                            "value": "NR",
                            "label": "Nauru"
                        }, {"value": "NP", "label": "Nepal"}, {"value": "NL", "label": "Netherlands"}, {
                            "value": "NC",
                            "label": "New Caledonia"
                        }, {"value": "NZ", "label": "New Zealand"}, {
                            "value": "NI",
                            "label": "Nicaragua"
                        }, {"value": "NE", "label": "Niger"}, {"value": "NG", "label": "Nigeria"}, {
                            "value": "NU",
                            "label": "Niue"
                        }, {"value": "NF", "label": "Norfolk Island"}, {
                            "value": "MK",
                            "label": "North Macedonia"
                        }, {"value": "MP", "label": "Northern Mariana Islands"}, {
                            "value": "NO",
                            "label": "Norway"
                        }, {"value": "OM", "label": "Oman"}, {"value": "PK", "label": "Pakistan"}, {
                            "value": "PW",
                            "label": "Palau"
                        }, {"value": "PS", "label": "Palestine, State of"}, {
                            "value": "PA",
                            "label": "Panama"
                        }, {"value": "PG", "label": "Papua New Guinea"}, {
                            "value": "PY",
                            "label": "Paraguay"
                        }, {"value": "PE", "label": "Peru"}, {"value": "PH", "label": "Philippines"}, {
                            "value": "PN",
                            "label": "Pitcairn"
                        }, {"value": "PL", "label": "Poland"}, {"value": "PT", "label": "Portugal"}, {
                            "value": "PR",
                            "label": "Puerto Rico"
                        }, {"value": "QA", "label": "Qatar"}, {"value": "RO", "label": "Romania"}, {
                            "value": "RU",
                            "label": "Russian Federation"
                        }, {"value": "RW", "label": "Rwanda"}, {"value": "RE", "label": "R\u00e9union"}, {
                            "value": "BL",
                            "label": "Saint Barth\u00e9lemy"
                        }, {"value": "SH", "label": "Saint Helena, Ascension and Tristan da Cunha"}, {
                            "value": "KN",
                            "label": "Saint Kitts and Nevis"
                        }, {"value": "LC", "label": "Saint Lucia"}, {
                            "value": "MF",
                            "label": "Saint Martin (French part)"
                        }, {"value": "PM", "label": "Saint Pierre and Miquelon"}, {
                            "value": "VC",
                            "label": "Saint Vincent and the Grenadines"
                        }, {"value": "WS", "label": "Samoa"}, {"value": "SM", "label": "San Marino"}, {
                            "value": "ST",
                            "label": "Sao Tome and Principe"
                        }, {"value": "SA", "label": "Saudi Arabia"}, {
                            "value": "SN",
                            "label": "Senegal"
                        }, {"value": "RS", "label": "Serbia"}, {"value": "SC", "label": "Seychelles"}, {
                            "value": "SL",
                            "label": "Sierra Leone"
                        }, {"value": "SG", "label": "Singapore"}, {
                            "value": "SX",
                            "label": "Sint Maarten (Dutch part)"
                        }, {"value": "SK", "label": "Slovakia"}, {"value": "SI", "label": "Slovenia"}, {
                            "value": "SB",
                            "label": "Solomon Islands"
                        }, {"value": "SO", "label": "Somalia"}, {
                            "value": "ZA",
                            "label": "South Africa"
                        }, {"value": "GS", "label": "South Georgia and the South Sandwich Islands"}, {
                            "value": "SS",
                            "label": "South Sudan"
                        }, {"value": "ES", "label": "Spain"}, {"value": "LK", "label": "Sri Lanka"}, {
                            "value": "SD",
                            "label": "Sudan"
                        }, {"value": "SR", "label": "Suriname"}, {
                            "value": "SJ",
                            "label": "Svalbard and Jan Mayen"
                        }, {"value": "SE", "label": "Sweden"}, {"value": "CH", "label": "Switzerland"}, {
                            "value": "SY",
                            "label": "Syrian Arab Republic"
                        }, {"value": "TW", "label": "Taiwan, Province of China"}, {
                            "value": "TJ",
                            "label": "Tajikistan"
                        }, {"value": "TZ", "label": "Tanzania, United Republic of"}, {
                            "value": "TH",
                            "label": "Thailand"
                        }, {"value": "TL", "label": "Timor-Leste"}, {"value": "TG", "label": "Togo"}, {
                            "value": "TK",
                            "label": "Tokelau"
                        }, {"value": "TO", "label": "Tonga"}, {
                            "value": "TT",
                            "label": "Trinidad and Tobago"
                        }, {"value": "TN", "label": "Tunisia"}, {
                            "value": "TM",
                            "label": "Turkmenistan"
                        }, {"value": "TC", "label": "Turks and Caicos Islands"}, {
                            "value": "TV",
                            "label": "Tuvalu"
                        }, {"value": "TR", "label": "T\u00fcrkiye"}, {"value": "UG", "label": "Uganda"}, {
                            "value": "UA",
                            "label": "Ukraine"
                        }, {"value": "AE", "label": "United Arab Emirates"}, {
                            "value": "GB",
                            "label": "United Kingdom"
                        }, {"value": "US", "label": "United States"}, {
                            "value": "UM",
                            "label": "United States Minor Outlying Islands"
                        }, {"value": "UY", "label": "Uruguay"}, {"value": "UZ", "label": "Uzbekistan"}, {
                            "value": "VU",
                            "label": "Vanuatu"
                        }, {"value": "VE", "label": "Venezuela, Bolivarian Republic of"}, {
                            "value": "VN",
                            "label": "Viet Nam"
                        }, {"value": "VG", "label": "Virgin Islands, British"}, {
                            "value": "VI",
                            "label": "Virgin Islands, U.S."
                        }, {"value": "WF", "label": "Wallis and Futuna"}, {
                            "value": "EH",
                            "label": "Western Sahara"
                        }, {"value": "YE", "label": "Yemen"}, {"value": "ZM", "label": "Zambia"}, {
                            "value": "ZW",
                            "label": "Zimbabwe"
                        }, {"value": "AX", "label": "\u00c5land Islands"}]
                    }, {
                        "name": "url",
                        "component": "field-text",
                        "label": "Homepage URL",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": false,
                        "value": null,
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "orcid",
                        "component": "field-text",
                        "label": "ORCID iD",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": false,
                        "value": null,
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "biography",
                        "component": "field-rich-textarea",
                        "label": "Bio Statement (e.g., department and rank)",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": true,
                        "value": {"fr_CA": "", "en": ""},
                        "plugins": "paste,link,noneditable",
                        "toolbar": "bold italic superscript subscript | link"
                    }, {
                        "name": "affiliation",
                        "component": "field-text",
                        "label": "Affiliation",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": true,
                        "value": {"fr_CA": "", "en": ""},
                        "inputType": "text",
                        "optIntoEdit": false,
                        "optIntoEditLabel": "",
                        "size": "normal",
                        "prefix": ""
                    }, {
                        "name": "userGroupId",
                        "component": "field-options",
                        "label": "Contributor's role",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": false,
                        "value": 14,
                        "type": "radio",
                        "isOrderable": false,
                        "options": [{"value": 14, "label": "Author"}, {"value": 15, "label": "Translator"}]
                    }, {
                        "name": "includeInBrowse",
                        "component": "field-options",
                        "label": "Publication Lists",
                        "groupId": "default",
                        "isRequired": false,
                        "isMultilingual": false,
                        "value": true,
                        "type": "checkbox",
                        "isOrderable": false,
                        "options": [{
                            "value": true,
                            "label": "Include this contributor when identifying authors in lists of publications."
                        }]
                    }],
                    "groups": [{"id": "default", "pageId": "default"}],
                    "hiddenFields": {},
                    "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                    "primaryLocale": "en",
                    "visibleLocales": ["en"],
                    "supportedFormLocales": [{"key": "fr_CA", "label": "French"}, {"key": "en", "label": "English"}],
                    "errors": {}
                },
                "i18nAddContributor": "Add Contributor",
                "i18nConfirmDelete": "Are you sure you want to remove {$name} as a contributor? This action can not be undone.",
                "i18nDeleteContributor": "Delete Contributor",
                "i18nEditContributor": "Edit",
                "i18nSetPrimaryContact": "Set Primary Contact",
                "i18nPrimaryContact": "Primary Contact",
                "i18nContributors": "List of Contributors",
                "i18nSaveOrder": "Save Order",
                "i18nPreview": "Preview",
                "i18nPreviewDescription": "Contributors to this publication will be identified in the following formats.",
                "i18nDisplay": "Display",
                "i18nFormat": "Format",
                "i18nAbbreviated": "Abbreviated",
                "i18nPublicationLists": "Publication Lists",
                "i18nFull": "Full"
            },
            "citations": {
                "id": "citations",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "citationsRaw",
                    "component": "field-textarea",
                    "label": "References",
                    "description": "Enter each reference on a new line so that they can be extracted and recorded separately.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": "(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990"
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [],
                "errors": {}
            },
            "publicationLicense": {
                "id": "publicationLicense",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "copyrightHolder",
                    "component": "field-text",
                    "label": "Copyright Holder",
                    "description": "Copyright will be assigned automatically to Alan Mwandenga Version 2, Amina Mansour, Nicolas Riouf (Author) when this is published.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": "Journal of Public Knowledge", "fr_CA": "Journal de la connaissance du public"},
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "Override",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "copyrightYear",
                    "component": "field-text",
                    "label": "Copyright Year",
                    "description": "The copyright year will be set automatically when this is published in an issue.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": 2023,
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "Override",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "licenseUrl",
                    "component": "field-text",
                    "label": "License URL",
                    "description": "",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": null,
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "Override",
                    "size": "normal",
                    "prefix": ""
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [{"key": "en", "label": "English"}, {"key": "fr_CA", "label": "French"}],
                "errors": {}
            },
            "titleAbstract": {
                "id": "titleAbstract",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "prefix",
                    "component": "field-text",
                    "label": "Prefix",
                    "description": "Examples: A, The",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": "The", "fr_CA": ""},
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "small",
                    "prefix": ""
                }, {
                    "name": "title",
                    "component": "field-rich-text",
                    "label": "Title",
                    "groupId": "default",
                    "isRequired": true,
                    "isMultilingual": true,
                    "value": {"en": "The Signalling Theory Dividends Version 2", "fr_CA": ""},
                    "i18nFormattingLabel": "Formatting",
                    "toolbar": "formatgroup",
                    "plugins": "paste",
                    "size": "oneline"
                }, {
                    "name": "subtitle",
                    "component": "field-rich-text",
                    "label": "Subtitle",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": "A Review Of The Literature And Empirical Evidence", "fr_CA": ""},
                    "i18nFormattingLabel": "Formatting",
                    "toolbar": "formatgroup",
                    "plugins": "paste",
                    "size": "oneline"
                }, {
                    "name": "abstract",
                    "component": "field-rich-textarea",
                    "label": "Abstract",
                    "groupId": "default",
                    "isRequired": true,
                    "isMultilingual": true,
                    "value": {
                        "en": "<p>The signaling theory suggests that dividends signal future prospects of a firm. However, recent empirical evidence from the US and the Uk does not offer a conclusive evidence on this issue. There are conflicting policy implications among financial economists so much that there is no practical dividend policy guidance to management, existing and potential investors in shareholding. Since corporate investment, financing and distribution decisions are a continuous function of management, the dividend decisions seem to rely on intuitive evaluation.<\/p>",
                        "fr_CA": ""
                    },
                    "plugins": "paste,link,noneditable",
                    "size": "large",
                    "toolbar": "bold italic superscript subscript | link",
                    "wordLimit": 500,
                    "wordCountLabel": "Word Count: {$count}\/{$limit}"
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [{"key": "en", "label": "English"}, {"key": "fr_CA", "label": "French"}],
                "errors": {}
            },
            "metadata": {
                "id": "metadata",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "keywords",
                    "component": "field-controlled-vocab",
                    "label": "Keywords",
                    "tooltip": "Keywords are typically one- to three-word phrases that are used to indicate the main topics of a submission.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": ["Professional Development", "Social Transformation"], "fr_CA": ""},
                    "apiUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/vocabs?vocab=submissionKeyword",
                    "deselectLabel": "Remove {$item}",
                    "getParams": {},
                    "selectedLabel": "Selected:",
                    "selected": {
                        "en": [{
                            "value": "Professional Development",
                            "label": "Professional Development"
                        }, {"value": "Social Transformation", "label": "Social Transformation"}], "fr_CA": []
                    }
                }, {
                    "name": "coverage",
                    "component": "field-text",
                    "label": "Coverage",
                    "tooltip": "Coverage will typically indicate a work's spatial location (a place name or geographic coordinates), temporal period (a period label, date, or date range) or jurisdiction (such as a named administrative entity).",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": "", "fr_CA": ""},
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "source",
                    "component": "field-text",
                    "label": "Source",
                    "tooltip": "The source may be an ID, such as a DOI, of another work or resource from which the submission is derived.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": "", "fr_CA": ""},
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "pub-id::publisher-id",
                    "component": "field-text",
                    "label": "Publisher ID",
                    "tooltip": "The publisher ID may be used to record the ID from an external database. For example, items exported for deposit to PubMed may include the publisher ID. This should not be used for DOIs.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": null,
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [{"key": "en", "label": "English"}, {"key": "fr_CA", "label": "French"}],
                "errors": {}
            },
            "selectRevisionDecision": {
                "id": "selectRevisionDecision",
                "method": "",
                "action": "emit",
                "fields": [{
                    "name": "decision",
                    "component": "field-options",
                    "label": "Require New Review Round",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": 4,
                    "type": "radio",
                    "isOrderable": false,
                    "options": [{
                        "value": 4,
                        "label": "Revisions will not be subject to a new round of peer reviews."
                    }, {"value": 5, "label": "Revisions will be subject to a new round of peer reviews."}]
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Next"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [],
                "errors": {}
            },
            "selectRevisionRecommendation": {
                "id": "selectRevisionRecommendation",
                "method": "",
                "action": "emit",
                "fields": [{
                    "name": "decision",
                    "component": "field-options",
                    "label": "Require New Review Round",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": 10,
                    "type": "radio",
                    "isOrderable": false,
                    "options": [{
                        "value": 10,
                        "label": "Revisions should not be subject to a new round of peer reviews."
                    }, {"value": 11, "label": "Revisions should be subject to a new round of peer reviews."}]
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Next"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [],
                "errors": {}
            },
            "issueEntry": {
                "id": "issueEntry",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "issueId",
                    "component": "field-select-issue",
                    "label": "Issue",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": 1,
                    "options": [{"value": "", "label": ""}, {"value": "", "label": "--- Back Issues ---"}, {
                        "value": 1,
                        "label": "Vol. 1 No. 2 (2014)"
                    }, {"value": 2, "label": "Vol. 2 No. 1 (2015)"}],
                    "publicationStatus": 1,
                    "assignLabel": "Assign to Issue",
                    "assignedNoticeBase": "This has been assigned to <a href=\"http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/issue\/view\/__issueId__\">{$issueName}<\/a> but it has not been scheduled for publication.",
                    "changeIssueLabel": "Change Issue",
                    "publishedNoticeBase": "Published in <a href=\"http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/issue\/view\/__issueId__\">{$issueName}<\/a>.",
                    "scheduledNoticeBase": "Scheduled for publication in <a href=\"http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/issue\/view\/__issueId__\">{$issueName}<\/a>.",
                    "unscheduledNotice": "This has not been scheduled for publication in an issue.",
                    "unscheduleLabel": "Unschedule"
                }, {
                    "name": "sectionId",
                    "component": "field-select",
                    "label": "Section",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": 1,
                    "options": [{"label": "Articles", "value": 1}, {"label": "Reviews", "value": 2}]
                }, {
                    "name": "categoryIds",
                    "component": "field-options",
                    "label": "Categories",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": [],
                    "type": "checkbox",
                    "isOrderable": false,
                    "options": [{"value": 1, "label": "Applied Science"}, {
                        "value": 2,
                        "label": "Applied Science > Computer Science"
                    }, {"value": 3, "label": "Applied Science > Engineering"}, {
                        "value": 4,
                        "label": "Social Sciences"
                    }, {"value": 5, "label": "Social Sciences > Sociology"}, {
                        "value": 6,
                        "label": "Social Sciences > Anthropology"
                    }]
                }, {
                    "name": "coverImage",
                    "component": "field-upload-image",
                    "label": "Cover Image",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": true,
                    "value": {"en": null, "fr_CA": null},
                    "options": {
                        "dropzoneDictDefaultMessage": "Drop files here to upload",
                        "dropzoneDictFallbackMessage": "Your browser does not support drag'n'drop file uploads.",
                        "dropzoneDictFallbackText": "Please use the fallback form below to upload your files.",
                        "dropzoneDictFileTooBig": "File is too big ({{filesize}}mb). Files larger than {{maxFilesize}}mb can not be uploaded.",
                        "dropzoneDictInvalidFileType": "Files of this type can not be uploaded.",
                        "dropzoneDictResponseError": "Server responded with {{statusCode}} code. Please contact the system administrator if this problem persists.",
                        "dropzoneDictCancelUpload": "Cancel upload",
                        "dropzoneDictUploadCanceled": "Upload canceled",
                        "dropzoneDictCancelUploadConfirmation": "Are you sure you want to cancel this upload?",
                        "dropzoneDictRemoveFile": "Remove file",
                        "dropzoneDictMaxFilesExceeded": "You can not upload any more files.",
                        "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/temporaryFiles",
                        "maxFilesize": 2,
                        "timeout": 30000,
                        "acceptedFiles": "image\/*"
                    },
                    "uploadFileLabel": "Upload File",
                    "restoreLabel": "Restore Original",
                    "baseUrl": "http:\/\/localhost\/ojs340\/public\/journals\/1",
                    "thumbnailDescription": "Preview of the currently selected image.",
                    "altTextLabel": "Alternate text",
                    "altTextDescription": "Describe this image for visitors viewing the site in a text-only browser or with assistive devices. Example: \"Our editor speaking at the PKP conference.\""
                }, {
                    "name": "pages",
                    "component": "field-text",
                    "label": "Pages",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": "71-98",
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "urlPath",
                    "component": "field-text",
                    "label": "URL Path",
                    "description": "An optional path to use in the URL instead of the ID.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": "mwandenga",
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "datePublished",
                    "component": "field-text",
                    "label": "Date Published",
                    "description": "The publication date will be set automatically when the issue is published. Do not enter a publication date unless the article was previously published elsewhere and you need to backdate it.",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": "2023-11-18",
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "small",
                    "prefix": ""
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [{"key": "en", "label": "English"}, {"key": "fr_CA", "label": "French"}],
                "errors": {}
            },
            "CitationManagerPlugin_PublicationForm": {
                "id": "CitationManagerPlugin_PublicationForm",
                "method": "PUT",
                "action": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
                "fields": [{
                    "name": "CitationManagerPlugin_StructuredCitations",
                    "component": "field-text",
                    "label": "",
                    "description": "",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": [{
                        "doi": "10.7717\/peerj.1990",
                        "url": "",
                        "urn": "",
                        "title": "A longitudinal study of independent scholar-published open access journals",
                        "abstract": null,
                        "publication_year": 2016,
                        "publication_date": "2016-05-10",
                        "type": "article",
                        "authors": [{
                            "orcid": "",
                            "display_name": "Bo\u2010Christer Bj\u00f6rk",
                            "given_name": "Bo\u2010Christer",
                            "family_name": "Bj\u00f6rk",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5036789552"
                        }, {
                            "orcid": "0000-0002-4411-9674",
                            "display_name": "Cenyu Shen",
                            "given_name": "Cenyu",
                            "family_name": "Shen",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5080285387"
                        }, {
                            "orcid": "0000-0003-3951-7990",
                            "display_name": "null null",
                            "given_name": "Mikael",
                            "family_name": "Laakso",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5067698582"
                        }],
                        "cited_by_count": 23,
                        "volume": "4",
                        "issue": null,
                        "pages": "0",
                        "first_page": "e1990",
                        "last_page": "e1990",
                        "is_retracted": false,
                        "venue_issn_l": null,
                        "venue_name": null,
                        "venue_publisher": null,
                        "venue_is_oa": null,
                        "venue_openalex_id": null,
                        "venue_url": null,
                        "updated_date": "2024-01-27T06:49:26.265856",
                        "created_date": "2016-06-24",
                        "pids": null,
                        "external_sources": null,
                        "isProcessed": true,
                        "openalex_id": "W2369996029",
                        "wikidata_id": "Q227269",
                        "opencitations_id": null,
                        "raw": "(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990"
                    }, {
                        "doi": "10.7717\/peerj.1990",
                        "url": "",
                        "urn": "",
                        "title": "A longitudinal study of independent scholar-published open access journals",
                        "abstract": null,
                        "publication_year": 2016,
                        "publication_date": "2016-05-10",
                        "type": "article",
                        "authors": [{
                            "orcid": "",
                            "display_name": "Bo\u2010Christer Bj\u00f6rk",
                            "given_name": "Bo\u2010Christer",
                            "family_name": "Bj\u00f6rk",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5036789552"
                        }, {
                            "orcid": "0000-0002-4411-9674",
                            "display_name": "Cenyu Shen",
                            "given_name": "Cenyu",
                            "family_name": "Shen",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5080285387"
                        }, {
                            "orcid": "0000-0003-3951-7990",
                            "display_name": "null null",
                            "given_name": "Mikael",
                            "family_name": "Laakso",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5067698582"
                        }],
                        "cited_by_count": 23,
                        "volume": "4",
                        "issue": null,
                        "pages": "0",
                        "first_page": "e1990",
                        "last_page": "e1990",
                        "is_retracted": false,
                        "venue_issn_l": null,
                        "venue_name": null,
                        "venue_publisher": null,
                        "venue_is_oa": null,
                        "venue_openalex_id": null,
                        "venue_url": null,
                        "updated_date": "2024-01-27T06:49:26.265856",
                        "created_date": "2016-06-24",
                        "pids": null,
                        "external_sources": null,
                        "isProcessed": true,
                        "openalex_id": "W2369996029",
                        "wikidata_id": "Q227269",
                        "opencitations_id": null,
                        "raw": "(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990"
                    }, {
                        "doi": "10.7717\/peerj.1990",
                        "url": "",
                        "urn": "",
                        "title": "A longitudinal study of independent scholar-published open access journals",
                        "abstract": null,
                        "publication_year": 2016,
                        "publication_date": "2016-05-10",
                        "type": "article",
                        "authors": [{
                            "orcid": "",
                            "display_name": "Bo\u2010Christer Bj\u00f6rk",
                            "given_name": "Bo\u2010Christer",
                            "family_name": "Bj\u00f6rk",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5036789552"
                        }, {
                            "orcid": "0000-0002-4411-9674",
                            "display_name": "Cenyu Shen",
                            "given_name": "Cenyu",
                            "family_name": "Shen",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5080285387"
                        }, {
                            "orcid": "0000-0003-3951-7990",
                            "display_name": "null null",
                            "given_name": "Mikael",
                            "family_name": "Laakso",
                            "works_count": null,
                            "cited_by_count": null,
                            "counts_by_year": null,
                            "updated_date": null,
                            "created_date": null,
                            "pids": null,
                            "external_sources": null,
                            "isProcessed": null,
                            "wikidata_id": null,
                            "openalex_id": "A5067698582"
                        }],
                        "cited_by_count": 23,
                        "volume": "4",
                        "issue": null,
                        "pages": "0",
                        "first_page": "e1990",
                        "last_page": "e1990",
                        "is_retracted": false,
                        "venue_issn_l": null,
                        "venue_name": null,
                        "venue_publisher": null,
                        "venue_is_oa": null,
                        "venue_openalex_id": null,
                        "venue_url": null,
                        "updated_date": "2024-01-27T06:49:26.265856",
                        "created_date": "2016-06-24",
                        "pids": null,
                        "external_sources": null,
                        "isProcessed": true,
                        "openalex_id": "W2369996029",
                        "wikidata_id": "Q227269",
                        "opencitations_id": null,
                        "raw": "(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990"
                    }],
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }, {
                    "name": "CitationManagerPlugin_PublicationWork",
                    "component": "field-text",
                    "label": "",
                    "description": "",
                    "groupId": "default",
                    "isRequired": false,
                    "isMultilingual": false,
                    "value": "",
                    "inputType": "text",
                    "optIntoEdit": false,
                    "optIntoEditLabel": "",
                    "size": "normal",
                    "prefix": ""
                }],
                "groups": [{"id": "default", "pageId": "default"}],
                "hiddenFields": {},
                "pages": [{"id": "default", "submitButton": {"label": "Save"}}],
                "primaryLocale": "en",
                "visibleLocales": ["en"],
                "supportedFormLocales": [{"key": "en", "label": "English"}, {"key": "fr_CA", "label": "French"}],
                "errors": {}
            },
            "CitationManagerPlugin_PublicationWork": {
                "doi": null,
                "url": null,
                "urn": null,
                "title": null,
                "abstract": null,
                "publication_year": null,
                "publication_date": null,
                "type": null,
                "authors": null,
                "cited_by_count": null,
                "volume": null,
                "issue": null,
                "pages": null,
                "first_page": null,
                "last_page": null,
                "is_retracted": null,
                "venue_issn_l": null,
                "venue_name": null,
                "venue_publisher": null,
                "venue_is_oa": null,
                "venue_openalex_id": null,
                "venue_url": null,
                "updated_date": null,
                "created_date": null,
                "pids": null,
                "external_sources": null,
                "isProcessed": null,
                "openalex_id": null,
                "wikidata_id": null,
                "opencitations_id": null
            },
            "CitationManagerPlugin_AuthorModel": {
                "orcid": null,
                "display_name": null,
                "given_name": null,
                "family_name": null,
                "works_count": null,
                "cited_by_count": null,
                "counts_by_year": null,
                "updated_date": null,
                "created_date": null,
                "pids": null,
                "external_sources": null,
                "isProcessed": null,
                "wikidata_id": null,
                "openalex_id": null
            }
        },
        "currentPublication": {
            "CitationManagerPlugin_StructuredCitations": "[{\"doi\":\"10.7717/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https://doi.org/10.7717/peerj.1990\"},{\"doi\":\"10.7717/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https://doi.org/10.7717/peerj.1990\"},{\"doi\":\"10.7717/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https://doi.org/10.7717/peerj.1990\"}]",
            "CitationManagerPlugin_PublicationWork": "{\"doi\":null,\"url\":null,\"urn\":null,\"title\":null,\"abstract\":null,\"publication_year\":null,\"publication_date\":null,\"type\":null,\"authors\":null,\"cited_by_count\":null,\"volume\":null,\"issue\":null,\"pages\":null,\"first_page\":null,\"last_page\":null,\"is_retracted\":null,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"openalex_id\":null,\"wikidata_id\":null,\"opencitations_id\":null}",
            "_href": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
            "abstract": {
                "en": "<p>The signaling theory suggests that dividends signal future prospects of a firm. However, recent empirical evidence from the US and the Uk does not offer a conclusive evidence on this issue. There are conflicting policy implications among financial economists so much that there is no practical dividend policy guidance to management, existing and potential investors in shareholding. Since corporate investment, financing and distribution decisions are a continuous function of management, the dividend decisions seem to rely on intuitive evaluation.<\/p>",
                "fr_CA": ""
            },
            "accessStatus": 0,
            "authors": [{
                "affiliation": {"en": "University of Cape Town", "fr_CA": ""},
                "country": "ZA",
                "email": "amwandenga@mailinator.com",
                "familyName": {"en": "Mwandenga Version 2", "fr_CA": ""},
                "fullName": "Alan Mwandenga Version 2",
                "givenName": {"en": "Alan", "fr_CA": ""},
                "id": 5,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 0,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }, {
                "affiliation": {"en": "", "fr_CA": ""},
                "country": "BB",
                "email": "notanemailamansour@mailinator.com",
                "familyName": {"en": "Mansour", "fr_CA": ""},
                "fullName": "Amina Mansour",
                "givenName": {"en": "Amina", "fr_CA": ""},
                "id": 6,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 1,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }, {
                "affiliation": {"en": "", "fr_CA": ""},
                "country": "ZA",
                "email": "nriouf@mailinator.com",
                "familyName": {"en": "Riouf", "fr_CA": ""},
                "fullName": "Nicolas Riouf",
                "givenName": {"en": "Nicolas", "fr_CA": ""},
                "id": 7,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 2,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }],
            "authorsString": "Alan Mwandenga Version 2, Amina Mansour, Nicolas Riouf (Author)",
            "authorsStringIncludeInBrowse": "Alan Mwandenga Version 2, Amina Mansour, Nicolas Riouf (Author)",
            "authorsStringShort": "Mwandenga Version 2 et al.",
            "categoryIds": [],
            "citations": ["(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>", "(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>", "(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>"],
            "citationsRaw": "(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990",
            "copyrightHolder": {"en": "Journal of Public Knowledge", "fr_CA": "Journal de la connaissance du public"},
            "copyrightYear": 2023,
            "coverImage": {"en": null, "fr_CA": null},
            "coverage": {"en": "", "fr_CA": ""},
            "dataAvailability": {"en": "", "fr_CA": ""},
            "datePublished": "2023-11-18",
            "disciplines": {"en": [], "fr_CA": []},
            "doiObject": {
                "contextId": 1,
                "doi": "10.3400\/mrh97z33",
                "id": 6,
                "registrationAgency": null,
                "resolvingUrl": "https:\/\/doi.org\/10.3400\/mrh97z33",
                "status": 1
            },
            "fullTitle": {
                "en": "The The Signalling Theory Dividends Version 2: A Review Of The Literature And Empirical Evidence",
                "fr_CA": ""
            },
            "galleys": [{
                "doiObject": {
                    "contextId": 1,
                    "doi": "10.3400\/mm0hwk82",
                    "id": 7,
                    "registrationAgency": null,
                    "resolvingUrl": "https:\/\/doi.org\/10.3400\/mm0hwk82",
                    "status": 1
                },
                "file": {
                    "_href": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/files\/12",
                    "assocId": 1,
                    "assocType": 521,
                    "caption": null,
                    "copyrightOwner": null,
                    "createdAt": "2023-11-18 02:21:31",
                    "creator": {"en": "", "fr_CA": ""},
                    "credit": null,
                    "dateCreated": null,
                    "dependentFiles": [],
                    "description": {"en": "", "fr_CA": ""},
                    "documentType": "pdf",
                    "fileId": 7,
                    "fileStage": 10,
                    "genreId": 1,
                    "genreIsDependent": false,
                    "genreIsSupplementary": false,
                    "genreName": {"en": "Article Text", "fr_CA": "Texte de l'article"},
                    "id": 12,
                    "language": null,
                    "locale": "en",
                    "mimetype": "application\/pdf",
                    "name": {"en": "article.pdf", "fr_CA": ""},
                    "path": "journals\/1\/articles\/1\/65581fab296f5.pdf",
                    "publisher": {"en": "", "fr_CA": ""},
                    "revisions": [],
                    "source": {"en": "", "fr_CA": ""},
                    "sourceSubmissionFileId": null,
                    "sponsor": {"en": "", "fr_CA": ""},
                    "subject": {"en": "", "fr_CA": ""},
                    "submissionId": 1,
                    "terms": null,
                    "updatedAt": "2023-11-18 02:21:32",
                    "uploaderUserId": 3,
                    "uploaderUserName": "dbarnes",
                    "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/api\/file\/file-api\/download-file?submissionFileId=12&submissionId=1&stageId=5",
                    "viewable": null
                },
                "id": 2,
                "isApproved": false,
                "label": "PDF Version 2",
                "locale": "en",
                "pub-id::other::urn": null,
                "pub-id::publisher-id": null,
                "publicationId": 2,
                "seq": 0,
                "submissionFileId": 12,
                "urlPublished": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/article\/view\/mwandenga\/version\/2\/pdf",
                "urlRemote": null,
                "urnSuffix": null
            }],
            "hideAuthor": null,
            "id": 2,
            "issueId": 1,
            "keywords": {"en": ["Professional Development", "Social Transformation"], "fr_CA": []},
            "languages": {"en": [], "fr_CA": []},
            "lastModified": "2024-01-31 11:37:47",
            "licenseUrl": null,
            "locale": "en",
            "pages": "71-98",
            "prefix": {"en": "The", "fr_CA": ""},
            "primaryContactId": 5,
            "pub-id::other::urn": null,
            "pub-id::publisher-id": null,
            "rights": {"en": "", "fr_CA": ""},
            "sectionId": 1,
            "seq": 0,
            "source": {"en": "", "fr_CA": ""},
            "status": 1,
            "subjects": {"en": [], "fr_CA": []},
            "submissionId": 1,
            "subtitle": {"en": "A Review Of The Literature And Empirical Evidence", "fr_CA": ""},
            "supportingAgencies": {"en": [], "fr_CA": []},
            "title": {"en": "The Signalling Theory Dividends Version 2", "fr_CA": ""},
            "type": {"en": "", "fr_CA": ""},
            "urlPath": "mwandenga",
            "urlPublished": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/article\/view\/mwandenga\/version\/2",
            "urnSuffix": null,
            "version": 2
        },
        "decisionUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/decision\/record\/1?decision=__decision__&reviewRoundId=__reviewRoundId__",
        "editorialHistoryUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/information-center\/submission-information-center\/view-information-center?submissionId=1",
        "publicationFormIds": ["citations", "publicationLicense", "publish", "titleAbstract", "metadata", "issueEntry"],
        "publicationList": [{"id": 1, "datePublished": "2023-11-18", "status": 1, "version": 1}, {
            "id": 2,
            "datePublished": "2023-11-18",
            "status": 1,
            "version": 2
        }],
        "publicationTabsLabel": "Publication details for version {$version}",
        "publishLabel": "Publish",
        "publishUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/modals\/publish\/publish\/publish?submissionId=1&publicationId=__publicationId__",
        "representationsGridUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/grid\/article-galleys\/article-galley-grid\/fetch-grid?submissionId=1&publicationId=__publicationId__",
        "schedulePublicationLabel": "Schedule For Publication",
        "statusLabel": "Status:",
        "submission": {
            "_href": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1",
            "contextId": 1,
            "currentPublicationId": 2,
            "dateLastActivity": "2024-01-31 11:37:47",
            "dateSubmitted": "2023-11-18 02:17:49",
            "id": 1,
            "lastModified": "2023-11-18 02:17:49",
            "locale": "en",
            "stageId": 5,
            "status": 1,
            "statusLabel": "Queued",
            "submissionProgress": "",
            "urlAuthorWorkflow": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/authorDashboard\/submission\/1",
            "urlEditorialWorkflow": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/workflow\/access\/1",
            "urlPublished": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/article\/view\/mwandenga",
            "urlSubmissionWizard": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/submission?id=1",
            "urlWorkflow": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/workflow\/access\/1"
        },
        "submissionFileApiUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/files",
        "submissionApiUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1",
        "submissionLibraryLabel": "Submission Library",
        "submissionLibraryUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/modals\/document-library\/document-library\/document-library?submissionId=1",
        "supportsReferences": true,
        "unpublishConfirmLabel": "Are you sure you don't want this to be published?",
        "unpublishLabel": "Unpublish",
        "unscheduleConfirmLabel": "Are you sure you don't want this scheduled for publication?",
        "unscheduleLabel": "Unschedule",
        "versionLabel": "Version:",
        "versionConfirmTitle": "Create New Version",
        "versionConfirmMessage": "Are you sure you want to create a new version?",
        "workingPublication": {
            "CitationManagerPlugin_StructuredCitations": "[{\"doi\":\"10.7717\/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\"},{\"doi\":\"10.7717\/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\"},{\"doi\":\"10.7717\/peerj.1990\",\"url\":\"\",\"urn\":\"\",\"title\":\"A longitudinal study of independent scholar-published open access journals\",\"abstract\":null,\"publication_year\":2016,\"publication_date\":\"2016-05-10\",\"type\":\"article\",\"authors\":[{\"orcid\":\"\",\"display_name\":\"Bo\u2010Christer Bj\u00f6rk\",\"given_name\":\"Bo\u2010Christer\",\"family_name\":\"Bj\u00f6rk\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5036789552\"},{\"orcid\":\"0000-0002-4411-9674\",\"display_name\":\"Cenyu Shen\",\"given_name\":\"Cenyu\",\"family_name\":\"Shen\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5080285387\"},{\"orcid\":\"0000-0003-3951-7990\",\"display_name\":\"null null\",\"given_name\":\"Mikael\",\"family_name\":\"Laakso\",\"works_count\":null,\"cited_by_count\":null,\"counts_by_year\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"wikidata_id\":null,\"openalex_id\":\"A5067698582\"}],\"cited_by_count\":23,\"volume\":\"4\",\"issue\":null,\"pages\":\"0\",\"first_page\":\"e1990\",\"last_page\":\"e1990\",\"is_retracted\":false,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":\"2024-01-27T06:49:26.265856\",\"created_date\":\"2016-06-24\",\"pids\":null,\"external_sources\":null,\"isProcessed\":true,\"openalex_id\":\"W2369996029\",\"wikidata_id\":\"Q227269\",\"opencitations_id\":null,\"raw\":\"(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\"}]",
            "CitationManagerPlugin_PublicationWork": "{\"doi\":null,\"url\":null,\"urn\":null,\"title\":null,\"abstract\":null,\"publication_year\":null,\"publication_date\":null,\"type\":null,\"authors\":null,\"cited_by_count\":null,\"volume\":null,\"issue\":null,\"pages\":null,\"first_page\":null,\"last_page\":null,\"is_retracted\":null,\"venue_issn_l\":null,\"venue_name\":null,\"venue_publisher\":null,\"venue_is_oa\":null,\"venue_openalex_id\":null,\"venue_url\":null,\"updated_date\":null,\"created_date\":null,\"pids\":null,\"external_sources\":null,\"isProcessed\":null,\"openalex_id\":null,\"wikidata_id\":null,\"opencitations_id\":null}",
            "_href": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/publications\/2",
            "abstract": {
                "en": "<p>The signaling theory suggests that dividends signal future prospects of a firm. However, recent empirical evidence from the US and the Uk does not offer a conclusive evidence on this issue. There are conflicting policy implications among financial economists so much that there is no practical dividend policy guidance to management, existing and potential investors in shareholding. Since corporate investment, financing and distribution decisions are a continuous function of management, the dividend decisions seem to rely on intuitive evaluation.<\/p>",
                "fr_CA": ""
            },
            "accessStatus": 0,
            "authors": [{
                "affiliation": {"en": "University of Cape Town", "fr_CA": ""},
                "country": "ZA",
                "email": "amwandenga@mailinator.com",
                "familyName": {"en": "Mwandenga Version 2", "fr_CA": ""},
                "fullName": "Alan Mwandenga Version 2",
                "givenName": {"en": "Alan", "fr_CA": ""},
                "id": 5,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 0,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }, {
                "affiliation": {"en": "", "fr_CA": ""},
                "country": "BB",
                "email": "notanemailamansour@mailinator.com",
                "familyName": {"en": "Mansour", "fr_CA": ""},
                "fullName": "Amina Mansour",
                "givenName": {"en": "Amina", "fr_CA": ""},
                "id": 6,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 1,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }, {
                "affiliation": {"en": "", "fr_CA": ""},
                "country": "ZA",
                "email": "nriouf@mailinator.com",
                "familyName": {"en": "Riouf", "fr_CA": ""},
                "fullName": "Nicolas Riouf",
                "givenName": {"en": "Nicolas", "fr_CA": ""},
                "id": 7,
                "includeInBrowse": true,
                "locale": "en",
                "orcid": null,
                "preferredPublicName": {"en": "", "fr_CA": ""},
                "publicationId": 2,
                "seq": 2,
                "userGroupId": 14,
                "userGroupName": {"en": "Author", "fr_CA": "Auteur-e"}
            }],
            "authorsString": "Alan Mwandenga Version 2, Amina Mansour, Nicolas Riouf (Author)",
            "authorsStringIncludeInBrowse": "Alan Mwandenga Version 2, Amina Mansour, Nicolas Riouf (Author)",
            "authorsStringShort": "Mwandenga Version 2 et al.",
            "categoryIds": [],
            "citations": ["(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>", "(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>", "(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 <a href=\"https:\/\/doi.org\/10.7717\/peerj.1990\">https:\/\/doi.org\/10.7717\/peerj.1990<\/a>"],
            "citationsRaw": "(1. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(2. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990\n(3. publicationId: 2) Bj\u00f6rk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4 https:\/\/doi.org\/10.7717\/peerj.1990",
            "copyrightHolder": {"en": "Journal of Public Knowledge", "fr_CA": "Journal de la connaissance du public"},
            "copyrightYear": 2023,
            "coverImage": {"en": null, "fr_CA": null},
            "coverage": {"en": "", "fr_CA": ""},
            "dataAvailability": {"en": "", "fr_CA": ""},
            "datePublished": "2023-11-18",
            "disciplines": {"en": [], "fr_CA": []},
            "doiObject": {
                "contextId": 1,
                "doi": "10.3400\/mrh97z33",
                "id": 6,
                "registrationAgency": null,
                "resolvingUrl": "https:\/\/doi.org\/10.3400\/mrh97z33",
                "status": 1
            },
            "fullTitle": {
                "en": "The The Signalling Theory Dividends Version 2: A Review Of The Literature And Empirical Evidence",
                "fr_CA": ""
            },
            "galleys": [{
                "doiObject": {
                    "contextId": 1,
                    "doi": "10.3400\/mm0hwk82",
                    "id": 7,
                    "registrationAgency": null,
                    "resolvingUrl": "https:\/\/doi.org\/10.3400\/mm0hwk82",
                    "status": 1
                },
                "file": {
                    "_href": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/submissions\/1\/files\/12",
                    "assocId": 1,
                    "assocType": 521,
                    "caption": null,
                    "copyrightOwner": null,
                    "createdAt": "2023-11-18 02:21:31",
                    "creator": {"en": "", "fr_CA": ""},
                    "credit": null,
                    "dateCreated": null,
                    "dependentFiles": [],
                    "description": {"en": "", "fr_CA": ""},
                    "documentType": "pdf",
                    "fileId": 7,
                    "fileStage": 10,
                    "genreId": 1,
                    "genreIsDependent": false,
                    "genreIsSupplementary": false,
                    "genreName": {"en": "Article Text", "fr_CA": "Texte de l'article"},
                    "id": 12,
                    "language": null,
                    "locale": "en",
                    "mimetype": "application\/pdf",
                    "name": {"en": "article.pdf", "fr_CA": ""},
                    "path": "journals\/1\/articles\/1\/65581fab296f5.pdf",
                    "publisher": {"en": "", "fr_CA": ""},
                    "revisions": [],
                    "source": {"en": "", "fr_CA": ""},
                    "sourceSubmissionFileId": null,
                    "sponsor": {"en": "", "fr_CA": ""},
                    "subject": {"en": "", "fr_CA": ""},
                    "submissionId": 1,
                    "terms": null,
                    "updatedAt": "2023-11-18 02:21:32",
                    "uploaderUserId": 3,
                    "uploaderUserName": "dbarnes",
                    "url": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/api\/file\/file-api\/download-file?submissionFileId=12&submissionId=1&stageId=5",
                    "viewable": null
                },
                "id": 2,
                "isApproved": false,
                "label": "PDF Version 2",
                "locale": "en",
                "pub-id::other::urn": null,
                "pub-id::publisher-id": null,
                "publicationId": 2,
                "seq": 0,
                "submissionFileId": 12,
                "urlPublished": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/article\/view\/mwandenga\/version\/2\/pdf",
                "urlRemote": null,
                "urnSuffix": null
            }],
            "hideAuthor": null,
            "id": 2,
            "issueId": 1,
            "keywords": {"en": ["Professional Development", "Social Transformation"], "fr_CA": []},
            "languages": {"en": [], "fr_CA": []},
            "lastModified": "2024-01-31 11:37:47",
            "licenseUrl": null,
            "locale": "en",
            "pages": "71-98",
            "prefix": {"en": "The", "fr_CA": ""},
            "primaryContactId": 5,
            "pub-id::other::urn": null,
            "pub-id::publisher-id": null,
            "rights": {"en": "", "fr_CA": ""},
            "sectionId": 1,
            "seq": 0,
            "source": {"en": "", "fr_CA": ""},
            "status": 1,
            "subjects": {"en": [], "fr_CA": []},
            "submissionId": 1,
            "subtitle": {"en": "A Review Of The Literature And Empirical Evidence", "fr_CA": ""},
            "supportingAgencies": {"en": [], "fr_CA": []},
            "title": {"en": "The Signalling Theory Dividends Version 2", "fr_CA": ""},
            "type": {"en": "", "fr_CA": ""},
            "urlPath": "mwandenga",
            "urlPublished": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/article\/view\/mwandenga\/version\/2",
            "urnSuffix": null,
            "version": 2
        },
        "assignToIssueUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/$$$call$$$\/modals\/publish\/assign-to-issue\/assign?submissionId=1&publicationId=__publicationId__",
        "issueApiUrl": "http:\/\/localhost\/ojs340\/index.php\/publicknowledge\/api\/v1\/issues\/__issueId__",
        "sectionWordLimits": {"1": 500, "2": 0},
        "selectIssueLabel": "Select an issue to schedule for publication"
    }
);
