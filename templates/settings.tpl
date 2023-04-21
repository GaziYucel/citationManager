<script>
    $(function () {ldelim}
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

    {fbvFormArea id="optimetaCitationsSettingsArea"}

    {fbvFormSection title="plugins.generic.optimetaCitations.settings.description"}{/fbvFormSection}
        <!-- OpenCitations -->
    {fbvFormSection label="plugins.generic.optimetaCitations.settings.open_citations_title"}
        <p>
            {fbvElement
            type="text"
            id="{$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER}"
            value=${$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER}
            label="plugins.generic.optimetaCitations.settings.open_citations_owner"
            description="plugins.generic.optimetaCitations.settings.open_citations_owner"
            placeholder="plugins.generic.optimetaCitations.settings.open_citations_owner"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY}"
            value=${$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY}
            label="plugins.generic.optimetaCitations.settings.open_citations_repository"
            description="plugins.generic.optimetaCitations.settings.open_citations_repository"
            placeholder="plugins.generic.optimetaCitations.settings.open_citations_repository"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN}"
            value=${$smarty.const.OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN}
            label="plugins.generic.optimetaCitations.settings.open_citations_token"
            description="plugins.generic.optimetaCitations.settings.open_citations_token"
            placeholder="plugins.generic.optimetaCitations.settings.open_citations_token"
            }
        </p>
    {/fbvFormSection}
    <!-- OpenCitations -->

    <!-- Wikidata -->
    {fbvFormSection label="plugins.generic.optimetaCitations.settings.wikidata_title"}
        <p>
            {fbvElement
            type="text"
            id="{$smarty.const.OPTIMETA_CITATIONS_WIKIDATA_USERNAME}"
            value=${$smarty.const.OPTIMETA_CITATIONS_WIKIDATA_USERNAME}
            label="plugins.generic.optimetaCitations.settings.wikidata_username"
            description="plugins.generic.optimetaCitations.settings.wikidata_username"
            placeholder="plugins.generic.optimetaCitations.settings.wikidata_username"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{$smarty.const.OPTIMETA_CITATIONS_WIKIDATA_PASSWORD}"
            value=${$smarty.const.OPTIMETA_CITATIONS_WIKIDATA_PASSWORD}
            label="plugins.generic.optimetaCitations.settings.wikidata_password"
            description="plugins.generic.optimetaCitations.settings.wikidata_password"
            placeholder="plugins.generic.optimetaCitations.settings.wikidata_password"
            }
        </p>
    {/fbvFormSection}
    <!-- Wikidata -->

    {/fbvFormArea}

    {fbvFormButtons submitText="common.save"}
</form>
