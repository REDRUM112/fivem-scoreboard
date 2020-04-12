<script>
var auto_refresh = setInterval(
(function () {
    $("#pl-list").load("fivem.php"); //Load the content into the div
}), 1000);
</script>
<?php include 'example-esx\include\config.php'; ?>
<?php include 'example-esx\include\connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="include/style.css">
    <title>FiveM Scoreboard</title>
</head>
<body>
    <div class="scoreboard">
        <div id="pl-list">12</div>
    </div>
</body>
</html>
