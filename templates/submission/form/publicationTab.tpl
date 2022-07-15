<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<script>
    var optimetaCitations = JSON.parse(`{$citationsParsed}`);

    var optimetaCitationsApp = new pkp.Vue({
        //el: '#optimetaCitations',
        data: {
            citations: optimetaCitations,
            helper: optimetaCitationsGetHelperArray(optimetaCitations),
            author: { {$authorModel} },
            deposit: {
                opencitations_url: ''
            }
        },
        computed: {
            citationsJsonComputed: function() {
                return JSON.stringify(this.citations);
            },
            optimetaCitationsIsParsed: function() {
                if(this.citations.length === 0){
                    return false;
                }
                return true;
            }
        },
        methods: {
            startEdit: function(index){
                this.helper[index].editRow = true;
            },
            endEdit: function(index){
                if(this.citations[index].authors !== null){
                    this.cleanupEmptyAuthorRows(index);
                }
                this.helper[index].editRow = false;
            },
            addAuthor: function(index){
                if(this.citations[index].authors === null){
                    this.citations[index].authors = [];
                }
                this.citations[index].authors.push(this.author);
            },
            removeAuthor: function(index, authorIndex){
                if (confirm('{translate key="plugins.generic.optimetaCitationsPlugin.author.remove.question"}') !== true) {
                    return;
                }
                this.citations[index].authors.splice(authorIndex, 1);
            },
            cleanupEmptyAuthorRows: function(index){
                var iS = '';
                for(let i = 0; i < this.citations[index].authors.length; i++){
                    var rowIsNull = true;
                    for(var key in this.citations[index].authors[i]){
                        if(this.citations[index].authors[i][key] !== null){
                            rowIsNull = false;
                        }
                    }
                    if(rowIsNull === true){
                        this.citations[index].authors.splice(i);
                    }
                }
            }
        }
    });

    function optimetaLoadingImage(show){
        show = (typeof show !== 'undefined') ?  show : true;

        let elEmpty = document.getElementById("optimetaScrollableDivEmpty");
        let elValue = document.getElementById("optimetaScrollableDivValue");
        let elLoading = document.getElementById("optimetaScrollableDivLoading");

        if (show === true) {
            elEmpty.classList.add("optimetaHide");
            elValue.classList.add("optimetaHide");
            elLoading.classList.remove("optimetaHide");
        } else {
            elEmpty.classList.remove("optimetaHide");
            elValue.classList.remove("optimetaHide");
            elLoading.classList.add("optimetaHide");
        }
    }

    function optimetaProcessCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.process.question"}';
        if (confirm(questionText) !== true) { return; }

        optimetaLoadingImage(true);

        $.ajax({
            url: '{$pluginApiUrl}/process',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: optimetaCitationsGetCitationsRaw()
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {
                optimetaCitations = JSON.parse(JSON.stringify(response['message']));
                optimetaCitationsApp.citations = JSON.parse(JSON.stringify(response['message']));
                optimetaCitationsApp.helper = optimetaCitationsGetHelperArray(JSON.parse(JSON.stringify(response['message'])));

                optimetaLoadingImage(false);
            }
        });
    }

    function optimetaDepositCitations (){
        {*let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.deposit.question"}';*}
        // if (confirm(questionText) !== true) { return; }

        optimetaLoadingImage(true);

        $.ajax({
            url: '{$pluginApiUrl}/deposit',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citations: JSON.stringify(optimetaCitationsApp.citations)
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {
                optimetaCitationsApp.deposit = JSON.parse(JSON.stringify(response['message']));
                optimetaLoadingImage(false);
            }
        });
    }

    function optimetaClearCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.clear.question"}';
        if (confirm(questionText) !== true) { return; }

        optimetaCitations = [];
        optimetaCitationsApp.citations = [];
        optimetaCitationsApp.helper = [];
    }
</script>

