<?php

$ret = @include(".htconf");

if (!$ret) :
	echo "Cocodrilo is not configured yet.\n";
	echo "Please copy htconf-dist to .htconf and edit its values.\n";
	exit;
endif;

$theme = $conf_theme;

ob_start(); 
$ret = @readfile("$theme/index.tmpl");
$contents_index = ob_get_contents(); 
ob_end_clean(); 

if (!$ret) :
	echo "Cocodrilo could not load the template file $theme/index.tmpl.\n";
	exit;
endif;

ob_start(); 
$ret = @readfile("$theme/entry.tmpl");
$contents_entry = ob_get_contents(); 
ob_end_clean(); 

if (!$ret) :
	echo "Cocodrilo could not load the template file $theme/entry.tmpl.\n";
	exit;
endif;

include("cocodrilo.php");

$c = new CocodriloGHNS();
$ret = $c->connect();

if (!$ret) :
	echo "Cocodrilo was unable to connect to the database.\n";
	exit;
endif;

if ($conf_category) :
	$filter_category = $conf_category;
else :
	$filter_category = $_GET["filter_category"];
endif;

$filter_author = $_GET["filter_author"];

if ($conf_entriesperpage) :
	$entriesperpage = $conf_entriesperpage;
else :
	$entriesperpage = 10;
endif;

$filter_search = $_POST["q"];

$c->load($filter_search, $filter_category, $filter_author, null, $entriesperpage);
$list = $c->entries;

$r = new CocodriloTemplate();

foreach ($list as $e)
{
	$entrylist .= $r->substitute_entry($contents_entry, $e);
}

if (sizeof($list) == 0) :
	$entrylist = "No entries found.";
endif;

$contents_index = preg_replace("/%ENTRY_LIST%/", $entrylist, $contents_index);

$contents_index = preg_replace("/%SEARCH_URL%/", "index.php", $contents_index);

echo $contents_index;

?>
