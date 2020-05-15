<?php
    if(isset($_POST["link"])){
        $link = $_POST["link"];
    }
    if(isset($_POST["maxNum"])){
        $maxNum = intval($_POST["maxNum"]);
    }
    if(isset($_POST["programId"])){
        $programId = intval($_POST["programId"]);
    }
?>

<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />      
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            var redirectData = { 
                link: "<?php echo $link ?>",
                maxNum: "<?php echo $maxNum ?>",
                programId: "<?php echo $programId ?>"
            } 
            $(function(){
                // console.log(redirectData);
                $("#loading-alert").load("./redirect-part.php", redirectData);
            })
        </script>
	</head>
    <body>

    
    <div id="loading-alert" align="center">
        <img src="/images/gif/earth-loader.gif"alt="Now Loading..." />	
        <p style="text-align:center;">Loading･･･</p>	
        <p>現在申し込みページへ移動中です。<br>Now you are moving to the another page.</p>
    </div>

    </body>
</html>
