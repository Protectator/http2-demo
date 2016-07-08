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
<div class="ui stackable inverted menu" id="topBar" style="margin-top: 0px;">
    <div class="ui container">
        <a href="/" class="header item">
            http2-demo
        </a>
        <a href="/" class="item">Home</a>
    </div>
</div>
<div class="ui main text" style="margin-top: 1.5em;" id="stickContext">
    <div class="ui middle aligned center aligned grid">
        <div class="column">
            <h2 class="ui image">http2-demo</h2>
            <h3 class="ui">Compare HTTP 1.1, HTTP/2 and HTTP/2 + PUSH</h3>
            <div class="ui stacked segment">
                <h4 class="ui">Site <?php echo htmlspecialchars($_GET['siteName']); ?></h4>
                <div class="ui form">
                    <div class="inline field">
                        <label>Number of tests</label>
                        <div class="ui right action input">
                            <input type="number" value="5" id="numberOfTests" name="numberOfTests" min="0" max="10000" style="width:84px; padding-right: 4px;">
                            <button class="ui teal labeled icon button" id="launchButton">
                                <i class="lab icon"></i>
                                Run
                            </button>
                        </div>
                    </div>
                </div>
                <table class="ui celled striped table">
                    <thead>
                    <tr>
                        <th>Protocol</th>
                        <th><a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8081/'.$_GET['siteName']); ?>" target="_blank">HTTP 1.1</a></th>
                        <th><a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8082/'.$_GET['siteName']); ?>" target="_blank">HTTP 2</a></th>
                        <th><a href="<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8083/'.$_GET['siteName']); ?>" target="_blank">HTTP 2 + PUSH</a></th>
                    </tr>
                    </thead><tbody>
                    <tr>
                        <td style="width:10%">View</td>
                        <td style="width:30%"><iframe id="h1" style="width:100%; height:500px;">Loading...</iframe></td>
                        <td style="width:30%"><iframe id="h2" style="width:100%; height:500px;">Loading...</iframe></td>
                        <td style="width:30%"><iframe id="h2push" style="width:100%; height:500px;">Loading...</iframe></td>
                    </tr>
                    <?php
                    $stats = array(
                        "Redirect time" => "timing['redirectEnd'] - timing['navigationStart']",
                        "DNS time" => "timing['domainLookupEnd'] - timing['redirectEnd']",
                        "TCP time" => "timing['requestStart'] - timing['domainLookupEnd']",
                        "Request time" => "timing['responseStart'] - timing['requestStart']",
                        "Response time" => "timing['responseEnd'] - timing['responseStart']",
                        "DOM Processing time" => "timing['domComplete'] - timing['domLoading']",
                        "Time to responseEnd" => "timing['responseEnd'] - timing['navigationStart']",
                        "Time to domContentLoaded" => "timing['domContentLoadedEventStart'] - timing['navigationStart']",
                        "Time to domComplete" => "timing['domComplete'] - timing['navigationStart']",
                    );

                    $id = 0;
                    foreach ($stats as $key => $value) {
                        if ($id == 5) {
                            echo "</tbody><tfoot>";
                        }
                        if ($id <= 5) {
                            echo "<tr><td>".$key."</td><td id='stat-h1-".$id."'></td><td id='stat-h2-".$id."'></td><td id='stat-h2push-".$id."'></td></tr>";
                        } else {
                            echo "<tr><th>".$key."</th><th id='stat-h1-".$id."'></th><th id='stat-h2-".$id."'></th><th id='stat-h2push-".$id."'></th></tr>";
                        }
                        $id++;
                    }
                    ?>
                    </tfoot>
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
    var origins = ["https://fraudit.tic.heia-fr.ch:8081",
        "https://fraudit.tic.heia-fr.ch:8082",
        "https://fraudit.tic.heia-fr.ch:8083"];

    total = [];
    total[0] = [];
    total[1] = [];
    total[2] = [];

    window.addEventListener("message",
        function (e) {
            var timing = JSON.parse(e.data);
            var stats = [<?php echo $stats.implode(", ") ?>];
            console.log("Message recieved ! : " + e.origin + " : " + e.data);
            var i;
            switch(e.origin) {
                case origins[0]:
                    for (i = 0; i < stats.length; i++) {
                        total[0][i] += stats[i];
                        $('#stat-h1-' + i).html(stats[i] + "ms");
                    }
                    break;
                case origins[1]:
                    for (i = 0; i < stats.length; i++) {
                        total[1][i] += stats[i];
                        $('#stat-h2-' + i).html(stats[i] + "ms");
                    }
                    break;
                case origins[2]:
                    for (i = 0; i < stats.length; i++) {
                        total[2][i] += stats[i];
                        $('#stat-h2push-' + i).html(stats[i] + "ms");
                    }
                    break;
                default:
                    console.log(e.origin + " : " + e.data);
            }
        },
        false);

    $("#launchButton").click(function() {
        launchBenchmark(1000, $("#numberOfTests"));
    });

    function launchBenchmark(delay, times) {
        console.log("Initializing iframes");
        var h1 = $('#h1');
        var h2 = $('#h2');
        var h2push = $('#h2push');
        h1[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8081/'.$_GET['siteName']); ?>");
        h1.load(function(){
            h1[0].parentNode.innerHTML = "Test complete";
            setTimeout(function(){
                h2[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8082/'.$_GET['siteName']); ?>");
                h2.load(function(){
                    h2[0].parentNode.innerHTML = "Test complete";
                    setTimeout(function() {
                        h2push[0].setAttribute("src", "<?php echo htmlspecialchars('https://fraudit.tic.heia-fr.ch:8083/'.$_GET['siteName']); ?>");
                        h2push.load(function(){
                            h2push[0].parentNode.innerHTML = "Test complete";
                            console.log("Loading finished");
                            if (times-- > 0) {
                                setTimeout(function() {
                                    launchBenchmark(delay, times);
                                }, delay);
                            }
                        });
                    }, delay);
                });
            }, delay);
        });
    }

</script>
</html>
