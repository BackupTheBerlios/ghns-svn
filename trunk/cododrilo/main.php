<style type="text/css">
.hotstuff {
  color: #000000;
  border-width: 0px;
  border-spacing: 1px;
  padding: 4pt;
  font-size: 9pt;
  font-weight: normal;
}
.hotstuff td {
  background-color: #cccccc;
  background: #cccccc;
  padding: 7px;
  font-size: 10pt;
}
.hotstuff th {
  background-color: #7b90a3;
  background: #7b90a3;
  padding: 0px;
  font-size: 12pt;
}
</style>

<?php

include(".htconf");

$conn = pg_connect("host=$conf_host dbname=$conf_name user=$conf_user password=$conf_pass");

echo "This is an overview to all the content hosted on this server.\n";
echo "Available downloads in the selected category:\n";
echo "<br/><br/>\n";

$query = "SELECT DISTINCT type FROM directory";
$res = pg_exec($conn, $query);

echo "<form action=\"index.php\" method=\"post\">\n";
echo "<select name=\"form_filter\">\n";
echo "<option value=\"\">All types</option>\n";
for ($i = 0; $i < pg_numrows($res); $i++)
{
	$type = pg_result($res, $i, "type");
	$selected = "";
	if($type == $form_filter) :
		$selected = " selected";
	endif;
	echo "<option$selected>$type</option>\n";
}
echo "</select>\n";
echo "&nbsp;&nbsp;\n";

echo "<select name=\"form_sorting\">\n";
$selected = "";
if($form_sorting == "releasedate") :
	$selected = " selected";
endif;
echo "<option value=\"releasedate\" $selected>Sort by date</option>\n";
$selected = "";
if($form_sorting == "rating") :
	$selected = " selected";
endif;
echo "<option value=\"rating\" $selected>Sort by rating</option>\n";
$selected = "";
if($form_sorting == "downloads") :
	$selected = " selected";
endif;
echo "<option value=\"downloads\" $selected>Sort by popularity</option>\n";
echo "</select>\n";

echo "&nbsp;&nbsp;\n";
echo "<input type=\"submit\" value=\"Apply\">\n";
echo "</form>\n";

$lang = "de";

function autoselect($var, $type, $lang)
{
	global $conn;

	$res2 = pg_exec($conn, "SELECT * FROM contents " .
		"WHERE id = $var AND type = '$type' AND language = '$lang'");
	if (($res2) && (pg_numrows($res2) == 1)) :
		$var = pg_result($res2, 0, "content");
	else :

		$res2 = pg_exec($conn, "SELECT * FROM contents " .
			"WHERE id = $var AND type = '$type' LIMIT 1");
		if (($res2) && (pg_numrows($res2) == 1)) :
			$var = pg_result($res2, 0, "content");
		else :
			$var = "Not available.";
		endif;

	endif;
	return $var;
}

if ($rating) :
	$query = "SELECT rating FROM directory WHERE id = $id";
	$res = pg_exec($conn, $query);
	if(pg_numrows($res) == 1) :
		$oldrating = pg_result($res, 0, "rating");
		if ($rating == "worse") :
			$rating = $oldrating - 1;
		elseif ($rating == "better") :
			$rating = $oldrating + 1;
		endif;
		if ($rating < 0) :
			$rating = 0;
		endif;
		if ($rating > 10) :
			$rating = 10;
		endif;
		$query = "UPDATE directory SET rating = $rating WHERE id = $id";
		$res = pg_exec($conn, $query);
	endif;
endif;

$query = "SELECT * FROM directory WHERE (validity = '' OR validity IS NULL)";
if ($form_filter) :
	$query .= " AND type = '$form_filter'";
endif;
if ($form_sorting) :
	$query .= " ORDER BY $form_sorting DESC";
endif;
if ($form_id) :
	$query .= " AND id = $form_id";
endif;

$res = pg_exec($conn, $query);

for ($i = 0; $i < pg_numrows($res); $i++)
{
	$id = pg_result($res, $i, "id");

	$name = pg_result($res, $i, "name");
	$version = pg_result($res, $i, "version");
	$author = pg_result($res, $i, "author");
	$releasedate = pg_result($res, $i, "releasedate");

	$licence = pg_result($res, $i, "licence");
	$release = pg_result($res, $i, "release");
	$rating = pg_result($res, $i, "rating");
	$downloads = pg_result($res, $i, "downloads");
	$type = pg_result($res, $i, "type");

	$summary = pg_result($res, $i, "summary");
	$payload = pg_result($res, $i, "payload");
	$preview = pg_result($res, $i, "preview");
	$summary = autoselect($summary, "summary", $lang);
	$payload = autoselect($payload, "payload", $lang);
	$preview = autoselect($preview, "preview", $lang);

	if (!$preview) :
		$preview = "nopreview.png";
	elseif (!@fopen($preview, "r")) :
		//$preview = "nopreview.png";
	endif;

	$basetypes = explode("/", $type);
	$basetype = $basetypes[0];
	$baseurl = "/hotstuff/directory/content/$basetype/$basetype.png";

	echo "<table border=\"0\" width=\"100%\" class=\"hotstuff\">\n";
	echo "<tr><td width=\"64\">\n";
	echo "<img src=\"$preview\" width=\"64\">\n";
	echo "</td><td>\n";

	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\">\n";
	echo "<img src=\"$baseurl\">\n";
	echo "</td><td valign=\"top\">\n";
	echo "<b>$name</b> ($version (release $release), $releasedate)<br/>\n";
	echo "Author: $author<br/>\n";
	echo "</td></tr></table>\n";

	echo "Rating: $rating/10\n";
	for ($j = 0; $j < $rating; $j++)
	{
		echo "<img src='rating.png'>";
	}
	if ($rating != 0) :
		echo "<a href='index.php?p=main&rating=worse&id=$id'>[it's worse]</a>\n";
	endif;
	if ($rating != 10) :
		echo "<a href='index.php?p=main&rating=better&id=$id'>[it's better]</a>\n";
	endif;
	echo "<br>\n";
	echo "Licence: $licence<br>\n";
	echo "Summary: <i>$summary</i><br/><br/>\n";
	echo "Downloads: $downloads<br>\n";
	echo "Download: <i><a href=\"$payload\">$payload</a></i>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
}

?>

