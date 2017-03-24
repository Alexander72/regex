<?

function build_nfa($graph)
{
	$all_terminals = $graph->get_all_str_terminals();
	$headers = array_merge(['State'], $all_terminals, ['Is final']);
	$terminals_count = count($all_terminals);
	$rows = [];
	$j = 0;
	foreach ($graph->states as $state) 
	{
		$data = [];

		//заполняем пустыми значениями строку
		for($i = 0; $i < $terminals_count; $i++) 
			$data[] = '';

		$terminals = [];
		foreach($state->outcoming_routes as $route)
		{
			$terminals[$route->terminal->str][] = $route->dest->id;
		}

		foreach($terminals as $str => $value)
		{
			$index = array_search($str, $all_terminals);
			$data[$index] = implode(',', $value);
		}
		$row['data'] = $data;
		$row['state'] = $state;

		$rows[] = $row;
	}
	return ['headers' => $headers, 'rows' => $rows];
}