<?php error_reporting(0); ?>
<div class="row">
    <div class="col-md-12">
        <!-- Header -->
        <!-- #1B1A1A grey header -->
        <div style="background-color: #000d1a;"  id="header-wrapper">
            <div id="header" class="container">

                <!-- Banner -- 76403.46 pounds in pennies -->
                <h1 id="logo">Steam Currently Weighs: </h1><br />
                <h1 id="logo"><div id="test4">9740.31 pounds</div><div id="test3" style="display:none;"></div></h1>
                <p>Last updated: 6/11/2017</p>

                <!-- Nav -->
                <nav id="nav">
                    <ul>
                        <li><a class="icon fa-child" href="#" id="test1"><span>Physical Weight</span></a></li>
                        <li><a class="icon fa-dollar" href="#" id="test2"><span>Value Weight</span></a></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('#test1').on('click',function(){
        if($('#test4').css('display')!='none'){
            $('#test3').html('9740.31 pounds').show().siblings('div').hide();
            document.getElementById('val').style.display = "none";
            document.getElementById('phy').style.display = "block";
        }else if($('#test4').css('display')!='none'){
            $('#test3').show().siblings('div').hide();
            document.getElementById('phy').style.display = "none";
            document.getElementById('val').style.display = "block";
        }
    });

    $('#test2').on('click',function(){
        if($('#test3').css('display')!='none'){
            $('#test4').html('79311.36 pounds in pennies').show().siblings('div').hide();
            document.getElementById('phy').style.display = "none";
            document.getElementById('val').style.display = "block";
        }else if($('#test3').css('display')!='none'){
            $('#test4').show().siblings('div').hide();
            document.getElementById('val').style.display = "none";
            document.getElementById('phy').style.display = "block";
        }
    });

</script>