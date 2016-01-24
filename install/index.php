<?php
REQUIRE_ONCE str_replace(DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'index.php','',$_SERVER['SCRIPT_FILENAME']).'/configs/init.config.php';

$package = json_decode(file_get_contents(__MINITALK_PATH__.'/package.json'));
$language = Request('language') ? Request('language') : 'en';
$acceptLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
?>
<!DOCTYPE HTML>
<html lang="<?php echo $language; ?>" data-lang="<?php echo $acceptLanguage; ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=800">
<title>MoimzTools Install - <?php echo $package->title; ?></title>
<link rel="stylesheet" href="//www.moimz.com/modules/moimz/styles/install.css" type="text/css">
<?php if ($language != 'en') { ?><link rel="stylesheet" href="//www.moimz.com/modules/moimz/styles/install.<?php echo $language; ?>.css" type="text/css"><?php } ?>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="//www.moimz.com/modules/moimz/scripts/jquery.js"></script>
<script src="//www.moimz.com/modules/moimz/scripts/install.js"></script>
</head>
<body>

<input type="hidden" id="language" value="<?php echo $language; ?>">
<div class="selectControl" data-field="language">
	<button type="button">Select Language <span class="arrow"></span></button>
</div>

<header>
	<h1>MoimzTools Install <small>From moimz.com</small></h1>
</header>

<main data-start="license" data-id="<?php echo $package->id; ?>" data-version="<?php echo $package->version; ?>">
	<section>
		<article>
			<h2><?php echo $package->title; ?> <small>v.<?php echo $package->version; ?></small></h2>
			
			<article data-step="license">
				<p></p>
				
				<label><input type="checkbox"> <span data-language="agree"></span></label>
			</article>
			
			<article data-step="check" data-url="./process/index.php">
				<button type="button" onclick="Step('check');"><i class="fa fa-refresh"></i> <span data-language="dependencyRefresh"></span></button>
				<ul>
					<li data-dependency="latest"></li>
					<?php foreach ($package->dependencies as $dependency=>$version) { ?>
					<li data-dependency="<?php echo $dependency; ?>" data-version="<?php echo $version; ?>"></li>
					<?php } ?>
					<?php foreach ($package->directories as $directory=>$permission) { if (preg_match('/^@/',$directory) == true && is_dir(__MINITALK_PATH__.DIRECTORY_SEPARATOR.str_replace('@','',$directory)) == false) continue; ?>
					<li data-directory="<?php echo str_replace('@','',$directory); ?>" data-permission="<?php echo $permission; ?>"></li>
					<?php } ?>
					<?php foreach ($package->configs as $config) { ?>
					<li data-config="<?php echo $config; ?>"></li>
					<?php } ?>
				</ul>
			</article>
			
			<article data-step="insert">
				<form onsubmit="return false;">
					<div data-role="input">
						<label data-language="key"></label>
						<input type="text" name="key" class="inputControl">
						<div class="helpBlock" data-language="key_help"></div>
					</div>
					
					<hr>
					
					<div data-role="input">
						<label data-language="db_host"></label>
						<input type="text" name="db_host" class="inputControl" value="localhost">
					</div>
					
					<div data-role="input">
						<label data-language="db_id"></label>
						<input type="text" name="db_id" class="inputControl">
					</div>
					
					<div data-role="input">
						<label data-language="db_password"></label>
						<input type="text" name="db_password" class="inputControl">
					</div>
					
					<div data-role="input">
						<label data-language="db_name"></label>
						<input type="text" name="db_name" class="inputControl">
						<div class="helpBlock" data-language="db_help"></div>
					</div>
					
					<hr>
					
					<div data-role="input">
						<label data-language="admin_id"></label>
						<input type="text" name="admin_id" class="inputControl">
					</div>
					
					<div data-role="input">
						<label data-language="admin_password"></label>
						<input type="text" name="admin_password" class="inputControl">
					</div>
				</form>
			</article>
			
			<article data-step="complete">
				<h3 data-language="complete"></h3>
				
				<p data-language="go_admin"></p>
			</article>
		</article>
		
		<nav>
			<ul>
				<li data-step="license" data-next="check"></li>
				<li data-step="check" data-prev="license" data-next="insert"></li>
				<li data-step="insert" data-prev="check" data-action="./process/index.php?action=install" data-next="complete"></li>
				<li data-step="complete" data-link="../admin/index.php"></li>
			</ul>
			
			<button type="button" data-role="prev"><i class="fa fa-arrow-left"></i><span data-language="back"></span></button>
			<button type="submit" data-role="next"><span data-language="continue"></span><i class="fa fa-arrow-right"></i></button>
		</nav>
	</section>
</main>

</body>
</html>