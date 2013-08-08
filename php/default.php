<?php 

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])
		|| !($_SERVER['PHP_AUTH_USER'] == 'np' && $_SERVER['PHP_AUTH_PW'] == 'npnp')) {
	header('WWW-Authenticate: Basic realm="My Realm"');
	header('HTTP/1.0 401 Unauthorized');
	echo '&copy; 2013 QDA';
	exit;
}

$d = new DateTime();

$from = clone $d;
$offset = $from->format('w');
$from->modify("-$offset day");

if (!empty($_GET['from'])) {
	$from = DateTime::createFromFormat('Y-m-d', $_GET['from']);
}

// d($from);

$prevD = clone $from; $prevD->modify('-1 day');
$prevW = clone $from; $prevW->modify('-7 day');
$nextD = clone $from; $nextD->modify('+1 day');
$nextW = clone $from; $nextW->modify('+7 day');

// fetch data from DB.

require_once 'lib.php';
$events = getDataset($from);
$names = array('Daniel', 'Ray', 'Hunter', 'Harris', 'Tom');
natcasesort($names);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=no">
	
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-responsive.min.css" type="text/css" />
	<link rel="stylesheet" href="assets/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css" type="text/css" />
	
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" />
	<link rel="stylesheet" href="assets/css/template.css" type="text/css" />
	
	<script type="text/javascript" src="assets/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.min.js"></script>
	
	<title>Nextplus Cal</title>
</head>
<body>
<div class="container-fluid">
	<div id="hd">
		<div class="row-fluid">
			<div class="span2">
				<a href="default.php" class="logo">
					Nextplus Inc.
				</a>
			</div>
			<div class="span2">
				<div class="name">理想令人坚强</div>
				<div class="desc">我们在绿色的拂晓，去天涯远征</div>
			</div>
		</div>
	</div>
	<div id="bd">
		<div class="ctrl" style="margin-bottom: 20px;">
			<div class="row-fluid">
				<div class="span4">
					<a href="?from=<?php echo $prevW->format('Y-m-d'); ?>" class="btn btn-default">&lt;&lt;</a>
					<a href="?from=<?php echo $prevD->format('Y-m-d'); ?>" class="btn btn-default">&lt;</a>
				</div>
				<div class="span4 ct">
					<a href="?from=" class="btn btn-success">TODAY</a>
				</div>
				<div class="span4 rh">
					<a href="?from=<?php echo $nextD->format('Y-m-d'); ?>" class="btn btn-default">&gt;</a>
					<a href="?from=<?php echo $nextW->format('Y-m-d'); ?>" class="btn btn-default">&gt;&gt;</a>
				</div>
			</div>
		</div>
		<table class="table table-bordered table-hover table-striped table-condensed">
			<thead>
				<tr>
					<th>Name / Date</th>
					<?php for ($i = 0; $i < 7; $i++) : ?>
					<?php
					
					$p = clone $from;
					date_add($p, date_interval_create_from_date_string("$i days"));
					
					?>
					<th <?php if ($p->format('Y-m-d') === $d->format('Y-m-d')) echo 'style="background-color: #f2dede;"'; ?> width="12%">
					<span title="<?php echo $p->format('D / Y-m-d'); ?>"><?php echo $p->format('D / Y-m-d'); ?></span>
					<input type="hidden" name="date" value="<?php echo $p->format('Y-m-d'); ?>" />
					</th>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($names as $name) : ?>
				<tr>
					<td style="height: 60px; vertical-align: middle;">
						<?php echo $name; ?>
						<input type="hidden" name="name" value="<?php echo $name; ?>" />
					</td>
					<?php for ($i = 0; $i < 7; $i++) : ?>
					<td>
					<?php
					
					$p = clone $from;
					date_add($p, date_interval_create_from_date_string("$i days"));
					$dateLit = $p->format('Y-m-d');
					
					if (isset($events[$name][$dateLit])) {
						$notes = $events[$name][$dateLit];
					} else {
						$notes = '';
					}
					
					$placement = $i < 7 / 2 ? 'right' : 'left';
					
					?>
					<a href="#" class="notes" data-type="textarea" data-pk="1" data-placement="<?php echo $placement; ?>">
						<?php echo nl2br($notes); ?>
					</a>
					</td>
					<?php endfor; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div id="ft" class="rh">
		<hr />
		<p>&copy 2013 Creativoo</p>
	</div>
</div>
<script type="text/javascript">
<!--

$(document).ready(function() {
	$('td a.notes').editable({
		url: 'post.php',
		emptytext: '......',
	 	params: function(params) {
    		var name = $(this).parent().parent().children().first().children('input').val();
    		var date = $('tr th:nth-child(' + ($(this).parent().index() + 1) + ') input').val();
	        params.name = name;
	        params.date = date;
	        return params;
	    },
	   	success: function(resp, value ) {}
	});
});

//-->
</script>
</body>
</html>