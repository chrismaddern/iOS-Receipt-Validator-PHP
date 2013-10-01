<html>
<head>
<title>Validate iTunes In App Purchase Receipt Code Online Tool</title>
<meta name="description" value="A tool to allow you to verify iTunes In-App Purchase Receipt Codes against Apple's Servers. PHP Implementation." />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
    var urlToSend = 'validateaction.php?receipt=' + '<?php echo $_GET['receipt'] ?>';
    if(!('<?php echo $_GET['receipt'] ?>' == '')) {
        $.get(
            urlToSend,
            { language: "php", version: 5 },
            function(responseText) {  
                $("#retData").html('<br /><br /><br />'+responseText + '<br /><a href="index.php">Try another </a>');
            },  
            "html"  
        );  
    }
</script>

</head>
<body>

<div id="retData" style="float:center; text-align:center; font-family:helvetica,arial; font-size:16px;">
<?php if($_GET['receipt'] != '') { ?>
<br /><br /><br />Validating receipt code:<br /> <?php echo $_GET['receipt'] ?><br /><br /><br /><img src="loading.gif" />
<?php } else { ?>
<br /><br />
<form name="receipttoken" action="index.php" method="get">
Enter Receipt Token (b64)<br /><br />  <textarea type="text" style="width:300px; height:200px; font-family:helvetica,arial; font-size:16px;" name="receipt"></textarea><br /><br />
<input type="submit" value="Validate" />
</form>
<?php } ?>
</div>
</body>
</html> 
