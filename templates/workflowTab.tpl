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
                <td><strong>{translate key="context.context"}</strong></td>
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
                    <span v-for="(author, i) in citationManagerApp.authors" style="margin-right: 5px;">
                        <span class="citationManager-Tag">
                            {{ author.givenName[workingPublication.locale] }} {{ author.familyName[workingPublication.locale] }}
                        </span>
                        <a class="pkpButton citationManager-Button" target="_blank"
                           :class="(author.orcid)?'':'citationManager-Disabled'"
                           :href="author.orcid">iD</a>
                        <a class="pkpButton citationManager-Button" target="_blank"
                           :class="(author.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}.wikidata_id)?'':'citationManager-Disabled'"
                           :href="'{$url.wikidata}/' + author.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}.wikidata_id">WD</a>
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
            <span aria-hidden="true" class="pkpSpinner"></span>
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
                <tr v-for="(citation, i) in citationManagerApp.citationsHelper" class="citationManager-Row">
                    <td class="citationManager-ScrollableDiv-nr">{{ i + 1 }}</td>
                    <td class="citationManager-ScrollableDiv-parts">
                        <div>
                            <a :href="'{$url.doi}' + '/' + citation.doi"
                               v-show="!citation.editRow" target="_blank">{{ citation.doi }}</a>
                            <input id="doi-{{ i + 1 }}" placeholder="DOI" v-show="citation.editRow"
                                   v-model="citation.doi" class="citationManager-Input"/>
                            <a :href="citation.urn"
                               v-show="!citation.editRow" target="_blank">{{ citation.urn }}</a>
                            <input id="urn-{{ i + 1 }}" placeholder="URN" v-show="citation.editRow"
                                   v-model="citation.urn" class="citationManager-Input"/>
                            <a :href="citation.url"
                               v-show="!citation.editRow" target="_blank">{{ citation.url }}</a>
                            <input id="url-{{ i + 1 }}" placeholder="URL" v-show="citation.editRow"
                                   v-model="citation.url" class="citationManager-Input"/>
                        </div>
                        <div>
                            <div>
                                <span v-for="(author, j) in citation.authors">
                                    <span v-show="!citation.editRow"
                                          class="citationManager-Tag">{{ citation.authors[j].given_name }}</span>
                                    <input id="given_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Given name"
                                           v-show="citation.editRow"
                                           v-model="citation.authors[j].given_name"
                                           class="citationManager-Input"/>
                                    <span v-show="!citation.editRow"
                                          class="citationManager-Tag">{{ citation.authors[j].family_name }}</span>
                                    <input id="family_name-{{ i + 1 }}-{{ j + 1 }}" placeholder="Family name"
                                           v-show="citation.editRow"
                                           v-model="citation.authors[j].family_name"
                                           class="citationManager-Input"/>
                                    <input id="orcid-{{ i + 1 }}-{{ j + 1 }}" placeholder="Orcid"
                                           v-show="citation.editRow"
                                           v-model="citation.authors[j].orcid_id"
                                           class="citationManager-Input"/>
                                    <a class="pkpButton citationManager-Button" target="_blank"
                                       :class="(citation.authors[j].orcid_id)?'':'citationManager-Disabled'"
                                       :href="'{$url.orcid}' + '/' + citation.authors[j].orcid_id">iD</a>
                                    <a class="pkpButton" v-show="citation.editRow"
                                       v-on:click="citationManagerApp.removeAuthor(i, j)">
                                        <i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <br v-show="citation.editRow"/>
                                </span>
                                <a class="pkpButton" v-show="citation.editRow"
                                   v-on:click="citationManagerApp.addAuthor(i)">
                                    {translate key="plugins.generic.citationManager.author.add.button"}
                                </a>
                            </div>
                            <div>
                                <span v-show="!citation.editRow && !citation.isProcessed"
                                      class="citationManager-Tag">No information found</span>

                                <span v-show="!citation.editRow && citation.title"
                                      class="citationManager-Tag">{{ citation.title }}</span>
                                <input id="title-{{ i + 1 }}" placeholder="Title" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.title"/>

                                <span v-show="!citation.editRow && citation.journal_name"
                                      class="citationManager-Tag">{{ citation.journal_name }}</span>
                                <input id="venue_display_name-{{ i + 1 }}" placeholder="Venue" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.journal_name"/>

                                <span v-show="!citation.editRow && citation.publication_year"
                                      class="citationManager-Tag">{{ citation.publication_year }}</span>
                                <input id="publication_year-{{ i + 1 }}" placeholder="Year" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.publication_year"/>

                                <span v-show="!citation.editRow && citation.volume"
                                      class="citationManager-Tag">Volume {{ citation.volume }}</span>
                                <input id="volume-{{ i + 1 }}" placeholder="Volume" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.volume"/>

                                <span v-show="!citation.editRow && citation.issue"
                                      class="citationManager-Tag">Issue {{ citation.issue }}</span>
                                <input id="issue-{{ i + 1 }}" placeholder="Issue" v-show="citation.editRow"
                                       class="citationManager-Input" v-model="citation.issue"/>

                                <span v-show="!citation.editRow && citation.first_page"
                                      class="citationManager-Tag">Pages {{ citation.first_page }} - {{ citation.last_page }}</span>
                                <input id="first_page-{{ i + 1 }}" placeholder="First page" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.first_page"/>
                                <input id="last_page-{{ i + 1 }}" placeholder="Last page" v-show="citation.editRow"
                                       class="citationManager-Input"
                                       v-model="citation.last_page"/>
                            </div>
                        </div>
                        <div class="citationManager-RawText">{{ citation.raw }}</div>
                        <div>
                            <a class="pkpButton citationManager-Button" target="_blank"
                               :class="(citation.wikidata_id)?'':'citationManager-Disabled'"
                               :href="'{$url.wikidata}/' + citation.wikidata_id">Wikidata</a>
                            <a class="pkpButton citationManager-Button" target="_blank"
                               :class="(citation.wikidata_id)?'':'citationManager-Disabled'"
                               :href="'{$url.openAlex}/' + citation.openalex_id">OpenAlex</a>
                        </div>
                    </td>
                    <td class="citationManager-ScrollableDiv-actions">
                        <a v-show="!citation.editRow" @click="citationManagerApp.toggleEdit(i)" class="pkpButton"
                           :class="(!citationManagerApp.isPublished)?'':'citationManager-Disabled'">
                            <i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a v-show="citation.editRow" @click="citationManagerApp.toggleEdit(i)" class="pkpButton">
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
            metadataJournal: {$metadataJournal},
            authorModel: {$authorModel},
            metadataPublication: [],
            authorsIn: [],
            citationsHelper: [],
            publicationId: 0,
            submissionId: 0,                // workingPublication.submissionId
            citationsRaw: '',               // workingPublication.citationsRaw
            workingPublicationId: 0,        // workingPublication.id
            workingPublicationStatus: 0,    // workingPublication.status
            workingCitationsStructured: [], // workingPublication.citationsCitationManagerPlugin_CitationsStructured
            workingMetadataPublication: [], // workingPublication.CitationManagerPlugin_MetadataPublication
            workingAuthors: [],             // workingPublication.authors
        },
        computed: {
            authors: function () {
                let result = this.authorsIn;
                for (let i = 0; i < result.length; i++) {
                    let metadata = result[i].{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR};
                    if (typeof metadata === 'string') {
                        result[i].{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} = JSON.parse(metadata);
                    }
                }
                return result;
            },
            citations: function () {
                let result = JSON.parse(JSON.stringify(this.citationsHelper));
                for (let i = 0; i < result.length; i++) {
                    if (Object.hasOwn(result[i], 'editRow')) {
                        delete result[i]['editRow'];
                    }
                }
                return result;
            },
            isStructured: function () {
                return this.citationsHelper.length !== 0;
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
            }
        },
        methods: {
            clear: function () {
                if (confirm('{translate key="plugins.generic.citationManager.clear.question"}') !== true) return;

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
                        self.setCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];
                        for (let i = 0; i < self.authorsIn.length; i++) {
                            self.authorsIn[i] = self.authorsIn[i]._data;
                        }
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
                        self.setCitationsHelper(result['citations']);
                        self.authorsIn = result['authors'];
                        for (let i = 0; i < self.authorsIn.length; i++) {
                            self.authorsIn[i] = self.authorsIn[i]._data;
                        }
                    },
                    complete() {
                        self.toggleLoading();
                    }
                });
            },
            addAuthor: function (index) {
                if (this.citationsHelper[index].authors === null) {
                    this.citationsHelper[index].authors = [];
                }
                this.citationsHelper[index].authors.push(JSON.parse(JSON.stringify(this.authorModel)));
            },
            removeAuthor: function (index, authorIndex) {
                if (confirm('{translate key="plugins.generic.citationManager.author.remove.question"}') !== true) {
                    return;
                }
                this.citationsHelper[index].authors.splice(authorIndex, 1);
            },
            toggleEdit: function (index) {
                this.citationsHelper[index].editRow = !this.citationsHelper[index].editRow;
                if (this.citationsHelper[index].authors !== null) {
                    for (let i = 0; i < this.citationsHelper[index].authors.length; i++) {
                        let rowIsNull = true;
                        for (let key in this.citationsHelper[index].authors[i]) {
                            if (this.citationsHelper[index].authors[i][key] !== null) {
                                rowIsNull = false;
                            }
                        }
                        if (rowIsNull === true) {
                            this.citationsHelper[index].authors.splice(i);
                        }
                    }
                }
            },
            toggleLoading: function () {
                let cssClass = 'citationManager-Hide';
                document.getElementById('citationManager-ScrollableDivEmpty').classList.toggle(cssClass);
                document.getElementById('citationManager-ScrollableDivValue').classList.toggle(cssClass);
                document.getElementById('citationManager-ScrollableDivLoading').classList.toggle(cssClass);
            },
            setCitationsHelper: function (citations) {
                this.citationsHelper = [];
                for (let i = 0; i < citations.length; i++) {
                    let row = citations[i];
                    row.editRow = false;
                    this.citationsHelper.push(row);
                }
            }
        },
        watch: {
            workingPublicationId(newValue, oldValue) {
                this.publicationId = this.workingPublicationId;

                this.citationsHelper = [];
                if (this.workingCitationsStructured && this.workingCitationsStructured.length > 0) {
                    this.setCitationsHelper(JSON.parse(this.workingCitationsStructured));
                }

                this.metadataPublication = [];
                if (this.workingMetadataPublication && this.workingMetadataPublication.length > 0) {
                    this.metadataPublication = JSON.parse(this.workingMetadataPublication);
                }

                this.authorsIn = [];
                if (this.workingAuthors && this.workingAuthors.length > 0) {
                    for (let i = 0; i < this.workingAuthors.length; i++) {
                        let row = [];
                        row = this.workingAuthors[i];
                        let metadata = JSON.parse(JSON.stringify(this.authorModel));

                        if (Object.hasOwn(row, '{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR}')) {
                            if (row.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} !== null) {
                                metadata = row.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR};
                            }
                        }

                        if (typeof metadata === 'string') {
                            metadata = JSON.parse(row.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR});
                        }

                        row.{CitationManagerPlugin::CITATION_MANAGER_METADATA_AUTHOR} = metadata;

                        this.authorsIn.push(row);
                    }
                }
                console.log(oldValue + ' > ' + newValue);
            }
        },
        created() {
        }
    });
</script>
