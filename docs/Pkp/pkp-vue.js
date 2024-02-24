$.pkp = $.pkp || {};
$.pkp.app =
    {
        "currentLocale": "en",
        "primaryLocale": "en",
        "baseUrl": "http:\/\/localhost\/ojs340",
        "contextPath": "publicknowledge",
        "apiBasePath": "\/api\/v1",
        "restfulUrlsEnabled": false,
        "tinyMceContentCSS": "http:\/\/localhost\/ojs340\/plugins\/generic\/tinymce\/styles\/content.css",
        "tinyMceOneLineContentCSS": "http:\/\/localhost\/ojs340\/plugins\/generic\/tinymce\/styles\/content_oneline.css",
        "rtlLocales": []
    };
$.pkp.cons =
    {
        "LISTBUILDER_SOURCE_TYPE_TEXT": 0,
        "LISTBUILDER_SOURCE_TYPE_SELECT": 1,
        "LISTBUILDER_OPTGROUP_LABEL": "optGroupLabel",
        "REALLY_BIG_NUMBER": 10000,
        "UPLOAD_MAX_FILESIZE": "2M",
        "WORKFLOW_STAGE_ID_PUBLISHED": 0,
        "WORKFLOW_STAGE_ID_SUBMISSION": 1,
        "WORKFLOW_STAGE_ID_INTERNAL_REVIEW": 2,
        "WORKFLOW_STAGE_ID_EXTERNAL_REVIEW": 3,
        "WORKFLOW_STAGE_ID_EDITING": 4,
        "WORKFLOW_STAGE_ID_PRODUCTION": 5,
        "INSERT_TAG_VARIABLE_TYPE_PLAIN_TEXT": "PLAIN_TEXT",
        "ROLE_ID_MANAGER": 16,
        "ROLE_ID_SITE_ADMIN": 1,
        "ROLE_ID_AUTHOR": 65536,
        "ROLE_ID_REVIEWER": 4096,
        "ROLE_ID_ASSISTANT": 4097,
        "ROLE_ID_READER": 1048576,
        "ROLE_ID_SUB_EDITOR": 17,
        "ROLE_ID_SUBSCRIPTION_MANAGER": 2097152,
        "STATUS_QUEUED": 1,
        "STATUS_PUBLISHED": 3,
        "STATUS_DECLINED": 4,
        "STATUS_SCHEDULED": 5
    };
$.pkp.plugins = {};
pkp.const =
    {
        "LISTBUILDER_SOURCE_TYPE_TEXT": 0,
        "LISTBUILDER_SOURCE_TYPE_SELECT": 1,
        "LISTBUILDER_OPTGROUP_LABEL": "optGroupLabel",
        "REALLY_BIG_NUMBER": 10000,
        "UPLOAD_MAX_FILESIZE": "2M",
        "WORKFLOW_STAGE_ID_PUBLISHED": 0,
        "WORKFLOW_STAGE_ID_SUBMISSION": 1,
        "WORKFLOW_STAGE_ID_INTERNAL_REVIEW": 2,
        "WORKFLOW_STAGE_ID_EXTERNAL_REVIEW": 3,
        "WORKFLOW_STAGE_ID_EDITING": 4,
        "WORKFLOW_STAGE_ID_PRODUCTION": 5,
        "INSERT_TAG_VARIABLE_TYPE_PLAIN_TEXT": "PLAIN_TEXT",
        "ROLE_ID_MANAGER": 16,
        "ROLE_ID_SITE_ADMIN": 1,
        "ROLE_ID_AUTHOR": 65536,
        "ROLE_ID_REVIEWER": 4096,
        "ROLE_ID_ASSISTANT": 4097,
        "ROLE_ID_READER": 1048576,
        "ROLE_ID_SUB_EDITOR": 17,
        "ROLE_ID_SUBSCRIPTION_MANAGER": 2097152,
        "STATUS_QUEUED": 1,
        "STATUS_PUBLISHED": 3,
        "STATUS_DECLINED": 4,
        "STATUS_SCHEDULED": 5,
        "FORM_CITATIONS": "citations",
        "FORM_PUBLICATION_LICENSE": "publicationLicense",
        "FORM_PUBLISH": "publish",
        "FORM_TITLE_ABSTRACT": "titleAbstract",
        "FORM_METADATA": "metadata",
        "FORM_SELECT_REVISION_DECISION": "selectRevisionDecision",
        "FORM_SELECT_REVISION_RECOMMENDATION": "selectRevisionRecommendation",
        "FORM_ASSIGN_TO_ISSUE": "assignToIssue",
        "FORM_ISSUE_ENTRY": "issueEntry"
    };
