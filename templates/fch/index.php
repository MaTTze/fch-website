<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
    	html, body, div {
    		margin: 0;
    		padding: 0;
    		border: none;
    	}
    </style>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/reset.css" type="text/css" />
    
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bfv_egm.css" type="text/css" />
	
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/small.css" type="text/css" />
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width,user-scalable=yes">
</head>
<body>
    <div id="header" class="box">
    	<div class="left centering">
    		<img class="logo" alt="" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo.png"/>
    	</div>
    	<div class="right">
    		<img class="logo" alt="" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/logo.png"/>
    	</div>
    	<div class="wrapper">
    	<h1>FC&nbsp;Hochbrück</h1>
    	<h2>
    		<?php
				echo $this->getTitle();
			?>
		</h2>
    	</div>
    </div>
    <div id="navigation" class="box"><input type="checkbox" id="menu-toggle"/><label for="menu-toggle">Menü</label><jdoc:include type="modules" name="navigation"/></div>
    <div id="main_container">
    	<div id="left-side-top" class="box"><jdoc:include type="modules" name="sidenavigation"/></div>
    	<div id="aside" class="box">
			<p>Nützliche Zusatzinfos!</p>
			<jdoc:include type="modules" name="aside"/>
			<div class="ad skyscraper">
				<div>Ihre Werbung hier!
				</div>
			</div>
		</div>
    	<div id="content" class="box">
			<jdoc:include type="component" />
		</div>
		<div id="left-side-bottom" class="box">
			<jdoc:include type="modules" name="leftside"/>
		</div>
    </div>
    <div id="footer" class="box">
    	<div class="left">
    		<jdoc:include type="modules" name="footer"/>
    	</div>
    	<div class="right">
    		&#169; 2014 FC&nbsp;Hochbrück | <a href="http://localhost/fch/index.php/impressum">Impressum</a>
    	</div>
    </div>
</body>
</html>

 

