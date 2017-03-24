<?
function build_dfa($graph)
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
			$class = $route->src->is_start() ? 'class="start_row"' : '';
			$row[$index + 1] = "<td ".$class.">".$route->dest->id."</td>\n";
		}
		p($row, 1);
		$row[$width - 1] = $route->dest->is_finish() ? '1' : '';

		$rows[] = $row;
	}
	return ['headers' => $headers, 'rows' => $rows];

}