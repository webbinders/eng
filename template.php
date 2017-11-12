<?php
include_once __DIR__ . '/forms/auto_form.php';
include_once __DIR__ . '/forms/menu_form.php';
/*
<!DOCTYPE html>
<html>
 */
$head = <<<HEAD
<head>
	<meta charset="utf-8">
	<title>Read in english</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">   
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
HEAD;
//<body>
$header =<<<HEADER
<header>
<div class="center-block">
	<div class="logo">
		<img src="logo.svg" width="50">
		<p><i>Читайте с легкостью.</i></p>
	</div>
	<div id="panelNav" class="menu">	
		<div class="autoriz">
                    $auto_form_str

		</div>
		<nav>
                    $menu_form_str

		</nav>
		
        </div>
</div>
</header>
HEADER;
$t_content = <<<CONTENT
<div class="center-block">
	<section>
		$_content

	</section>
	
</div>
CONTENT;
$footer = <<<FOOTER
	<footer>
		<div class="center-block">
			$socButtons
		</div>		
	</footer>
	<script src="forms/eng.js"></script>
FOOTER;
/*
</body>
</html>
 * 
 */
?>