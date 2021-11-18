
<script src="{$pluginJavaScriptURL}/submissionEditForm.js"></script>

<tab id="optimetaCitationsPlugin_citations" label="{translate key="plugins.generic.optimetaCitationsPlugin.submissionEditFormLabel"}">
    <div class="">
        <!-- <h1>submissionEditForm.tpl</h1> -->
        <!-- <h2>{$submissionEditFormTitle}</h2> -->
        <div class="header">
            <h4>Citations</h4>
        </div>
        <div>
			<style type="text/css">
				#iframe {
					margin: 0;
					padding: 0;
					width: 100%;
					height: 100%;
					border: 0;
				}
			</style>
			<div style="text-align: right; border-bottom: 1px solid #ddd; width: 100%;">
				<img src="{$pluginImagesURL}/optimeta-citations-embed_edit-status.png">
			</div>
			<iframe id="iframe" src="https://ojs330.yucel.nl/plugins/generic/optimetaCitations/demo/data.html"
			title="citations"
			style="width: 100%; height: 500px; border: 0; padding: 0; margin: 0;"></iframe>

        </div>
        <div aria-live="polite" class="pkpFormPage__footer">
            <span role="status" aria-live="polite" aria-atomic="true"></span>
            <div class="pkpFormPage__buttons">
                <button class="pkpButton" label="Save"> Save </button>
            </div>
        </div>
    </div>

</tab>
