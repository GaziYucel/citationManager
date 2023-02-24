/**
 * @file cypress/e2e/integration/21-configuration-enable-references.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst, Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */

describe('Enable builtin references', function () {

  it('Enable References', function () {
    cy.login('admin');
    cy.get('a:contains("admin")').click();
    cy.get('a:contains("Dashboard")').click();
    cy.wait(2000);

    cy.get('nav[class="app__nav"] a:contains("Workflow")').click();
    cy.get('button[id="metadata-button"]').click();
    cy.get(':nth-child(10) > .pkpFormField__control > .pkpFormField--options__option > .pkpFormField--options__input').check();
    //cy.get('input[class="pkpFormField--options__input pkpFormField--metadata__submissionInput"]').eq(2).click();
    cy.get(':nth-child(10) > .pkpFormField__control > .pkpFormField--metadata__submissionOptions > :nth-child(2) > .pkpFormField--options__input').click();

    cy.get('div[class="pkpFormPage__footer"] button:contains("Save"):visible').click();
    cy.get('span:contains("Saved")');
  });

});
