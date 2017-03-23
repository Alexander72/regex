<?
require_once "route.php";

class Graph
{
	private $states = [];
	private $routes = [];

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
				$last_sumbol = $terminal->str[$len - 1];
				$tmp = substr($terminal->str, 0, $len - 1);
				$simple_terminal = new Terminal($tmp);

				if($last_sumbol !== '*' && $last_sumbol !== '+')
					continue;

				$cycle_terminal = $last_sumbol === '*' ? false : $simple_terminal;

				$dest = new State;
				$routes = [];
				$routes[] = new Route(['src' => $route->src, 'terminal' => false, 'dest' => $dest]);
				$routes[] = new Route(['src' => $dest, 'terminal' => $simple_terminal, 'dest' => $dest]);
				$routes[] = new Route(['src' => $dest, 'terminal' => $cycle_terminal, 'dest' => $route->dest]);
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

	function __toString()
	{
		$res = [];
		foreach($this->states as $state)
		{
			$res[] = $state->id;
		}

		$res = "<h3>States:</h3><p>".implode(", ", $res)."</p>";
		$res .= "<h3>Routes:</h3>";
		foreach($this->routes as $route)
		{
			$res .= "<p>".$route->src->id." ----\"".$route->terminal->content()."\"----> ".$route->dest->id."</p>";
		}
		return $res;
	}

	function print_in_raphaeljs()
	{
		$res = "";
		foreach($this->states as $state)
		{
			$fill = "render : render";
			if($state->is_start())
				$fill = " fill : '#ffd343' ";
			if($state->is_finish())
				$fill = " fill : '#ff6b43' ";
			$res .= 'g.addNode("'.$state->id.'", {label : "'.$state->id.'", '.$fill.'});';
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
}