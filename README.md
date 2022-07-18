[![OPTIMETA Logo](images/optimeta_logo_full_bg_white.png)](https://projects.tib.eu/optimeta/en/)

OptimetaCitations plugin
---------------------
[![Project Status: WIP – Initial development is in progress, but there has not yet been a stable, usable release suitable for the public.](https://www.repostatus.org/badges/latest/wip.svg)](https://www.repostatus.org/#wip)

The OPTIMETA project strengthens the Open Access (OA) ecosystem by capturing articles' citation information and spatiotemporal metadata and transferring these as open data from OA journals to openly accessible data sources. This work contributes to the metadata commons by adding crucial functionalities to [Open Journal Systems](https://pkp.sfu.ca/ojs/) (OJS), which is the most widely used free and open source software for publishing scientific journals. In this way, the aforementioned metadata can be collected, used by journals and shared with others to enable novel metascience studies and discovery of research artefacts. By combining both open source and open data, journals will be able to offer their respective communities innovative services for linking articles in a semantically meaningful way via geodata and to contribute to open citation graphs, such as COCI. Both geodata and citation data give publications better visibility and, thus, will increase findability, which in turn will increase the attraction of OA journals as publication venues.

On the technical side, this project aims to develop two OJS plugins for spatiotemporal and citation metadata, respectively. With these plugins, authors and editors can create or extract the geographic and citation data during an article's publication process, validate it if necessary and transfer it to open, freely licensed data sources, such as Wikidata. Independent OA journals run by the scientific community, professional organisations or universities usually have few human and financial resources. Therefore, OPTIMETA will rely on automatic and semi-automatic processes for efficient and reliable gathering of metadata, suitable for even non-experts to use. In addition, we will focus on making the software user-friendly and sustainable, as well as adapting the application to the needs of the user groups (authors, editors, OJS hosters and OA publishers). This is ensured by a user-centred design and iterative, agile development processes in constant exchange with the future users of the project results and the OJS community and, in particular, with a group of partner journals.

Download & Installation
---------------------
1. Download the plugin from https://github.com/TIBHannover/optimetaCitations and unzip the folder into `/plugins/generic/optimetaCitations` in OJS
2. Activate the plugin in the OJS plug-in settings
3. Fill in your authentication info (such as username/password or tokens) for all Open Access websites where you are registered (registration is on external sites, see below)

Screenshot(s)
---------------------
![OPTIMETA screenshot add submission](.project/screenshots/optimeta-citations-submission-edit.gif)

Tests
---------------------

```bash
npm install

# start containers
npm run-script test_compose

# run tests with UI (new console)
npm run-script test_open
```

Contribute
---------------------
All help is welcome: asking questions, providing documentation, testing, or even development.

Please note that this project is released with a [Contributor Code of Conduct](CONDUCT.md). By participating in this project you agree to abide by its terms.

License
---------------------
This project is published under GNU General Public License, Version 3.

Registering at external sites
---------------------

### Registering for OpenCitations.org CROCI

Depositing at OpenCitations will be done through GitHub issues of OpenCitations. For this you need a GitHub account, if you have none please register one through https://github.com/signup.

1. Login at https://github.com and navigate to https://github.com/settings/tokens
2. Click "Generate new token" button at the right top
3. At the input field "Note" typ in "OpenCitations CROCI"
4. Select "No expiration" at Expiration selectbox
5. Check the checkbox "public_repo"; leave all other checkboxes unchecked
6. Click on the button "Generate token"
7. You will be provided the token; save this token, as you will not shown this again
8. Login to your OJS with an administrator account
9. Navigate to Settings > Website > Plugins and find "Optimeta Citations Plugin" on the page
10. Click on the arrow at the left and click "Settings"
11. At Owner field, fill in "GaziYucel"
12. At Resository field, fill in  "open_citations_croci_depot"
13. Fill in your token, which you generated above
14. Click Save

