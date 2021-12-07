/**
 * @file plugins/generic/optimetaCitations/js/submissionEditForm.js
 *
 * Copyright (c) 2021+ TIB Hannover
 * Copyright (c) 2021+ Gazi Yucel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class
 * @ingroup plugins_generic_optimetacitations
 *
 * @brief This script is used in submissionEditForm.tpl during article submission.
 */

var optimetaApp = new pkp.Vue({
	//el: 'optimetaCitationsApp',
	data: {
		citations: [
			{ edit : false, raw :  "Björk BC, Shen C, Laakso M (2016) A longitudinal study of independent scholar-published open access journals. PeerJ 4", pid : "https://doi.org/10.7717/peerj.1990", status : true },
			{ edit : false, raw :  "Brown J (2019) Crossref grant IDs: a global, open database of funding information and identifiers. Autumn 2019 euroCRIS Strategic Membership Meeting. Strategic Membership Meeting 2019 – Autumn, Münster, Nov 18-20, 2019. euroCRIS, 33 pp. URL: http://hdl.handle.net/11366/1249", pid : "", status : false },
			{ edit : false, raw :  "Bundesministerium für Bildung und Forschung (2020) Richtlinie zur Förderung von Projektenzur Beschleunigung der Transformation zu Open Access. Bundesanzeiger, Bundesministerium der Justiz und für Verbraucherschutz. URL:", pid : "https://www.bildung-forschung.digital/files/BAnz%20AT%2017.06.2020%20B3-1.pdf", status : true },
			{ edit : false, raw :  "Conlon M, et al. (2019) VIVO: a system for research discovery. The Journal of Open Source Software 4 (39).", pid : "https://doi.org/10.21105/joss.01182", status : true },
			{ edit : false, raw :  "Daquino M, Peroni S (2019) OCO, the OpenCitations Ontology. Accessed on: 2021-2-08.", pid : "https://opencitations.github.io/ontology/current/ontology.html.", status : true },
			{ edit : false, raw :  "Degbelo A, Kuhn W, Przibytzin H, Scheider S (2014) Content and context description-How linked spatio-temporal data enables novel information services for libraries. gis.Science 4: 138-149. URL:", pid : "https://www.semanticscholar.org/paper/Content-and-context-description-How-linked-data-for-Degbelo-Kuhn/b31adbb47d54bee3c020c3b374ca14e69d1186aa", status : true },
			{ edit : false, raw :  "Fenner M, Aryani A (2019) Introducing the PID Graph. (Version 1.0).", pid : "https://doi.org/10.5438/JWVF-8A66", status : true },
			{ edit : false, raw :  "Gil Y, David CH, Demir I, et al. (2016) Toward the Geoscience Paper of the Future: Best practices for documenting and sharing research from data to software to provenance. Earth and Space Science 3 (1): 388-415.", pid : "https://doi.org/10.1002/2015EA000136", status : true },
			{ edit : false, raw :  "Hagemann-Wilholt S, Plank M, Hauschke C (2020) ConfIDent – An Open Platform for FAIR Conference Metadata. In: Farace D, Frantzen J (Eds) Open Science Encompasses New Forms of Grey Literature. Twenty-First International Conference on Grey Literature, Hannover, 22/23.10.2019. TextRelease, Amsterdam, 5 pp.", pid : "https://doi.org/10.15488/9424", status : true },
			{ edit : false, raw :  "Hauschke C, Cartellieri S, Heller L (2018) Reference implementation for open scientometric indicators (ROSI). Research Ideas and Outcomes 4", pid : "https://doi.org/10.3897/rio.4.e31656", status : true },
			{ edit : false, raw :  "Heibi I, Peroni S, Shotton D (2019a) Software review: COCI, the OpenCitations Index of Crossref open DOI-to-DOI citations. Scientometrics 121 (2).", pid : "https://doi.org/10.1007/s11192-019-03217-6", status : true },
			{ edit : false, raw :  "Heibi I, Peroni S, Shotton D (2019b) Crowdsourcing open citations with CROCI. An analysis of the current status of open citations, and a proposal. URL:", pid : "https://arxiv.org/abs/1902.02534", status : true },
			{ edit : false, raw :  "Karl J (2019) Mining location information from life- and earth-sciences studies to facilitate knowledge discovery. Journal of Librarianship and Information Science 51 (4): 1007-1021.", pid : "https://doi.org/10.1177/0961000618759413", status : true },
			{ edit : false, raw :  "Karl JW, Herrick JE, Unnasch RS, Gillan JK, Ellis EC, Lutters WG, Martin LJ (2013) Discovering Ecologically Relevant Knowledge from Published Studies through Geosemantic Searching. BioScience 63 (8).", pid : "https://doi.org/10.1525/bio.2013.63.8.10", status : true },
			{ edit : false, raw :  "Katumba S, Coetzee S (2017) Employing Search Engine Optimization (SEO) Techniques for Improving the Discovery of Geospatial Resources on the Web. ISPRS International Journal of Geo-Information 6 (9): 284.", pid : "https://doi.org/10.3390/ijgi6090284", status : true },
			{ edit : false, raw :  "Kmoch A, Uuemaa E, Klug H, Cameron S (2018) Enhancing Location-Related Hydrogeological Knowledge. ISPRS Int. J. Geo-Inf 7 (4).", pid : "https://doi.org/10.3390/ijgi7040132", status : true },
			{ edit : false, raw :  "Konkol M, Kray C (2018) In-depth examination of spatiotemporal figures in open reproducible research. Cartography and Geographic Information Science 46 (9): 412-427.", pid : "https://doi.org/10.1080/15230406.2018.1512421", status : true },
			{ edit : false, raw :  "Lauscher A, Eckert K, Galke L, Scherp A, Rizvi STR, Ahmed S, Dengel A, Zumstein P, Klein A (2018) Linked Open Citation Database. Proceedings of the 18th ACM/IEEE on Joint Conference on Digital Libraries", pid : "https://doi.org/10.1145/3197026.3197050", status : true },
			{ edit : false, raw :  "Margulies J, Magliocca N, Schmill M, Ellis E (2016) Ambiguous Geographies: Connecting Case Study Knowledge with Global Change Science. Annals of the American Association of Geographers 106 (3): 572-596.", pid : "https://doi.org/10.1080/24694452.2016.1142857", status : true },
			{ edit : false, raw :  "Morrison H (2016) Small scholar-led scholarly journals: Can they survive and thrive in an open access future? Learned Publishing 29 (2).", pid : "https://doi.org/10.1002/leap.1015", status : true },
			{ edit : false, raw :  "Niers T, Nüst D (2020) Geospatial Metadata for Discovery in Scholarly Publishing. The 15th Munin Conference on Scholarly Publishing 2020. 15 Munin Conference on Scholarly Publishing 2020, November 17–19, 2020. Septentrio Conference Series", pid : "https://doi.org/10.7557/5.5590", status : true },
			{ edit : false, raw :  "Nüst D, Bache F, Bröring A, Sasch C, Jirka S (2010) Visualizing the Availability of Temporally Structured Sensor Data. In: Painho M, Santos MY, Pundt H (Eds) Short Paper Proceedings of the 13th AGILE International Conference on GIScience 2010. 13th AGILE International Conference on GIScience 2010, Guimarães, Portugal.", pid : "https://doi.org/10.31223/osf.io/jq5df", status : true },
			{ edit : false, raw :  "OJS-de.net (2021) Über OJS. Accessed on: 2021-2-05. ", pid : "https://ojs-de.net/ueber-ojs.", status : true },
			{ edit : false, raw :  "Peroni S, Shotton D (2019) Open Citation Identifier: Definition. Figshare.", pid : "https://doi.org/10.6084/m9.figshare.7127816", status : true },
			{ edit : false, raw :  "Prinčič A, Belliard F (2020) Towards a community-driven, open access university publisher. Research Information URL:", pid : "https://www.researchinformation.info/analysis-opinion/towards-community-driven-open-access-university-publisher", status : true },
			{ edit : false, raw :  "Scheider S, Degbelo A, Kuhn W, Przibytzin H (2014) Content and context description How linked spatio-temporal data enables novel information services for libraries. gis.Science 4: 138-149.", pid : "", status : false },
			{ edit : false, raw :  "Shapiro JT, Báldi A (2012) Lost locations and the (ir)repeatability of ecological studies. Frontiers in Ecology and the Environment 10 (5): 235-236.", pid : "https://doi.org/10.1890/12.WB.015", status : true },
			{ edit : false, raw :  "Shotton D (2010) CiTO, the Citation Typing Ontology. Journal of Biomedical Semantics 1 (Suppl 1).", pid : "https://doi.org/10.1186/2041-1480-1-S1-S6", status : true },
			{ edit : false, raw :  "Shotton D (2020) In-Text Reference Pointer Identifiers - InTRePIDs. PIDapalooza URL:", pid : "https://pidapalooza20.sched.com/event/XlAA", status : true },
			{ edit : false, raw :  "Visser M, van Eck NJ, Waltman L (2020) Large-scale comparison of bibliographic data sources: Scopus, Web of Science, Dimensions, Crossref, and Microsoft Academic. URL:", pid : "https://arxiv.org/abs/2005.10732", status : true },
			{ edit : false, raw :  "Wilkinson M, et al. (2016) The FAIR Guiding Principles for scientific data management and stewardship. Scientific Data 3", pid : "https://doi.org/10.1038/sdata.2016.18", status : true },
			{ edit : false, raw :  "Wolf B, Lindenthal T, Szerencsits M, Holbrook JB, Heß J (2013) Evaluating Research beyond Scientific Impact. How to Include Criteria for Productive Interactions and Impact on Practice and Society. GAIA - Ecological Perspectives for Science and Society 22 (2): 104-114.", pid : "https://doi.org/10.14512/gaia.22.2.9", status : true }
		]
	},
	methods: {
		helloVue() {
			window.alert('hello Vue');
		}
	}

});
