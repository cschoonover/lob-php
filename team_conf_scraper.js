var request = require('request');
var cheerio = require('cheerio');
var mysql = require('mysql2');

var connection = mysql.createConnection({
    user: 'uglycoffeecan',
    database: 'c9'
});

var teams_url = 'http://espn.go.com/mens-college-basketball/standings';

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
            var confid = confurl.split('/')[8]; //to get espn id
            var confname = confurl.split('/')[11]; //quick n dirty
            var hasdivisions = $(this).find('.colhead').length > 1;

            var confsql = createInsertSQL('conference_tbl (conferenceid, name, has_divisions)', [confid, confname, hasdivisions]);

            //console.log(confsql);
            connection.query(confsql, function(sql_err, results) {
                if (sql_err !== null) {
                    err_ct++;
                    console.log('Total errors: ' + err_ct);
                }
            });
            //bi is the class on the 'expanded standings' link
            $(this).find('a:not(.bi)').each(function(team_ct) {
                var teamurl = $(this).attr('href');
                team_request(confid, teamurl);
            });
        });
    }))
}

var team_request = function(confid, teamurl) {
    // request(urldata, (function(err_t, resp_t, body_t) {
    //     if (err_t) throw err_t;
    //     $ = cheerio.load(body_t);
    var espnid = teamurl.split('/')[7];
    var teamname = teamurl.split('/')[8];
    if (Number(espnid) < 1) //don't insert junk data
    {
        console.log(teamname);
    }
    else {
        var teamsql = createInsertSQL('team_tbl (ESPN_id, name)', [espnid, teamname]);
        var teamid = -1;
        connection.query(teamsql, function(sql_err, results) {
            if (sql_err !== null) {
                console.log(teamsql);
                console.log(sql_err);
            }
            teamid = results['insertId'];
            var teamconf_rel_sql = createInsertSQL('conference_to_team_tbl (teamid, conferenceid)', [teamid, confid]);
            connection.query(teamconf_rel_sql, function(sql_err, results) {
                if (sql_err !== null) {
                    console.log(teamconf_rel_sql);
                    console.log(sql_err);
                }
            });
        });
    }
    // }))
}

connection.query("DELETE FROM conference_tbl;", function(sql_err, results) {
    if (sql_err !== null) {
        err_ct++;
        console.log(sql_err);
        console.log('Total errors: ' + err_ct);
    }
});

connection.query("DELETE FROM team_tbl;", function(sql_err, results) {
    if (sql_err !== null) {
        err_ct++;
        console.log(sql_err);
        console.log('Total errors: ' + err_ct);
    }
});

connection.query("DELETE FROM conference_to_team_tbl;", function(sql_err, results) {
    if (sql_err !== null) {
        err_ct++;
        console.log(sql_err);
        console.log('Total errors: ' + err_ct);
    }
});

request_all();

