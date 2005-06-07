<?php include_once("../header.inc"); ?>

<div class="rightcontainer">

<div class="rightbox">
<h2>Desktop Exchange Service</h2>
<p>
<a href="dxs.tar.gz">Download</a> (v0.1)<br/>
</p>
</div>

</div>

<div class="content">

<h2>The Desktop Exchange Service</h2>
<p>
The DXS allows for upload and download of very customised information.
For instance, an upload call would include the data file, its preview and
its meta information in one file, whereas a download list request could
be generated based on various parameters such as time of last modification,
popularity, data type and more.
</p>

<h2>Software and Design</h2>
<p>
A preliminary implementation exists based on SOAP::Lite as webservice
and client-side test script, written in Perl. Efforts are going on in
defining the WSDL and WSGUI files for easy desktop access.
</p>

</div>

<?php include_once("../footer.inc"); ?>
