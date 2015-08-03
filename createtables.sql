CREATE TABLE scraped_players_tbl (
     id INT NOT NULL AUTO_INCREMENT,
     ESPN_id int,
     jersey_no varchar(10),
     fullname varchar(100),
     firstname varchar(50),
     lastname varchar(50),
     pos varchar(10),
     ht varchar(10),
     wt int,
     class varchar(5),
     hometown varchar(100),
     PRIMARY KEY (id)
);

UPDATE scraped_players_tbl
SET firstname = LEFT(fullname, LOCATE(' ', fullname) - 1),
       lastname = RIGHT(fullname, LOCATE(' ', REVERSE(fullname), LOCATE(' ', REVERSE(fullname)) + 1))
       WHERE fullname like '% % %';
       
CREATE TABLE espn_player_stats_tbl(
    playerid INT NOT NULL,
    ppg DOUBLE,
    rpg DOUBLE,
    apg DOUBLE,
    PRIMARY KEY (playerid)
    );
    
CREATE TABLE espn_bpi_tbl(
    ESPN_id INT NOT NULL,
    bpi DOUBLE,
    PRIMARY KEY (ESPN_id)
);

CREATE TABLE espn_team_to_player_tbl(
    tid INT,
    pid INT
);

CREATE TABLE conference_tbl(
    conferenceid INT NOT NULL,
    name varchar(100),
    logourl varchar(200),
    has_divisions BOOL,
    division_type INT,
    tournament_type INT,
    PRIMARY KEY (conferenceid)
    );
    
    
CREATE TABLE team_tbl(
    teamid INT NOT NULL AUTO_INCREMENT,
    ESPN_id INT,
    name varchar(50),
    primary_color varchar(6),
    stadium_name varchar(100),
    stadium_capacity int,
    location_name varchar(100),
    loc_x double,
    loc_y double,
    PRIMARY KEY(teamid)
    );
    
CREATE TABLE game_tbl(
    gameid INT NOT NULL AUTO_INCREMENT,
    hometeamid INT,
    awayteamid INT,
    confmatchupflag BOOL,
    date DATETIME,
    neutralsiteflag BOOL,
    homescore INT,
    awayscore INT,
    PRIMARY KEY(gameid)
    );
    
CREATE TABLE player_tbl(
     playerid INT NOT NULL AUTO_INCREMENT,
     espn_id INT,
     jersey_no varchar(10),
     firstname varchar(50),
     lastname varchar(50),
     age int,
     pos varchar(10),
     ht int,
     wt int,
     class int,
     redshirt BOOL,
     hometown varchar(100),
     PRIMARY KEY (playerid)
);

CREATE TABLE player_ratings_tbl(
    playerid INT NOT NULL,
    p_str INT,
    p_end INT,
    p_jmp INT,
    p_spd INT,
    p_vis INT,
    
    s_dnk INT,
    s_cls INT,
    s_lng INT,
    s_ft INT,
    s_qr INT,
    s_usd INT,
    
    b_pm INT,
    b_blk INT,
    b_stl INT,
    b_reb INT,
    b_idf INT,
    b_pdf INT,
    b_hlp INT,
    b_ps INT,
    b_drb INT,
    b_mvt INT,
    
    m_tgh INT,
    m_mrl INT,
    m_tmw INT,
    m_acd INT,
    m_iq INT,
    PRIMARY KEY (playerid)
);

CREATE VIEW player_overall_vw as
    select playerid, ((p_str +
    p_end +
    p_jmp +
    p_spd +
    p_vis +
    
    s_dnk +
    s_cls +
    s_lng +
    s_ft +
    s_qr +
    s_usd +
    
    b_pm +
    b_blk +
    b_stl +
    b_reb +
    b_idf +
    b_pdf +
    b_hlp +
    b_ps +
    b_drb +
    b_mvt +
    
    m_tgh +
    m_mrl +
    m_tmw +
    m_acd +
    m_iq ) / 26) as overall from player_ratings_tbl;

##SIM TRACKING TABLES *******
    
CREATE TABLE date_counter_tbl (
 simdate DATETIME
 );
    
## relationship tables
CREATE TABLE team_to_player_tbl(
    teamid INT,
    playerid INT
    );
    
CREATE TABLE conference_to_team_tbl(
    teamid INT,
    conferenceid INT,
    divisionid INT
    );
    
    
    