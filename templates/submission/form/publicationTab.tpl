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

    function parseCitations(){
        let questionText = '{translate key="plugins.generic.optimetaCitationsPlugin.parse.question"}';
        if (confirm(questionText) !== true) { return; }

        $.ajax({
            url: '{$pluginApiParseUrl}',
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
                       class="pkpButton">Parse References</a></td>
            </tr>
        </table>
    </div>

    <div class="optimetaScrollableDiv">
        <table>
            <colgroup>
                <col class="grid-column column-nr" style="width: 2%;">
                <col class="grid-column column-rawRemainder" style="">
                <col class="grid-column column-doi" style="width: 20%;">
                <col class="grid-column column-action" style="width: 6%;">
            </colgroup>
            <thead>
                <tr>
                    <th> # </th>
                    <th> raw </th>
                    <th> pid </th>
                    <th> action </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, i) in optimetaCitationsApp.helper">
                    <td>{{ i + 1 }}</td>
                    <td style="">
                        <textarea id="rawRemainder-{{ i + 1 }}"
                                  placeholder="Remainder of Citation"
                                  v-show="row.editRow"
                                  v-model="optimetaCitationsApp.citations[i].rawRemainder"
                                  class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"
                                  style="height: 100px;"></textarea>
                        <span v-show="!row.editRow">{{ optimetaCitationsApp.citations[i].rawRemainder }}</span>
                    </td>
                    <td>
                        <input id="urn-{{ i + 1 }}"
                               placeholder="URN"
                               v-show="row.editRow"
                               v-model="optimetaCitationsApp.citations[i].urn"
                               class="pkpFormField__input pkpFormField--text__input" />
                        <span v-show="!row.editRow">{{ optimetaCitationsApp.citations[i].urn }}</span>
                        <input id="doi-{{ i + 1 }}"
                               placeholder="DOI"
                               v-show="row.editRow"
                               v-model="optimetaCitationsApp.citations[i].doi"
                               class="pkpFormField__input pkpFormField--text__input" />
                        <span v-show="!row.editRow">{{ optimetaCitationsApp.citations[i].doi }}</span>
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
        <pkp-form v-bind="components.{$smarty.const.OptimetaCitations_PublicationForm}" @set="set"/>
    </div>

</tab>
