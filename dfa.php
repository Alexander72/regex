<?
function data($data)
{
	$res = [];
	if(isset($data[0]))
	{
		$len = count($data[0]);
		for($i = 0; $i < $len; $i++)
			$res[$i] = implode(',', array_column($data, $i));
	}
	return $res;
}

function build_dfa($graph, $table)
{
	$all_terminals = $graph->get_all_str_terminals();
	$terminals_count = count($all_terminals);
	$rows = [];

	//ищем все начальные состояния
	$first_states = [];
	$data = [];
	foreach($table['rows'] as $row)
	{
		if($row['state']->is_start())
		{
			$first_states[] = $row['state']->id;
			$data[] = $row['data'];
		}
	}
	$row = ['state' => implode(',', $first_states), 'data' => merge_data($data), 'is_first' => true, 'is_last' => false];
	$rows[] = $row;
	return ['headers' => $headers, 'rows' => $rows];

}