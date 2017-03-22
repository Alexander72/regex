<?
require_once "terminal.php";
require_once "state.php";

class Route
{
	public $terminal;
	public $src;
	public $dest;
	public $id;

	private static $auto_increment = 0;

	function __construct($params)
	{
		$this->id = self::$auto_increment;
		self::$auto_increment = self::$auto_increment + 1;

		if(isset($params['terminal']))
		{
			if($params['terminal'] instanceof Terminal)
				$this->terminal = $params['terminal'];
			else
				$this->terminal = new Terminal($params['terminal']);
		}

		if(isset($params['src']))
		{
			$this->src = $params['src'];
			$this->src->add_outcome_route($this);
		}
		else
			$this->src = new State(array('outcome' => $this));

		if(isset($params['dest']))
		{
			$this->dest = $params['dest'];
			$this->dest->add_income_route($this);
		}
		else
			$this->dest = new State(array('income' => $this));

	}

	function delete()
	{
		unset($this->terminal);

		if($this->src instanceof State)
			$this->src->delete_outcome_route($this);

		if($this->dest instanceof State)
			$this->dest->delete_income_route($this);
	}

}