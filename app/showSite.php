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
            <div class="ui stacked segment">
                <h4 class="ui">Site <?php echo htmlspecialchars($GET_['siteName']); ?></h4>
                <div class="ui list">
                    <div class="item">
                        <div class="content">
                            <a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8081/'.$GET_['siteName']);>">HTTP 1.1</a>
                        </div>
                    </div>
                    <div class="item">
                        <div class="content">
                            <a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8082/'.$GET_['siteName']);>">HTTP 2</a>
                        </div>
                    </div>
                    <div class="item">
                        <div class="content">
                            <a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8083/'.$GET_['siteName']);>">HTTP 2 + push</a>
                        </div>
                    </div>
                </div>
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
</body>
</html>
