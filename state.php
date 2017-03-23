<?
class State
{
	public $id;
	public $incoming_routes;
	public $outcoming_routes;

	private $start;
	private $finish;
	private $deleted;

	private static $auto_increment = 0;
	private static $list = [];

	function __construct($params = array())
	{
		$this->id = self::$auto_increment;
		self::$auto_increment = self::$auto_increment + 1;

		$this->deleted = false;

		if(isset($params['start']))
			$this->start = (bool) $params['start'];
		else
			$this->start = false;

		if(isset($params['finish']))
			$this->finish = (bool) $params['finish'];
		else
			$this->finish = false;

		if(isset($params['income']))
			$this->add_income_route($params['income']);
		else
			$this->incoming_routes = [];

		if(isset($params['outcome']))
			$this->add_outcome_route($params['outcome']);
		else
			$this->outcoming_routes = [];

		self::$list[$this->id] = $this;
	}

	function set_finish()
	{
		$this->finish = true;
	}

	function set_start()
	{
		$this->start = true;
	}

	function is_finish()
	{
		return $this->finish;
	}

	function is_start()
	{
		return $this->start;
	}

	function delete()
	{
		$this->deleted = true;
		unset(self::$list[$this->id]);
	}

	function has_outcome_e_route()
	{
		foreach($this->outcoming_routes as $route)
		{
			if($route->is_e_terminal())
				return true;
		}
		return false;
	}

	function add_outcome_route($route)
	{
		$this->outcoming_routes[$route->id] = $route;
	}

	function add_income_route($route)
	{
		$this->incoming_routes[$route->id] = $route;
	}

	function delete_outcome_route($route)
	{
		unset($this->outcoming_routes[$route->id]);
	}

	function delete_income_route($route)
	{
		unset($this->incoming_routes[$route->id]);
	}
}