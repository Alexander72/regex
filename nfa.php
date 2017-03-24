<?
function build_nfa($graph)
{
	$headers = array_merge(['State'], $graph->get_all_str_terminals(), ['Is final']);
	$width = count($headers);
	$rows = [];
	foreach ($graph->states as $state) 
	{
		$row = [$state->id];

		//заполняем пустыми значениями строку
		for($i = 1; $i < $width; $i++) 
			$row[] = "";

		foreach($state->outcoming_routes as $route)
		{
			$terminal = $route->terminal->str;
			$index = array_search($terminal, $headers);
			$row[$index + 1] = $route->dest->id;
		}
		$row[$width - 1] = $route->dest->is_finish() ? '1' : '';

		$rows[] = $row;
	}
	return ['headers' => $headers, 'rows' => $rows];
}