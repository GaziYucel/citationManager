
<div class="section" id="optimetaCitationsPlugin_submissionWizard" style="clear:both;">

    <span class="label">Citations</span>

	<div>
		<style type="text/css">
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

	<!--
    <div>
        <img src="{$pluginImagesURL}/optimeta-citations-embed_wizard.png" style="width: 100%;">
    </div>
	-->

    <!-- <h2>{$submissionWizardFormTitle}</h2> -->
    <!-- <h2>citations > {$citations}</h2> -->
    <!--
    <div>
        <p>If you click Schedule For Publication, the selected citations will be published to the supported Websites</p>
        <p>Green badge: submitted / Orange: processing / White: queued</p>
    </div>
    <div>
        <ol style="padding-left: 0px;">
            <li>
                <input type="text" id="pid-i-1" name="pid-i-1" class="field text" size="30" style="margin-bottom: 2px;" value="https://doi.org/10.1371/journal">
                <textarea type="text" id="pid-t-1" name="pid-t-1" class="" rows="3" cols="80" style="height: 5em; margin-bottom: 2px;">Hutchins, B. I., Baker, K. L., Davis, M. T., Diwersy, M. A., Haque, E., Harriman, R. M., … Santangelo, G. M. (2019). The NIH Open Citation Collection: A public access, broad coverage resource. PLOS Biology, 17(10), e3000385. https://doi.org/10.1371/journal. pbio.3000385</textarea>
            </li>
            <li>
                <input type="text" id="pid-i-2" name="pid-i-2" class="field text" size="30" style="margin-bottom: 2px;" value="https://doi.org/10.6084/m9.figshare.7127816">
                <textarea id="pid-t-2" name="pid-t-2" class="" rows="3" cols="80" style="height: 5em; margin-bottom: 2px;">Peroni, S., & Shotton, D. (2019). Open Citation Identifier: Definition. Figshare. https://doi.org/10.6084/m9.figshare.7127816</textarea>
            </li>
            <li>
                <input type="text" id="pid-i-3" name="pid-i-3" class="field text" size="30" style="margin-bottom: 2px;" value="https://doi.org/10.6084/m9.figshare.6683855">
                <textarea id="pid-t-3" name="pid-t-3" class="" rows="3" cols="80" style="height: 5em; margin-bottom: 2px;">Peroni, S., & Shotton, D. (2018a). Open Citation: Definition. Figshare. https://doi.org/10.6084/m9.figshare.6683855</textarea>
            </li>
            <li>
                <input type="text" id="pid-i-3" name="pid-i-3" class="field text" size="30" style="margin-bottom: 2px;" value="https://doi.org/10.1007/s11192-009-0146-3">
                <textarea id="pid-t-3" name="pid-t-3" class="" rows="3" cols="80" style="height: 5em; margin-bottom: 2px;">Van Eck, N. J., & Waltman, L. (2010). Software survey: VOSviewer, a computer program for bibliometric mapping. Scientometrics, 84(2), 523–538. https://doi.org/10.1007/s11192-009-0146-3</textarea>
            </li>
            <li>
                <input type="text" id="pid-i-new" name="pid-i-new" class="field text" size="30" style="margin-bottom: 2px;" value="">
                <textarea id="pid-t-new" name="pid-t-new" class="" rows="3" cols="80" style="height: 5em; margin-bottom: 2px;"></textarea>
            </li>
            <li>
                <button class="pkp_button submitFormButton" type="submit" name="submitFormButton" id="submitFormButton-614b0f19797">Add new line</button>
            </li>
        </ol>
    </div>
    -->
</div>
