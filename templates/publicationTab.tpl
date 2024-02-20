<link rel="stylesheet" href="{$assetsUrl}/css/backend.css" type="text/css"/>
<link rel="stylesheet" href="{$assetsUrl}/css/frontend.css" type="text/css"/>

<tab v-if="supportsReferences" id="citationManager"
     label="{translate key="plugins.generic.citationManager.publication.label"}">

    <div class="header">
        <table>
            <tr>
                <td colspan="2">
                    <label class="pkpFormFieldLabel">
                        {translate key="plugins.generic.citationManager.process.label"}
                    </label>
                    <br/>
                    <div class="pkpFormField__description">
                        {translate key="plugins.generic.citationManager.process.description"}
                    </div>
                    <div>
                        <div>
                            Journal
                            <a class="citationManager-Button citationManager-ButtonGreen"
                               v-if="citationManagerApp.journalMetadata.openalex_id"
                               :href="'{$url.wikidata}/' + citationManagerApp.journalMetadata.openalex_id"
                               target="_blank"><span>OpenAlex</span>
                            </a>
                            <span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!citationManagerApp.journalMetadata.openalex_id">OpenAlex</span>

                            <a class="citationManager-Button citationManager-ButtonGreen"
                               v-if="citationManagerApp.journalMetadata.wikidata_id"
                               :href="'{$url.wikidata}/' + citationManagerApp.journalMetadata.wikidata_id"
                               target="_blank"><span>Wikidata</span></a>
                            <span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!citationManagerApp.journalMetadata.wikidata_id">Wikidata</span>

                            Authors
                            <span v-for="(row, i) in citationManagerApp.authors" class="citationManager-Outline" style="margin-right: 5px;">
                                <span class="citationManager-Tag">
                                    {{ row.givenName[citationManagerApp.locale] + ' ' + row.familyName[citationManagerApp.locale] }}
                                </span><a class="citationManager-Button citationManager-ButtonGreen"
                                        v-if="row.orcid" :href="'{$url.orcid}/' + row.orcid" target="_blank">iD
                                </a><span class="citationManager-Button citationManager-ButtonGrey"
                                          v-if="!row.orcid">iD
                                </span><a class="citationManager-Button citationManager-ButtonGreen"
                                   v-if="row.authorMetadata.wikidata_id"
                                   :href="'{$url.wikidata}/' + row.authorMetadata.wikidata_id"
                                   target="_blank">WD
                                </a><span class="citationManager-Button citationManager-ButtonGrey"
                                        v-if="!row.authorMetadata.wikidata_id">WD
                                </span></span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.publicationMetadata.wikidata_id"
                       :href="'{$url.wikidata}/' + citationManagerApp.publicationMetadata.wikidata_id"
                       target="_blank"><span>Wikidata</span></a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.publicationMetadata.wikidata_id">Wikidata</span>
                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.publicationMetadata.opencitations_id"
                       :href="'{$url.openCitations}/' + citationManagerApp.publicationMetadata.opencitations_id"
                       target="_blank"><span>OpenCitations</span></a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.publicationMetadata.opencitations_id">OpenCitations</span>
                </td>
                <td class="citationManager-AlignRight">
                    <a @click="citationManagerApp.deposit()" id="buttonDeposit" class="pkpButton"
                       :class="(citationManagerApp.isStructured && citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                        {translate key="plugins.generic.citationManager.deposit.button"}</a>
                    <a @click="citationManagerApp.clear()" id="buttonClear" class="pkpButton"
                       :class="(citationManagerApp.isStructured && !citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                        {translate key="plugins.generic.citationManager.clear.button"}</a>
                    <a @click="citationManagerApp.process()" id="buttonProcess" class="pkpButton"
                       :class="(!citationManagerApp.isStructured && !citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                        {translate key="plugins.generic.citationManager.process.button"}</a>
                </td>
            </tr>
        </table>
    </div>

    <div class="citationManager-ScrollableDiv">
        <div id="citationManager-ScrollableDivLoading"
             class="citationManager-ScrollableDivLoading citationManager-Hide">
            <img src="{$assetsUrl}/images/loading-transparent.gif" alt=""/>
        </div>
        <div id="citationManager-ScrollableDivEmpty" class="citationManager-ScrollableDivEmpty"
             v-show="!citationManagerApp.isStructured">
            {translate key="plugins.generic.citationManager.citations.empty.description"}
        </div>
        <div id="citationManager-ScrollableDivValue" class="citationManager-ScrollableDivValue">
            <table v-show="citationManagerApp.isStructured">
                <colgroup>
                    <col class="grid-column column-nr" style="width: 2%;">
                    <col class="grid-column column-parts" style="">
                    <col class="grid-column column-action" style="width: 6%;">
                </colgroup>
                <tbody>
                <tr v-for="(row, i) in citationManagerApp.citationsHelper" class="citationManager-Row">
                    <td class="citationManager-ScrollableDiv-nr">{{ i + 1 }}</td>
                    <td class="citationManager-ScrollableDiv-parts">
                        <div>
                             <span v-show="!row.editRow">
                                 <a :href="'{$url.doi}' + '/' + citationManagerApp.citations[i].doi"
                                    target="_blank">{{ citationManagerApp.citations[i].doi }}</a></span>
                            <input id="doi-{{ i + 1 }}" placeholder="DOI" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].doi"
                                   class="citationManager-Input"/>

                            <span v-show="!row.editRow">
                                    <a :href="citationManagerApp.citations[i].urn"
                                       target="_blank">{{ citationManagerApp.citations[i].urn }}</a></span>
                            <input id="urn-{{ i + 1 }}" placeholder="URN" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].urn"
                                   class="citationManager-Input"/>

                            <span v-show="!row.editRow">
                                    <a :href="citationManagerApp.citations[i].url"
                                       target="_blank">{{ citationManagerApp.citations[i].url }}</a></span>
                            <input id="url-{{ i + 1 }}" placeholder="URL" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].url"
                                   class="citationManager-Input"/>
                        </div>

                        <div>

                            <div>
                                <span v-for="(author, j) in citationManagerApp.citations[i].authors">
                                    <span v-show="!row.editRow"
                                          class="citationManager-Tag">{{ citationManagerApp.citations[i].authors[j].given_name }}</span>
                                    <input id="given_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Given name"
                                           v-show="row.editRow"
                                           v-model="citationManagerApp.citations[i].authors[j].given_name"
                                           class="citationManager-Input"/>

                                    <span v-show="!row.editRow"
                                          class="citationManager-Tag">{{ citationManagerApp.citations[i].authors[j].family_name }}</span>
                                    <input id="family_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Family name"
                                           v-show="row.editRow"
                                           v-model="citationManagerApp.citations[i].authors[j].family_name"
                                           class="citationManager-Input"/>

                                    <input id="orcid-{{ i + 1 }}-{{ j + 1 }}" placeholder="Orcid"
                                           v-show="row.editRow"
                                           v-model="citationManagerApp.citations[i].authors[j].orcid_id"
                                           class="citationManager-Input"/>

                                    <span class="citationManager-Button citationManager-ButtonGrey"
                                          v-if="!citationManagerApp.citations[i].authors[j].orcid_id">iD</span>
                                    <a class="citationManager-Button citationManager-ButtonGreen"
                                       v-if="citationManagerApp.citations[i].authors[j].orcid_id"
                                       :href="'{$url.orcid}' + '/' + citationManagerApp.citations[i].authors[j].orcid_id"
                                       target="_blank">iD</a>

                                    <a class="pkpButton" v-show="row.editRow"
                                       v-on:click="citationManagerApp.removeAuthor(i, j)">
                                        <i class="fa fa-trash" aria-hidden="true"></i> </a>
                                        <br v-show="row.editRow"/>
                                </span>
                                <a class="pkpButton" v-show="row.editRow"
                                   v-on:click="citationManagerApp.addAuthor(i)">{translate key="plugins.generic.citationManager.author.add.button"}</a>
                            </div>

                            <div>
                                <span v-show="!row.editRow && !citationManagerApp.citations[i].isProcessed"
                                      class="citationManager-Tag">No information found</span>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].title"
                                      class="citationManager-Tag">{{ citationManagerApp.citations[i].title }}</span>
                                <input id="title-{{ i + 1 }}" placeholder="Title" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].title"/>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].journal_name"
                                      class="citationManager-Tag">{{ citationManagerApp.citations[i].journal_name }}</span>
                                <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].journal_name"/>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].publication_year"
                                      class="citationManager-Tag">{{ citationManagerApp.citations[i].publication_year }}</span>
                                <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].publication_year"/>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].volume"
                                      class="citationManager-Tag">Volume {{ citationManagerApp.citations[i].volume }}</span>
                                <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].volume"/>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].issue"
                                      class="citationManager-Tag">Issue {{ citationManagerApp.citations[i].issue }}</span>
                                <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].issue"/>

                                <span v-show="!row.editRow && citationManagerApp.citations[i].first_page"
                                      class="citationManager-Tag">Pages {{ citationManagerApp.citations[i].first_page }} - {{ citationManagerApp.citations[i].last_page }}</span>
                                <input id="first_page-{{ i + 1 }}" placeholder="First page" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].first_page"/>
                                <input id="last_page-{{ i + 1 }}" placeholder="Last page" v-show="row.editRow"
                                       class="citationManager-Input"
                                       v-model="citationManagerApp.citations[i].last_page"/>
                            </div>

                        </div>

                        <div class="citationManager-RawText">{{ citationManagerApp.citations[i].raw }}</div>

                        <div>
                            <a class="citationManager-Button citationManager-ButtonGreen"
                               v-if="citationManagerApp.citations[i].wikidata_id"
                               :href="'{$url.wikidata}/' + citationManagerApp.citations[i].wikidata_id"
                               target="_blank"><span>Wikidata</span></a>
                            <span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!citationManagerApp.citations[i].wikidata_id">Wikidata</span>
                            <a class="citationManager-Button citationManager-Button citationManager-ButtonGreen"
                               v-if="citationManagerApp.citations[i].openalex_id"
                               :href="'{$url.openAlex}/' + citationManagerApp.citations[i].openalex_id"
                               target="_blank"><span>OpenAlex</span></a>
                            <span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!citationManagerApp.citations[i].openalex_id">OpenAlex</span>
                        </div>
                    </td>
                    <td class="citationManager-ScrollableDiv-actions">
                        <a v-show="!row.editRow" @click="citationManagerApp.startEdit(i)"
                           class="pkpButton"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a v-show="row.editRow" @click="citationManagerApp.endEdit(i)"
                           class="pkpButton"><i class="fa fa-check" aria-hidden="true"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="citationManager-Hide">
            <span>{{ citationManagerApp.citationsRaw      = workingPublication.citationsRaw }}</span>
            <span>{{ citationManagerApp.submissionId      = workingPublication.submissionId }}</span>
            <span>{{ citationManagerApp.publicationStatus = workingPublication.status }}</span>
            <span>{{ citationManagerApp.workingCitations           = workingPublication.CitationManagerPlugin_CitationsStructured }}</span>
            <span>{{ citationManagerApp.workingPublicationMetadata = workingPublication.CitationManagerPlugin_MetadataPublication }}</span>
            <span>{{ citationManagerApp.workingJournalMetadata = workingPublication.CitationManagerPlugin_MetadataJournal }}</span>
            <span>{{ citationManagerApp.workingAuthorsMetadata = workingPublication.CitationManagerPlugin_MetadataAuthors }}</span>
            <span>{{ citationManagerApp.workingPublicationId       = workingPublication.id }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.fields[0]['value'] = citationManagerApp.citationsJsonComputed }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.fields[1]['value'] = citationManagerApp.publicationMetadataJsonComputed }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.action             = '{$apiBaseUrl}submissions/' + workingPublication.submissionId + '/publications/' + workingPublication.id }}</span>
        </div>
        <pkp-form v-bind="components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}" @set="set"/>
    </div>

