<?php

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
			$filter .= " AND summary_ref IN " .
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

			$summaryref = pg_result($res, $i, "summary_ref");
			$payloadref = pg_result($res, $i, "payload_ref");
			$previewref = pg_result($res, $i, "preview_ref");

			$lang = "de";
			$e->summary = $this->autoselect($summaryref, "summary", $lang);
			$e->payload = $this->autoselect($payloadref, "payload", $lang);
			$e->preview = $this->autoselect($previewref, "preview", $lang);

			$e->summaries = $this->multiple($summaryref, "summary");
			$e->payloads = $this->multiple($payloadref, "payload");
			$e->previews = $this->multiple($previewref, "preview");

			$this->entries[] = $e;
		}
	}

	function get()
	{
		return $this->entries;
	}
}

class CocodriloTemplate
{
	var $theme;

	function CocodriloTemplate()
	{
	}

	function substitute_entry($contents, $e)
	{
		if ($e->preview) :
			$previewfile = "directory/" . $e->category . "/" . $e->preview;
		else :
			$previewfile = "";
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

