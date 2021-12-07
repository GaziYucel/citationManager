
<script src="{$pluginJavaScriptURL}/submissionEditForm.js"></script>

<tab id="optimetaCitationsPlugin_citations" label="{translate key="plugins.generic.optimetaCitationsPlugin.submissionEditFormLabel"}">

    <div class="">

		<div class="header">
            <h4>Citations</h4>
        </div>

        <div id="optimetaCitationsApp" class="pkp_controllers_grid optimetaScrollableDiv">
				<table>
					<colgroup>
						<col class="grid-column column-nr" style="width: 2%;">
						<col class="grid-column column-raw" style="">
						<col class="grid-column column-pid" style="width: 20%;">
						<col class="grid-column column-action" style="width: 6%;">
					</colgroup>
					<thead>
						<tr>
							<th> &nbsp; </th>
							<th> raw </th>
							<th> pid </th>
							<th> action </th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(citation, i) in optimetaApp.citations">
							<td>
								{{ i + 1 }}
							</td>
							<td style="">
								<textarea v-show="citation.edit" v-model="citation.raw" class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"></textarea>
								<span v-show="!citation.edit">{{ citation.raw }}</span>
							</td>
							<td>
								<input v-show="citation.edit" v-model="citation.pid" class="pkpFormField__input pkpFormField--text__input" />
								<span v-show="!citation.edit">{{ citation.pid }}</span>
							</td>
							<td style="text-align: center;">
								<button v-show="!citation.edit" v-on:click="citation.edit = !citation.edit" class="pkpButton" label="Edit"> Edit </button>
								<button v-show="citation.edit" v-on:click="citation.edit = !citation.edit" class="pkpButton" label="Close"> Close </button>
							</td>
						</tr>
					</tbody>
				</table>
        </div>

        <div aria-live="polite" class="pkpFormPage__footer">
            <span role="status" aria-live="polite" aria-atomic="true"></span>
            <div class="pkpFormPage__buttons">
                <button class="pkpButton" label="Save"> Save </button>
            </div>
        </div>

    </div>

</tab>
