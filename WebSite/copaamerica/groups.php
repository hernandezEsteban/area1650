<?php
	function host_tournament($year)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$resultado = $mysqli->query('select a.code, b.name from tournament a, country b where a.country = b.code and a.code = ' . $year);
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<p>' . $fila['code'] . ' - ' . $fila['name'] . '</p>';
		}
		$mysqli->close();
		return $script;
	}

	function group_detail($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$resultado = $mysqli->query('select a.group_code, b.code, b.name from group_stage a, country b where a.tournament = ' . $year . ' and a.group_code = \'' . $group . '\' and a.squad = b.code order by a.id');
		$script = '<ul>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '	<li><p>' . $fila['name'] . '</p><p><a href="http://www.area1650.net/copaamerica/country_stats.html?country=' . $fila['code'] . '">stats</a></p></li>';
		}
		$script = $script . '</ul>';
		$mysqli->close();
		return $script;
	}

	function group_table($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = ' select'; 
		$query = $query . '      a.name name, ';
		$query = $query . ' 	 sum(b.points) points, ';
		$query = $query . ' 	 count(b.squad) games, ';
		$query = $query . ' 	 count(c.squad) win, ';
		$query = $query . ' 	 count(d.squad) draw, ';
		$query = $query . ' 	 count(e.squad) loose,';
		$query = $query . ' 	 sum(f.goals) goals,';
		$query = $query . ' 	 sum(g.goals) again,';
		$query = $query . ' 	 (sum(f.goals) - sum(g.goals)) diff';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h inner join group_stage i';
		$query = $query . '     on a.code = b.squad and b.matchid = h.matchid and h.game_type in (2) and year(h.matchdate) = ' . $year;
		$query = $query . ' 	 and b.squad = i.squad and b.goals is not null and i.group_code = \'' . $group  . '\' and i.tournament = ' . $year;
		$query = $query . ' left join game_score c';
		$query = $query . '     on c.points >= 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points = 1 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 0 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad';
		$query = $query . ' left join game_score f';
		$query = $query . '     on b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad';
		$query = $query . ' left join game_score g';
		$query = $query . '     on b.matchid = g.matchid and b.time_type = g.time_type and b.squad <> g.squad';
		$query = $query . ' where b.time_type in (2, 4, 6)'; 
		$query = $query . ' group by a.code';
		$query = $query . ' order by group_code, points desc, diff desc, goals desc, again desc';
		
		$resultado = $mysqli->query($query);
		$script = '<table>';
		$script = $script . '<thead>';
		$script = $script . '<tr id = "group_table_a">';
		$script = $script . '<th>Team</th>';
		$script = $script . '<th>Points</th>';
		$script = $script . '<th>Games</th>';
		$script = $script . '<th>Won</th>';
		$script = $script . '<th>Draw</th>';
		$script = $script . '<th>Loose</th>';
		$script = $script . '<th>Goals</th>';
		$script = $script . '<th>Against</th>';
		$script = $script . '<th>Difference</th>';
		$script = $script . '</tr>';
		$script = $script . '<tr id = "group_table_b">';
		$script = $script . '<th>Team</th>';
		$script = $script . '<th>P</th>';
		$script = $script . '<th>G</th>';
		$script = $script . '<th>W</th>';
		$script = $script . '<th>D</th>';
		$script = $script . '<th>L</th>';
		$script = $script . '<th>G</th>';
		$script = $script . '<th>A</th>';
		$script = $script . '<th>Di</th>';
		$script = $script . '</tr>';
		$script = $script . '</thead>';
		$script = $script . '<tbody>';		
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '<tr>';
			$script = $script . '<td>' . $fila['name'] . '</td>';
			$script = $script . '<td>' . $fila['points'] . '</td>';
			$script = $script . '<td>' . $fila['games'] . '</td>';
			$script = $script . '<td>' . $fila['win'] . '</td>';
			$script = $script . '<td>' . $fila['draw'] . '</td>';
			$script = $script . '<td>' . $fila['loose'] . '</td>';
			$script = $script . '<td>' . $fila['goals'] . '</td>';
			$script = $script . '<td>' . $fila['again'] . '</td>';
			$script = $script . '<td>' . $fila['diff'] . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</tbody>';		
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}

	function group_matches($year, $group)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = 'select b.matchdate matchdate, c.squad squada, d.name namea, e.squad squadb, f.name nameb, ';
		$query = $query . 'ifnull(c.goals, "-") ftgsquada, ifnull(e.goals, "-") ftgsquadb, ifnull(g.goals, "-") htgsquada, ifnull(h.goals, "-") htgsquadb from ';
		$query = $query . 'group_stage a inner join game b inner join game_score c '; 
		$query = $query . 'inner join country d inner join game_score e inner join country f ';
		$query = $query . 'on b.matchid = c.matchid and c.time_type = e.time_type '; 
		$query = $query . 'and c.id = (select min(id) from game_score where matchid = c.matchid) ';
		$query = $query . 'and c.matchid = e.matchid and c.squad <> e.squad ';
		$query = $query . 'and a.squad = c.squad and a.tournament = ' . $year;
		$query = $query . ' and year (b.matchdate) = a.tournament and a.group_code = \'' . $group . '\' and ';
		$query = $query . 'b.game_type = 2 and c.time_type = 2 and c.squad = d.code and e.squad = f.code ';
		$query = $query . 'left join game_score g ';
		$query = $query . ' on g.time_type = 1 and g.squad = c.squad and g.matchid = c.matchid ';
		$query = $query . 'left join game_score h ';
		$query = $query . ' on h.time_type = 1 and h.squad = e.squad and h.matchid = e.matchid ';
		$query = $query . 'order by b.matchid';	

		$resultado = $mysqli->query($query);
		$script = '<table>';
		$script = $script . '<tr>';
		$script = $script . '<th>Match Date</th>';
		$script = $script . '<th colspan="3">Match</th>';
		$script = $script . '<th colspan="2">FT</th>';
		$script = $script . '<th colspan="2">HT</th>';
		$script = $script . '<th></th>';
		$script = $script . '</tr>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '<tr>';
			$script = $script . '<td>' . $fila['matchdate'] . '</td>';
			$script = $script . '<td>' . $fila['namea'] . '</td>';
			$script = $script . '<td>vs</td>';
			$script = $script . '<td>' . $fila['nameb'] . '</td>';
			$script = $script . '<td>' . $fila['ftgsquada'] . '</td>';
			$script = $script . '<td>' . $fila['ftgsquadb'] . '</td>';
			$script = $script . '<td>' . $fila['htgsquada'] . '</td>';
			$script = $script . '<td>' . $fila['htgsquadb'] . '</td>';
			$script = $script . '<td><a href="http://www.area1650.net/copaamerica/match_stats.html?squada=' . $fila['squada'] . '&squadb=' . $fila['squadb'] . '">stats</a></td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
	
	function country_stats($country)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = 'select a.name, sum(b.points) points, count(c.squad) games, count(d.squad) win, count(e.squad) draw,';
		$query = $query . ' count(f.squad) loose, sum(g.goals) goals, sum(i.goals) again, (sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' (count(d.squad) / count(c.squad)) pw, (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' (count(f.squad) / count(c.squad)) pl ';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h ';
		$query = $query . '     on a.code = ? and a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
		$query = $query . ' left join game_score c';
		$query = $query . '     on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad';
		$query = $query . ' left join game_score f';
		$query = $query . '     on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid)'; 
		$query = $query . ' 	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad';
		$query = $query . ' left join game_score g';
		$query = $query . '    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad'; 
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad';
		$query = $query . ' group by a.code';
		$query = $query . ' order by points desc, diff desc, goals desc, again desc';
	
		$resultado = $mysqli->prepare($query);
		$resultado->bind_param('i', $country);
		$resultado->execute();
        $resultado->bind_result($name, $points, $games, $win, $draw, $loose, $goals, $again, $diff, $pw, $pd, $pl);		
		
		$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
		$script = $script . '<table>';
		$record = 0;
		while  ($resultado->fetch())
		{
			$record = 1;
			$script = $script . '<tr>';
			$script = $script . '<td>Country</td><td>'               . $name   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $points . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games</td><td>'                 . $games  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $win    . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Draw</td><td>'            . $draw   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Defeated</td><td>'        . $loose  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $goals  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Against</td><td>'         . $again  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Difference</td><td>'      . $diff   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $pw     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Draw</td><td>'    . $pd     . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Defeat</td><td>'  . $pl     . '</td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		
		if ($record == 0)
		{
			$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
			$script = $script .'<p>No records for this team</p>';
		}
		
		return $script;
	}
	
	function match_stats($squada, $squadb)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		
		$query = ' select ';
		$query = $query . '      a.name squada,';
		$query = $query . '      k.name squadb,';
		$query = $query . ' 	 sum(b.points) pointsa,';
		$query = $query . ' 	 sum(i.points) pointsb,';
		$query = $query . ' 	 count(c.squad) games,';
		$query = $query . ' 	 count(d.squad) winb,';
		$query = $query . ' 	 count(e.squad) draw,';
		$query = $query . ' 	 count(f.squad) wina,';
		$query = $query . '  	 sum(g.goals) goalsa,';
		$query = $query . ' 	 sum(i.goals) goalsb,';
		$query = $query . ' 	 abs(sum(g.goals) - sum(i.goals)) diff,';
		$query = $query . ' 	 (count(d.squad) / count(c.squad)) pb,';
		$query = $query . ' 	 (count(e.squad) / count(c.squad)) pd,';
		$query = $query . ' 	 (count(f.squad) / count(c.squad)) pa';
		$query = $query . ' from ';
		$query = $query . ' country a inner join game_score b inner join game h inner join game_score j';
		$query = $query . '     on a.code = ? and a.code = b.squad and b.matchid = h.matchid and b.matchid = j.matchid and j.squad = ? and b.time_type = j.time_type';
		$query = $query . ' 	 and b.time_type in (2,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()';
		$query = $query . ' left join game_score c';
		$query = $query . '     on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and c.squad = ?';
		$query = $query . ' left join game_score d';
		$query = $query . '     on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and d.squad = ?';
		$query = $query . ' left join game_score e';
		$query = $query . '     on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and e.squad = ?';
		$query = $query . ' left join game_score f';
		$query = $query . '     on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid)'; 
		$query = $query . ' 	                 and b.matchid = f.matchid and b.time_type = f.time_type and f.squad = ?';
		$query = $query . ' left join game_score g';
		$query = $query . '     on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = g.matchid and b.time_type = g.time_type and g.squad = ?';
		$query = $query . ' left join game_score i';
		$query = $query . '     on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,4,6))';  
		$query = $query . ' 	                 and b.matchid = i.matchid and b.time_type = i.time_type and i.squad = ?';
		$query = $query . ' left join country k on k.code = ?';
		$query = $query . ' group by a.code';
		$resultado = $mysqli->prepare($query);
		$resultado->bind_param('iiiiiiiii', $squada, $squadb, $squadb, $squadb, $squadb, $squadb, $squada, $squadb, $squadb);
		$resultado->execute();
	    $resultado->bind_result($namea, $nameb, $pointsa, $pointsb, $games, $winb, $draw, $wina, $goalsa, $goalsb, $diff, $pb, $pd, $pa);		
		
		$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
		$script = $script . '<table>';
		$record = 0;
		while ($resultado->fetch())
		{
			$record = 1;
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">'               . $namea   . '</td><td colspan="2">'               . $nameb    . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Games</td><td colspan="2">'                 . $games   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Points</td><td>'                . $pointsa . '</td><td>Points</td><td>'                . $pointsb . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Games Won</td><td>'             . $wina    . '</td><td>Games Won</td><td>'             . $winb    . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Games Draw</td><td colspan="2">'            . $draw   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Goals Scored</td><td>'          . $goalsa  . '</td><td>Goals Scored</td><td>'          . $goalsb  . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Goals Difference</td><td colspan="2">'      . $diff   . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td>Likelihood of Victory</td><td>' . $pa     . '</td><td>Likelihood of Victory</td><td>'  . $pb      . '</td>';
			$script = $script . '</tr>';
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Likelihood of Draw</td><td colspan="2">'    . $pd     . '</td></td>';
			$script = $script . '</tr>';
		}
		$script = $script . '</table>';
		$mysqli->close();
		
		if ($record == 0)
		{
			$script = '<a href="http://www.area1650.net/copaamerica/page.php">Copa America Centenario</a>';
			$script = $script .'<p>No records for this match</p>';
		}
		
		return $script;
	}
	
	function match_details($squada, $squadb)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}
		$query = ' select i.name namea, j.name  nameb, f.name gametype, g.name timetype, ifnull(a.goals, "-") goalsa, ifnull(b.goals, "-") goalsb, c.matchdate matchdate, ifnull(d.goals,-1) goalsd, ifnull(e.goals, -1) goalse, ifnull(h.name, \'\') penalties from game_score a inner join game_score b inner join game c';
		$query = $query . ' on a.matchid = b.matchid and a.time_type = b.time_type and'; 
		$query = $query . ' a.time_type = (select max(time_type) from game_score where matchid = b.matchid and time_type in (2,3,4,6))';
		$query = $query . ' and a.matchid = c.matchid and c.matchdate < now()';
		$query = $query . ' left join game_score d'; 
		$query = $query . '  on d.time_type = 7'; 
		$query = $query . '  and a.matchid = d.matchid';
		$query = $query . '  and a.squad = d.squad';
		$query = $query . ' left join game_score e'; 
		$query = $query . '  on e.time_type = 7'; 
		$query = $query . '  and b.matchid = e.matchid';
		$query = $query . '  and b.squad = e.squad';
		$query = $query . '  left join game_type f on c.game_type = f.id';
		$query = $query . '  left join time_type g on a.time_type = g.id';
		$query = $query . '  left join time_type h on e.time_type = h.id';
		$query = $query . '  left join country i on a.squad = i.code';
		$query = $query . '  left join country j on b.squad = j.code';
		$query = $query . ' where a.squad = ? and b.squad = ?';
		$query = $query . ' order by matchdate desc';		
		$resultado = $mysqli->prepare($query);
		$resultado->bind_param('ii', $squada, $squadb);
		$resultado->execute();
	    $resultado->bind_result($namea, $nameb, $gametype, $timetype, $goalsa, $goalsb, $matchdate, $goalsd, $goalse, $penalties);		
		$script = '<table>';
		while ($resultado->fetch())
		{
			$script = $script . '<tr>';
			$script = $script . '<th colspan="4">Match Date: ' . $matchdate . '</th>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">' . $namea . '</td><td colspan="2">' . $nameb . '</td>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">' . $goalsa . '</td><td colspan="2">' . $goalsb . '</td>';
			$script = $script . '</tr>';
			
			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Game type</td><td colspan="2">' . $gametype . '</td>';
			$script = $script . '</tr>';

			$script = $script . '<tr>';
			$script = $script . '<td colspan="2">Time Type</td><td colspan="2">' . $timetype . '</td>';
			$script = $script . '</tr>';

		    if ($goalsd > -1 && $goalse > -1)
			{
				$script = $script . '<tr>';
				$script = $script . '<td colspan="2">Time Type</td><td colspan="2">' . $penalties . '</td>';
				$script = $script . '</tr>';
				$script = $script . '<tr>';
				$script = $script . '<td colspan="2">' . $goalsd . '</td><td colspan="2">' . $goalse . '</td>';
				$script = $script . '</tr>';
			}
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}

	function knockout_stage($year, $stage)
	{
		$mysqli = new mysqli('127.0.0.1', 'areanet_admin', 'erSS1979_', 'areanet_copaamerica');
		if ($mysqli->connect_errno) 
		{
			echo 'Fall� la conexi�n a MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}

		$query = 'select b.matchdate matchdate, c.squad squada, d.name namea, e.squad squadb, f.name nameb, ';
		$query = $query . 'ifnull(c.goals, "-") ftgsquada, ifnull(e.goals, "-") ftgsquadb, '; 
		$query = $query . 'ifnull(g.goals, "-") htgsquada, ifnull(h.goals, "-") htgsquadb, ';
		$query = $query . 'ifnull(i.goals, "-") ot2gsquada, ifnull(j.goals, "-") ot2gsquadb, ';
		$query = $query . 'ifnull(k.goals, "-") ot1gsquada, ifnull(l.goals, "-") ot1gsquadb, ';
		$query = $query . 'ifnull(m.goals, "-") pgsquada, ifnull(n.goals, "-") pgsquadb ';
		$query = $query . 'from game b inner join game_score c '; 
		$query = $query . 'inner join country d inner join game_score e inner join country f ';
		$query = $query . 'on b.matchid = c.matchid and c.time_type = e.time_type '; 
		$query = $query . 'and c.id = (select min(id) from game_score where matchid = c.matchid) ';
		$query = $query . 'and c.matchid = e.matchid and c.squad <> e.squad ';
		$query = $query . 'and year (b.matchdate) = ' . $year . ' and ';
		$query = $query . 'b.game_type = ' . $stage . ' and c.time_type = 2 and c.squad = d.code and e.squad = f.code ';
		$query = $query . 'left join game_score g ';
		$query = $query . ' on g.time_type = 1 and g.squad = c.squad and g.matchid = c.matchid ';
		$query = $query . 'left join game_score h ';
		$query = $query . ' on h.time_type = 1 and h.squad = e.squad and h.matchid = e.matchid ';
		$query = $query . 'left join game_score i ';
		$query = $query . ' on i.time_type = 4 and i.squad = c.squad and i.matchid = c.matchid ';
		$query = $query . 'left join game_score j ';
		$query = $query . ' on j.time_type = 4 and j.squad = e.squad and j.matchid = e.matchid ';
		$query = $query . 'left join game_score k ';
		$query = $query . ' on k.time_type = 3 and k.squad = c.squad and k.matchid = c.matchid ';
		$query = $query . 'left join game_score l ';
		$query = $query . ' on l.time_type = 3 and l.squad = e.squad and l.matchid = e.matchid ';
		$query = $query . 'left join game_score m ';
		$query = $query . ' on m.time_type = 7 and m.squad = c.squad and m.matchid = c.matchid ';
		$query = $query . 'left join game_score n ';
		$query = $query . ' on n.time_type = 7 and n.squad = e.squad and n.matchid = e.matchid ';
		$query = $query . 'order by b.matchid';	

		$resultado = $mysqli->query($query);
		$script = '<table>';
		$script = $script . '<tr>';
		$script = $script . '<th>Match Date</th>';
		$script = $script . '<th colspan="3">Match</th>';
		$script = $script . '<th colspan="2">FT</th>';
		$script = $script . '<th colspan="2">HT</th>';
		$script = $script . '<th></th>';
		$script = $script . '</tr>';
		for ($num_fila = 0; $num_fila <= $resultado->num_rows - 1; $num_fila++) 
		{
			$resultado->data_seek($num_fila);
			$fila = $resultado->fetch_assoc();
			$script = $script . '<tr>';
			$script = $script . '<td>' . $fila['matchdate'] . '</td>';
			$script = $script . '<td>' . $fila['namea'] . '</td>';
			$script = $script . '<td>vs</td>';
			$script = $script . '<td>' . $fila['nameb'] . '</td>';
			$script = $script . '<td>' . $fila['ftgsquada'] . '</td>';
			$script = $script . '<td>' . $fila['ftgsquadb'] . '</td>';
			$script = $script . '<td>' . $fila['htgsquada'] . '</td>';
			$script = $script . '<td>' . $fila['htgsquadb'] . '</td>';
			$script = $script . '<td><a href="http://www.area1650.net/copaamerica/match_stats.html?squada=' . $fila['squada'] . '&squadb=' . $fila['squadb'] . '">stats</a></td>';
			$script = $script . '</tr>';
			if ($fila['ot2gsquada'] <> "-")
			{
				$script = $script . '<tr>';
				$script = $script . '<th></th>';
				$script = $script . '<th colspan="3">Overtime</th>';
				$script = $script . '<th colspan="2">OT2</th>';
				$script = $script . '<th colspan="2">OT1</th>';
				$script = $script . '<th></th>';
				$script = $script . '</tr>';
				$script = $script . '<tr>';
				$script = $script . '<td colspan="4"></th>';
				$script = $script . '<td>' . $fila['ot2gsquada'] . '</td>';
				$script = $script . '<td>' . $fila['ot2gsquadb'] . '</td>';
				$script = $script . '<td>' . $fila['ot1gsquada'] . '</td>';
				$script = $script . '<td>' . $fila['ot1gsquadb'] . '</td>';
				$script = $script . '<td></td>';
				$script = $script . '</tr>';
			}
			if ($fila['pgsquada'] <> "-")
			{
				$script = $script . '<tr>';
				$script = $script . '<th></th>';
				$script = $script . '<th colspan="3">Penalties</th>';
				$script = $script . '<th colspan="5"></th>';
				$script = $script . '</tr>';
				$script = $script . '<tr>';
				$script = $script . '<td colspan="4"></th>';
				$script = $script . '<td>' . $fila['pgsquada'] . '</td>';
				$script = $script . '<td>' . $fila['pgsquadb'] . '</td>';
				$script = $script . '<td colspan="3"></th>';				
				$script = $script . '</tr>';
			}
		}
		$script = $script . '</table>';
		$mysqli->close();
		return $script;
	}
?>