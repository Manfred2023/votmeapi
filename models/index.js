// Centralise model relationships

const Country = require('./CountryModel');
const City = require('./CityModel');

//Define relationships
// Country 1 --- N City
Country.hasMany(City, { foreignKey: "countryId", as: "cities", onDelete: "CASCADE" });
City.belongsTo(Country, { foreignKey: "countryId", as: "country" });

// // User 1 --- N Product (example)
// User.hasMany(Product, { foreignKey: "userId", as: "products" });
// Product.belongsTo(User, { foreignKey: "userId", as: "owner" });

module.exports = {
    Country,
    City
}