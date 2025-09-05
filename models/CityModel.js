// models/CityModel.js

const { DataTypes } = require("sequelize");
const { v4: uuidv4 } = require('uuid');
const sequelize = require('../config/database');
const { lowercaseFields } = require("../utils/shared/formatters");

const CityModel = sequelize.define("City", {
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
  name: {
    type: DataTypes.STRING(100),
    allowNull: false,
    unique: {
      code: "must be unique",
      msg: "must be unique"
    },
    comment: "The official name of the city (e.g., Ouagadougou, Berlin).",
  },
}, {
  tableName: "city",
  timestamps: true, // adds createdAt and updatedAt
  // paranoid: true,   // adds deletedAt (for soft delete)
  hooks: {
    beforeSave: (CityModel) => lowercaseFields(CityModel, ["name"]),
  }
});

CityModel.beforeCreate((city) => {
  if (!city.guid) {
    city.guid = uuidv4();
  }
});

//use to format city response when responding to a data with this data
CityModel.prototype.toJSON = function () {
  const values = { ...this.get() };
  delete values.id;
  delete values.createdAt;
  delete values.updatedAt;
  delete values.deletedAt;
  delete values.countryId;
  return values;
}

module.exports = CityModel;

