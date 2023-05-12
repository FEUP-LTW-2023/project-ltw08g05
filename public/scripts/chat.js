/* NOTE: This file is not used in the current version of the application

$(document).ready(function(){
    // get ticket id and user id from hidden input fields
    var ticket_id = $("input[name='ticket_id']").val();
    var user_id = $("input[name='user_id']").val();

    // function to append a new message to the chat window
    function appendMessage(message, sender) {
        $(".chat-window").append("<article class='message " + sender + "-message'>" + message + "<span class='sender'>" + sender + "</span></article>");
        // scroll to the bottom of the chat window
        $(".chat-window").scrollTop($(".chat-window")[0].scrollHeight);
    }

    // retrieve and display all messages for the current ticket
    $.ajax({
        url: "../../src/controllers/get_messages.php",
        type: "POST",
        data: {ticket_id: ticket_id},
        success: function(data){
            console.log(data);
            var messages = JSON.parse(data);
            for (var i = 0; i < messages.length; i++) {
                var message = messages[i].message;
                var sender = messages[i].id_user == user_id ? "user" : "agent";
                appendMessage(message, sender);
            }
        }
    });

    // submit a new message to the server
    $(".chat-form").on("submit", function(e){
        e.preventDefault();
        var message = $("#message").val();
        //console.log("ticket_id:", ticket_id);
        //console.log("message:", message); 
        $.ajax({
            url: "../../src/controllers/send_message.php",
            type: "POST",
            data: {user_id: user_id, ticket_id: ticket_id, message: message},
            success: function(data){
                console.log(data);
                $("#message").val("");
                appendMessage(message, "user");
            }
        });
    });

    // poll the server for new messages every 5 seconds
    setInterval(function() {
        $.ajax({
            url: "../../src/controllers/get_messages.php",
            type: "POST",
            data: {ticket_id: ticket_id},
            success: function(data){
                console.log(data);
                var messages = JSON.parse(data);
                if (messages.length > 0) {
                    var lastMessage = messages[messages.length - 1];
                    if (lastMessage.id_user == user_id) {
                        // last message was sent by the user, so do nothing
                    } else {
                        // last message was sent by the agent, so display it in the chat window
                        appendMessage(lastMessage.message, "agent");
                    }
                }
            }
        });
    }, 5000);
});
*/