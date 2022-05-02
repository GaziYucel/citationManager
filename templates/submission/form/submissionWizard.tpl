<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<script>
    var optimetaCitationsJson = `{$citationsParsed}`;
    var optimetaCitations = JSON.parse(optimetaCitationsJson);

    var optimetaCitationsApp = new pkp.Vue({
        el: '#optimetaCitations',
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
                citationsRaw: document.getElementsByName('citationsRaw')[0]['value'],
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
                citationsRaw: document.getElementsByName('citationsRaw')[0]['value']
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) { },
            success(response) {

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
                citationsRaw: document.getElementsByName('citationsRaw')[0]['value']
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

<div class="section" id="optimetaCitations" style="clear:both;">

    <div class="header">
        <table>
            <tr>
                <td><span class="label">Citations</span></td>
                <td><a href="javascript:parseCitations()" id="buttonParse"
                       class="pkpButton">Parse References</a>
                    <a href="javascript:enrichCitations()" id="buttonEnrich"
                       class="pkpButton">Enrich References</a>
                    <a href="javascript:submitCitations()" id="buttonSubmit"
                       class="pkpButton">Submit References</a></td>
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
            <tr v-for="(row, i) in helper">
                <td>{{ i + 1 }}</td>
                <td style="">
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

                    <div v-show="!citations[i].isProcessed">
                        <span class="optimetaTag">No information found</span>
                    </div>

                    <div v-show="citations[i].isProcessed">
                        <span class="optimetaTag" v-for="(author, j) in citations[i].authors">{{ citations[i].authors[j].display_name }}</span>

                        <span v-show="!row.editRow" class="optimetaTag">{{ citations[i].title }}</span>
                        <input id="title-{{ i + 1 }}" placeholder="Title" v-show="row.editRow"
                               v-model="citations[i].title"
                               class="optimetaInput" />

                        <span v-show="!row.editRow" class="optimetaTag">{{ citations[i].venue_display_name }}</span>
                        <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="row.editRow"
                               v-model="citations[i].venue_display_name"
                               class="optimetaInput" />

                        <span v-show="!row.editRow" class="optimetaTag">{{ citations[i].publication_year }}</span>
                        <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="row.editRow"
                               v-model="citations[i].publication_year"
                               class="optimetaInput" />

                        <span v-show="!row.editRow" class="optimetaTag">Volume {{ citations[i].volume }}</span>
                        <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="row.editRow"
                               v-model="citations[i].volume"
                               class="optimetaInput" />

                        <span v-show="!row.editRow" class="optimetaTag">Issue {{ citations[i].issue }}</span>
                        <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="row.editRow"
                               v-model="citations[i].issue"
                               class="optimetaInput" />

                        <span v-show="!row.editRow" class="optimetaTag">Pages {{ citations[i].first_page }} - {{ citations[i].last_page }}</span>
                        <input id="first_page-{{ i + 1 }}" placeholder="First page" v-show="row.editRow"
                               v-model="citations[i].first_page"
                               class="optimetaInput" /> -
                        <input id="last_page-{{ i + 1 }}" placeholder="Last page" v-show="row.editRow"
                               v-model="citations[i].last_page"
                               class="optimetaInput" />

                        Processed <input v-show="!row.editRow" type="checkbox" v-model="citations[i].isProcessed" disabled/>
                        <input v-show="row.editRow" type="checkbox" v-model="citations[i].isProcessed"/>
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
        <textarea name="{$citationsKeyForm}" style="display: none;">{{ citationsJsonComputed }}</textarea>
    </div>

</div>
