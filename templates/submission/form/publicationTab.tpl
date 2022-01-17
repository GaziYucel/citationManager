<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<script>
    var optimetaCitationsJson = `{$citationsParsed}`;
    var optimetaCitations = JSON.parse(optimetaCitationsJson);

	var optimetaCitationsApp = new pkp.Vue({
		//el: '#optimetaCitations',
		data: {
			citations: optimetaCitations,
			helper: getHelperArray(optimetaCitations)
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

        let citationsRawTextArea = document.getElementById("citations-citationsRaw-control").value;

        $.ajax({
            url: '{$pluginApiParseUrl}',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: citationsRawTextArea
            },
            headers: {
                'X-Csrf-Token': pkp.currentUser.csrfToken,
            },
            error(r) { },
            success(response) {
                optimetaCitations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.citations = JSON.parse(response['citationsParsed']);
                optimetaCitationsApp.helper = getHelperArray(JSON.parse(response['citationsParsed']));
            }
        });
    }

	function getHelperArray(baseArray){
		let helperArray = JSON.parse(JSON.stringify(baseArray));
		for(let i = 0;i < baseArray.length; i++){
			helperArray[i].editRow = false;
		}
		return helperArray;
	}

	function IsStringJson(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}

</script>

<tab v-if="supportsReferences" id="optimetaCitations"
     label="{translate key="plugins.generic.optimetaCitationsPlugin.publication.label"}">

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td><h4>Citations</h4></td>
                <td><a href="javascript:parseCitations()" id="buttonParse"
                       class="pkpButton">Parse References</a></td>
            </tr>
        </table>
    </div>

    <div class="optimetaScrollableDiv">
        <table style="width: 100%;">
            <colgroup>
                <col class="grid-column column-nr" style="width: 2%;">
                <col class="grid-column column-raw" style="">
                <col class="grid-column column-pid" style="width: 20%;">
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
                        <textarea v-show="row.editRow"
                                  v-model="optimetaCitationsApp.citations[i].raw"
                                  class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"
                                  style="height: 100px;"></textarea>
                        <span v-show="!row.editRow">{{ optimetaCitationsApp.citations[i].raw }}</span>
                    </td>
                    <td>
                        <input v-show="row.editRow"
                               v-model="optimetaCitationsApp.citations[i].pid"
                               class="pkpFormField__input pkpFormField--text__input" />
                        <span v-show="!row.editRow">{{ optimetaCitationsApp.citations[i].pid }}</span>
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
        <span style="display: none;">{{ components.optimetaCitations_PublicationForm.fields[0]['value'] = optimetaCitationsApp.citationsJsonComputed }}</span>
        <pkp-form v-bind="components.{$smarty.const.OPTIMETA_CITATIONS_PUBLICATION_FORM}" @set="set"/>
    </div>

</tab>
