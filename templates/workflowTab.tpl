<link rel="stylesheet" href="{$assetsUrl}/css/backend.css" type="text/css"/>
<link rel="stylesheet" href="{$assetsUrl}/css/frontend.css" type="text/css"/>

<tab v-if="supportsReferences" id="citationManager"
     label="{translate key="plugins.generic.citationManager.publication.label"}">

    <div class="header">
        <div>
            {translate key="plugins.generic.citationManager.process.description"}
        </div>
        <table>
            <tr>
                <td style="width: 80px;"><strong>{translate key="context.context"}</strong></td>
                <td colspan="2">
                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.metadataJournal.openalex_id"
                       :href="'{$url.openAlex}/' + citationManagerApp.metadataJournal.openalex_id"
                       target="_blank"><span>OpenAlex</span>
                    </a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.metadataJournal.openalex_id">OpenAlex</span>

                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.metadataJournal.wikidata_id"
                       :href="'{$url.wikidata}/' + citationManagerApp.metadataJournal.wikidata_id"
                       target="_blank"><span>Wikidata</span></a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.metadataJournal.wikidata_id">Wikidata</span>
                </td>
            </tr>
            <tr>
                <td><strong>{translate key="article.authors"}</strong></td>
                <td colspan="2">
                    <span v-for="(row, i) in citationManagerApp.authors" class="citationManager-Outline" style="margin-right: 5px;">
                        <span class="citationManager-Tag">
                            {{ row._data.givenName[citationManagerApp.locale] }} {{ row._data.familyName[citationManagerApp.locale] }}
                        </span><a class="citationManager-Button citationManager-ButtonGreen"
                                  v-if="row._data.orcid" :href="row._data.orcid" target="_blank">iD
                        </a><span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!row._data.orcid">iD
                        </span><a class="citationManager-Button citationManager-ButtonGreen"
                                  v-if="row._data.metadata.wikidata_id"
                                  :href="'{$url.wikidata}/' + row._data.metadata.wikidata_id"
                                  target="_blank">WD
                        </a><span class="citationManager-Button citationManager-ButtonGrey"
                                  v-if="!row._data.metadata.wikidata_id">WD
                    </span></span>
                </td>
            </tr>
            <tr>
                <td><strong>{translate key="common.publication"}</strong></td>
                <td>
                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.metadataPublication.wikidata_id"
                       :href="'{$url.wikidata}/' + citationManagerApp.metadataPublication.wikidata_id"
                       target="_blank"><span>Wikidata</span></a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.metadataPublication.wikidata_id">Wikidata</span>
                    <a class="citationManager-Button citationManager-ButtonGreen"
                       v-if="citationManagerApp.metadataPublication.opencitations_id"
                       :href="'{$url.openCitations}/' + citationManagerApp.metadataPublication.opencitations_id"
                       target="_blank"><span>OpenCitations</span></a>
                    <span class="citationManager-Button citationManager-ButtonGrey"
                          v-if="!citationManagerApp.metadataPublication.opencitations_id">OpenCitations</span>
                </td>
                <td class="citationManager-AlignRight">
                    <a @click="citationManagerApp.deposit()" id="buttonDeposit" class="pkpButton"
                       :class="(citationManagerApp.isStructured && citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                        {translate key="plugins.generic.citationManager.deposit.button"}</a>
                    <a @click="citationManagerApp.clear()" id="buttonClear" class="pkpButton"
                       :class="(citationManagerApp.isStructured && !citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                        {translate key="plugins.generic.citationManager.clear.button"}</a>
                    <a @click="citationManagerApp.process()" id="buttonProcess" class="pkpButton"
                       :class="(!citationManagerApp.isPublished)?'':'citationManager-Disabled'">
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
            <span>{{ citationManagerApp.citationsRaw               = workingPublication.citationsRaw }}</span>
            <span>{{ citationManagerApp.submissionId               = workingPublication.submissionId }}</span>
            <span>{{ citationManagerApp.publicationStatus          = workingPublication.status }}</span>
            <span>{{ citationManagerApp.workingCitationsStructured = workingPublication.CitationManagerPlugin_CitationsStructured }}</span>
            <span>{{ citationManagerApp.workingMetadataPublication = workingPublication.CitationManagerPlugin_MetadataPublication }}</span>
            <span>{{ citationManagerApp.workingMetadataJournal     = workingPublication.CitationManagerPlugin_MetadataJournal }}</span>
            <span>{{ citationManagerApp.workingPublicationId       = workingPublication.id }}</span>
            <span>{{ citationManagerApp.locale                     = workingPublication.locale }}</span>

            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.fields[0]['value']
                = citationManagerApp.citationsStructuredJsonComputed }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.fields[1]['value']
                = citationManagerApp.metadataPublicationJsonComputed }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.fields[2]['value']
                = citationManagerApp.metadataJournalJsonComputed }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}.action
                = '{$apiBaseUrl}submissions/' + workingPublication.submissionId + '/publications/' + workingPublication.id }}</span>
        </div>
        <pkp-form v-bind="components.{CitationManagerPlugin::CITATION_MANAGER_STRUCTURED_CITATIONS_FORM}" @set="set"/>
    </div>

