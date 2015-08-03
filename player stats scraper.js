//LIBRARIES
var request = require('request');
var cheerio = require('cheerio');
var mysql = require('mysql2');

//DB CONNECTION
var connection = mysql.createConnection({
    user: 'uglycoffeecan',
    database: 'c9'
});

$ = cheerio.load();

//PLAYER PAGE URL
var url = 'http://espn.go.com/mens-college-basketball/player/_/id/';

var request_all = function() {
    connection.query('SELECT id, ESPN_id from scraped_players_tbl', function(sql_err, results) {
        $(results).each(function() {
            var playerurl = url + this[0]['ESPN_id'];
            //console.log(playerurl);
            player_request(this[0]['id'], playerurl);
        });
    });
}

var player_request = function(params, urldata) {
    request(urldata, (function(err_t, resp_t, body_t) {
        if (err_t) throw err_t;
        $ = cheerio.load(body_t);
        var pid = urldata.split('/')[7];
        var sql = 'INSERT INTO espn_player_stats_tbl (playerid, ppg, rpg, apg) VALUES (' + connection.escape(pid);
        $('.header-stats td').each(function (b){
            sql += ',' + connection.escape($(this).text());
        });
        sql += ')';
        
        // $('.mod-player-stats .oddrow td').each(function (b){
        //     console.log(connection.escape($(this).text()));
        // });
        
        connection.query(sql, function(sql_err, results){
            if(sql_err !== null)
            {
                console.log(sql);
                console.log(sql_err);
            }
        });
        
        ///%%%%%%%%%%%%%%%%%%%%%%%%
        // var teamurl = $('.general-info .last a').first().attr('href');
        
        // var pid = 0;
        // var tid = 0;
        
        // if(teamurl)
        // {
        //     var pid = urldata.split('/')[7];
        //     var tid = teamurl.split('/')[7];
        // }
        // else
        // {
        //     console.log("malformed teamurl for" + urldata);
        // }

        
        // var rel_sql = "INSERT INTO espn_team_to_player_tbl (pid, tid) VALUES ('" + pid +"','"+ tid + "');";
        // //console.log(rel_sql);
        
        // connection.query(rel_sql, function(sql_err, results){
        //     if(sql_err !== null)
        //     {
        //         console.log(rel_sql);
        //         console.log(sql_err);
        //     }
        // });
        
        //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

        
    }))
}

//player_request('0', 'http://espn.go.com/mens-college-basketball/player/_/id/51064');
request_all();