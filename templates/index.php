<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<!-- Latest compiled and minified CSS -->
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
		<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
		<script src="js/jquery-3.2.0.min.js"></script>
	</head>
	<body>
		<section class="container">
			<br>
			<form method="POST" class="form-horizontal">
				<p> Example: (12|(33)+|a)*a|123*12(3)+|Q(RH|(09|4)+Z)*W+R+R</p>
			  	<div class="form-group <?=$status?> has-feedback">
			    	<label class="col-sm-2 control-label" for="input_text">Input string: </label>
			    	<div class="col-sm-10">
			      		<input type="text" class="form-control" id="input_text" name="str" value="<?=$str?>">
			      		<?/*/?>
			      			<p class="help-block"><?=$status == 'has-error' ? 'Incorrect' : ($status == 'has-success' ? 'Correct' : '')?></p>
			      		<?/*/?>
			    	</div>
			  	</div>
				<button type="submit" name="build" class="btn btn-primary">Build graph</button>
				<button type="submit" name="simplify" class="btn btn-primary">Remove e-routes</button>
				<button type="submit" name="nfa" class="btn btn-primary">Build NFA</button>
				<button type="submit" name="dfa" class="btn btn-primary">Build DFA</button>

			</form>
		</section>
	</body>
</html>