<?
require_once "route.php";

class Graph
{
	public $states = [];
	public $routes = [];
	private static $debug = 0;

	function simplify()
	{
		$all_trivial = false;
		$counter = 0;

		while(!$all_trivial)
		{
			$all_trivial = true;

			//split into 'or' cases:
			foreach($this->routes as $route)
			{
				if(/*$route->dest->is_finish() || */$route->terminal->is_trivial())
					continue;

				if($terminals = $route->terminal->split_by_or())
				{
					$routes = [];
					foreach($terminals as $terminal)
					{
						$routes[] = new Route(['src' => $route->src, 'terminal' => $terminal, 'dest' => $route->dest]);
						$all_trivial &= $terminal->is_trivial();
					}
					$this->replace_route($route, $routes);
				}
			}

			//split into 'and' cases:
			foreach($this->routes as $route)
			{
				if(/*$route->dest->is_finish() || */$route->terminal->is_trivial())
					continue;

				if($terminals = $route->terminal->split_by_and())
				{
					$last_dest = $route->src;
					$terminals_cnt = count($terminals);
					$routes = [];
					$i = 0;
					foreach($terminals as $terminal)
					{
						$i++;
						if($i == $terminals_cnt)
						{					
							$routes[] = new Route(['src' => $last_dest, 'terminal' => $terminal, 'dest' => $route->dest]);
						}
						else
						{
							$dest = new State;
							$routes[] = new Route(['src' => $last_dest, 'terminal' => $terminal, 'dest' => $dest]);
							$last_dest = $dest;
						}
						$all_trivial &= $terminal->is_trivial();
					}
					$this->replace_route($route, $routes);
				}
			}

			//open repeat
			foreach($this->routes as $route)
			{
				if(/*$route->dest->is_finish() || */$route->terminal->is_trivial())
					continue;

				$terminal = $route->terminal;
				$len = strlen($terminal->str);
				if($len)
				{
					$last_sumbol = $terminal->str[$len - 1];
					$tmp = substr($terminal->str, 0, $len - 1);
				}
				else
				{
					$tmp = "";
				}
				$simple_terminal = new Terminal($tmp);

				if($last_sumbol !== '*' && $last_sumbol !== '+')
					continue;

				$cycle_terminal = $last_sumbol === '*' ? false : $simple_terminal;

				$dest = new State;
				$routes = [];
				$routes[] = new Route(['src' => $route->src, 'terminal' => $cycle_terminal, 'dest' => $dest]);
				$routes[] = new Route(['src' => $dest, 'terminal' => $simple_terminal, 'dest' => $dest]);
				$routes[] = new Route(['src' => $dest, 'terminal' => false, 'dest' => $route->dest]);
				$this->replace_route($route, $routes);
				$all_trivial &= $simple_terminal->is_trivial();	
			}

			//open groups
			foreach($this->routes as $route)
			{
				if(/*$route->dest->is_finish() || */$route->terminal->is_trivial())
					continue;

				$route->terminal->trim_bracket();		
			}

		}
	}

	function has_e_route()
	{
		foreach($this->routes as $route)
		{
			if($route->terminal->is_e_terminal())
				return true;
		}
		return false;
	}

	function remove_e_route($state, $debug = 0)
	{	
		self::$debug++;
		static $e_states = [];
		if($debug > 7) die('too deep recursion(8). stop in state ID:'.$state->id);
		if(!$state->has_outcome_e_route())
		{
			if($e_states)
			{
				//дошли до конца е-дуг
				//удаляем е-дугу по алгоритму:
				$first_state = reset($e_states);
				$last_state = end($e_states);

				if($first_state->is_start())
					$state->set_start();

				if($state->is_finish())
					$last_state->set_finish();

				if($route = $state->find_income_e_route($last_state))
					$this->delete_route($route);

				$this->merge_outcome_routes($last_state, $state);

				$e_states = [];
				return true;
			}
			else
			{
				//не было найдено ни одной е-дуги
				$e_states = [];
				return false;
			}
		}
		else
		{
			foreach($state->outcoming_routes as $route)
			{
				if(!$route->is_e_terminal())
					continue;

				if(in_array($route->dest->id, array_keys($e_states)))
				{
					//удаляем цикл
					$should_merge = false;
					$e_states[$state->id] = $state;
					$dest = $route->dest;
					foreach($e_states as $visited_state)
					{
						$should_merge = $should_merge ? $should_merge : $visited_state->id == $dest->id;
						if($should_merge)
						{
							$this->merge($dest, $visited_state);
						}
					}
					$e_states = [];
					return true;
				}
				else
				{
					//идем по е-дугам дальше
					$e_states[$state->id] = $state;
					return $this->remove_e_route($route->dest, $debug + 1);
				}
			}
		}
	}

