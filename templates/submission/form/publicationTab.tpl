<script src="{$pluginJavaScriptURL}/optimetaCitations.js"></script>
<link rel="stylesheet" href="{$pluginStylesheetURL}/optimetaCitations.css" type="text/css"/>

<script>
    var optimetaCitations = {$citationsParsed};

    var optimetaCitationsApp = new pkp.Vue({
        // el: '#optimetaCitations',
        data: {
            citations: optimetaCitations,
            helper: optimetaCitationsGetHelperArray(optimetaCitations),
            author: {$authorModel},
            publicationWork: {$publicationWork},
            statusCodePublished: {$statusCodePublished},
            publicationStatus: 1
        },
        computed: {
            citationsJsonComputed: function () {
                return JSON.stringify(this.citations);
            },
            publicationWorkJsonComputed: function () {
                return JSON.stringify(this.publicationWork);
            },
            optimetaCitationsIsParsed: function () {
                return this.citations.length !== 0;
            },
            isPublished() {
                let isPublished = false;

                if (this.statusCodePublished === this.publicationStatus) {
                    isPublished = true;
                }

                if (document.querySelector('#optimetaCitations button.pkpButton') !== null) {
                    var saveBtn = document.querySelector('#optimetaCitations button.pkpButton');
                    if (isPublished) {
                        saveBtn.disabled = true;
                    } else {
                        saveBtn.disabled = false;
                    }
                }

                return isPublished;
            }
        },
        methods: {
            startEdit: function (index) {
                this.helper[index].editRow = true;
            },
            endEdit: function (index) {
                if (this.citations[index].authors !== null) {
                    this.cleanupEmptyAuthorRows(index);
                }
                this.helper[index].editRow = false;
            },
            addAuthor: function (index) {
                if (this.citations[index].authors === null) {
                    this.citations[index].authors = [];
                }
                this.citations[index].authors.push(this.author);
            },
            removeAuthor: function (index, authorIndex) {
                if (confirm('{translate key="plugins.generic.optimetaCitations.author.remove.question"}') !== true) {
                    return;
                }
                this.citations[index].authors.splice(authorIndex, 1);
            },
            cleanupEmptyAuthorRows: function (index) {
                var iS = '';
                for (let i = 0; i < this.citations[index].authors.length; i++) {
                    var rowIsNull = true;
                    for (var key in this.citations[index].authors[i]) {
                        if (this.citations[index].authors[i][key] !== null) {
                            rowIsNull = false;
                        }
                    }
                    if (rowIsNull === true) {
                        this.citations[index].authors.splice(i);
                    }
                }
            }
        }
    });

    function optimetaLoadingImage(show) {
        show = (typeof show !== 'undefined') ? show : true;

        let elEmpty = document.getElementById("optimetaScrollableDivEmpty");
        let elValue = document.getElementById("optimetaScrollableDivValue");
        let elLoading = document.getElementById("optimetaScrollableDivLoading");

        if (show === true) {
            elEmpty.classList.add("optimetaHide");
            elValue.classList.add("optimetaHide");
            elLoading.classList.remove("optimetaHide");
        } else {
            elEmpty.classList.remove("optimetaHide");
            elValue.classList.remove("optimetaHide");
            elLoading.classList.add("optimetaHide");
        }
    }

    function optimetaProcessCitations() {
        let questionText = '{translate key="plugins.generic.optimetaCitations.process.question"}';
        if (confirm(questionText) !== true) {
            return;
        }

        optimetaLoadingImage(true);

        $.ajax({
            url: '{$pluginApiUrl}/process',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citationsRaw: optimetaCitationsGetCitationsRaw()
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) {
            },
            success(response) {
                optimetaCitations = JSON.parse(JSON.stringify(response['message']));
                optimetaCitationsApp.citations = JSON.parse(JSON.stringify(response['message']));
                optimetaCitationsApp.helper = optimetaCitationsGetHelperArray(JSON.parse(JSON.stringify(response['message'])));

                optimetaLoadingImage(false);
            }
        });
    }

    function optimetaDepositCitations() {
        let questionText = '{translate key="plugins.generic.optimetaCitations.deposit.question"}';
        if (confirm(questionText) !== true) {
            return;
        }

        optimetaLoadingImage(true);

        $.ajax({
            url: '{$pluginApiUrl}/deposit',
            method: 'POST',
            data: {
                submissionId: {$submissionId},
                citations: JSON.stringify(optimetaCitationsApp.citations)
            },
            headers: {
                'X-Csrf-Token': optimetaCitationsGetCsrfToken(),
            },
            error(r) {
            },
            success(response) {
                optimetaCitationsApp.publicationWork = JSON.parse(JSON.stringify(response['message']));
                optimetaLoadingImage(false);
            }
        });
    }

    function optimetaClearCitations() {
        let questionText = '{translate key="plugins.generic.optimetaCitations.clear.question"}';
        if (confirm(questionText) !== true) {
            return;
        }

        optimetaCitations = [];
        optimetaCitationsApp.citations = [];
        optimetaCitationsApp.helper = [];
    }
</script>

<tab v-if="supportsReferences" id="optimetaCitations"
     label="{translate key="plugins.generic.optimetaCitations.publication.label"}">

    <div class="header">
        <table>
            <tr>
                <td colspan="2">
                    <label class="pkpFormFieldLabel">
                        {translate key="plugins.generic.optimetaCitations.process.label"}
                    </label>
                    <br/>
                    <div class="pkpFormField__description">
                        {translate key="plugins.generic.optimetaCitations.process.description"}
                    </div>
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td class="optimetaAlignRight">

                </td>
            </tr>
        </table>
    </div>

    <div class="optimetaScrollableDiv">
        ---main---
    </div>

    <div>
        ---footer---
    </div>

</tab>
