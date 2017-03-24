<?
	error_reporting(E_ALL);
	require_once "graph.php";
	require_once "nfa.php";
	include_once "dfa.php";
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
		include "templates/index.php";
		die();
	}
	$str = $_POST['str'];

	$start  = new State(['start'  => true]);
	$finish = new State(['finish' => true]);

	$route  = new Route(['src' => $start, 'dest' => $finish, 'terminal' => $str]);

	$graph = new Graph;
	$graph->add_route($route);
	if(isset($_POST['build']))
	{
		$graph->simplify();
		include "templates/graph.php";
	}
	elseif(isset($_POST['simplify']))
	{
		$graph->simplify();
		$graph->remove_e_routes();	
		include "templates/graph.php";	
	}
	elseif(isset($_POST['nfa']))
	{
		$graph->simplify();
		$graph->remove_e_routes();		
		$table = build_nfa($graph);	
		include "templates/table.php";	
	}
	elseif(isset($_POST['dfa']))
	{
		$graph->simplify();
		$graph->remove_e_routes();		
		$table = build_nfa($graph);
		$table = build_dfa($graph, $table);	
		include "templates/table.php";	
	}
	
