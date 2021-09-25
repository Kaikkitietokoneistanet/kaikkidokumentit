'use strict';

const Hapi = require('@hapi/hapi');
const { MongoClient } = require('mongodb');

const { newDocument } = require('./document')

const init = async () => {
    const url = process.env.MONGO_URL;
    const client = new MongoClient(url);

    const dbName = 'SimpleDocument';

    await client.connect();

    const db = client.db(dbName);
    const collection = db.collection('documents');

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
            console.log(request.payload.owner);

            if ("owner" in request.payload && "content" in request.payload && "name" in request.payload) {
                return newDocument(
                    request.payload.name, 
                    request.payload.content, 
                    request.payload.owner, 
                    collection
                );
            } else {
                let data = {
                    "error": "Owner, content or name was not found in request JSON."
                };

                return h.response(data).code(400);
            }
        }
    });

    await server.start();
    console.log('Server running on %s', server.info.uri);
};

process.on('unhandledRejection', (err) => {

    console.log(err);
    process.exit(1);
});

init();