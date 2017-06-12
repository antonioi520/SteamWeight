<?php error_reporting(0); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Steam Weight</title>


    <link href="css/css/bootstrap.css" rel="stylesheet" />
    <link href="css/css/bootstrap-theme.css" rel="stylesheet" />
    <link href="css/css/css.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/css/main.css" />


</head>
<body>

<?php include 'templates/header.php' ?>


<?php include 'search.php' ?>


<?php require('accountCalc.php') ?>

<?php
$type = 0;
if ($type == 2) {
    require 'templates/searchResult.php';
} else {
    if (file_exists('templates/searchResult.php')) {
        require 'templates/searchResult.php';
    } else {
        require 'templates/searchResult.php';
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Save output and display it
    $output = ob_get_clean();
    // ob_flush();
    echo $output;
    $output = time()."\r\n".$output;

}


?>


<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-90995944-2', 'auto');
    ga('send', 'pageview');

</script>


</body>
</html>