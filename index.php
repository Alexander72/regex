<?
	error_reporting(E_ALL);
	function v($var, $die = false)
	{
		echo "<pre>";
		var_dump($var);
		echo "<pre>";
		if($die) die();
	}
	$trivials = ['0', '1'];
	$str = '';

	function p($var, $die = false)
	{
		echo "<pre>".print_r($var, 1)."</pre>";
		if($die) die();
	}

	function is_trivial_terminal($str)
	{
		global $trivials;
		return in_array($str, $trivials);
	}

	function get_close_bracket_index($str, $start_pos)
	{
		$len = strlen($str);
		$i = $start_pos + 1;
		$sum = 1;
		while($sum != 0 && $i < $len)
		{
			if($str[$i] == '(')
				$sum++;
			elseif($str[$i] == ')')
			{
				$sum--;
				if($sum < 0)
					die('incorrect brackets');
			}
			$i++;
		}

		//now we are at the next by last bracket symbol
		$i--;

		if($sum != 0)
			die('incorrect brackets');
		else
			return $i;

	}

	function split_by_alternatives($str)
	{
		$result = [];
		while($str)
		{
			$len = strlen($str);
			$i = 0;
			while($i < $len && $str[$i] != '|')
			{
				if($str[$i] == '(')
				{
					$i = get_close_bracket_index($str, $i);
				}
				else
				{
					$i++;
				}
			}

			if($i == $len - 1 && $str[$i] == '|')
			{
				die('ending at |');
			}
			elseif($i == 0 && $str[$i] == '|')
			{
				die('started at |');
			}
			else
			{
				$result[] = substr($str, 0, $i);
				if($i >= $len)
					break;
				else
					$str = substr($str, $i + 1);				
			}	
		}
		return $result;
	}

	if(!isset($_POST['str']))
	{
		include "template.php";
		die();
	}
	$str = $_POST['str'];

	$table = [
		['src' => 0, 'terminal' => $str, 'dest' => 1, 'is_finish' => 0],
		['is_finish' => 1],
	];

	$all_trivial = is_trivial_terminal($str);

	while(!$all_trivial)
	{
		$all_trivial = true;
		//split into cases:
		foreach($table as $key => $route)
		{
			if($route['is_finish'])
				continue;

			if(is_trivial_terminal($route['terminal']))
				continue;

			if($alternatives = split_by_alternatives($route['terminal']))
			{
				foreach($alternatives as $terminal)
				{
					$table[] = ['src' => $table[$key]['src'], 'terminal' => $terminal, 'dest' => $table[$key]['dest'], 'is_finish' => 0];
				}
				unset($table[$key]);
				$all_trivial &= is_trivial_terminal($terminal);
			}
		}
		//заглушка:
		$all_trivial = true;
	}
	v($_POST['str']);
	v(array_column($table, 'terminal'), 1);

	

	include "template.php";



