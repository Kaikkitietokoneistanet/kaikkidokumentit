const { ObjectId } = require("mongodb").ObjectId;

/**
 * @uuid - document uuid
 * @collection - mongodb collection
 */
 module.exports = getDocumentByUuid = async (uuid, collection) => {
    // TODO: validate uuid
    let document = await collection.find({ uuid }).toArray();

    return {
        "document": document
    };
}