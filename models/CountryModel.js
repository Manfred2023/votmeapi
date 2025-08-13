// models/CountryModel.js
const { DataTypes } = require('sequelize');
const { v4: uuidv4 } = require('uuid');
const sequelize = require('../config/database');

const CountryModel = sequelize.define('Country', {
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
            code : "must be unique",
            msg: "must be unique"
        },
        comment:"identifiant externe de chaque object"
    },
    name: {
        type: DataTypes.STRING,
        allowNull: false,
        unique: {
            code : "must be unique",
            msg: "must be unique"
        },
        comment:"nom en anglais"
    },
    iso2: {
        type: DataTypes.STRING,
        allowNull: false,
        unique: {
            code : "must be unique",
            msg: "must be unique"
        },
        comment:"identifiant a 2 charactere de chaque pays ex CM pour le cameroun"
    },
    iso3: {
        type: DataTypes.STRING,
        allowNull: false,
        unique: {
            code : "must be unique",
            msg: "must be unique"
        },
        comment:"identifiant a 3 charactere de chaque pays ex CMR pour le cameroun"
    },
    dialCode: {
        type: DataTypes.STRING,
        allowNull: false,
        comment : "code d'appel de chaque pays"
    },
}, {
    tableName: 'country',
    timestamps: true,
});

CountryModel.beforeCreate((country) => {
    if (!country.guid) {
        country.guid = uuidv4();
    }
});

CountryModel.prototype.toJSON = function () {
    const values = { ...this.get() };
    delete values.id;
    delete values.createdAt;
    delete values.updatedAt;
    return values;
};

module.exports = CountryModel;
