var request = require('request');
var cheerio = require('cheerio');
var mysql = require('mysql2');

var connection = mysql.createConnection({
    user: 'uglycoffeecan',
    database: 'c9'
});

var url = 'http://espn.go.com/mens-college-basketball/teams';
var ROSTER_PAGE_URL = 'http://espn.go.com/mens-college-basketball/team/roster/_/id/<TEAM_ID>/'
var TEAM_ID_PLACEHOLDER = '<TEAM_ID>';
var INSERT_STATEMENT = 'INSERT INTO scraped_players_tbl (ESPN_id, fullname, pos, ht, wt, class, hometown) values (?)';

// request(url, (function(err, resp, body) {
//     if (err) throw err;
//     $ = cheerio.load(body);
//     $('h5').each(function(team) {
//         var teamurl = $(this).find('a').first().attr('href');
//         var teamid = teamurl.split('/')[7]; //id is in the 7th place in the URL
//         var teamname = $(this).text().trim();
//         teamurl = ROSTER_PAGE_URL.replace(TEAM_ID_PLACEHOLDER,teamid)
//         console.log(teamurl);
//         console.log(teamname);
//         // request(teamurl, (function(err1, resp1, body1) {
//         //     if (err1) throw err1;
//         //     $ = cheerio.load(body1);
//         //     console.log($('title').first().text());

//         // }))
//     });
// }))

request('http://espn.go.com/mens-college-basketball/team/roster/_/id/2011/', (function(err_t, resp_t, body_t) {
    if (err_t) throw err_t;
    $ = cheerio.load(body_t);
    $('tr').each(function(row_num) {

        //messed up way to ignore the first 2 rows (col headers)
        if (row_num > 1) {
            var attributes = $(this).find('td').map(function (){ return connection.escape($(this).text());});
            var player_id = $(this).find('a').first().attr('href').split('/')[7];
            attributes = ['\''+player_id+'\''].concat(attributes);
            var sql = 'INSERT INTO scraped_players_tbl (ESPN_id, jersey_no, fullname, pos, ht, wt, class, hometown)  values (' + attributes+ ')';
            console.log(sql);
            
            //console.log(attributes);
            
            connection.query(sql ,function(sql_err , results) {
                console.log(sql_err);
                console.log(results);
            });

        }

    });
}))