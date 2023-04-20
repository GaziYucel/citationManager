<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<script>
    var optimetaCitations = JSON.parse(`{$citationsParsed}`);

    var optimetaCitationsApp = new pkp.Vue({
        el: '#optimetaCitations',
        data: {
            citations: optimetaCitations,
            helper: optimetaCitationsGetHelperArray(optimetaCitations),
            author: JSON.parse(`{$authorModel}`),
            publicationWork: JSON.parse(`{$workModel}`)
        },
        computed: {
            citationsJsonComputed: function() {
                return JSON.stringify(this.citations);
            },
            publicationWorkJsonComputed: function() {
                return JSON.stringify(this.publicationWork);
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
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.deposit.question"}';
        if (confirm(questionText) !== true) { return; }

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
                optimetaCitationsApp.publicationWork = JSON.parse(JSON.stringify(response['message']));
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

    <!-- custom scripts -->
    {$customScript}
    <!-- custom scripts -->
</script>

{if $citationsEnabled}

    <div class="section" id="optimetaCitations" style="clear:both;">

        <div class="header">
            <table>
                <tr>
                    <td colspan="2">
                        <span class="label">{translate key="plugins.generic.optimetaCitationsPlugin.process.label"}</span> <br/>
                        <span class="description">{translate key="plugins.generic.optimetaCitationsPlugin.process.description"}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="optimetaButton optimetaButtonGrey">Wikidata</span>
                        <a class="optimetaButton optimetaButtonGreen"
                           v-if="publicationWork.opencitations_url"
                           :href="publicationWork.opencitations_url"
                           target="_blank"><span>OpenCitations</span></a>
                        <span class="optimetaButton optimetaButtonGrey"
                              v-if="!publicationWork.opencitations_url">OpenCitations</span>
                    </td>
                    <td class="optimetaAlignRight">
                        {* <a href="javascript:optimetaDepositCitations()" id="buttonDeposit" class="pkpButton"
                           :class="(optimetaCitationsIsParsed)?'':'optimetaDisabled'">{translate key="plugins.generic.optimetaCitationsPlugin.deposit.button"}</a> *}
                        <a href="javascript:optimetaClearCitations()" id="buttonClear" class="pkpButton"
                           :class="(optimetaCitationsIsParsed)?'':'optimetaDisabled'">{translate key="plugins.generic.optimetaCitationsPlugin.clear.button"}</a>
                        <a href="javascript:optimetaProcessCitations()" id="buttonProcess" class="pkpButton">{translate key="plugins.generic.optimetaCitationsPlugin.process.button"}</a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="optimetaScrollableDiv">

            <div id="optimetaScrollableDivLoading" class="optimetaScrollableDivLoading optimetaHide">
                <img src="{$pluginImagesURL}/loading-transparent.gif"/>
            </div>

            <div id="optimetaScrollableDivEmpty" class="optimetaScrollableDivEmpty" v-show="!optimetaCitationsIsParsed">
                {translate key="plugins.generic.optimetaCitationsPlugin.citations.empty.description"}
            </div>

            <div id="optimetaScrollableDivValue" class="optimetaScrollableDivValue">
                <table v-show="optimetaCitationsIsParsed">
                    <colgroup>
                        <col class="grid-column column-nr" style="width: 2%;">
                        <col class="grid-column column-parts" style="">
                        <col class="grid-column column-action" style="width: 6%;">
                    </colgroup>
                    <tbody>
                    <tr v-for="(row, i) in helper" class="optimetaRow">
                        <td class="optimetaScrollableDiv-nr">{{ i + 1 }}</td>
                        <td class="optimetaScrollableDiv-parts">
                            <div>
                                <span v-show="!row.editRow">
                                    <a :href="citations[i].doi"
                                       target="_blank">{{ citations[i].doi }}</a></span>
                                <input id="doi-{{ i + 1 }}" placeholder="DOI" v-show="row.editRow"
                                       v-model="citations[i].doi"
                                       class="optimetaInput" />

                                <span v-show="!row.editRow">
                                    <a :href="citations[i].urn"
                                       target="_blank">{{ citations[i].urn }}</a></span>
                                <input id="urn-{{ i + 1 }}" placeholder="URN" v-show="row.editRow"
                                       v-model="citations[i].urn"
                                       class="optimetaInput" />

                                <span v-show="!row.editRow">
                                    <a :href="citations[i].url"
                                       target="_blank">{{ citations[i].url }}</a></span>
                                <input id="url-{{ i + 1 }}" placeholder="URL" v-show="row.editRow"
                                       v-model="citations[i].url"
                                       class="optimetaInput" />
                            </div>

                            <div>

                                <div>
                                    <span v-for="(author, j) in citations[i].authors">
                                        <span v-show="!row.editRow" class="optimetaTag">{{ citations[i].authors[j].name }}</span>
                                        <input id="display_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Author" v-show="row.editRow"
                                               v-model="citations[i].authors[j].name"
                                               class="optimetaInput" />
                                        <input id="orcid-{{ i + 1 }}-{{ j + 1 }}" placeholder="Orcid" v-show="row.editRow"
                                               v-model="citations[i].authors[j].orcid"
                                               class="optimetaInput" />
                                        <a class="optimetaButton optimetaButtonGreen"
                                           v-if="citations[i].authors[j].orcid"
                                           :href="citations[i].authors[j].orcid"
                                           target="_blank">iD</a>
                                    <a class="pkpButton" v-show="row.editRow"
                                       v-on:click="removeAuthor(i, j)">
                                        <i class="fa fa-trash" aria-hidden="true"></i> </a>
                                        <br v-show="row.editRow"/>
                                    </span>
                                    <a class="pkpButton" v-show="row.editRow"
                                       v-on:click="addAuthor(i)">{translate key="plugins.generic.optimetaCitationsPlugin.author.add.button"}</a>
                                </div>

                                <div>
                                    <span v-show="!row.editRow && !citations[i].isProcessed"
                                          class="optimetaTag">No information found</span>

                                    <span v-show="!row.editRow && citations[i].title"
                                          class="optimetaTag">{{ citations[i].title }}</span>
                                    <input id="title-{{ i + 1 }}" placeholder="Title" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].title" />

                                    <span v-show="!row.editRow && citations[i].venue_name"
                                          class="optimetaTag">{{ citations[i].venue_name }}</span>
                                    <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].venue_name" />

                                    <span v-show="!row.editRow && citations[i].publication_year"
                                          class="optimetaTag">{{ citations[i].publication_year }}</span>
                                    <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].publication_year" />

                                    <span v-show="!row.editRow && citations[i].volume"
                                          class="optimetaTag">Volume {{ citations[i].volume }}</span>
                                    <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].volume" />

                                    <span v-show="!row.editRow && citations[i].issue"
                                          class="optimetaTag">Issue {{ citations[i].issue }}</span>
                                    <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].issue" />

                                    <span v-show="!row.editRow && citations[i].first_page"
                                          class="optimetaTag">Pages {{ citations[i].first_page }} - {{ citations[i].last_page }}</span>
                                    <input id="first_page-{{ i + 1 }}" placeholder="First page" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].first_page" />
                                    <input id="last_page-{{ i + 1 }}" placeholder="Last page" v-show="row.editRow" class="optimetaInput"
                                           v-model="citations[i].last_page" />
                                </div>

                            </div>

                            <div class="optimetaRawText">{{ citations[i].raw }}</div>

                            <div>
                                <a class="optimetaButton optimetaButtonGreen"
                                   v-if="citations[i].wikidata_qid"
                                   :href="'https://www.wikidata.org/wiki/' + citations[i].wikidata_qid"
                                   target="_blank"><span>Wikidata</span></a>
                                <span class="optimetaButton optimetaButtonGrey"
                                      v-if="!citations[i].wikidata_qid">Wikidata</span>
                                <a class="optimetaButton optimetaButton optimetaButtonGreen"
                                   v-if="citations[i].openalex_id"
                                   :href="'https://openalex.org/' + citations[i].openalex_id"
                                   target="_blank"><span>OpenAlex</span></a>
                                <span class="optimetaButton optimetaButtonGrey"
                                      v-if="!citations[i].openalex_id">OpenAlex</span>
                            </div>
                        </td>
                        <td class="optimetaScrollableDiv-actions">
                            <a v-show="!row.editRow" v-on:click="startEdit(i)"
                               class="pkpButton"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a v-show="row.editRow" v-on:click="endEdit(i)"
                               class="pkpButton"><i class="fa fa-check" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <textarea name="{$smarty.const.OPTIMETA_CITATIONS_FORM_FIELD_PARSED}" style="display: none;">{{ citationsJsonComputed }}</textarea>
            <textarea name="{$smarty.const.OPTIMETA_CITATIONS_PUBLICATION_WORK}" style="display: none;">{{ publicationWorkJsonComputed }}</textarea>
        </div>

    </div>
{/if}
