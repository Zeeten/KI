<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<link href="main.css" rel="stylesheet" type="text/css" media="all" />
<link href="countertest.css" rel="stylesheet" type="text/css" media="all" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GBRF Coming Soon</title>

    <style type="text/css">
        .fancybox-custom .fancybox-skin {
            box-shadow: 0 0 50px #222;
        }
        .fancybox-inner { height: 500px !important;}
    </style>
    
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="ParallaxContentSlider/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="ParallaxContentSlider/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="ParallaxContentSlider/css/style2.css" />
    <script type="text/javascript" src="ParallaxContentSlider/js/modernizr.custom.28468.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Economica:700,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="jquery.countdown.css" />
    
    <noscript>
        <link rel="stylesheet" type="text/css" href="ParallaxContentSlider/css/nojs.css" />
    </noscript>
   
    <script src="js/jquery.js" type="text/javascript"></script> 
    <script src="js/script.js" type="text/javascript"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script type="text/javascript">
        $(function () {
            var austDay = new Date();
            austDay = new Date('January 26, 2015 04:00:00 pm');
            $('#year').text(austDay.getFullYear());
  
            var roundDown = function(num){
                var full = num.toString();
                var reg = /([\d]+)/i;
                var res = reg.exec(full);
                return res[1];
            }

            function test(){
            var today = new Date(); // date and time right now
            var goLive = new Date("February 14, 2015 04:00:00 pm"); // target date
            var diffMs = (goLive - today); // milliseconds between now & target date
            var diffDays = roundDown(diffMs / 86400000); // days
            var diffHrs = roundDown((diffMs % 86400000) / 3600000); // hours
            var diffMins = roundDown(((diffMs % 86400000) % 3600000) / 60000); // minutes
            var diffSecs = roundDown((((diffMs % 86400000) % 3600000) % 60000) / 1000 );

            if(diffDays.toString().length==1){
                document.getElementById('date').innerHTML = '0'+diffDays+'<br><pstrday>Days</pstrday>';                
            }else{
            document.getElementById('date').innerHTML = diffDays+'<br><pstrday>Days</pstrday>';
            }
            if(diffHrs.toString().length==1){
                document.getElementById('hour').innerHTML = '0'+diffHrs+'<br><pstrhour>Hours</pstrhour>';                
            }else{
                document.getElementById('hour').innerHTML = diffHrs+'<br><pstrhour>Hours</pstrhour>';
            }
            if(diffMins.toString().length==1){
               document.getElementById('minute').innerHTML = '0'+diffMins+'<br><pstrminute>Minutes</pstrminute>';              
            }else{
                document.getElementById('minute').innerHTML = diffMins+'<br><pstrminute>Minutes</pstrminute>';
            }
            if(diffSecs.toString().length==1){
                document.getElementById('second').innerHTML = '0'+diffSecs+'<br><pstrsecond>Seconds</pstrsecond>';               
            }else{
                document.getElementById('second').innerHTML = diffSecs+'<br><pstrsecond>Seconds</pstrsecond>';
            }

            }

            setInterval(test, 1000);
        });
    </script>
</head>

<body>

<div id="wrapper"> 
    <section id="counter">
        <div class="top_block">         
            <div class="title">
                <img src="img/logo.png" alt="Global Book Release Forum">
               
                <div class="col">
                    <div class="container">
                        <div id="da-slider" class="da-slider">
                            <div class="da-slide" style="background:#bf0a30">
                                <p>“Mothers may still want their favorite sons
                                    to grow up to be president, but they donot want
                                    them to become politicians in the process”</p>
                                <h2>John F. Kennedy</h2>
                            </div>
                            <div class="da-slide" style="background:#002868">
                                <p>“Future mothers will surely want
                                    their  favorite sons and daughters 
                                    to grow up to be Presidents, Prime Ministers, 
                                    Chief Ministers, Ministers and 
                                    People Representatives etc.,
                                    across the Globe only if die hard professionals 
                                    come forward in numbers 
                                    for the sake of Welfare Security   of people, 
                                    do Cleansing and make Politics       
                                    It is possible NOW!</p>
                                <h2>Creative Youth</h2>
                            </div>
                        </div>
                    </div>
                </div> 
                  
            </div>
                
        <div class="cal">
            <div class="left_social_icon">
               <a href="https://www.facebook.com/CreativePoliticsCreativeIndiaCreativeYouth"> <img src="img/fb.jpg" /></a>
               <img style="margin-left:7px" src="img/tw.jpg" />
               <img src="img/g+.jpg" />
               <img style="margin-left:7px" src="img/in.jpg" />
            </div>
            
            <pday id="date"></pday><phour id="hour"></phour><pminute id="minute"></pminute><psecond id="second"></psecond>
            
             <div class="text">
                <p5>LAUNCH DATE</p5>
                <p2>14</p2>
                <p3>February</p3>
                <p4>4:00 PM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2015</p4>
                <div class="arwright"></div>
             </div>
        </div>
   
        <div class="bottom">
            <div class="abt5">
                <a href="#"><img src="img/yt.jpg"></a>
            </div>
            <ul>
                <li style="margin-left:11px"><a href="#"><img src="img/Untitled-1.jpg"></a></li>
                <li style="margin-left:11px"><a href="#"><img src="img/blg.jpg"></a></li>
                <li style="margin-left:11px"><a href="#"><img src="img/pinterest.jpg"></a></li>
            </ul>
            <div class="abt">
                <a href="#"><img src="img/about.jpg"></a>
            </div>
        </div>
        </section>
        </div>
</div>

        <script type="text/javascript" src="ParallaxContentSlider/js/jquery.cslider.js"></script>
        <script type="text/javascript">
            $(function() {
            
                $('#da-slider').cslider({
                    autoplay    : true,
                    bgincrement : 450
                });
            
            });
        </script>   
        <script type="text/javascript" src="source/jquery.fancybox.js?v=2.1.5"></script>
        <link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.1.5" media="screen" />


        <script type="text/javascript">

        $(document).ready(function() {
           // $.fancybox.helpers.overlay.defaults.closeClick=false;
          
            $.fancybox.open({
                    href : 'pop.php',
                    type : 'iframe',
                    'closeBtn' : false,
                    padding : 5
            });

            //$.fancybox.close();

            $("#test").click(function() {
                alert("in test");
                $.fancybox.close(); 
            });
             
        });

    </script>
</body>
</html>