	function remove_e_routes()
	{
		$i = 0;
		while($this->has_e_route())
		{
			if($i++ > 20) die();
			$this->remove_dummy_routes();
			$this->remove_dummy_states();

			$start = $this->states[0];
			if($this->remove_e_route($start))
				continue;

			foreach($this->states as $state)
			{
				if($this->remove_e_route($state))
					continue;
			}
		}
	}

	function remove_dummy_routes()
	{
		foreach($this->routes as $route)
		{
			if($route->src->id == $route->dest->id && $route->is_e_terminal())
				$this->delete_route($route);
		}
	}

	function remove_dummy_states()
	{
		foreach($this->states as $state)
		{
			if(!$state->is_start() && !$state->has_income_route())
				$this->delete_state($state);

			if(!$state->has_income_route() && !$state->has_outcome_route())
				$this->delete_state($state);				
		}
	}

	function __toString()
	{
		/*$res = [];
		foreach($this->states as $state)
		{
			$res[] = $state->id;
		}

		$res = "<h3>States:</h3><p>".implode(", ", $res)."</p>";*/
		$res = '';
		$res .= "<h3>Routes:</h3>";
		foreach($this->routes as $route)
		{	
			$src = $route->src->id.($route->src->is_start() ? '(S)' : '').($route->src->is_finish() ? '(Z)' : '');
			$dest = $route->dest->id.($route->dest->is_start() ? '(S)' : '').($route->dest->is_finish() ? '(Z)' : '');
			$res .= "<p>".$src." ----\"".$route->terminal->content()."\"----> ".$dest."</p>";
		}
		return $res;
	}

	function print_in_raphaeljs()
	{
		$res = "";
		foreach($this->states as $state)
		{
			$render = "render : ";
			$type = "render";
			if($state->has_self_route())
				$type .= "Self";
			if($state->is_start())
				$type .= "Start";
			if($state->is_finish())
				$type .= "Finish";
			$render .= $type;
			$route = $state->has_self_route();
			$self = $route ? "(".$route->terminal->str.")" : '';
			$res .= 'g.addNode("'.$state->id.'", {label : "'.$state->id.$self.'", '.$render.'});';
		}

		foreach($this->routes as $route)
		{
			$terminal = $route->terminal->is_e_terminal() ? 'ε' : $route->terminal->str;
		    $res .= 'g.addEdge("'.$route->src->id.'", "'.$route->dest->id.'", { stroke : "#bfa" , fill : "#56f", label : "'.$terminal.'", directed : true });';
		}

		return $res;
	}

	function add_route($route)
	{
		if(!in_array($route->id, array_keys($this->routes)))
			$this->routes[$route->id] = $route;

		if(isset($route->src->id) && !in_array($route->src->id, array_keys($this->states)))
			$this->states[$route->src->id] = $route->src;

		if(isset($route->dest->id) && !in_array($route->dest->id, array_keys($this->states)))
			$this->states[$route->dest->id] = $route->dest;
	}

	function delete_route($route)
	{
		if(in_array($route->id, array_keys($this->routes)))
		{
			unset($this->routes[$route->id]);
			$route->delete();
		}
	}

	function add_routes($routes)
	{
		if(is_array($routes))
		{
			foreach($routes as $route)
				$this->add_route($route);
		}
		elseif($routes instanceof Route)
		{
			$this->add_route($routes);
		}
	}

	function replace_route($route, $routes)
	{
		$this->add_routes($routes);

		if($route instanceof Route)
		{
			$this->delete_route($route);
		}
	}	

	function merge($state, $merged_state)
	{
		if($state->id != $merged_state->id)
		{
			$outcoming_routes = $merged_state->outcoming_routes;
			foreach($outcoming_routes as $route)
			{
				$new_route = new Route(['src' => $state, 'terminal' => $route->terminal, 'dest' => $route->dest]);
				$this->add_route($new_route);
			}

			$incoming_routes = $merged_state->incoming_routes;
			foreach($incoming_routes as $route)
			{
				$new_route = new Route(['src' => $route->src, 'terminal' => $route->terminal, 'dest' => $state]);
				$this->add_route($new_route);
			}
			$this->delete_state($merged_state);
		}
	}	

	function merge_outcome_routes($destination, $source)
	{
		foreach($source->outcoming_routes as $route)
		{
			$new_route = new Route(['src' => $destination, 'terminal' => $route->terminal, 'dest' => $route->dest]);
			$destination->add_outcome_route($new_route);
			$this->add_route($new_route);
		}
	}

	function delete_state($state)
	{
		foreach($state->outcoming_routes as $route)
			$this->delete_route($route);

		foreach($state->incoming_routes as $route)
			$this->delete_route($route);

		unset($this->states[$state->id]);
		$state->delete();
	}

	function get_all_str_terminals()
	{
		$res = [];
		foreach($this->routes as $route)
		{
			if(!in_array($route->terminal->str, $res))
				$res[] = $route->terminal->str;
		}
		return $res;
	}
}

