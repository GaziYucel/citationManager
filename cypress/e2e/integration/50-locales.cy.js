/**
 * @file cypress/tests/integration/html_head.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */

describe('OPTIMETA Citation Plugin Locales', function () {

  before(() => {
    cy.login('admin', 'admin', Cypress.env('contextPath'));

    cy.get('nav[class="app__nav"] a:contains("Website")').click();
    cy.get('#setup-button').click();
    cy.get('#languages-button').click();

    cy.get('h4').should('contain', 'Languages');
    cy.get('input[id^=select-cell-de_DE-uiLocale').check();
    cy.get('input[id^=select-cell-es_ES-uiLocale').check();
    cy.get('input[id^=select-cell-nl_NL-uiLocale').check();
    cy.get('input[id^=select-cell-tr_TR-uiLocale').check();

    cy.logout();
  });

  beforeEach(() => {
    cy.login('aauthor');
    cy.get('a:contains("aauthor")').click();
    cy.get('a:contains("Dashboard"), a:contains("Panel de control"), a:contains("Tableau de bord")').click();
  });

  afterEach(() => {
    cy.get('.pkpDropdown > .pkpButton').click();
    cy.get('a:contains("English")').click();
    cy.logout();
  });

  it('Has the German headline in submission and the frontend if language is enabled for the UI', function () {
    this.skip();

    cy.get('.pkpDropdown > .pkpButton').click();
    cy.get('a:contains("Deutsch")').click();

    //cy.get('h1').should('contain', 'Einreichungen');
    //cy.get('div#myQueue a:contains("Neue Einreichung")').click();
    //cy.get('input[id^="checklist-"]').click({ multiple: true });
    //cy.get('input[id="privacyConsent"]').click();
    //cy.get('button.submitFormButton').click();
    //cy.wait(2000);
    //cy.get('button.submitFormButton').click();
    //cy.wait(2000);
    //cy.contains('#submitStep3Form', /Zeit und Ort/);
    //cy.visit('/');
    //cy.get('#navigationPrimary > :nth-child(1) > a').click();
    //cy.contains('.pkp_structure_main', /Zeiten \& Orte/);
  });

});

describe('OPTIMETA Citation Plugin Locale Files', function () {

  it('Has the same number of entries and no erorrs in the locale files', function () {
    const reference = 'en_US';

    cy.readFile('locale/' + reference + '/locale.po').then((text) => {
      var count = text.match(/msgid/g).length - 1; // adjust for metadata field

      cy.task("readdir", { path: 'locale/' }).then((folders) => {
        var locales = folders;
        locales.forEach((l) => {
          cy.exec('msgfmt --statistics locale/' + l + '/locale.po', { failOnNonZeroExit: false }).then((result) => {
            expect(result.code, 'Validation of locale file ' + l + ': ' + result.stderr).to.eq(0);
            expect(result.stderr, 'in locale ' + l).to.contain(count + ' translated messages');
          });
        });
      });
    });
  });

});
