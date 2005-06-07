<?php include_once("../header.inc"); ?>

<div class="rightcontainer">

<div class="rightbox">
<h2>Hotstuff</h2>
<p>
<a href="http://www.kstuff.org/hotstuff/">Homepage</a><br/>
<a href="http://www.kstuff.org/source/?p=cvs">Downloads/CVS</a><br/>
</p>
</div>

</div>

<div class="content">

<h2>Hotstuff backend scripts</h2>
<p>
A collection of perl scripts which ensure that GHNS actually works automatically
without requiring the application authors to handle contributions manually.
The script hotstuff-scan.pl scans uploads and moves them to the admin queue
or the download area. The script hotstuff-downloads.pl counts downloads and
writes the values back into the database. PHP scripts exist to dynamically
return data based on requests, and allow for load-balancing download servers.
The script hotstuff-http.pl handles uploads being done via HTTP.
Finally, the SQL schema and such is included.
</p>

<h2>Hotstuff web interface</h2>
<p>
A web application which consists of several parts. The user part allows
the user to navigate through uploaded content, rate it and download it.
The admin part allows to allow or reject incoming uploads, add translations
and categorise the data. The upload part allows HTTP-based data uploads.
The repository browser part is a meta-frontend which lets the user select
from many different GHNS repositories.
</p>

</div>

<?php include_once("../footer.inc"); ?>
