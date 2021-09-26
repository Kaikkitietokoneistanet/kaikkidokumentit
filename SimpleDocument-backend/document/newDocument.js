const { v4: uuidv4 } = require('uuid');

/**
 * @name - document name
 * @content - document content
 * @owner - document owner
 * @collection - mongodb collection
 */
module.exports = newDocument = async (name, content, owner, collection) => {
    let uuid= uuidv4()
    
    const insertResult = await collection.insertOne({
        uuid,
        name, 
        content,
        owner 
    });

    return {
        uuid
    };
}