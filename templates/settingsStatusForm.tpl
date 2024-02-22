{**
 * templates/statusForm.tpl
 *
 * @copyright (c) 2021+ TIB Hannover
 * @copyright (c) 2021+ Gazi Yücel
 * @license Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Settings form for the citationManager plugin.
 *}

<script>
    $(function () {ldelim}
        $('#{$smarty.const.CITATION_MANAGER_PLUGIN_NAME}Status').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
        {rdelim});
</script>

<div id="orcidProfileStatus">
    <h3>{translate key="plugins.generic.citationManager.not_implemented"}</h3>
</div>