</tab>

<script>
    let citationManagerApp = new pkp.Vue({
        // el: '#citationManager',
        data: {
            locale: {$locale},
            csrfToken: pkp.currentUser.csrfToken,
            citations: {$structuredCitations},
            citationsHelper: [],
            citationsRaw: '', // workingPublication.citationsRaw
            journalMetadata: {$journalMetadata},
            authors: {$authors},
            authorsMetadata: [],
            author: {$authorModel},
            publicationMetadata: {$publicationMetadata},
            statusCodePublished: pkp.const.STATUS_PUBLISHED,
            publicationStatus: 0, // workingPublication.status
            submissionId: 0, // workingPublication.submissionId
            publicationId: 0, // workingPublication.publicationId
            workingPublicationId: 0, // workingPublication.publicationId
            workingCitations: [], // workingPublication.CitationManagerPlugin_StructuredCitations
            workingPublicationMetadata: [], // workingPublication.CitationManagerPlugin_PublicationMetadata
            workingJournalMetadata: [], // workingPublication.CitationManagerPlugin_MetadataJournal
            workingAuthorsMetadata: [], // workingPublication.CitationManagerPlugin_MetadataAuthors
        },
        computed: {
            citationsJsonComputed: function () {
                return JSON.stringify(this.citations);
            },
            publicationMetadataJsonComputed: function () {
                return JSON.stringify(this.publicationMetadata);
            },
            isStructured: function () {
                return this.citations.length !== 0;
            },
            isPublished() {
                let isPublished = false;

                if (this.statusCodePublished === this.publicationStatus) {
                    isPublished = true;
                }

                if (document.querySelector('#citationManager button.pkpButton') !== null) {
                    let saveBtn = document.querySelector('#citationManager button.pkpButton');
                    saveBtn.disabled = isPublished;
                }

                return isPublished;
            }
        },
        methods: {
            startEdit: function (index) {
                this.citationsHelper[index].editRow = true;
            },
            endEdit: function (index) {
                if (this.citations[index].authors !== null) {
                    this.cleanupEmptyAuthorRows(index);
                }
                this.citationsHelper[index].editRow = false;
            },
            addAuthor: function (index) {
                if (this.citations[index].authors === null) {
                    this.citations[index].authors = [];
                }
                this.citations[index].authors.push(this.author);
            },
            removeAuthor: function (index, authorIndex) {
                if (confirm('{translate key="plugins.generic.citationManager.author.remove.question"}') !== true) {
                    return;
                }
                this.citations[index].authors.splice(authorIndex, 1);
            },
            cleanupEmptyAuthorRows: function (index) {
                let iS = '';
                for (let i = 0; i < this.citations[index].authors.length; i++) {
                    let rowIsNull = true;
                    for (let key in this.citations[index].authors[i]) {
                        if (this.citations[index].authors[i][key] !== null) {
                            rowIsNull = false;
                        }
                    }
                    if (rowIsNull === true) {
                        this.citations[index].authors.splice(i);
                    }
                }
            },
            clear: function () {
                let questionText = '{translate key="plugins.generic.citationManager.clear.question"}';

                if (confirm(questionText) !== true) return;

                this.citations = [];
                this.citationsHelper = [];
            },
            process: function () {
                let questionText = '{translate key="plugins.generic.citationManager.process.question"}';

                if (confirm(questionText) !== true) return;

                this.loadingImage(true);

                let self = this;
                $.ajax({
                    url: '{$apiBaseUrl}{CITATION_MANAGER_PLUGIN_NAME}/process',
                    method: 'POST',
                    data: {
                        submissionId: self.submissionId,
                        publicationId: self.publicationId,
                        citationsRaw: self.citationsRaw
                    },
                    headers: {
                        'X-Csrf-Token': self.csrfToken,
                    },
                    error(r) {
                    },
                    success(response) {
                        let result = JSON.parse(JSON.stringify(response['message']));

                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);

                        self.loadingImage(false);
                    }
                });
            },
            deposit: function () {
                let questionText = '{translate key="plugins.generic.citationManager.deposit.question"}';
                if (confirm(questionText) !== true) {
                    return;
                }

                this.loadingImage(true);

                let self = this;
                $.ajax({
                    url: '{$apiBaseUrl}{CITATION_MANAGER_PLUGIN_NAME}/deposit',
                    method: 'POST',
                    data: {
                        submissionId: self.submissionId,
                        publicationId: self.publicationId,
                        publicationMetadata: JSON.stringify(self.publicationMetadata),
                        citations: JSON.stringify(self.citations),
                        // citationsRaw: self.citationsRaw
                    },
                    headers: {
                        'X-Csrf-Token': self.csrfToken,
                    },
                    error(r) {
                    },
                    success(response) {
                        let result = JSON.parse(JSON.stringify(response['message']));

                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);
                        self.publicationMetadata = result['publicationMetadata'];
                        self.authors = result['authors'];

                        self.loadingImage(false);
                    }
                });
            },
            loadingImage: function (show) {
                show = (typeof show !== 'undefined') ? show : true;

                let elEmpty = document.getElementById('citationManager-ScrollableDivEmpty');
                let elValue = document.getElementById('citationManager-ScrollableDivValue');
                let elLoading = document.getElementById('citationManager-ScrollableDivLoading');

                if (show === true) {
                    elEmpty.classList.add('citationManager-Hide');
                    elValue.classList.add('citationManager-Hide');
                    elLoading.classList.remove('citationManager-Hide');
                } else {
                    elEmpty.classList.remove('citationManager-Hide');
                    elValue.classList.remove('citationManager-Hide');
                    elLoading.classList.add('citationManager-Hide');
                }
            },
            getCitationsHelper: function (arr) {
                let result = [];
                for (let i = 0; i < arr.length; i++) {
                    result[i] = { 'editRow': false }; // fixme: don't use prettier CTRL+ALT+L
                }
                return result;
            }
        },
        watch: {
            workingPublicationId(newValue, oldValue) {
                this.publicationId = this.workingPublicationId;
                if (oldValue !== 0) {
                    if (this.workingCitations && this.workingCitations.length > 0) {
                        this.citations = JSON.parse(this.workingCitations);
                        this.citationsHelper = this.getCitationsHelper(JSON.parse(this.workingCitations));
                    } else {
                        this.citations = [];
                        this.citationsHelper = [];
                    }
                    this.publicationMetadata = [];
                    if (this.workingPublicationMetadata && this.workingPublicationMetadata.length > 0) {
                        this.publicationMetadata = JSON.parse(this.workingPublicationMetadata);
                    }
                    this.workingJournalMetadata = [];
                    if (this.workingJournalMetadata && this.workingJournalMetadata.length > 0) {
                        this.journalMetadata = JSON.parse(this.workingJournalMetadata);
                    }
                    this.workingAuthorsMetadata = [];
                    if (this.workingAuthorsMetadata && this.workingAuthorsMetadata.length > 0) {
                        this.authorsMetadata = JSON.parse(this.workingAuthorsMetadata);
                    }
                }
                console.log(oldValue + ' > ' + newValue);
            },
        },
        created() {
            this.citationsHelper = this.getCitationsHelper(this.citations);
        }
    });
</script>
