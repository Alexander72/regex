<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<!-- Latest compiled and minified CSS -->
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
		<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
		<script src="js/jquery-3.2.0.min.js"></script>
	</head>
	<body>		
		<div class="container">
		  <h2>NFA</h2>
		  <p>string is: <?=$str?></p>            
		  <a href="" class="btn btn-primary">Back</a>
		  <br>
		  <br>
		  <table class="table table-bordered">
		    <thead>
		      <tr>
		      	<?foreach($table['headers'] as $cell):?>
		        	<th><?=$cell?></th>
		      	<?endforeach;?>
		      </tr>
		    </thead>
		    <tbody>
		    	<?foreach($table['rows'] as $row):?>
		    		<tr>
		    			<?foreach ($row as $cell):?>
		        			<td><?=$cell?></td>
		    			<?endforeach;?>
		    		</tr>
		    	<?endforeach;?>
		    </tbody>
		  </table>
		</div>
	</body>
</html>