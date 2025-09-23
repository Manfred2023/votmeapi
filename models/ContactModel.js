// models/ContactModel.js

const { DataTypes } = require('sequelize');
const { v4: uuidv4 } = require('uuid');
const sequelize = require('../config/database');
const { lowercaseFields } = require("../utils/shared/formatters");

const ContactModel = sequelize.define("Contact", {
    id: {
        type: DataTypes.INTEGER.UNSIGNED,
        primaryKey: true,
        autoIncrement: true,
    },
    guid: {
        type: DataTypes.UUID,
        allowNull: false,
        defaultValue: DataTypes.UUIDV4,
        unique: {
            code: "must be unique",
            msg: "must be unique"
        },
        comment: "external identifier of each object"
    },
    firstname: {
        type: DataTypes.STRING(100),
        allowNull: false,
        validate: {
            notEmpty: { msg: "First name is required" },
        },
    },
    lastname: {
        type: DataTypes.STRING(100),
        allowNull: true,
        // validate: {
        //     notEmpty: { msg: "Last name is required" },
        // },
    },
    mobile: {
        type: DataTypes.STRING(20),
        allowNull: false,
        unique: true,
        validate: {
            notEmpty: { msg: "Mobile number is required" },
            is: {
                args: /^[0-9+\-()\s]*$/, // simple regex for phone numbers
                msg: "Invalid phone number format",
            },
        },
    },
    email: {
        type: DataTypes.STRING(150),
        allowNull: true,
        unique: true,
        validate: {
            isEmail: { msg: "Must be a valid email address" },
        },
    },
    location: {
        type: DataTypes.STRING(255),
        allowNull: true,
    },
}, {
    tableName: "contact",
    timestamps: true,
    hooks: {
        beforeSave: (ContactModel) => lowercaseFields(ContactModel, ["firstname", "lastname", "mobile"]),

    }
});

ContactModel.beforeCreate((contact) => {
    if (!contact.guid) {
        contact.guid = uuidv4();
    }
});


ContactModel.prototype.toJSON = function () {
    const values = { ...this.get() };
    delete values.id;
    delete values.createdAt;
    delete values.updatedAt;
    delete values.deletedAt;
    delete values.cityId;
    return values;
}


module.exports = ContactModel;