</tab>

<script>
    let citationManagerApp = new pkp.Vue({
        // el: '#citationManager',
        data: {
            citations: {$citationsStructured},
            metadataJournal: {$metadataJournal},
            metadataPublication: {$metadataPublication},
            authorsIn: {$authors},
            author: {$authorModel},
            citationsHelper: [],
            csrfToken: pkp.currentUser.csrfToken,
            statusCodePublished: pkp.const.STATUS_PUBLISHED,
            locale: '',                     // workingPublication.locale
            publicationStatus: 0,           // workingPublication.status
            submissionId: 0,                // workingPublication.submissionId
            publicationId: 0,               // workingPublication.publicationId
            citationsRaw: '',               // workingPublication.citationsRaw
            workingPublicationId: 0,        // workingPublication.publicationId
            workingCitationsStructured: [], // workingPublication.CitationManagerPlugin_CitationsStructured
            workingMetadataPublication: [], // workingPublication.CitationManagerPlugin_MetadataPublication
            workingMetadataJournal: [],     // workingPublication.CitationManagerPlugin_MetadataJournal
        },
        computed: {
            citationsStructuredJsonComputed: function () {
                return JSON.stringify(this.citations);
            },
            metadataPublicationJsonComputed: function () {
                return JSON.stringify(this.metadataPublication);
            },
            metadataJournalJsonComputed: function () {
                return JSON.stringify(this.metadataJournal);
            },
            isStructured: function () {
                return this.citations.length !== 0;
            },
            isPublished: function () {
                let isPublished = false;

                if (this.statusCodePublished === this.publicationStatus) {
                    isPublished = true;
                }

                if (document.querySelector('#citationManager button.pkpButton') !== null) {
                    let saveBtn = document.querySelector('#citationManager button.pkpButton');
                    saveBtn.disabled = isPublished;
                }

                return isPublished;
            },
            authors: function() {
                let result = this.authorsIn;
                for (let i = 0; i < result.length; i++) {
                    result[i]['_data']['metadata'] =
                        result[i]['_data']['{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}']
                }
                return result;
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
                        
                        self.metadataJournal = result['metadataJournal'];
                        self.metadataPublication = result['metadataPublication'];
                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];

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
                        citations: JSON.stringify(self.citations),
                    },
                    headers: {
                        'X-Csrf-Token': self.csrfToken,
                    },
                    error(r) {
                    },
                    success(response) {
                        let result = JSON.parse(JSON.stringify(response['message']));

                        self.metadataJournal = result['metadataJournal'];
                        self.metadataPublication = result['metadataPublication'];
                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];

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
                    if (this.workingCitationsStructured && this.workingCitationsStructured.length > 0) {
                        this.citations = JSON.parse(this.workingCitationsStructured);
                        this.citationsHelper = this.getCitationsHelper(JSON.parse(this.workingCitationsStructured));
                    } else {
                        this.citations = [];
                        this.citationsHelper = [];
                    }
                    this.metadataPublication = [];
                    if (this.workingMetadataPublication && this.workingMetadataPublication.length > 0) {
                        this.metadataPublication = JSON.parse(this.workingMetadataPublication);
                    }
                    this.workingMetadataJournal = [];
                    if (this.workingMetadataJournal && this.workingMetadataJournal.length > 0) {
                        this.metadataJournal = JSON.parse(this.workingMetadataJournal);
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
