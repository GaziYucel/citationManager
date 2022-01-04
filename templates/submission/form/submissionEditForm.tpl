<script>
    var optimetaCitationsJson = `{$parsedCitations}`;
    var optimetaCitations = JSON.parse(optimetaCitationsJson);
	var optimetaCitationsHelper = JSON.parse(optimetaCitationsJson);

    for(let i = 0;i < optimetaCitationsHelper.length; i++){
		optimetaCitationsHelper[i].editRow = false;
    }

	var optimetaApp = new pkp.Vue({
		data: {
			citations: optimetaCitations,
			helper: optimetaCitationsHelper
		},
		methods: {
			parseCitationsRaw(){
				this.isParsingFinished = !this.isParsingFinished;
			}
		},
		computed: {
			citationsJsonComputed: function() {
				return JSON.stringify(this.citations);
			}
		}
	});

</script>
<script src="{$pluginJavaScriptURL}/submissionEditForm.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />

<tab v-if="supportsReferences" id="optimetaCitations" label="{translate key="plugins.generic.optimetaCitationsPlugin.submissionEditFormLabel"}">

	<div class="header">
		<h4>Citations</h4>
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
				<tr v-for="(helper, i) in optimetaApp.helper">
					<td>
						{{ i + 1 }}
					</td>
					<td style="">
						<textarea v-show="helper.editRow"
								  v-model="optimetaApp.citations[i].raw"
								  class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"
								  style="height: 100px;"></textarea>
						<span v-show="!helper.editRow">{{ optimetaApp.citations[i].raw }}</span>
					</td>
					<td>
						<input v-show="helper.editRow"
							   v-model="optimetaApp.citations[i].pid"
							   class="pkpFormField__input pkpFormField--text__input" />
						<span v-show="!helper.editRow">{{ optimetaApp.citations[i].pid }}</span>
					</td>
					<td>
						<button v-show="!helper.editRow"
								v-on:click="helper.editRow = !helper.editRow"
								class="pkpButton" label="Edit"> Edit </button>
						<button v-show="helper.editRow"
								v-on:click="helper.editRow = !helper.editRow"
								class="pkpButton" label="Close"> Close </button>
					</td>
				</tr>
			</tbody>
		</table>

	</div>

	<div>
		<span style="display: none;">{{ components.optimetaCitationsForm.fields[0]['value'] = optimetaApp.citationsJsonComputed }}</span>
		<pkp-form v-bind="components.{$smarty.const.FORM_PUBLICATION_OPTIMETA_CITATIONS}" @set="set"/>
	</div>

</tab>
