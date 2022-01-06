<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css" />
<script>
	var optimetaCitationsJson = `{$parsedCitations}`;
	var optimetaCitations = JSON.parse(optimetaCitationsJson);
	var optimetaCitationsHelper = JSON.parse(optimetaCitationsJson);

	for(let i = 0;i < optimetaCitationsHelper.length; i++){
		optimetaCitationsHelper[i].editRow = false;
	}

	var optimetaCitationsApp = new pkp.Vue({
		el: '#optimetaCitations',
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

	var divCont = document.querySelector('#divCont');
	var buttonParse = document.querySelector('#buttonParse');

	function showContainer(){
		divCont.style.display = 'block';
	}

	$.ajax({
		method: "GET",
		contentType: "application/json",
		url: "https://ojs330.yucel.nl/index.php/ojs/api/v1/submissions/22/publications/22",
		data: ''
	})
		.done(function(response) {
			// Here I need to call my function inside the plugin code
		});

	function getData(){
		divCont.style.display = 'block';

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("debugTextarea").innerHTML = this.responseText;
			}
		};
		xhttp.open("GET", "https://ojs330.yucel.nl/index.php/ojs/api/v1/submissions/22/publications/22", true);
		xhttp.send();
	}
</script>

<div class="section" id="optimetaCitations" style="clear:both;">

    <span class="label">Citations</span>

	<div>
		<a href="javascript:showContainer();" id="buttonParse" class="pkp_button">Parse References</a>
		<a href="javascript:getData();" id="buttonParse" class="pkp_button">Parse References</a>
{*        {capture assign=staticPageGridUrl}{url router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.staticPages.controllers.grid.StaticPageGridHandler" op="fetchGrid" escape=false}{/capture}*}
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
				<td>
                    {{ i + 1 }}
				</td>
				<td style="">
						<textarea v-show="row.editRow"
								  v-model="citations[i].raw"
								  class="pkpFormField__input pkpFormField--textarea__input optimetaTextArea"
								  style="height: 100px;"></textarea>
					<span v-show="!row.editRow">{{ citations[i].raw }}</span>
				</td>
				<td>
					<input v-show="row.editRow"
						   v-model="citations[i].pid"
						   class="pkpFormField__input pkpFormField--text__input" />
					<span v-show="!row.editRow">{{ citations[i].pid }}</span>
				</td>
				<td>
					<button v-show="!row.editRow"
							v-on:click="row.editRow = !row.editRow"
							class="pkpButton" label="Edit"> Edit </button>
					<button v-show="row.editRow"
							v-on:click="row.editRow = !row.editRow"
							class="pkpButton" label="Close"> Close </button>
				</td>
			</tr>
			</tbody>
		</table>

	</div>

	<div>
		<textarea name="{$citationsKeyForm}" style="display: none;">{{ citationsJsonComputed }}</textarea>
	</div>

	<div id="divCont" style="display: block;">
		citationsJsonComputed
		<textarea>{{ citationsJsonComputed }}</textarea>
		parsedCitations
		<textarea>{$parsedCitations}</textarea>
		citationsRaw
		<textarea>{$citationsRaw}</textarea>
		xhttp
		<textarea id="debugTextarea"></textarea>
	</div>

</div>
