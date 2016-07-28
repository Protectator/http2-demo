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
    <div class="ui middle aligned center aligned grid container">
        <div class="column">
            <h2 class="ui image">http2-demo</h2>
            <h3 class="ui">Compare HTTP 1.1, HTTP/2 and HTTP/2 + PUSH</h3>
            <div class="ui stacked segment">
                <h4 class="ui">Site <?php echo htmlspecialchars($_GET['siteName']); ?></h4>
                <div class="ui form">
                    <div class="inline field">
                        <label>Delay</label>
                        <div id="testInput" class="ui right action input">
                            <input type="number" value="<?php echo intval(file_get_contents("currentDelay.txt")); ?>" id="delay" name="delay" min="0" max="10000" style="width:84px; padding-right: 4px;">
                            <button class="ui teal labeled icon button" id="delayButton">
                                <i class="hourglass half icon"></i>
                                Set
                            </button>
                        </div>
                    </div>
                    <div class="inline field">
                        <label>Number of tests</label>
                        <div id="testInput" class="ui right action input">
                            <input type="number" value="5" id="numberOfTests" name="numberOfTests" min="0" max="10000" style="width:84px; padding-right: 4px;">
                            <button class="ui teal labeled icon button" id="launchButton">
                                <i class="lab icon"></i>
                                Run
                            </button>
                        </div>
                    </div>
                </div>
                <p id="runningTest"></p>
                <table class="ui celled striped table">
                    <thead>
                    <tr>
                        <th>Protocol</th>
                        <th><a id="link-h1" target="_blank">HTTP 1.1</a></th>
                        <th><a id="link-h2" target="_blank">HTTP 2</a></th>
                        <th><a id="link-h2push" target="_blank">HTTP 2 + PUSH</a></th>
                    </tr>
                    </thead><tbody>
                    <tr>
                        <td style="width:10%">View</td>
                        <td style="width:30%; height:250px;" id="h1container">Ready</td>
                        <td style="width:30%; height:250px;" id="h2container">Ready</td>
                        <td style="width:30%; height:250px;" id="h2pushcontainer">Ready</td>
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

                    $values = array_values($stats);

                    $id = 0;
                    foreach ($stats as $key => $value) {
                        if ($id == 6) {
                            echo "</tbody><tfoot>";
                        }
                        if ($id < 6) {
                            echo "<tr><td>".$key."</td><td id='stat-h1-".$id."'></td><td id='stat-h2-".$id."'></td><td id='stat-h2push-".$id."'></td></tr>";
                        } else {
                            echo "<tr><th>".$key."</th><th id='stat-h1-".$id."'></th><th id='stat-h2-".$id."'></th><th id='stat-h2push-".$id."'></th></tr>";
                        }
                        $id++;
                    }
                    ?>
                    </tfoot>
                </table>
                <p>
                    <b>Average</b> | Min | Max<br>
                    All units are in milliseconds [ms].
                </p>
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

    var search = window.location.search;
    var n = search.lastIndexOf('siteName=');
    var siteName = search.substring(n + 9);
    $('#link-h1').setAttribute("href", window.location.hostname + ':8081/' + siteName);
    $('#link-h2').setAttribute("href", window.location.hostname + ':8082/' + siteName);
    $('#link-h2push').setAttribute("href", window.location.hostname + ':8083/' + siteName);

    var origins = [window.location.hostname + ':8081',
        window.location.hostname + ':8082',
        window.location.hostname + ':8083'];

    total = [];
    total['avg'] = [];
    total['avg'][0] = [];
    total['avg'][1] = [];
    total['avg'][2] = [];
    total['min'] = [];
    total['min'][0] = [];
    total['min'][1] = [];
    total['min'][2] = [];
    total['max'] = [];
    total['max'][0] = [];
    total['max'][1] = [];
    total['max'][2] = [];
    total['pass'] = 0;
    total['tests'] = 0;

    function round1(float) {
        return Math.round(float * 10) / 10;
    }

    window.addEventListener("message",
        function (e) {
            var timing = JSON.parse(e.data);
            var stats = [<?php echo implode(", ", $values); ?>];
            console.log("Message recieved ! : " + e.origin + " : " + e.data);
            var i;
            switch(e.origin) {
                case origins[0]:
                    for (i = 0; i < stats.length; i++) {
                        if (total['avg'][0][i]) {
                            total['avg'][0][i] += stats[i];
                        } else {
                            total['avg'][0][i] = stats[i];
                        }
                        if (total['min'][0][i]) {
                            total['min'][0][i] = Math.min(total['min'][0][i], stats[i]);
                        } else {
                            total['min'][0][i] = stats[i];
                        }
                        if (total['max'][0][i]) {
                            total['max'][0][i] = Math.max(total['max'][0][i], stats[i]);
                        } else {
                            total['max'][0][i] = stats[i];
                        }
                        var currentAvg = total['avg'][0][i]/total['pass'];
                        var currentMin = total['min'][0][i];
                        var currentMax = total['max'][0][i];
                        $('#stat-h1-' + i).html("<b>" + round1(currentAvg) + "</b> | " + currentMin + " | " + currentMax);
                    }
                    break;
                case origins[1]:
                    for (i = 0; i < stats.length; i++) {
                        if (total['avg'][1][i]) {
                            total['avg'][1][i] += stats[i];
                        } else {
                            total['avg'][1][i] = stats[i];
                        }
                        if (total['min'][1][i]) {
                            total['min'][1][i] = Math.min(total['min'][1][i], stats[i]);
                        } else {
                            total['min'][1][i] = stats[i];
                        }
                        if (total['max'][1][i]) {
                            total['max'][1][i] = Math.max(total['max'][1][i], stats[i]);
                        } else {
                            total['max'][1][i] = stats[i];
                        }
                        var currentAvg = total['avg'][1][i]/total['pass'];
                        var currentMin = total['min'][1][i];
                        var currentMax = total['max'][1][i];
                        $('#stat-h2-' + i).html("<b>" + round1(currentAvg) + "</b> | " + currentMin + " | " + currentMax);
                    }
                    break;
                case origins[2]:
                    for (i = 0; i < stats.length; i++) {
                        if (total['avg'][2][i]) {
                            total['avg'][2][i] += stats[i];
                        } else {
                            total['avg'][2][i] = stats[i];
                        }
                        if (total['min'][2][i]) {
                            total['min'][2][i] = Math.min(total['min'][2][i], stats[i]);
                        } else {
                            total['min'][2][i] = stats[i];
                        }
                        if (total['max'][2][i]) {
                            total['max'][2][i] = Math.max(total['max'][2][i], stats[i]);
                        } else {
                            total['max'][2][i] = stats[i];
                        }
                        var currentAvg = total['avg'][2][i]/total['pass'];
                        var currentMin = total['min'][2][i];
                        var currentMax = total['max'][2][i];
                        $('#stat-h2push-' + i).html("<b>" + round1(currentAvg) + "</b> | " + currentMin + " | " + currentMax);
                    }
                    break;
                default:
                    console.log(e.origin + " : " + e.data);
            }
        },
        false);

    $("#launchButton").click(function() {
        $("#launchButton").prop('disabled', true);
        $("#testInput").prop('disabled', true);
        $("#launchButton").addClass('loading disabled');
        $("#testInput").addClass('disabled');
        $("#delay").prop('disabled', true).addClass('disabled');
        $("#delayButton").prop('disabled', true).addClass('disabled');
        total['tests'] += parseInt($("#numberOfTests")[0].value);
        launchBenchmark(500, parseInt($("#numberOfTests")[0].value));
    });

    $("#delayButton").click(function(){
        var delayValue = $("#delay")[0].value;
        $("#delay").prop('disabled', true).addClass('disabled');
        $("#delayButton").prop('disabled', true).addClass('disabled');
        $.get("/delay.php?delay=" + delayValue, function(data) {
            if (data.substring(0, 5) == "ERROR") {
                alert(data);
            } else {
                alert("Delay has been set to " + data + "ms.");
            }
            $("#delay").prop('disabled', false).removeClass('disabled');
            $("#delayButton").prop('disabled', false).removeClass('disabled');
        });
    });

    function launchBenchmark(delay, times) {
        $('#h1container').html("");
        $('#h2container').html("");
        $('#h2pushcontainer').html("");
        $('<iframe>', {
            id:  'h1',
            style: 'width:100%; height:100%;'
        }).appendTo('#h1container');
        $('<iframe>', {
            id:  'h2',
            style: 'width:100%; height:100%;'
        }).appendTo('#h2container');
        $('<iframe>', {
            id:  'h2push',
            style: 'width:100%; height:100%;'
        }).appendTo('#h2pushcontainer');
        total['pass']++;
        $("#runningTest").html("Test " + total['pass'] + " / " + total['tests']);
        console.log("Starting test " + total['pass']);
        console.log("Initializing iframes");
        var h1 = $('#h1');
        var h2 = $('#h2');
        var h2push = $('#h2push');
        h1[0].setAttribute("src", "<?php echo htmlspecialchars($_SERVER['SERVER_NAME'].':8081/'.$_GET['siteName']); ?>");
        h1.load(function(){
            h1[0].parentNode.innerHTML = "Test complete";
            setTimeout(function(){
                h2[0].setAttribute("src", "<?php echo htmlspecialchars($_SERVER['SERVER_NAME'].':8082/'.$_GET['siteName']); ?>");
                h2.load(function(){
                    h2[0].parentNode.innerHTML = "Test complete";
                    setTimeout(function() {
                        h2push[0].setAttribute("src", "<?php echo htmlspecialchars($_SERVER['SERVER_NAME'].':8083/'.$_GET['siteName']); ?>");
                        h2push.load(function(){
                            h2push[0].parentNode.innerHTML = "Test complete";
                            console.log("Loading finished");
                            if (--times > 0) {
                                setTimeout(function() {
                                    launchBenchmark(delay, times);
                                }, delay);
                            } else {
                                $("#launchButton").prop('disabled', false);
                                $("#testInput").prop('disabled', false);
                                $("#launchButton").removeClass('loading disabled');
                                $("#testInput").removeClass('disabled');
                                $("#delay").prop('disabled', false).removeClass('disabled');
                                $("#delayButton").prop('disabled', false).removeClass('disabled');
                            }
                        });
                    }, delay);
                });
            }, delay);
        });
    }

</script>
</html>
