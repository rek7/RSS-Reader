<?php

$tech_rss = array(
"https://latesthackingnews.com/feed",
"http://www.zdnet.com/blog/security/rss",
"https://www.techworld.com/news/rss",
"http://rss.nytimes.com/services/xml/rss/nyt/Technology.xml",
"https://www.bleepingcomputer.com/feed/",
"https://blog.hackersonlineclub.com/feeds/posts/default?alt=rss",
"https://hnrss.org/frontpage"
);

$business_rss = array(
"https://cryptocurrencynews.com/feed/",
"https://cointelegraph.com/feed",
"http://rss.cnn.com/rss/money_latest.rss",
"http://markets.businessinsider.com/rss/news",
"http://feeds.bbci.co.uk/news/business/rss.xml",
"https://www.investopedia.com/feedbuilder/feed/getfeed/?feedName=rss_headline",
"https://www.economist.com/rss/the_world_this_week_rss.xml",
"https://fortune.com/feed/",
"https://www.huffingtonpost.com/section/business/feed",
"https://www.investing.com/rss/investing_news.rss"
);

$politics_rss = array(
"http://rss.cnn.com/rss/cnn_allpolitics.rss",
"http://online.wsj.com/xml/rss/3_7087.xml",
"http://www.msnbc.com/feeds/latest",
"http://rt.com/rss"
);

$news_rss = array(
"http://feeds.reuters.com/Reuters/worldNews",
"http://feeds.bbci.co.uk/news/world/rss.xml",
"https://www.cnbc.com/id/100003114/device/rss/rss.html",
"https://nypost.com/feed/",
"http://rss.cnn.com/rss/cnn_topstories.rss",
"http://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml"    
);

date_default_timezone_set("GMT+0");

function make_rss_request($site)
{

    $ch = curl_init(); 
    
    $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36';
    
    curl_setopt($ch, CURLOPT_URL, $site); 
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch,CURLOPT_TIMEOUT, 3); // 3 second timeout

    $output = curl_exec($ch); 

    curl_close($ch);        
    
    return $output;
}

function print_div($rss_array, $section_name)
{
    $url_array = array();
    echo '
                <body>
                    <div class="container">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a data-toggle="tab" href="#'.$section_name.'overview">General Overview</a></li>
    ';
    foreach($rss_array as $tech)
    {
        $rss_string = json_decode(json_encode(simplexml_load_string(make_rss_request($tech), 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        str_replace(array('<\![CDATA[',']]>'), '', $rss_string);
        array_push($url_array, $rss_string);
        $real_location = count($url_array)-1;
        echo '<li><a data-toggle="tab" href="#'.$section_name.$real_location.'">'.$rss_string["channel"]["title"].'</a></li>';
    } 
    echo ' 
                        </ul>
                    </div>
                    <div class="tab-content">
    ';
    
    
    echo '<div id="'.$section_name.'overview" class="tab-pane fade">';
        foreach($url_array as $site)
        {
            echo '<h1>'.$site["channel"]["title"].'</h1>';
            $count = 0;
            foreach($site["channel"]['item'] as $article)
            {
                echo '<div class="container">
                        <div class="panel panel-default">
                          <div class="panel-heading">'.$article['title'].'</div>
                            <div class="panel-body"><p><a href="'.$article["link"].'">Article link</a></p>
                            <p>Date of publish: '.$article['pubDate'].'</p>
                            </div>
                          </div>
                     </div>';
                 $count++;
                 if($count >= 3)
                 {
                     break;
                 }
            }
        }
    echo '</div>';
    
    foreach($url_array as $key=>$site)
    {
        echo '<div id="'.$section_name.$key.'" class="tab-pane fade">';
        
        echo '<h1>'.$site["channel"]["title"].'</h1>';
        foreach($site["channel"]['item'] as $article)
        {
            echo '<div class="container">
                    <div class="panel panel-default">
                      <div class="panel-heading">'.$article['title'].'</div>
                        <div class="panel-body">'.$article['description'].'
                        <p><a href="'.$article["link"].'">Article link</a></p>
                        <p>Date of publish: '.$article['pubDate'].'</p>
                        </div>
                      </div>
                 </div>';
        }
        echo '</div>';
    }
    echo '
                    </div>
                </body>    
    ';
}
?>
<html lang="en">
<font color="white">
    
<head>
    <title>RSS Reader</title>
    <meta charset="UTF-8">
    <meta name="description" content="RSS Feeds from many news sources">
    <meta name="keywords" content="News,RSS,Current Events">
    <meta name="author" content="Rek7">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://meyerweb.com/eric/tools/css/reset/reset.css">
    <link rel="stylesheet" href="https://bootswatch.com/3/sandstone/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 95%;
            width: 100%;
        }       
        body {
            background-color: #111111;
        }
        .panel.panel-default{
            background-color: #00171f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Multi RSS Reader</h2>
        <p>RSS tabs below. Last time of page update: <?php echo date('Y-m-d H:i:s').' '.date_default_timezone_get(); ?> Click <a HREF="javascript:history.go(0)">Here</a> to refresh the page.</p>

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            <li><a data-toggle="tab" href="#tech">Tech RSS</a></li>
            <li><a data-toggle="tab" href="#business">Business RSS</a></li>
            <li><a data-toggle="tab" href="#politics">Politics RSS</a></li>
            <li><a data-toggle="tab" href="#news">News RSS</a></li>
            <li><a data-toggle="tab" href="#about">About</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>HOME</h3>
                <p>Select a tab to begin reading.</p>
            </div>
            <div id="tech" class="tab-pane fade">
                <h3>Tech RSS feeds</h3>
                <p>Below are tech sites.</p>
                <?php print_div($tech_rss, "tech_"); ?>
            </div>
            <div id="business" class="tab-pane fade">
                <h3>Business RSS</h3>
                <p>Business sites below.</p>
                <?php print_div($business_rss, "business_"); ?>
            </div>
            <div id="politics" class="tab-pane fade">
                <h3>Politics RSS</h3>
                <p>Political sites below.</p>
                <?php print_div($politics_rss, "politics_"); ?>
            </div>
            <div id="news" class="tab-pane fade">
                <h3>News</h3>
                <p>News feeds below.</p>
                <?php print_div($news_rss, "news_"); ?>
            </div>
            <div id="about" class="tab-pane fade">
                <h3>About</h3>
                <p>RSS reader created by <a href="https://github.com/rek7">@rek7</a></a>.</p>
            </div>
        </div>
    </div>

</body>
</font>
</html>
