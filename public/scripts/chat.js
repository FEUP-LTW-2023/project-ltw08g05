
document.addEventListener('DOMContentLoaded', function() {
    var ticket_id = document.querySelector("input[name='id']").value;
    var user_id = document.querySelector("input[name='user_id']").value;
    //console.log(ticket_id);
    //console.log(user_id);

    function appendMessage(message, sender) {
        var article = document.createElement('article');
        article.classList.add('message', sender + '-message');
        article.innerHTML = message + "<span class='sender'>" + ' ' + sender + "</span>";

        document.querySelector(".chat-window").appendChild(article);
        document.querySelector(".chat-window").scrollTop = document.querySelector(".chat-window").scrollHeight;
    }

    function renderMessages(messages) {
        var chatWindow = document.querySelector(".chat-window");
        chatWindow.innerHTML = "";
      
        for (var i = 0; i < messages.length; i++) {
          //console.log(messages[i]);
          //console.log(user_id);
          var message = messages[i].message;
          var sender = messages[i].userID == user_id ? "user" : "agent";
          appendMessage(message, sender);
        }
    }

    function getMessages() {
        fetch("../../src/controllers/get_messages.php?ticket_id=" + ticket_id)
        .then(function(response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Error fetching messages");
            }
        })
        .then(function(data) {
            //console.log(data);
            renderMessages(data);
        })
    }

    function sendMessage(message) {
        console.log(message);
        fetch("../../src/controllers/send_message.php?user_id=" + user_id + "&ticket_id=" + ticket_id + "&message=" + message)
        .then(function(response) {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error("Error sending message");
            }
        })
        .then(function(data) {
            //console.log(message);
            appendMessage(message, "user");
        })
    }

    getMessages();

    document.querySelector(".chat-form").addEventListener("submit", function(e) {
        e.preventDefault();
        var message = document.querySelector("#message").value;
        sendMessage(message);
        document.querySelector("#message").value = "";
    });

    setInterval(function() {
        getMessages();
    }, 5000);
});
