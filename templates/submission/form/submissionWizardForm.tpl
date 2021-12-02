
<div class="section" id="optimetaCitationsPlugin_submissionWizard" style="clear:both;">

    <span class="label">Citations</span>

	<div>

		<style>
			#divCont {
				display: none;
			}
			#iframe {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				border: 0;
			}
		</style>

		<div>
			<a href="javascript:showContainer();" id="buttonParse" class="pkp_button">Parse References</a>
		</div>

		<div id="divCont">
			<div style="text-align: right; border-bottom: 1px solid #ddd; width: 100%;">
				<img src="{$pluginImagesURL}/optimeta-citations-embed_wizard-status.png">
			</div>
			<iframe id="iframe" src="/plugins/generic/optimetaCitations/demo/data.html"
			title="citations"
			style="width: 100%; height: 500px; border: 0; padding: 0; margin: 0;"></iframe>
		</div>

		<script type="text/javascript">
			const divCont = document.querySelector('#divCont');
			const buttonParse = document.querySelector('#buttonParse');

			function showContainer(){
				divCont.style.display = 'block';
			}
		</script>

	</div>

</div>
