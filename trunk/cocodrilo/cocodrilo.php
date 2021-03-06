<?php

function xmlvalue($tag)
{
	##if (!$tag->children) :
	##	return "";
	##endif;

	return $tag->textContent;
	
	##foreach ($tag->children as $child)
	##{
	##	if ($child->type == XML_TEXT_NODE) :
	##		return $child->content;
	##	endif;
	##}

	##return "";
}

function xmlname($tag)
{
	##return $tag->tagname;
	return $tag->tagName;
}

class CocodriloEntry
{
	var $id;
	var $author;
	var $licence;
	var $category;

	var $version;
	var $release;
	var $releasedate;

	var $rating;
	var $downloads;

	var $name;
	var $summary;
	var $payload;
	var $preview;

	var $names;
	var $summaries;
	var $payloads;
	var $previews;

	function get_languages()
	{
		$ret = "";
		foreach ($this->summaries as $key => $value)
		{
			if ($ret) :
				$ret .= ", ";
			endif;
			$ret .= $key;
		}
		return $ret;
	}

	function get_size()
	{
		return "unknown";
	}

	function get_htmlsummary()
	{
		$s = $this->summary;
		$s = htmlspecialchars($s);
		$s = preg_replace("/(http:\/\/[^\\,\\ ,\\)]*)/", "<a href='\\1'>\\1</a>", $s);
		return $s;
	}

	function get_sameauthor()
	{
		return "?filter_author=$this->author";
	}

	function get_samecategory()
	{
		return "?filter_category=$this->category";
	}
}

class CocodriloGHNS
{
	var $conn;
	var $entries;

	function CocodriloGHNS()
	{
	}

	function connect()
	{
		include(".htconf");

		$this->conn = @pg_connect("host=$conf_host dbname=$conf_name user=$conf_user password=$conf_pass");

		return $this->conn;
	}

	function autoselect($var, $type, $lang)
	{
		$res = pg_exec($this->conn, "SELECT * FROM contents " .
			"WHERE index = $var AND type = '$type' AND language = '$lang'");
		if (($res) && (pg_numrows($res) == 1)) :
			$var = pg_result($res, 0, "content");
		else :
			$res = pg_exec($this->conn, "SELECT * FROM contents " .
				"WHERE index = $var AND type = '$type' LIMIT 1");
			if (($res) && (pg_numrows($res) == 1)) :
				$var = pg_result($res, 0, "content");
			else :
				$var = null;
			endif;

		endif;
		return $var;
	}

	function multiple($var, $type)
	{
		$res = pg_exec($this->conn, "SELECT * FROM contents " .
			"WHERE index = $var AND type = '$type'");
		$var = array();
		for ($i = 0; $i < pg_numrows($res); $i++)
		{
			$c = pg_result($res, 0, "content");
			$l = pg_result($res, 0, "language");
			$var[$l] = $c;
		}
		return $var;
	}

	function load($searchterm, $category, $author, $criteria, $max)
	{
		$filter = "";
		if ($author) :
			$filter .= " AND author = '$author'";
		endif;
		if ($category) :
			$filter .= " AND category = '$category'";
		endif;
		if ($searchterm) :
			$filter .= " AND meta_ref IN " .
				"(SELECT index FROM contents WHERE type = 'summary' " .
				"AND content LIKE '%$searchterm%')";
		endif;
		if ($max) :
			$filter .= " LIMIT $max";
		endif;
		$query = "SELECT * FROM directory " .
			"WHERE (validity = '' OR validity IS NULL) $filter";
		$res = pg_exec($this->conn, $query);

		$this->entries = array();

		for ($i = 0; $i < pg_numrows($res); $i++)
		{
			$e = new CocodriloEntry();

			$e->id = pg_result($res, $i, "id");

			$e->name = pg_result($res, $i, "name");
			$e->version = pg_result($res, $i, "version");
			$e->author = pg_result($res, $i, "author");
			$e->releasedate = pg_result($res, $i, "releasedate");

			$e->licence = pg_result($res, $i, "licence");
			$e->release = pg_result($res, $i, "release");
			$e->rating = pg_result($res, $i, "rating");
			$e->downloads = pg_result($res, $i, "downloads");
			$e->category = pg_result($res, $i, "category");

			$metaref = pg_result($res, $i, "meta_ref");

			$lang = "de";
			$e->summary = $this->autoselect($metaref, "summary", $lang);
			$e->payload = $this->autoselect($metaref, "payload", $lang);
			$e->preview = $this->autoselect($metaref, "preview", $lang);

			$e->summaries = $this->multiple($metaref, "summary");
			$e->payloads = $this->multiple($metaref, "payload");
			$e->previews = $this->multiple($metaref, "preview");

			$this->entries[] = $e;
		}
	}

