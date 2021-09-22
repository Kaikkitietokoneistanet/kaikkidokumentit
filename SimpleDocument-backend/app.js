const Hapi = require('@hapi/hapi');

const server = Hapi.server({ load: { sampleInterval: 1000 } });

