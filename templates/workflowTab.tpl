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
                    <a class="pkpButton citationManager-Button" target="_blank"
                       :class="(citationManagerApp.metadataJournal.openalex_id)?'':'citationManager-Disabled'"
                       :href="'{$url.openAlex}/' + citationManagerApp.metadataJournal.openalex_id">OpenAlex</a>
                    <a class="pkpButton citationManager-Button" target="_blank"
                       :class="(citationManagerApp.metadataJournal.wikidata_id)?'':'citationManager-Disabled'"
                       :href="'{$url.wikidata}/' + citationManagerApp.metadataJournal.wikidata_id">Wikidata</a>
                </td>
            </tr>
            <tr>
                <td><strong>{translate key="article.authors"}</strong></td>
                <td colspan="2">
                    <span v-for="(row, i) in citationManagerApp.authors" style="margin-right: 5px;">
                        <span class="citationManager-Tag">
                            {{ row._data.givenName[workingPublication.locale] }} {{ row._data.familyName[workingPublication.locale] }}
                        </span>
                        <a class="pkpButton citationManager-Button" target="_blank"
                           :class="(row._data.orcid)?'':'citationManager-Disabled'"
                           :href="row._data.orcid">iD</a>
                        <a class="pkpButton citationManager-Button" target="_blank"
                           :class="(row._data.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}.wikidata_id)?'':'citationManager-Disabled'"
                           :href="'{$url.wikidata}/' + row._data.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}.wikidata_id">WD</a>
			</span>
                </td>
            </tr>
            <tr>
                <td><strong>{translate key="common.publication"}</strong></td>
                <td>
                    <a class="pkpButton citationManager-Button" target="_blank"
                       :class="(citationManagerApp.metadataPublication.wikidata_id)?'':'citationManager-Disabled'"
                       :href="'{$url.wikidata}/' + citationManagerApp.metadataPublication.wikidata_id">Wikidata</a>
                    <a class="pkpButton citationManager-Button" target="_blank"
                       :class="(citationManagerApp.metadataPublication.opencitations_id)?'':'citationManager-Disabled'"
                       :href="'{$url.openCitations}/' + citationManagerApp.metadataPublication.opencitations_id">OpenCitations</a>
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
                            <a :href="'{$url.doi}' + '/' + citationManagerApp.citations[i].doi"
                               v-show="!row.editRow" target="_blank">{{ citationManagerApp.citations[i].doi }}</a>
                            <input id="doi-{{ i + 1 }}" placeholder="DOI" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].doi" class="citationManager-Input"/>
                            <a :href="citationManagerApp.citations[i].urn"
                               v-show="!row.editRow" target="_blank">{{ citationManagerApp.citations[i].urn }}</a>
                            <input id="urn-{{ i + 1 }}" placeholder="URN" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].urn" class="citationManager-Input"/>
                            <a :href="citationManagerApp.citations[i].url"
                               v-show="!row.editRow" target="_blank">{{ citationManagerApp.citations[i].url }}</a>
                            <input id="url-{{ i + 1 }}" placeholder="URL" v-show="row.editRow"
                                   v-model="citationManagerApp.citations[i].url" class="citationManager-Input"/>
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
                                    <input id="orcid-{{ i + 1 }}-{{ j + 1 }}" placeholder="Orcid" v-show="row.editRow"
                                           v-model="citationManagerApp.citations[i].authors[j].orcid_id"
                                           class="citationManager-Input"/>
                                    <a class="pkpButton citationManager-Button" target="_blank"
                                       :class="(citationManagerApp.citations[i].authors[j].orcid_id)?'':'citationManager-Disabled'"
                                       :href="'{$url.orcid}' + '/' + citationManagerApp.citations[i].authors[j].orcid_id">iD</a>
                                    <a class="pkpButton" v-show="row.editRow"
                                       v-on:click="citationManagerApp.removeAuthor(i, j)">
                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <br v-show="row.editRow"/>
                                </span>
                                <a class="pkpButton" v-show="row.editRow"
                                   v-on:click="citationManagerApp.addAuthor(i)">
                                    {translate key="plugins.generic.citationManager.author.add.button"}
                                </a>
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
                            <a class="pkpButton citationManager-Button" target="_blank"
                               :class="(citationManagerApp.citations[i].wikidata_id)?'':'citationManager-Disabled'"
                               :href="'{$url.wikidata}/' + citationManagerApp.citations[i].wikidata_id">Wikidata</a>
                            <a class="pkpButton citationManager-Button" target="_blank"
                               :class="(citationManagerApp.citations[i].wikidata_id)?'':'citationManager-Disabled'"
                               :href="'{$url.openAlex}/' + citationManagerApp.citations[i].openalex_id">OpenAlex</a>
                        </div>
                    </td>
                    <td class="citationManager-ScrollableDiv-actions">
                        <a v-show="!row.editRow"  @click="citationManagerApp.toggleEdit(i)" class="pkpButton"
                           :class="(!citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                            <i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a v-show="row.editRow"  @click="citationManagerApp.toggleEdit(i)" class="pkpButton">
                            <i class="fa fa-check" aria-hidden="true"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="citationManager-Hide">
            <span>{{ citationManagerApp.workingPublicationStatus   = workingPublication.status }}</span>
            <span>{{ citationManagerApp.submissionId               = workingPublication.submissionId }}</span>
            <span>{{ citationManagerApp.citationsRaw               = workingPublication.citationsRaw }}</span>
            <span>{{ citationManagerApp.workingPublicationId       = workingPublication.id }}</span>
            <span>{{ citationManagerApp.workingCitationsStructured = workingPublication.{CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED} }}</span>
            <span>{{ citationManagerApp.workingMetadataPublication = workingPublication.{CitationManagerPlugin::CITATION_MANAGER_METADATA_PUBLICATION} }}</span>
            <span>{{ citationManagerApp.workingAuthors             = workingPublication.authors }}</span>

            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED_FORM}.fields[0]['value'] = JSON.stringify(citationManagerApp.citations) }}</span>
            <span>{{ components.{CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED_FORM}.action = '{$apiBaseUrl}submissions/' + workingPublication.submissionId + '/publications/' + workingPublication.id }}</span>
        </div>
        <div>
            <pkp-form v-bind="components.{CitationManagerPlugin::CITATION_MANAGER_CITATIONS_STRUCTURED_FORM}" @set="set"/>
        </div>
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
            authorModel: {$authorModel},
            citationsHelper: [],
            publicationId: 0,
            submissionId: 0,                // workingPublication.submissionId
            citationsRaw: '',               // workingPublication.citationsRaw
            workingPublicationId: 0,        // workingPublication.id
            workingPublicationStatus: 0,    // workingPublication.status
            workingCitationsStructured: [], // workingPublication.CitationManagerPlugin_CitationsStructured
            workingMetadataPublication: [], // workingPublication.CitationManagerPlugin_MetadataPublication
            workingAuthors: [],             // workingPublication.authors
        },
        computed: {
            isStructured: function () {
                return this.citations.length !== 0;
            },
            isPublished: function () {
                let isPublished = false;

                if (pkp.const.STATUS_PUBLISHED === this.workingPublicationStatus) {
                    isPublished = true;
                }

                if (document.querySelector('#citationManager button.pkpButton') !== null) {
                    let saveBtn = document.querySelector('#citationManager button.pkpButton');
                    saveBtn.disabled = isPublished;
                }

                return isPublished;
            },
            authors: function () {
                let result = this.authorsIn;
                for (let i = 0; i < result.length; i++) {
                    let metadata = result[i].{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR};
                    if (typeof metadata === 'string') {
                        result[i].{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} = JSON.parse(metadata);
                    }
                }
                return result;
            }
        },
        methods: {
            clear: function () {
                if (confirm('{translate key="plugins.generic.citationManager.clear.question"}') !== true) return;

                this.citations = [];
                this.citationsHelper = [];
            },
            process: function () {
                if (confirm('{translate key="plugins.generic.citationManager.process.question"}') !== true) return;

                this.toggleLoading();

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
                        'X-Csrf-Token': pkp.currentUser.csrfToken
                    },
                    success(response) {
                        let result = JSON.parse(JSON.stringify(response['message']));

                        self.metadataJournal = result['metadataJournal'];
                        self.metadataPublication = result['metadataPublication'];
                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];
                    },
                    complete() {
                        self.toggleLoading();
                    }
                });
            },
            deposit: function () {
                if (confirm('{translate key="plugins.generic.citationManager.deposit.question"}') !== true) {
                    return;
                }

                this.toggleLoading();

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
                        'X-Csrf-Token': pkp.currentUser.csrfToken
                    },
                    success(response) {
                        let result = JSON.parse(JSON.stringify(response['message']));

                        self.metadataJournal = result['metadataJournal'];
                        self.metadataPublication = result['metadataPublication'];
                        self.citations = result['citations'];
                        self.citationsHelper = self.getCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];
                    },
                    complete() {
                        self.toggleLoading();
                    }
                });
            },
            toggleEdit: function (index) {
                this.citationsHelper[index].editRow =
                    !this.citationsHelper[index].editRow;
                if (this.citations[index].authors !== null) {
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
                }
            },
            addAuthor: function (index) {
                if (this.citations[index].authors === null) {
                    this.citations[index].authors = [];
                }
                this.citations[index].authors.push(JSON.parse(JSON.stringify(this.authorModel)));
            },
            removeAuthor: function (index, authorIndex) {
                if (confirm('{translate key="plugins.generic.citationManager.author.remove.question"}') !== true) {
                    return;
                }
                this.citations[index].authors.splice(authorIndex, 1);
            },
            toggleLoading: function () {
                let cssClass = 'citationManager-Hide';
                document.getElementById('citationManager-ScrollableDivEmpty').classList.toggle(cssClass);
                document.getElementById('citationManager-ScrollableDivValue').classList.toggle(cssClass);
                document.getElementById('citationManager-ScrollableDivLoading').classList.toggle(cssClass);
            },
            getCitationsHelper: function (arr) {
                let result = [];
                for (let i = 0; i < arr.length; i++) {
                    result[i] = { /**/ 'editRow': false};
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

                    // authors
                    let result = [];
                    for (let i = 0; i < this.workingAuthors.length; i++) {
                        let row = [];
                        row._data = this.workingAuthors[i];

                        let metadata = row._data.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR};
                        if (typeof metadata === 'string') {
                            row._data.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} = JSON.parse(metadata);
                        }
                        result.push(row);
                    }
                    this.authorsIn = result;

                }
                console.log(oldValue + ' > ' + newValue);
            },
        },
        created() {
            this.citationsHelper = this.getCitationsHelper(this.citations);
        }
    });
</script>
