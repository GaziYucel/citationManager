<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<script>
    var optimetaCitationsJson = `{$citationsParsed}`;
    var optimetaCitations = JSON.parse(optimetaCitationsJson);

    var optimetaCitationsApp = new pkp.Vue({
        //el: '#optimetaCitations',
        data: {
            citations: optimetaCitations,
            helper: optimetaCitationsGetHelperArray(optimetaCitations)
        },
        computed: {
            citationsJsonComputed: function() {
                return JSON.stringify(this.citations);
            }
        }
    });

    function enrichCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.enrich.question"}';
        if (confirm(questionText) !== true) { return; }

        $.ajax({
            url: '{$pluginApiUrl}/enrich',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: document.getElementById("citations-citationsRaw-control").value,
                citationsParsed: JSON.stringify(optimetaCitationsApp.citations)
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {
                optimetaCitations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.citations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.helper = optimetaCitationsGetHelperArray(JSON.parse(response['citationsParsed']));
            }
        });
    }

    function submitCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.enrich.question"}';
        if (confirm(questionText) !== true) { return; }

        $.ajax({
            url: '{$pluginApiUrl}/submit',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: document.getElementById("citations-citationsRaw-control").value,
                citationsParsed: JSON.stringify(optimetaCitationsApp.citations)
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {
                optimetaCitations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.citations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.helper = optimetaCitationsGetHelperArray(JSON.parse(response['citationsParsed']));
            }
        });
    }

    function parseCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.parse.question"}';
        if (confirm(questionText) !== true) { return; }

        $.ajax({
            url: '{$pluginApiUrl}/parse',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: document.getElementById("citations-citationsRaw-control").value
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {
                optimetaCitations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.citations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.helper = optimetaCitationsGetHelperArray(JSON.parse(response['citationsParsed']));
            }
        });
    }

</script>

<tab v-if="supportsReferences" id="optimetaCitations"
     label="{translate key="plugins.generic.optimetaCitationsPlugin.publication.label"}">

    <div class="header">
        <table>
            <tr>
                <td><h4>Citations</h4></td>
                <td><a href="javascript:parseCitations()" id="buttonParse"
                       class="pkpButton">Parse</a>
                    <a href="javascript:enrichCitations()" id="buttonEnrich"
                       class="pkpButton">Enrich</a>
                    <a href="javascript:submitCitations()" id="buttonSubmit"
                       class="pkpButton">Submit</a></td>
            </tr>
        </table>
    </div>

    <div class="optimetaScrollableDiv">
        <table>
            <colgroup>
                <col class="grid-column column-nr" style="width: 2%;">
                <col class="grid-column column-parts" style="">
                <col class="grid-column column-action" style="width: 6%;">
            </colgroup>
            <thead>
                <tr>
                    <th> # </th>
                    <th> </th>
                    <th> action </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, i) in optimetaCitationsApp.helper" class="optimetaRow">
                    <td>{{ i + 1 }}</td>
                    <td style="">
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
                                    <span v-show="!row.editRow" class="optimetaTag">{{ optimetaCitationsApp.citations[i].authors[j].display_name }}</span>
                                    <input id="display_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Author" v-show="row.editRow"
                                           v-model="optimetaCitationsApp.citations[i].authors[j].display_name"
                                           class="optimetaInput" />
                                </span>
                            </div>
                            <div>
                                <span v-show="!row.editRow" class="optimetaTag">{{ optimetaCitationsApp.citations[i].title }}</span>
                                <input id="title-{{ i + 1 }}" placeholder="Title" v-show="row.editRow" class="optimetaInput"
                                       v-model="optimetaCitationsApp.citations[i].title" />

                                <span v-show="!row.editRow" class="optimetaTag">{{ optimetaCitationsApp.citations[i].venue_display_name }}</span>
                                <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="row.editRow" class="optimetaInput"
                                       v-model="optimetaCitationsApp.citations[i].venue_display_name" />

                                <span v-show="!row.editRow" class="optimetaTag">{{ optimetaCitationsApp.citations[i].publication_year }}</span>
                                <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="row.editRow" class="optimetaInput"
                                       v-model="optimetaCitationsApp.citations[i].publication_year" />

                                <span v-show="!row.editRow" class="optimetaTag">Volume {{ optimetaCitationsApp.citations[i].volume }}</span>
                                <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="row.editRow" class="optimetaInput"
                                       v-model="optimetaCitationsApp.citations[i].volume" />

                                <span v-show="!row.editRow" class="optimetaTag">Issue {{ optimetaCitationsApp.citations[i].issue }}</span>
                                <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="row.editRow" class="optimetaInput"
                                       v-model="optimetaCitationsApp.citations[i].issue" />

                                <span v-show="!row.editRow" class="optimetaTag">Pages {{ optimetaCitationsApp.citations[i].first_page }} - {{ optimetaCitationsApp.citations[i].last_page }}</span>
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
                    <td>
                        <a v-show="!row.editRow"
                           v-on:click="row.editRow = !row.editRow"
                           class="pkpButton" label="Edit"> Edit </a>
                        <a v-show="row.editRow"
                           v-on:click="row.editRow = !row.editRow"
                           class="pkpButton" label="Close"> Close </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <span style="display: none;">{{ components.OptimetaCitations_PublicationForm.fields[0]['value'] = optimetaCitationsApp.citationsJsonComputed }}</span>
        <pkp-form v-bind="components.{$smarty.const.OPTIMETA_CITATIONS_PUBLICATION_FORM}" @set="set"/>
    </div>

</tab>
