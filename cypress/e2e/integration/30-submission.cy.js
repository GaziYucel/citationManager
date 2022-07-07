/**
 * @file cypress/tests/integration/configuration.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */

describe('OPTIMETA Citations in Submission', function () {

  var submission;

  before(function () {
    submission = {
      id: 0,
      //section: 'Articles',
      prefix: '',
      title: 'Hanover is nice',
      subtitle: 'It really is',
      abstract: 'The city of Hanover is really nice, because it is home of the TIB.',
      timePeriod: '2022-01-01 - 2022-12-31',
      issue: '1'
    };
  });

  it('Parses references', function () {
    cy.login('aauthor');
    cy.get('a:contains("aauthor")').click();
    cy.get('a:contains("Dashboard")').click();

    cy.get('a:contains("New Submission"):visible').click();

    cy.get('input[id^="checklist-"]').click({ multiple: true });
    cy.get("body").then($body => {
      cy.get('input[id=userGroupId]').then($input => {
        if ($input.is(':visible')) {
          cy.get('input[id=userGroupId]').click();
        } // else: user is just an author and selection is not visible
      });
    });
    cy.get('input[id="privacyConsent"]').click();
    cy.get('button.submitFormButton').click();
    cy.wait(2000);
    cy.get('button.submitFormButton').click();
    cy.wait(2000);

    cy.fixture('references.txt').then((references) => {
      cy.get('textarea[id^="citationsRaw"]').clear().type(references);
    });

    cy.get('#buttonProcess').click();
    cy.on('window:confirm', () => true);
    cy.wait(30000);
    cy.get('.optimetaRow').should('have.length', 3);
  });

});