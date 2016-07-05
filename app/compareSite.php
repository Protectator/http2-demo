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
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
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
                <h4 class="ui">Site <?php echo htmlspecialchars($_GET['siteName']); ?></h4>
                <table>
                    <thead><td>Stat</td><td>HTTP 1.1</td><td>HTTP/2</td><td>HTTP/2 + PUSH</td></thead>
                    <tr>
                        <td>View</td>
                        <td><iframe id="h1" style="width: 32%">HTTP 1.1</iframe></td>
                        <td><iframe id="h2" style="width: 32%">HTTP 2</iframe></td>
                        <td><iframe id="h2push" style="width: 32%">HTTP 2 + push</iframe></td>
                    </tr>
                    <tr>
                        <td>Stats</td>
                        <td id="statsh1"></td>
                        <td id="statsh2"></td>
                        <td id="statsh2push"></td>
                    </tr>

                </table>
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
<script>
    $(document).ready(function() {
        console.log("Initializing iframes");
        var h1 = $('#h1');
        var h2 = $('#h2');
        var h2push = $('#h2push');
        h1[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8081/'.$_GET['siteName']); ?>");
        h1.load(function(){
            console.log();
            h2[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8082/'.$_GET['siteName']); ?>");
            h2.load(function(){
                h2push[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8083/'.$_GET['siteName']); ?>");
                h2push.load(function(){
                    console.log("Loading finished");
                });
            });
        });
    });
    var origins = ["https://fraudit.tic.heia-fr.ch:8081",
        "https://fraudit.tic.heia-fr.ch:8082",
        "https://fraudit.tic.heia-fr.ch:8083"];
    switch
    window.addEventListener("message",
        function (e) {
            switch(e.origin) {
                case origins[0]:
                    $('#statsh1').innerHTML = e.data;
                    break;
                case origins[1]:
                    $('#statsh2').innerHTML = e.data;
                    break;
                case origins[2]:
                    $('#statsh2push').innerHTML = e.data;
                    break;
            }
        },
        false);
</script>
</html>