<tab v-if="supportsReferences" id="optimetaCitations" label="{translate key="plugins.generic.optimetaCitationsPlugin.publication.label"}">

    <div class="header">
        <table>
            <tr>
                <td colspan="2">
                    <label class="pkpFormFieldLabel">{translate key="plugins.generic.optimetaCitationsPlugin.process.label"}</label> <br/>
                    <div class="pkpFormField__description">{translate key="plugins.generic.optimetaCitationsPlugin.process.description"}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="optimetaButton optimetaButtonGrey">Wikidata</span>
                    <a class="optimetaButton optimetaButtonGreen"
                       v-if="optimetaCitationsApp.deposit.opencitations_url"
                       :href="optimetaCitationsApp.deposit.opencitations_url"
                       target="_blank"><span>OpenCitations</span></a>
                    <span class="optimetaButton optimetaButtonGrey"
                          v-if="!optimetaCitationsApp.deposit.opencitations_url">OpenCitations</span>

                </td>
                <td class="optimetaAlignRight">
                    <a href="javascript:optimetaDepositCitations()" id="buttonSubmit" class="pkpButton"
                       :class="(optimetaCitationsApp.optimetaCitationsIsParsed)?'':'optimetaDisabled'">{translate key="plugins.generic.optimetaCitationsPlugin.deposit.button"}</a>
                    <a href="javascript:optimetaClearCitations()" id="buttonClear" class="pkpButton"
                       :class="(optimetaCitationsApp.optimetaCitationsIsParsed)?'':'optimetaDisabled'">{translate key="plugins.generic.optimetaCitationsPlugin.clear.button"}</a>
                    <a href="javascript:optimetaProcessCitations()" id="buttonProcess" class="pkpButton">{translate key="plugins.generic.optimetaCitationsPlugin.process.button"}</a>
                </td>
            </tr>
        </table>
    </div>

    <div class="optimetaScrollableDiv">

        <div id="optimetaScrollableDivLoading" class="optimetaScrollableDivLoading optimetaHide">
            <img src="{$pluginImagesURL}/loading-transparent.gif"/>
        </div>

        <div id="optimetaScrollableDivEmpty" class="optimetaScrollableDivEmpty" v-show="!optimetaCitationsApp.optimetaCitationsIsParsed">
            {translate key="plugins.generic.optimetaCitationsPlugin.citations.empty.description"}
        </div>

        <div id="optimetaScrollableDivValue" class="optimetaScrollableDivValue">
            <table v-show="optimetaCitationsApp.optimetaCitationsIsParsed">
                <colgroup>
                    <col class="grid-column column-nr" style="width: 2%;">
                    <col class="grid-column column-parts" style="">
                    <col class="grid-column column-action" style="width: 6%;">
                </colgroup>
                <tbody>
                    <tr v-for="(row, i) in optimetaCitationsApp.helper" class="optimetaRow">
                        <td class="optimetaScrollableDiv-nr">{{ i + 1 }}</td>
                        <td class="optimetaScrollableDiv-parts">
                            <div>
                                <span v-show="!row.editRow">
                                    <a :href="optimetaCitationsApp.citations[i].doi"
                                       target="_blank">{{ optimetaCitationsApp.citations[i].doi }}</a></span>
                                <input id="doi-{{ i + 1 }}" placeholder="DOI" v-show="row.editRow"
                                       v-model="optimetaCitationsApp.citations[i].doi"
                                       class="optimetaInput" />

                                <span v-show="!row.editRow">
                                    <a :href="optimetaCitationsApp.citations[i].urn"
                                       target="_blank">{{ optimetaCitationsApp.citations[i].urn }}</a></span>
                                <input id="urn-{{ i + 1 }}" placeholder="URN" v-show="row.editRow"
                                       v-model="optimetaCitationsApp.citations[i].urn"
                                       class="optimetaInput" />

                                <span v-show="!row.editRow">
                                    <a :href="optimetaCitationsApp.citations[i].url"
                                       target="_blank">{{ optimetaCitationsApp.citations[i].url }}</a></span>
                                <input id="url-{{ i + 1 }}" placeholder="URL" v-show="row.editRow"
                                       v-model="optimetaCitationsApp.citations[i].url"
                                       class="optimetaInput" />
                            </div>

                            <div>

                                <div>
                                    <span v-for="(author, j) in optimetaCitationsApp.citations[i].authors">
                                        <span v-show="!row.editRow" class="optimetaTag">{{ optimetaCitationsApp.citations[i].authors[j].name }}</span>
                                        <input id="display_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Author" v-show="row.editRow"
                                               v-model="optimetaCitationsApp.citations[i].authors[j].name"
                                               class="optimetaInput" />
                                        <input id="orcid-{{ i + 1 }}-{{ j + 1 }}" placeholder="Orcid" v-show="row.editRow"
                                               v-model="optimetaCitationsApp.citations[i].authors[j].orcid"
                                               class="optimetaInput" />
                                        <a class="optimetaButton optimetaButtonGreen"
                                           v-if="optimetaCitationsApp.citations[i].authors[j].orcid"
                                           :href="optimetaCitationsApp.citations[i].authors[j].orcid"
                                           target="_blank">iD</a>
                                    <a class="pkpButton" v-show="row.editRow"
                                       v-on:click="optimetaCitationsApp.removeAuthor(i, j)">
                                        <i class="fa fa-trash" aria-hidden="true"></i> </a>
                                        <br v-show="row.editRow"/>
                                    </span>
                                <a class="pkpButton" v-show="row.editRow"
                                   v-on:click="optimetaCitationsApp.addAuthor(i)">{translate key="plugins.generic.optimetaCitationsPlugin.author.add.button"}</a>
                                </div>

                                <div>
                                    <span v-show="!row.editRow && !optimetaCitationsApp.citations[i].isProcessed"
                                          class="optimetaTag">No information found</span>

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].title"
                                          class="optimetaTag">{{ optimetaCitationsApp.citations[i].title }}</span>
                                    <input id="title-{{ i + 1 }}" placeholder="Title" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].title" />

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].venue_name"
                                          class="optimetaTag">{{ optimetaCitationsApp.citations[i].venue_name }}</span>
                                    <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].venue_name" />

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].publication_year"
                                          class="optimetaTag">{{ optimetaCitationsApp.citations[i].publication_year }}</span>
                                    <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].publication_year" />

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].volume"
                                          class="optimetaTag">Volume {{ optimetaCitationsApp.citations[i].volume }}</span>
                                    <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].volume" />

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].issue"
                                          class="optimetaTag">Issue {{ optimetaCitationsApp.citations[i].issue }}</span>
                                    <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].issue" />

                                    <span v-show="!row.editRow && optimetaCitationsApp.citations[i].first_page"
                                          class="optimetaTag">Pages {{ optimetaCitationsApp.citations[i].first_page }} - {{ optimetaCitationsApp.citations[i].last_page }}</span>
                                    <input id="first_page-{{ i + 1 }}" placeholder="First page" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].first_page" />
                                    <input id="last_page-{{ i + 1 }}" placeholder="Last page" v-show="row.editRow" class="optimetaInput"
                                           v-model="optimetaCitationsApp.citations[i].last_page" />
                                </div>

                            </div>

                            <div class="optimetaRawText">{{ optimetaCitationsApp.citations[i].raw }}</div>

                            <div>
                                <a class="optimetaButton optimetaButtonGreen"
                                   v-if="optimetaCitationsApp.citations[i].wikidata_qid"
                                   :href="'https://www.wikidata.org/wiki/' + optimetaCitationsApp.citations[i].wikidata_qid"
                                   target="_blank"><span>Wikidata</span></a>
                                <span class="optimetaButton optimetaButtonGrey"
                                      v-if="!optimetaCitationsApp.citations[i].wikidata_qid">Wikidata</span>
                                <a class="optimetaButton optimetaButton optimetaButtonGreen"
                                   v-if="optimetaCitationsApp.citations[i].openalex_id"
                                   :href="'https://openalex.org/' + optimetaCitationsApp.citations[i].openalex_id"
                                   target="_blank"><span>OpenAlex</span></a>
                                <span class="optimetaButton optimetaButtonGrey"
                                      v-if="!optimetaCitationsApp.citations[i].openalex_id">OpenAlex</span>
                            </div>
                        </td>
                    <td class="optimetaScrollableDiv-actions">
                        <a v-show="!row.editRow" v-on:click="optimetaCitationsApp.startEdit(i)"
                           class="pkpButton"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a v-show="row.editRow" v-on:click="optimetaCitationsApp.endEdit(i)"
                           class="pkpButton"><i class="fa fa-check" aria-hidden="true"></i></a>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <span style="display: none;">{{ components.{$smarty.const.OPTIMETA_CITATIONS_PUBLICATION_FORM}.fields[0]['value'] = optimetaCitationsApp.citationsJsonComputed }}</span>
        <pkp-form v-bind="components.{$smarty.const.OPTIMETA_CITATIONS_PUBLICATION_FORM}" @set="set"/>
    </div>

</tab>
