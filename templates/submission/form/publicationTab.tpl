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
		let questionText = 'The current parsed citations will be overwritten if you click OK. Are you sure?';
		if (confirm(questionText) !== true) { return; }

		let citationsRawTextArea = document.getElementById("citations-citationsRaw-control").value;
		let xhr = new XMLHttpRequest();
		let url = '/index.php/ojs/optimetaCitations/parse';
		let params = 'submissionId=19&citationsRaw=' + encodeURIComponent(citationsRawTextArea);
		xhr.open('POST', url);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			if(IsStringJson(xhr.responseText)){
				var responseArray = JSON.parse(xhr.responseText);
				optimetaCitations = JSON.parse(responseArray.content['citationsParsed']);

				optimetaCitationsApp.citations = optimetaCitations;
				optimetaCitationsApp.helper = getHelperArray(optimetaCitations);
			}
		};
		xhr.send(params);
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
     label="{translate key="plugins.generic.optimetaCitationsPlugin.submissionEditFormLabel"}">

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
        <span style="display: none;">{{ components.optimetaCitationsForm.fields[0]['value'] = optimetaCitationsApp.citationsJsonComputed }}</span>
        <pkp-form v-bind="components.{$smarty.const.FORM_PUBLICATION_OPTIMETA_CITATIONS}" @set="set"/>
    </div>

</tab>
