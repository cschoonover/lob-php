var mysql      = require('mysql2');
var connection = mysql.createConnection({ user:'uglycoffeecan', database: 'c9'});

connection.query('SELECT * from teams as test1', function(err, rows) {
  console.log(rows);
});
