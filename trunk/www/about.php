<?php include_once("header.inc"); ?>

<div class="rightcontainer">

<div class="rightbox">
<h2>See also...</h2>
<p>
There's an <a href="http://dot.kde.org/1110652641/">interview</a> on dot.kde.org
explaining some of the concepts and plans.
</p>
</div>

</div>

<div class="content">

<h2>About GHNS</h2>
<p>
"Get Hot New Stuff" is the developer-biased codename for a concept which allows
users to create data, share it with others over the internet, provide evaluation
by user ratings and download numbers, and finally offers a convenient way to
download the files one wants to install. Since many different usage scenarios
exist, the GHNS name refers to the underlying concept, whereas the actual
implementations carry different names.
GHNS consists of software for the desktops and the server, as well as a webservice
architecture, and the specifications of file formats and protocols upon which
the whole concept is based.
</p>

<h2>History</h2>
<p>
The basic idea for uploads, downloads, user ratings and collaboration came into
existance during LinuxTag 2002. Before the event was over, a proof of concept
implementation was created on the KDE booth, dubbed KDEShare. Over the next months,
a similar design was used in KOrganizer, and both were finally merged to KNewStuff
during the 2003 Kastle conference. Since then, a lot of development went into the
library, but even more so into the surrounding framework.
In May 2005, the idea of extending these concepts to other desktops were shaped,
as the GNOME Art downloader provided a similar funcionality,
and in June 2005 the new GHNS website went live.
</p>

<h2>Architecture</h2>
<p>
On the client side, a desktop library provides upload and download dialogs to all
applications. The data is sent to or received from a server, on which several tools
ensure that the format is correct, the data is sorted and so on. Additionally, web
interfaces allow for a quick access from anywhere, while webservices enable application
authors to integrate GHNS functionality in modern SOAP-based software.
</p>

</div>

<?php include_once("footer.inc"); ?>
