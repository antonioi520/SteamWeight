<?php error_reporting(0); ?>
<?php
include '../accountCalc.php';
    ?>
<style>
.avatar {
padding:          3px;
background-color: <?=$avatarborder?>;
border-radius:    3px;
}
a {
    color:           <?=$avatarborder?>;
    text-decoration: none;
}
#inner {
    width: 50%;
    margin: 0 auto;
}
#gameDIV{
    display: none;
}
</style>
<script>
    $(document).ready(function () {
        // Handler for .ready() called.
        $('html, body').animate({
            scrollTop: $('#header2').offset().top
        }, 1000);
    });

</script>
<div class="container-fluid">
    <br><br>
    <?php if($private == 1) : ?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6" id="header2" style="padding-top: 3%; padding-bottom:0%;">
            <h1 id="logo" style="text-align: center; color:#504C4C;">Profile is </h1> <br> <h1 style="text-align: center; color: #9C4242;">Private</h1>
        </div>
    </div>

    <div class="row" style="width:100%;">
        <div class="col-md-3"></div>
        <div class="col-md-6" id="inner">
            <img src="../imgs/sad-xxl.png" style="display: block; margin: 0 auto;">
        </div>
    </div>
    <?php else : ?>

    <div class="row" id="phy">
        <div class="col-md-3"></div>
        <div class="col-md-6" id="header2" style="padding-top: 3%; padding-bottom:0%;">
            <h1 id="logo" style="text-align: center; color:#504C4C;">Total Physical Weight: </h1> <br> <h1 style="text-align: center; color: #9C4242;"><?php echo number_format($weightCalculation, 2);?> pounds</h1>
        </div>
    </div>

        <div class="row" id="val" style="display:none;">
            <div class="col-md-3"></div>
            <div class="col-md-6" id="header2" style="padding-top: 3%; padding-bottom:0%;">
                <h1 id="logo" style="text-align: center; color:#504C4C;">Total Value Weight: </h1> <br> <h1 style="text-align: center; color: #9C4242;"><?php echo number_format($pennyCalculation, 2);?> pounds in pennies</h1>
            </div>
        </div>

    <div class="row" style="margin-left:6%;">
        <div class="col-md-3"></div>

        <div class="col-md-3">
            <a href="<?=$profileLink;?>" style="margin-top: 2%; margin-left;20%;">
               <img class="avatar" src="<?php echo $avatar ?>" style="margin-left:15%; margin-top:4%;">
            </a>
        </div>
        <div class="col-md-3" style="padding-left:0%;">
            <div style="margin-left:0%;">
                <p style="margin-bottom: 3%;">Profile Name: <a href="<?=$profileLink?>"><?php echo $profileName; ?></a></p>
                <p style="margin-bottom: 3%;">Steam ID: <?php echo $steamid; ?></p>
                <p style="margin-bottom: 3%;">VAC Banned: <?php echo $vacban; ?></p>
                <p style="margin-bottom: 3%;">Community Banned: <?php echo $communityban; ?></p>
                <p style="margin-bottom: 3%;">Games Owned: <?php echo $gameCounter; ?></p>
                <p style="margin-bottom: 3%;">Games Not Played: <?php echo $notplayedPercent; ?>%</p>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-md-3"></div>

        <?php if ($ingame) : ?>
        <div class="col-lg-6" style="padding-left:3.3%;">
            <p style="margin-bottom: 1%">I'm currently playing:</p>
                <a href="<?=$game->link?>" target="_blank" title="<?=$game->name?>">
                    <img src="<?=$game->header?>" alt="<?=$game->name?>" style="width:200px; height:90px;" />
                </a>
        </div>
            <?php else : ?>

    <div class="col-md-6" style="padding-left:3.3%;" ><p>Recently played game<?=($profile->played_2weeks==1?'':'s')?>:</p></div>
        <div class="col-md-6">
        <?php foreach ($games as $game) : ?>
            <div class="row" style="padding-left:3.3%;">
                <div class="col-md-6"></div>
                <a href="<?=$game->link?>" target="_blank" style="padding-top: 0%;">
                    <div class="col-md-12">
                        <img src="<?=$game->image?>" title="<?=$game->name?>" /> - <?php echo $game->name ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>

    <div class="row" id="outer" style="width:100%;padding-bottom: 2%" >
        <div class="col-md-3"></div>
        <div class=col-md-6 style="padding-top:1%;" id="inner">
            <h2 style="text-align: center; margin-bottom: 0%;">Account Total Value: $<?php echo $value;?></h2>
        </div>
    </div>

    <div class="row" id="outer" style="width:100%;" >
        <div class="col-md-3"></div>
        <div class=col-md-6" style="padding-top: 1%;" id="inner">
            <button style="display: block; margin: 0 auto; background-color:#000d1a;" onclick="myFunction()">Show/Hide Games</button>
        </div>
    </div>



    <div class="row" id="gameDIV" style="width:100%" >
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <?php foreach ($allgame2 as $allgames2) : ?>
                <div class="col-md-3">
                    <a href="<?=$allgames2->link?>"><img src="<?=$allgames2->image?>" title="<?=$allgames2->name?>" /></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>





    <script>
        function myFunction() {
            var x = document.getElementById('gameDIV');
            if (x.style.display === 'block') {
                x.style.display = 'none';
            } else {
                x.style.display = 'block';
            }
        }
    </script>


</div>

