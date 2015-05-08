sent = getLanguage() == "tr_TR" ? 1 : 2126;

function process() {

    var input = $("#message");
    var message =input.val();
    input.val("");

    if (message != "" && message != null) {
        var divMessage = $("#divMessage");
        divMessage.html("<div class='rightBubble'><p>" + message + "</p></div>" + divMessage.html());
        $.ajax({
            type: "GET",
            url: "ajax.php",
            data: {
                sent: sent,
                message: message
            },
            success: function (response) {
                var received = response.substring(response.indexOf(" ") + 1);
                divMessage.html("<div class='leftBubble'><p>" + received + "</p></div>" + divMessage.html());
            }
        });
    }

}

jQuery(document).ready(function () {
    fillText();
    setTimeout(function () {
        jQuery("#title").fadeOut("slow");
    }, 6000);
});


function fillText() {
    var language = getLanguage();
    if (language == "tr_TR") {
        $("#title").html("<p>Merhaba, ben Barış. Eğer çevirimiçiysem benimle bu kutucuktan konuşabilirsiniz. <b>Şu an çevirimiçiyim.</b></p><br/>");
        $("#initialText").html("<p>Naber?</p>");
    } else {
        $("#title").html("<p>Hello, I'm Peace. If I'm online you can talk to me from this window. <b>I am online now.</b></p><br/>");
        $("#initialText").html("<p>What's up?</p>");

    }
}

function getLanguage() {
    return window.navigator.userLanguage || window.navigator.language;
}
