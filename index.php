<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="ajax.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Peace</title>
</head>
<body>
<section class="messages">
    <div id="title"></div>
    <input type="text" id="message" onkeypress="{if (event.keyCode==13)process()}"/>
    <br/>
    <br/>

    <div id="divMessage">
        <div class="leftBubble">
            <div id="initialText"></div>
        </div>
    </div>
</section>
</body>
</html>
