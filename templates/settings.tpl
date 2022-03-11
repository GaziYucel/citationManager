<h1>settings.tpl</h1>

<script>
    $(function() {ldelim}
        $('#pluginTemplateSettings').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
        {rdelim});
</script>

<form
        class="pkp_form"
        id="pluginTemplateSettings"
        method="POST"
        action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}"
>
    <!-- Always add the csrf token to secure your form -->
    {csrf}

    {fbvFormArea}
    {fbvFormSection label="plugins.generic.optimetaCitationsPlugin.publicationStatement"}
    {fbvElement
    type="text"
    id="publicationStatement"
    value=$publicationStatement
    description="plugins.generic.optimetaCitationsPlugin.publicationStatement.description"
    }
    {/fbvFormSection}
    {/fbvFormArea}
    {fbvFormButtons submitText="common.save"}
</form>
