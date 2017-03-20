<?
	error_reporting(E_ALL);
	function v($var, $die = false)
	{
		echo "<pre>";
		var_dump($var);
		echo "<pre>";
		if($die) die();
	}
	$trivials = '0123456789abcdefghijklmnopqrstuvwxyz ';
	$str = '';

	function p($var, $die = false)
	{
		echo "<pre>".print_r($var, 1)."</pre>";
		if($die) die();
	}

	function is_trivial_terminal($str)
	{
		global $trivials;

		if($str === NULL || (strlen($str) == 1 && stristr($trivials, $str) !== false))
			return true;
		else
			return false;
	}

	function get_last_index($table)
	{
		end($table);
		return key($table);
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

	function split_by_or($str)
	{
		$result = [];
		while($str !== '')
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

	function split_by_and($str)
	{
		$stop = $str == '123';
		$result = [];
		while($str !== '')
		{
			$i = 0;	
			$len = strlen($str);		
			if(!in_array($str[$i], ['*', '+']))
			{	
				if($str[$i] == '(')
				{
					$i = get_close_bracket_index($str, $i);
				}

				if($i + 1 == $len || (in_array($str[$i+1], ['*', '+']) && $i + 2 == $len))
				{
					$result[] = $str;
					break;
				}
				else
				{	
					if(in_array($str[$i+1], ['*', '+']))
					{
						$i++;
					}

					$result[] = substr($str, 0, $i + 1);
					$str = substr($str, $i + 1);	
				}
			}
			else
			{
				die('unexpected '.$str[$i]);
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
	p($str);

	$table = [
		['src' => 0, 'terminal' => $str, 'dest' => 1, 'is_finish' => 0],
		['src' => 1, 'is_finish' => 1],
	];

	$all_trivial = is_trivial_terminal($str);
	$counter = 0;

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

			if($alternatives = split_by_or($route['terminal']))
			{
				foreach($alternatives as $terminal)
				{
					$table[] = ['src' => $table[$key]['src'], 'terminal' => $terminal, 'dest' => $table[$key]['dest'], 'is_finish' => 0];
				}
				unset($table[$key]);
				$all_trivial &= is_trivial_terminal($terminal);
			}
		}

		//build chains
		foreach($table as $key => $route)
		{
			if($route['is_finish'])
				continue;

			if(is_trivial_terminal($route['terminal']))
				continue;

			if($items = split_by_and($route['terminal']))
			{
				$last_dest = $table[$key]['src'];
				$items_cnt = count($items);
				$i = 0;
				foreach($items as $item)
				{
					$i++;
					if($i == $items_cnt)
					{					
						$table[] = ['src' => $last_dest, 'terminal' => $item, 'dest' => $table[$key]['dest'], 'is_finish' => 0];	
					}
					else
					{
						$dest = get_last_index($table) + 1;
						$table[] = ['src' => $last_dest, 'terminal' => $item, 'dest' => $dest, 'is_finish' => 0];
						$last_dest = $dest;
					}
				}
				unset($table[$key]);
				$all_trivial &= is_trivial_terminal($terminal);
			}
		}

		//open repeat
		foreach($table as $key => $route)
		{
			if($route['is_finish'])
				continue;

			if(is_trivial_terminal($route['terminal']))
				continue;

			$terminal = $route['terminal'];
			$len = strlen($terminal);
			$last_sumbol = $terminal[$len - 1];
			$simply_terminal = substr($terminal, 0, $len-1);

			if($last_sumbol === '*')
			{
				$dest = get_last_index($table) + 1;
				$table[] = ['src' => $route['src'], 'terminal' => NULL, 'dest' => $dest, 'is_finish' => 0];
				$table[] = ['src' => $dest, 'terminal' => $simply_terminal, 'dest' => $dest, 'is_finish' => 0];
				$table[] = ['src' => $dest, 'terminal' => NULL, 'dest' => $route['dest'], 'is_finish' => 0];
				unset($table[$key]);
				$all_trivial &= is_trivial_terminal($terminal);
			}
			elseif($last_sumbol === '+')
			{
				$dest = get_last_index($table) + 1;
				$table[] = ['src' => $route['src'], 'terminal' => NULL, 'dest' => $dest, 'is_finish' => 0];
				$table[] = ['src' => $dest, 'terminal' => $simply_terminal, 'dest' => $dest, 'is_finish' => 0];
				$table[] = ['src' => $dest, 'terminal' => $simply_terminal, 'dest' => $route['dest'], 'is_finish' => 0];
				unset($table[$key]);
				$all_trivial &= is_trivial_terminal($terminal);
			}		
		}

		//open groups
		foreach($table as $key => $route)
		{
			if($route['is_finish'])
				continue;

			if(is_trivial_terminal($route['terminal']))
				continue;

			$len = strlen($route['terminal']);
			if($route['terminal'][0] == '(' && $route['terminal'][$len-1] == ')')
			{
				$table[$key]['terminal'] = substr($table[$key]['terminal'], 1, $len-2);
			}			
		}

		//заглушка:
		//$all_trivial = true;
		if($counter++ > 3)die('aaaaaaaaaaaaaaaa');
	}
	p($table, 1);

	

	include "template.php";



