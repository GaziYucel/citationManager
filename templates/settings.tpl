<script>
    $(function () {ldelim}
        $('#{$smarty.const.OPTIMETA_CITATIONS_PLUGIN_NAME}Settings').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
        {rdelim});
</script>

<form
        class="pkp_form"
        id="{$smarty.const.OPTIMETA_CITATIONS_PLUGIN_NAME}Settings"
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
            id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER}"
            value=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_OWNER}
            label="plugins.generic.optimetaCitations.settings.open_citations_owner"
            description="plugins.generic.optimetaCitations.settings.open_citations_owner"
            placeholder="plugins.generic.optimetaCitations.settings.open_citations_owner"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY}"
            value=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_REPOSITORY}
            label="plugins.generic.optimetaCitations.settings.open_citations_repository"
            description="plugins.generic.optimetaCitations.settings.open_citations_repository"
            placeholder="plugins.generic.optimetaCitations.settings.open_citations_repository"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN}"
            value=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_OPEN_CITATIONS_TOKEN}
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
            id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_USERNAME}"
            value=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_USERNAME}
            label="plugins.generic.optimetaCitations.settings.wikidata_username"
            description="plugins.generic.optimetaCitations.settings.wikidata_username"
            placeholder="plugins.generic.optimetaCitations.settings.wikidata_username"
            }
        </p>
        <p>
            {fbvElement
            type="text"
            id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_PASSWORD}"
            value=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_WIKIDATA_PASSWORD}
            label="plugins.generic.optimetaCitations.settings.wikidata_password"
            description="plugins.generic.optimetaCitations.settings.wikidata_password"
            placeholder="plugins.generic.optimetaCitations.settings.wikidata_password"
            }
        </p>
    {/fbvFormSection}
    <!-- Wikidata -->

    <!-- Show at Front -->
    {fbvFormSection title="plugins.generic.optimetaCitations.settings.show_structured_frontend.title" list="true"}
    {fbvElement
    type="checkbox"
    name="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED}"
    id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED}"
    value={OptimetaCitationsPlugin::OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED}
    label="plugins.generic.optimetaCitations.settings.show_structured_frontend.checkbox"
    checked=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_FRONTEND_SHOW_STRUCTURED}
    }
    {/fbvFormSection}
    <!-- Show at Front -->

        <!-- Show at Front -->
    {fbvFormSection title="plugins.generic.optimetaCitations.settings.is_production_environment.title" list="true"}
    {fbvElement
    type="checkbox"
    name="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY}"
    id="{OptimetaCitationsPlugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY}"
    value={OptimetaCitationsPlugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY}
    label="plugins.generic.optimetaCitations.settings.is_production_environment.checkbox"
    checked=${OptimetaCitationsPlugin::OPTIMETA_CITATIONS_IS_PRODUCTION_KEY}
    }
    {/fbvFormSection}
        <!-- Show at Front -->

    {/fbvFormArea}

    {fbvFormButtons submitText="common.save"}
</form>
