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
				citationsRaw: document.getElementById("citations-citationsRaw-control").value
			},
			headers: {
				'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
			},
			error(r) { },
			success(response) {

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
				citationsRaw: document.getElementById("citations-citationsRaw-control").value
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
                <td><a href="javascript:enrichCitations()" id="buttonEnrich"
					   class="pkpButton">Enrich References</a>
					<a href="javascript:submitCitations()" id="buttonSubmit"
					   class="pkpButton">Submit References</a>
					<a href="javascript:parseCitations()" id="buttonParse"
					   class="pkpButton">Parse References</a></td>
            </tr>
        </table>
    </div>

	<div class="optimetaScrollableDiv">
        <table>
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
			<tr v-for="(row, i) in helper">
				<td>{{ i + 1 }}</td>
				<td style="">
					<textarea id="rawRemainder-{{ i + 1 }}"
							  placeholder="Remainder of Citation"
							  v-show="row.editRow"
							  v-model="citations[i].rawRemainder"
							  class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"
							  style="height: 100px;"></textarea>
					<span v-show="!row.editRow">{{ citations[i].rawRemainder }}</span>
				</td>
				<td>
					<input id="url-{{ i + 1 }}"
						   placeholder="URL"
						   v-show="row.editRow"
						   v-model="citations[i].url"
						   class="pkpFormField__input pkpFormField--text__input" />
					<span v-show="!row.editRow"><a :href="citations[i].url" target="_blank">{{ citations[i].url }}</a></span>
					<input id="doi-{{ i + 1 }}"
						   placeholder="DOI"
						   v-show="row.editRow"
						   v-model="citations[i].doi"
						   class="pkpFormField__input pkpFormField--text__input" />
					<span v-show="!row.editRow"><a :href="citations[i].doi" target="_blank">{{ citations[i].doi }}</a></span>
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
