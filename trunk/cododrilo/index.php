<?php

include(".htconf");

$theme = $conf_theme;

ob_start(); 
$ret = @readfile("$theme/index.tmpl");
$contents_index = ob_get_contents(); 
ob_end_clean(); 

ob_start(); 
$ret = @readfile("$theme/entry.tmpl");
$contents_entry = ob_get_contents(); 
ob_end_clean(); 

include("cocodrilo.php");

$c = new CocodriloGHNS();
$c->connect();

$c->load(null, $filter_category, $filter_author, null, 10);
$list = $c->entries;

$r = new CocodriloTemplate();

foreach ($list as $e)
{
	$entrylist .= $r->substitute_entry($contents_entry, $e);
}

$contents_index = preg_replace("/%ENTRY_LIST%/", $entrylist, $contents_index);

$contents_index = preg_replace("/%SEARCH_URL%/", "http://localhost/cgi-bin/hotstuff-search.pl", $contents_index);

echo $contents_index;

?>
