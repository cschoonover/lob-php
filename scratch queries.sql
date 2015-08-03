
select count(1), hometown from scraped_players_tbl group by hometown having count(1) > 5 order by count(1) desc;

select * from team_tbl t 
    inner join
conference_to_team_tbl ct
 on t.teamid = ct.teamid
 where ct.conferenceid =2;
 

select * from team_tbl t 
inner join game_tbl g 
on t.teamid = g.awayteamid 
where g.hometeamid = 3524
union
select * from team_tbl t 
inner join game_tbl g 
on t.teamid = g.hometeamid 
where g.awayteamid = 3524;

select (select count(*) from game_tbl where hometeamid = 3600 and homescore>awayscore) + 
(select count(*) from game_tbl where awayteamid = 3600 and homescore<awayscore)  as wins, 
(select count(*) from game_tbl where hometeamid = 3600 and homescore<awayscore) + 
(select count(*) from game_tbl where awayteamid = 3600 and homescore>awayscore) as losses;


select * from team_tbl t 
inner join game_tbl g 
on t.teamid = g.awayteamid 
where g.hometeamid = 3524
union
select * from team_tbl t 
inner join game_tbl g 
on t.teamid = g.hometeamid 
where g.awayteamid = 3524;

insert into espn_bpi_tbl (espn_id, bpi)  select espn_id, (rand() * 100) from team_tbl;

select bpi, name from team_tbl t inner join espn_bpi_tbl b on t.espn_id = b.espn_id; 

update conference_tbl set name = REPLACE(REPLACE(name, '-conference', ''), '-', ' ');

select (substring(ht, 1, 1)*12) + substring(ht, 3, 1)from scraped_players_tbl limit 10;

select case class
    when 'FR' then 18
    when 'SO' then 19
    when 'JR' then 20
    when 'SR' then 21
    else 22
end 

, 

count(1) from scraped_players_tbl group by class;

INSERT INTO player_tbl (espn_id, jersey_no, firstname, lastname, pos, ht, wt, class, hometown, age, redshirt)
(select ESPN_id, jersey_no, firstname, lastname, pos, (substring(ht, 1, 1)*12) + substring(ht, 3, 1) as ht, wt, class, hometown, case class
    when 'FR' then 18
    when 'SO' then 19
    when 'JR' then 20
    when 'SR' then 21
    else 22
end as age, false as redshirt from scraped_players_tbl);

insert into team_to_player_tbl
select  t.teamid, p.playerid
from player_tbl p 
inner join espn_team_to_player_tbl tp 
on p.espn_id = tp.pid 
inner join team_tbl t 
on t.espn_id = tp.tid;

select p.* from 
team_tbl t
inner join team_to_player_tbl tp
on t.teamid = tp.teamid
inner join player_tbl p
on tp.playerid = p.playerid
where t.teamid = 3500;

select p.lastname, ps.* from 
team_tbl t
inner join team_to_player_tbl tp
on t.teamid = tp.teamid
inner join player_tbl p
on tp.playerid = p.playerid
inner join espn_player_stats_tbl ps
on p.espn_id = ps.playerid
where t.teamid = 3491;


select * from player_ratings_tbl limit 10;

select * from espn_player_stats_tbl limit 10;

select max(ppg), avg(ppg), stddev(ppg) from espn_player_stats_tbl;
select max(rpg), avg(rpg), stddev(rpg) from espn_player_stats_tbl;
select max(apg), avg(apg), stddev(apg) from espn_player_stats_tbl;

select max(ht), avg(ht), stddev(ht) from player_tbl where ht >0;
select max(wt), avg(wt), stddev(wt) from player_tbl where wt >0;

select p.lastname, ps.* from 
team_tbl t
inner join team_to_player_tbl tp
on t.teamid = tp.teamid
inner join player_tbl p
on tp.playerid = p.playerid
right outer join espn_player_stats_tbl ps
on p.espn_id = ps.playerid
where t.teamid = 3491;

select count(1), pos from player_tbl group by pos;


update player_tbl set class = age - 18;



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
    
select avg(overall) from (select p.lastname, p.pos, po.* from 
team_tbl t
inner join team_to_player_tbl tp
on t.teamid = tp.teamid
inner join player_tbl p
on tp.playerid = p.playerid
inner join player_overall_vw po
on p.playerid = po.playerid
where t.teamid = 3491 order by overall desc limit 5) as starters;


    


