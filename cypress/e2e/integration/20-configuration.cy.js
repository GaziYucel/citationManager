/**
 * @file cypress/tests/integration/configuration.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */

describe('OPTIMETA Citations Plugin Configuration', function () {

  it('Disable Plugin', function () {
    cy.login('admin', 'admin', Cypress.env('contextPath'));
    cy.get('nav[class="app__nav"] a:contains("Website")').click();
    cy.get('button[id="plugins-button"]').click();
    // disable plugin if enabled
    cy.get('input[id^="select-cell-optimetacitationsplugin-enabled"]')
      .then($btn => {
        if ($btn.attr('checked') === 'checked') {
          cy.get('input[id^="select-cell-optimetacitationsplugin-enabled"]').click();
          cy.get('div[class*="pkp_modal_panel"] button[class*="pkpModalConfirmButton"]').click();
          cy.get('div:contains(\'The plugin "Optimeta Citations Plugin" has been disabled.\')');
        }
      });
  });

  it('Enable Plugin', function () {
    cy.login('admin', 'admin', Cypress.env('contextPath'));
    cy.get('nav[class="app__nav"] a:contains("Website")').click();
    cy.get('button[id="plugins-button"]').click();
    // Find and enable the plugin
    cy.get('input[id^="select-cell-optimetacitationsplugin-enabled"]').click();
    cy.get('div:contains(\'The plugin "Optimeta Citations Plugin" has been enabled.\')');
  });

  it('Configure Plugin', function () {
    cy.login('admin', 'admin', Cypress.env('contextPath'));
    cy.get('nav[class="app__nav"] a:contains("Website")').click();
    cy.get('button[id="plugins-button"]').click();

    // Open the settings form
    cy.get('tr[id="component-grid-settings-plugins-settingsplugingrid-category-generic-row-optimetacitationsplugin"] a[class="show_extras"]').click();
    cy.get('a[id^="component-grid-settings-plugins-settingsplugingrid-category-generic-row-optimetacitationsplugin-settings-button"]').click();

    // Fill out settings form
    // Open Citations
    cy.get('form[id="optimetaCitationsSettings"] input[name^="OptimetaCitations_Open_Citations_Url"]')
        .clear()
        .type('https://opencitations.url');
    cy.get('form[id="optimetaCitationsSettings"] input[name^="OptimetaCitations_Open_Citations_Token"]')
        .clear()
        .type('opencitations-token');
    // Wikidata
    cy.get('form[id="optimetaCitationsSettings"] input[name^="OptimetaCitations_Wikidata_Username"]')
      .clear()
      .type('wikidata-username');
    cy.get('form[id="optimetaCitationsSettings"] input[name="OptimetaCitations_Wikidata_Password"]')
      .clear()
      .type('wikidata-password');
    cy.get('form[id="optimetaCitationsSettings"] input[name="OptimetaCitations_Wikidata_Api_Url"]')
      .clear()
      .type('https://wikidata.url');

    // submit settings form
    cy.get('form[id="optimetaCitationsSettings"] button[id^="submitFormButton"]').click();
    cy.waitJQuery();
  });

  it('Enable References', function () {
    cy.login('admin', 'admin', Cypress.env('contextPath'));
    cy.get('nav[class="app__nav"] a:contains("Workflow")').click();
    cy.get('button[id="metadata-button"]').click();
    cy.get(':nth-child(10) > .pkpFormField__control > .pkpFormField--options__option > .pkpFormField--options__input').check();
    //cy.get('input[class="pkpFormField--options__input pkpFormField--metadata__submissionInput"]').eq(2).click();
    cy.get(':nth-child(10) > .pkpFormField__control > .pkpFormField--metadata__submissionOptions > :nth-child(2) > .pkpFormField--options__input').click();

    cy.get('div[class="pkpFormPage__footer"] button:contains("Save"):visible').click();
    cy.get('span:contains("Saved")');
  });

  it('Has the citations management UI in the third submissions step', function () {
    cy.login('admin', 'admin', Cypress.env('contextPath'));

    cy.get('a:contains("Submissions")').click();
    cy.get('div#myQueue a:contains("New Submission")').click();
    cy.get('input[id^="checklist-"]').click({ multiple: true });
    cy.get('input[id="privacyConsent"]').click();
    cy.get('button.submitFormButton').click();
    cy.wait(2000);
    cy.get('button.submitFormButton').click();
    
    // checking contents
    cy.get('#optimetaCitations').should('exist');
  });
  
});
