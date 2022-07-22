/**
 * @file cypress/tests/integration/configuration.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst, Gazi Yücel
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */

describe('OPTIMETA Citations in Submission', function () {

  var submission;

  before(function () {
    submission = {
      id: 0,
      //section: 'Articles',
      prefix: 'OPTIMETA',
      title: 'Strengthening the Open Access publishing system through open citations and spatiotemporal metadata',
      subtitle: 'open access publishing, open research information, geospatial metadata, open citations',
      abstract: 'The BMBF project OPTIMETA aims to strengthen the Open Access publishing system by connecting open citations and spatiotemporal metadata from open access journals with openly accessible data sources. For this purpose, we will extend Open Journal Systems (OJS) to give it functionalities for collecting and distributing open data by developing two OJS plugins for capturing citation networks and articles\' spatial and temporal properties as machine-readable and accessible metadata. We will ensure the target group-orientated design of the plugins by performing a comprehensive needs analysis for key stakeholders: the editors or operators of OA journals and the researchers, as authors and readers of articles. The developments will be designed and tested in cooperation with several independent journals and OA publishers. Overall, OPTIMETA supports the attraction of independent OA journals as publication venues by substantially improving the discoverability and visibility of OA publications through enrichment and interlinking of article metadata.',
      //timePeriod: '2022-01-01 - 2022-12-31',
      issue: '1'
    };
  });

  it('Submit New Submission up to deposit to external site', function() {

    cy.log('Login');
    cy.login('aauthor');
    cy.get('a:contains("aauthor")').click();

    cy.log('Go to Dashboard and Submission Wizard');
    cy.get('a:contains("Dashboard")').click();

    cy.log('New Submission');
    cy.get('a:contains("New Submission"):visible').click();

    cy.log('Step 1 Start');

    cy.log('Submission Requirements');
    cy.get('input[id^="checklist-"]').click({ multiple: true });

    cy.log('Submit As');
    cy.get("body").then(($body) => {
      cy.get('input[id=userGroupId]').then($input => {
        if ($input.is(':visible')) {
          cy.get('input[id=userGroupId]').click();
        } // else: user is just an author and selection is not visible
      });
    });

    cy.log('Privacy Consent');
    cy.get('input[id="privacyConsent"]').click();

    cy.log('Save and continue');
    cy.get('#submitStep1Form').within(() => {
      cy.get('button.submitFormButton').click();
    });
    cy.wait(2000);

    cy.log('Step 2 Upload Submission');

    cy.log('Save and continue');
    cy.get('#submitStep2Form').within(() => {
      cy.get('button.submitFormButton').click();
    });
    cy.wait(2000);

    cy.log('Step 3 Enter Metadata');

    cy.log('Title');
    cy.get('input[id^="title"]').clear().type(submission.title);

    cy.log('Subtitle');
    cy.get('input[id^="subtitle"]').clear().type(submission.subtitle);

    cy.log('Abstract');
    cy.get('iframe')
        .then(($iframe) => {
          const $body = $iframe.contents().find('body')
          cy.wrap($body)
              .find('p')
              .type(submission.abstract);
        });
    // cy.get('textarea[id^="abstract"]').clear().type(submission.abstract);

    cy.log('CitationsRaw');
    cy.fixture('references.txt').then((references) => {
      cy.get('textarea[id^="citationsRaw"]').clear().type(references);
    });

    cy.log('Process');
    cy.get('#buttonProcess').click();
    cy.on('window:confirm', () => true);
    cy.wait(30000);
    cy.get('.optimetaRow').should('have.length', 3);
    cy.wait(2000);

    cy.log('Save and continue');
    cy.get('#submitStep3Form').within(() => {
      cy.get('button.submitFormButton').click();
    });
    cy.wait(2000);

    cy.log('Step 4 Confirmation');

    cy.log('Finish Submission');
    cy.get('#submitStep4Form').within(() => {
      cy.get('button.submitFormButton').click();
    });
    cy.wait(2000);

    cy.log('Confirm > OK');
    cy.get('button.ok.pkpModalConfirmButton').click();
    cy.wait(30000);

    cy.log('Step 5 Next Steps');

    cy.log('Review this submission');
    cy.get('a:contains("Review this submission"):visible').click();
    cy.wait(2000);

    cy.log('todo: work it out until deposit');
  });

});
