<?php include_once("header.inc"); ?>

<div class="rightcontainer">

<div class="rightbox">
<h2>Intro</h2>
<p>
GHNS enables creative desktop users to share their work.
Find out more <a href="about.php">here</a>.
</p>
</div>

<div class="rightbox linkbox">
<h2>Projects</h2>
<a href="/dxs/" title="Desktop Exchange Service">DXS/Webservice</a>
<a href="/hotstuff/" title="Hotstuff Server Framework">Hotstuff</a>
<a href="/spec/" title="GHNS Standard Specification">GHNS Standard</a>
<a href="/docs/" title="Additional Documentation">Documentation</a>
</div>

<div class="rightbox linkbox">
<h2>Implementations</h2>
<a href="/knewstuff/" title="KNewStuff (for KDE)">KNewStuff</a>
<a href="/sdlnewstuff/" title="SDLNewStuff (for SDL games)">SDLNewStuff</a>
<a href="/gnome-art/" title="GNOME Art (for GNOME)">GNOME Art</a>
</div>

</div>

<div class="content">

<h2>The idea of collaborative work</h2>
<p>
Free desktops empower their users to work together over the internet, and share their
ideas, artwork, scripts and files. The missions of GHNS is to provide the necessary infrastructure
on the client, the server and the protocols in between.
</p>

<h2>(07.06.2005) Newsflash: Desktop Exchange Service (DXS)</h2>
<p>
Based on an idea by <a href="contact.html">Michael Gebhart</a>, GHNS should also be accessible
as a webservice. The Desktop Exchange Service works similar to "traditional" uploads
and downloads, except that all data is embedded in a single SOAP message. The first implementation
is available thanks to the SOAP::Lite Perl module. Further work on finding a suitable format
is going on.
</p>

<h2>(07.06.2005) GHNS Project goes live!</h2>
<p>
The GHNS Project, formerly hosted at http://kstuff.org/ghns/, has found its new home at
http://ghns.berlios.de/. While the old URL remains to cover KDE-specific parts, the new one
enables us to advance the project in a cross-desktop way.
</p>

</div>

<?php include_once("footer.inc"); ?>
