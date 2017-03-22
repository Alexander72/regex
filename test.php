<?
	error_reporting(E_ALL);
	require_once "graph.php";
	function v($var, $die = false)
	{
		echo "<pre>";
		var_dump($var);
		echo "<pre>";
		if($die) die();
	}

	function p($var, $die = false)
	{
		echo "<pre>".print_r($var, 1)."</pre>";
		if($die) die();
	}
	$str = '';

	if(!isset($_POST['str']))
	{
		include "template.php";
		die();
	}
	$str = $_POST['str'];
	p($str);

	$start  = new State(['start'  => true]);
	$finish = new State(['finish' => true]);

	$route  = new Route(['src' => $start, 'dest' => $finish, 'terminal' => $str]);

	$graph = new Graph;
	$graph->add_route($route);
	$graph->simplify();
	
	echo $graph;