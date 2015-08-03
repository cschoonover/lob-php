var request = require('request');
var cheerio = require('cheerio');
var mysql = require('mysql2');

var connection = mysql.createConnection({
    user: 'uglycoffeecan',
    database: 'c9'
});

var teams_url = 'http://espn.go.com/mens-college-basketball/standings';

var bpi_url = 'http://espn.go.com/mens-college-basketball/bpi/_/teamid/';

var err_ct = 0;

var createInsertSQL = function(statement, values){
  return 'INSERT INTO ' + statement + ' VALUES (' + connection.escape(values) + ');'; 
};

var request_all = function() {
    request(teams_url, (function(err, resp, body) {
        if (err) throw err;
        $ = cheerio.load(body);
        $('.mod-table').each(function(conf_ct) {
            var confurl = $(this).find('.bi').first().attr('href');


            //bi is the class on the 'expanded standings' link
            $(this).find('a:not(.bi)').each(function(team_ct) {
                var teamurl = $(this).attr('href');
                team_request( teamurl);
            });
        });
    }))
}

var team_request = function( teamurl) {

    var espnid = teamurl.split('/')[7];
    var urldata = bpi_url + espnid;
    request(urldata, (function(err_t, resp_t, body_t) {
        if (err_t) throw err_t;
        $ = cheerio.load(body_t);
        if (Number(espnid) < 1) //don't insert junk data
        {
            console.log(espnid);
        }
        else {
            // var teamsql = createInsertSQL('espn_bpi_tbl (ESPN_id, bpi)', [espnid, bpi]);
            // connection.query(teamsql, function(sql_err, results) {
            //     if (sql_err !== null) {
            //         console.log(teamsql);
            //         console.log(sql_err);
            //     }
            console.log($('td:nth-child(1)').text());
        }
    }));

}



request_all();