pkp.localeKeys =
    {
        "common.attachFiles": "Attach Files",
        "common.cancel": "Cancel",
        "common.clearSearch": "Clear search phrase",
        "common.close": "Close",
        "common.commaListSeparator": ", ",
        "common.confirm": "Confirm",
        "common.delete": "Delete",
        "common.edit": "Edit",
        "common.editItem": "Edit {$name}",
        "common.error": "Error",
        "common.filter": "Filters",
        "common.filterAdd": "Add filter: {$filterTitle}",
        "common.filterRemove": "Clear filter: {$filterTitle}",
        "common.insertContent": "Insert Content",
        "common.loading": "Loading",
        "common.no": "No",
        "common.noItemsFound": "No items found.",
        "common.none": "None",
        "common.ok": "OK",
        "common.order": "Order",
        "common.orderUp": "Increase position of {$itemTitle}",
        "common.orderDown": "Decrease position of {$itemTitle}",
        "common.pageNumber": "Page {$pageNumber}",
        "common.pagination.goToPage": "Go to {$page}",
        "common.pagination.label": "View additional pages",
        "common.pagination.next": "Next page",
        "common.pagination.previous": "Previous page",
        "common.remove": "Remove",
        "common.required": "Required",
        "common.save": "Save",
        "common.saving": "Saving",
        "common.search": "Search",
        "common.selectWithName": "Select {$name}",
        "common.unknownError": "An unexpected error has occurred. Please reload the page and try again.",
        "common.uploadedBy": "Uploaded by {$name}",
        "common.uploadedByAndWhen": "Uploaded by {$name} on {$date}",
        "common.view": "View",
        "list.viewLess": "Hide expanded details about {$name}",
        "list.viewMore": "Show more details about {$name}",
        "common.viewWithName": "View {$name}",
        "common.yes": "Yes",
        "form.dataHasChanged": "The data on this form has changed. Do you wish to continue without saving?",
        "form.errorA11y": "Go to {$fieldLabel}: {$errorMessage}",
        "form.errorGoTo": "Jump to next error",
        "form.errorMany": "Please correct {$count} errors.",
        "form.errorOne": "Please correct one error.",
        "form.errors": "The form was not saved because {$count} error(s) were encountered. Please correct these errors and try again.",
        "form.multilingualLabel": "{$label} in {$localeName}",
        "form.multilingualProgress": "{$count}\/{$total} languages completed",
        "form.saved": "Saved",
        "help.help": "Help",
        "navigation.backTo": "\u27f5 Back to {$page}",
        "validator.required": "This field is required."
    };
pkp.currentUser =
    {
        "csrfToken": "db6ea7e7481b4b597ab5068ca5a808f3",
        "id": 1,
        "roles": [1, 16]
    };
pkp.documentTypeIcons =
    {
        "default": "file-o",
        "audio": "file-audio-o",
        "epub": "file-text-o",
        "excel": "file-excel-o",
        "html": "file-code-o",
        "image": "file-image-o",
        "pdf": "file-pdf-o",
        "word": "file-word-o",
        "video": "file-video-o",
        "zip": "file-archive-o",
        "url": "external-link"
    };