<?
class Terminal
{
	public $str;

	private $global_index;
	private $trivials = '0123456789abcdefghijklmnopqrstuvwxyz ';

	function __construct($str, $index = 0)
	{
		$this->str = $str;
		$this->global_index = $index;
	}

	function is_trivial()
	{
		if($this->str === false || (strlen($this->str) == 1 && stristr($this->trivials, $this->str) !== false))
			return true;
		else
			return false;	
	}

	function is_e_terminal()
	{
		return $this->str === false;
	}

	function trim_bracket()
	{		
		if($str = $this->str)
		{
			$len = strlen($str);
			if($str[0] == '(' && $str[$len-1] == ')')
			{
				$str = substr($str, 1, $len-2);
				$this->str = $str;
			}
		}	
	}

	function split_by_or()
	{
		$str = $this->str;
		$result = [];
		$current_pointer = 0;
		while($str !== '')
		{
			$len = strlen($str);
			$i = 0;
			while($i < $len && $str[$i] != '|')
			{
				if($str[$i] == '(')
				{
					$i = $this->get_close_bracket_index($str, $i);
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
				$result[] = new Terminal(substr($str, 0, $i), $this->global_index + $current_pointer);
				$current_pointer += $i + 2;
				if($i >= $len)
					break;
				else
					$str = substr($str, $i + 1);				
			}	
		}

		return $result;
	}

	function split_by_and()
	{
		$str = $this->str;
		$result = [];
		$current_pointer = 0;
		while($str !== '')
		{
			$i = 0;	
			$len = strlen($str);		
			if(!in_array($str[$i], ['*', '+']))
			{	
				if($str[$i] == '(')
				{
					$i = $this->get_close_bracket_index($str, $i);
				}

				if($i + 1 == $len || (in_array($str[$i+1], ['*', '+']) && $i + 2 == $len))
				{
					$result[] = new Terminal($str, $this->global_index + $current_pointer);
					break;
				}
				else
				{	
					if(in_array($str[$i+1], ['*', '+']))
					{
						$i++;
					}

					$result[] = new Terminal(substr($str, 0, $i + 1), $this->global_index + $current_pointer);
					$str = substr($str, $i + 1);	
					$current_pointer += ($i + 1);
				}
			}
			else
			{
				die('unexpected '.$str[$i]);
			}	
		}
		return $result;
	}

	private function get_close_bracket_index($str, $open_bracket_index)
	{
		$len = strlen($str);
		$i = $open_bracket_index + 1;
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
}
