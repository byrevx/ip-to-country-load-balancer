<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<h3>Shortcode Example</h3>

<b>Show visitor country code</b>
<pre class="code-text">
[IP2clb]

or

[IP2clb show=country]
</pre>

<b>Show country code by IP</b>
<pre class="code-text">
[IP2clb ip=79.118.113.143]
</pre>


<b>Show visitor IP</b>
<pre class="code-text">
[IP2clb show=ip]
</pre>

<b>Show visitor country flag image, default size is 720px (width)</b>
<pre class="code-text">
[IP2clb show=flag]
</pre>

<b>Show country flag image by IP</b>
<pre class="code-text">
[IP2clb show=flag ip=79.118.113.143]
</pre>

<b>Show country flag image by IP with custom size from range: </b>
<br />
<i>[100, 1024, 128, 1280, 1440, 150, 16, 1920, 200, 250, 2560, 32, 320, 3840, 48, 64, 640, 720]</i>
<br />
<span>The default size is 720px wide. The height depends on the format of the flag.</span>
<pre class="code-text">
[IP2clb show=flag size=1024 ip=79.118.113.143 ]
</pre>

<b>Show country flag image by IP with custom size and cusom extension from range: </b>
<br />
<i>['avif', 'webp', 'jxl', 'jpg', 'png']</i>
<br />
<span>The default extension is AVIF, the best performing image format, also suggested by google (PageSpeed Insights) along with WEBP instead of JPEG format.
<pre class="code-text">
[IP2clb show=flag ip=39.156.66.10 ext=webp]
</pre>

<?php 
