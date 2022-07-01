/**
 * @file cypress/tests/integration/10-installation.cy.js
 *
 * Copyright (c) 2022 OPTIMETA project
 * Copyright (c) 2022 Daniel Nüst
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Based on file cypress/tests/data/10-Installation.spec.js
 *
 */

describe('OPTIMETA Citations Plugin Installation', function () {

  it('Installs the software', function () {
    cy.install();
  });

  it('Adds a journal', function () {
    cy.createContext();
  });

  it('Adds issues to the journal', function () {
    cy.createIssues();
  });

  it('Adds test users', function () {
    cy.register({
      'username': 'aauthor',
      'givenName': 'Augusta',
      'familyName': 'Author',
      'affiliation': 'University of Research',
      'country': 'Germany',
    });
    cy.logout();

    let editor = {
      'username': 'eeditor',
      'givenName': 'Edd',
      'familyName': 'Editor',
      'country': 'Germany',
      'affiliation': 'University of Science',
      'roles': ['Journal editor']
    }

    cy.login('admin', 'admin');
    cy.get('a:contains("admin"):visible').click();
    cy.get('a:contains("Dashboard")').click({ force: true });
    cy.get('a:contains("Users & Roles")').click();
    cy.createUser(editor);
  });

});
