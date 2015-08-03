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

var err_ct = 0;

request(url, (function(err, resp, body) {
    if (err) throw err;
    $ = cheerio.load(body);
    $('h5').each(function(team) {
        var teamurl = $(this).find('a').first().attr('href');
        var teamid = teamurl.split('/')[7]; //id is in the 7th place in the URL
        var teamname = $(this).text().trim();
        teamurl = ROSTER_PAGE_URL.replace(TEAM_ID_PLACEHOLDER, teamid)
        console.log(teamurl);
        console.log(teamname);
        player_request(url,teamurl);
    });
}))

var player_request = function(params, urldata) {
    console.log(urldata);
    request(urldata, (function(err_t, resp_t, body_t) {
        if (err_t) throw err_t;
        $ = cheerio.load(body_t);
        $('tr').each(function(row_num) {
            //messed up way to ignore the first 2 rows (col headers)
            if (row_num > 1) {
                var attributes = $(this).find('td').map(function() {
                    return connection.escape($(this).text());
                });
                var player_id = $(this).find('a').first().attr('href').split('/')[7];
                attributes = ['\'' + player_id + '\''].concat(attributes);
                var sql = 'INSERT INTO scraped_players_tbl (ESPN_id, jersey_no, fullname, pos, ht, wt, class, hometown)  values (' + attributes + ')';
                console.log(sql);
                connection.query(sql, function(sql_err, results) {
                    if (sql_err !== null) {
                        err_ct++;
                        console.log('Total errors: ' + err_ct);
                    }
                });

            }

        });
    }))
}
