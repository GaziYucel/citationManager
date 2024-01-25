// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
import './commands'

import '@foreachbe/cypress-tinymce'

// Alternatively you can use CommonJS syntax:
// require('./commands')

Cypress.config('defaultCommandTimeout', 10000);

Cypress.on('uncaught:exception', (err, runnable) => {
    console.log(`********* Uncaught Exception: ${JSON.stringify(err)}`);

    return false;
});

// https://stackoverflow.com/a/55168680
Cypress.on('before:browser:launch', (browser = {}, args) => {
    if (browser.name === 'chrome') {
        args.push('--remote-debugging-port=9222')
        // whatever you return here becomes the new args
        return args
    }
});

