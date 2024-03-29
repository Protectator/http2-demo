<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/semantic-ui/2.1.8/semantic.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/semantic-ui/2.1.8/components/popup.min.css"/>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <title>http2-demo</title>
    <style type="text/css">body{background-color:#FFFFFF;}.ui.menu .item img.logo{margin-right:1.5em;}.main.container{margin-top:7em;}.wireframe{margin-top:2em;}.ui.footer.segment{margin:5em 0em 0em;padding:5em 0em;}.after-footer{background:#1b1c1d;}</style>
</head>
<body>
<div class="ui stackable inverted menu" id="topBar">
    <div class="ui container">
        <a href="/" class="header item">
            http2-demo
        </a>
        <a href="/" class="item">Home</a>
    </div>
</div>
<div class="ui main text container" style="margin-top: 1.5em;" id="stickContext">
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui image">http2-demo</h2>
            <h3 class="ui">Compare HTTP 1.1, HTTP/2 and HTTP/2 + PUSH</h3>
            <div class="ui stacked segment left aligned" id="dlContent">
                <p>Download of <?php echo htmlspecialchars($_GET['url']); ?> in progress... You'll be redirected once it has ended.</p>
                <p>If you haven't been redirected after a minute, click <a href="/compareSite.php?siteName=<?php echo htmlspecialchars($_GET['url']); ?>">here</a>.
                <pre id="feedback">
                </pre>
            </div>
        </div>
    </div>
</div>
<div class="ui inverted vertical footer segment">
    <div class="ui center aligned container">
        <div class="ui horizontal inverted small divided link list">
            <span class="item">http2-demo</span>
        </div>
    </div>
</div>
<div class="after-footer"></div>
<?php

ob_implicit_flush();

$shell_instruction = 'cd ..; /srv/http2-demo/downloadSite ';
$shell_instruction .= escapeshellarg($_GET['url']);

while (@ ob_end_flush()); // end all output buffers if any
$proc = popen($shell_instruction, 'r');
while (!feof($proc))
{
    fread($proc, 4096);
    @ flush();
}
$status = pclose($proc);
if ($status == 0) {
    $script = "<script type='text/javascript'> window.location.replace('/compareSite.php?siteName=".htmlspecialchars($_GET['url']). "');</script>";
    echo $script;
} else {
    echo "<script>feedback.innerHTML = 'Download failed : ";
    echo $status;
    echo "';</script>";
}
?>
</body>
</html>