	function fetch($feed)
	{
		$f = fopen($feed, "r");
		if (!$f) :
			return false;
		endif;

		$text = "";
		while (!feof($f))
		{
			$line = fgets($f, 1024);
			$text .= $line;
		}
		fclose($f);

		#echo htmlspecialchars($text);
		#echo "-------------<br>\n";

		##$tree = @domxml_xmltree($text);
		##if (!$tree) :
		##	return false;
		##endif;
		$doc = new DomDocument();
		$ret = $doc->loadXML($text);
		if (!$ret) :
			return false;
		endif;

		$list = $doc->getElementsByTagName("stuff");
		##$root = $tree->children[1];
		#print_r($root);
		foreach ($list as $child)
		##foreach ($root->children as $child)
		{
			##if ($child->type != XML_ELEMENT_NODE) :
			##	continue;
			##endif;

			if(true) :
			##if ($child->tagname == "stuff") :
				#print_r($child);
				#echo "<br>+++++<br>\n";
				$e = new CocodriloEntry();
				$e->id = -1;

				$childlist = $child->getElementsByTagName("*");
				foreach ($childlist as $tag)
				##foreach ($child->children as $tag)
				{
					##if ($tag->type != XML_ELEMENT_NODE) :
					##	continue;
					##endif;

					#print_r($tag);
					#echo "<br>+++++<br>\n";
					if (xmlname($tag) == "name") :
						$e->name = xmlvalue($tag);
					elseif (xmlname($tag) == "version") :
						$e->version = xmlvalue($tag);
					elseif (xmlname($tag) == "author") :
						$e->author = xmlvalue($tag);
					elseif (xmlname($tag) == "releasedate") :
						$e->releasedate = xmlvalue($tag);
					elseif (xmlname($tag) == "release") :
						$e->release = xmlvalue($tag);
					elseif (xmlname($tag) == "licence") :
						$e->licence= xmlvalue($tag);
					elseif (xmlname($tag) == "rating") :
						$e->rating = xmlvalue($tag);
					elseif (xmlname($tag) == "downloads") :
						$e->downloads = xmlvalue($tag);
					elseif (xmlname($tag) == "category") :
						# FIXME: is attribute!
						$e->category = xmlvalue($tag);
					elseif (xmlname($tag) == "summary") :
						$e->summary = xmlvalue($tag);
					elseif (xmlname($tag) == "payload") :
						$e->payload = xmlvalue($tag);
					elseif (xmlname($tag) == "preview") :
						$e->preview = xmlvalue($tag);
					endif;
				}

				$e->summaries = array();

				$this->entries[] = $e;
			endif;
		}

		return true;
	}

	function get()
	{
		return $this->entries;
	}
}

class CocodriloTemplate
{
	var $theme;
	var $feed;

	function CocodriloTemplate($feed)
	{
		$this->feed = $feed;
	}

	function substitute_entry($contents, $e)
	{
		if ($e->preview) :
			if (!$this->feed) :
				$previewfile = "directory/" . $e->category . "/" . $e->preview;
			else :
				$previewfile = $e->preview;
			endif;
		else :
			$previewfile = "";
		endif;

		if ($previewfile) :
			$previewimage = "<img src='$previewfile'>";
		else :
			$previewimage = "";
		endif;

		if ($e->downloads) :
			$downloads = $e->downloads;
		else :
			$downloads = "(none yet)";
		endif;

		if ($e->rating) :
			$rating = $e->rating;
		else :
			$rating = "(not yet rated)";
		endif;

		$tmp = $contents;
		$tmp = preg_replace("/%AUTHOR%/", $e->author, $tmp);
		$tmp = preg_replace("/%NAME%/", $e->name, $tmp);
		$tmp = preg_replace("/%VERSION%/", $e->version, $tmp);
		$tmp = preg_replace("/%RELEASE%/", $e->release, $tmp);
		$tmp = preg_replace("/%RELEASEDATE%/", $e->releasedate, $tmp);
		$tmp = preg_replace("/%SUMMARY%/", $e->get_htmlsummary(), $tmp);
		$tmp = preg_replace("/%CATEGORY%/", $e->category, $tmp);
		$tmp = preg_replace("/%DOWNLOAD%/", $e->payload, $tmp);
		$tmp = preg_replace("/%PREVIEW%/", $previewfile, $tmp);
		$tmp = preg_replace("/%PREVIEWIMAGE%/", $previewimage, $tmp);
		$tmp = preg_replace("/%DOWNLOADS%/", $downloads, $tmp);
		$tmp = preg_replace("/%RATING%/", $rating, $tmp);
		$tmp = preg_replace("/%LICENCE%/", $e->licence, $tmp);
		$tmp = preg_replace("/%ID%/", $e->id, $tmp);
		$tmp = preg_replace("/%LANGUAGES%/", $e->get_languages(), $tmp);
		$tmp = preg_replace("/%SIZE%/", $e->get_size(), $tmp);

		$tmp = preg_replace("/%SAMEAUTHOR%/", $e->get_sameauthor(), $tmp);
		$tmp = preg_replace("/%SAMECATEGORY%/", $e->get_samecategory(), $tmp);

		return $tmp;
	}
}


?>
