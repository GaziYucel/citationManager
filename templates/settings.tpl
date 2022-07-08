<script>
    $(function() {ldelim}
        $('#optimetaCitationsSettings').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
        {rdelim});
</script>

<form
        class="pkp_form"
        id="optimetaCitationsSettings"
        method="POST"
        action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}"
>
    <!-- Always add the csrf token to secure your form -->
    {csrf}

    {fbvFormArea id="optimetaCitationsSettingsArea" title="plugins.generic.optimetaCitations.settings.description"}
        {fbvFormSection label="plugins.generic.optimetaCitations.settings.wikidata_title"}
            {fbvElement
                type="text"
                id="optimetaCitations_wikidata_username"
                value=$optimetaCitations_wikidata_username
                label="plugins.generic.optimetaCitations.settings.wikidata_username"
                description="plugins.generic.optimetaCitations.settings.wikidata_username"
                placeholder="plugins.generic.optimetaCitations.settings.wikidata_username"
            }
            <br/>
            {fbvElement
                type="text"
                id="optimetaCitations_wikidata_password"
                value=$optimetaCitations_wikidata_password
                label="plugins.generic.optimetaCitations.settings.wikidata_password"
                description="plugins.generic.optimetaCitations.settings.wikidata_password"
                placeholder="plugins.generic.optimetaCitations.settings.wikidata_password"
            }
            <br/>
            {fbvElement
                type="text"
                id="optimetaCitations_wikidata_api_url"
                value=$optimetaCitations_wikidata_api_url
                label="plugins.generic.optimetaCitations.settings.wikidata_api_url"
                description="plugins.generic.optimetaCitations.settings.wikidata_api_url"
                placeholder="plugins.generic.optimetaCitations.settings.wikidata_api_url"
            }
        {/fbvFormSection}

        {fbvFormSection label="plugins.generic.optimetaCitations.settings.open_citations_title"}

            {fbvElement
                type="text"
                id="optimetaCitations_open_citations_url"
                value=$optimetaCitations_open_citations_url
                label="plugins.generic.optimetaCitations.settings.open_citations_url"
                description="plugins.generic.optimetaCitations.settings.open_citations_url"
                placeholder="plugins.generic.optimetaCitations.settings.open_citations_url"
            }
            <br/>
            {fbvElement
                type="text"
                id="optimetaCitations_open_citations_token"
                value=$optimetaCitations_open_citations_token
                label="plugins.generic.optimetaCitations.settings.open_citations_token"
                description="plugins.generic.optimetaCitations.settings.open_citations_token"
                placeholder="plugins.generic.optimetaCitations.settings.open_citations_token"
            }
        {/fbvFormSection}

    {/fbvFormArea}
    {fbvFormButtons submitText="common.save"}
</form>
