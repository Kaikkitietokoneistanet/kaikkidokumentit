'use strict';

const Hapi = require('@hapi/hapi');
const { newDocument } = require('./document')

const init = async () => {

    const server = Hapi.server({
        port: 3000,
        host: '0.0.0.0'
    });

    // TODO: switch to routes folder

    /**
     * Create new document
     */
    server.route({
        method: 'POST',
        path: '/',
        handler: (request, h) => {
            newDocument()
        }
    });

    await server.start();
    console.log('Server running on %s', server.info.uri);
};

process.on('unhandledRejection', (err) => {

    console.log(err);
    // process.exit(1);
});

init();