
document.addEventListener('DOMContentLoaded', function() {
    const ticket_id = document.querySelector("input[name='id']").value;
    const user_id = document.querySelector("input[name='user_id']").value;
    //console.log(ticket_id);
    //console.log(user_id);

    function appendMessage(message, sender) {
        let article = document.createElement('article');
        article.classList.add('message', sender + '-message');
        article.innerHTML = message;

        document.querySelector("#chat-get_messages").appendChild(article);
        document.querySelector("#chat-get_messages").scrollTop = document.querySelector("#chat-get_messages").scrollHeight;
    }

    function renderMessages(messages) {
        let chatWindow = document.querySelector("#chat-get_messages");
        chatWindow.innerHTML = "";

        for (let i = 0; i < messages.length; i++) {
          //console.log(messages[i]);
          //console.log(user_id);
          let message = messages[i].message;
          let sender = messages[i].userID == user_id ? "user" : "agent";
          appendMessage(message, sender);
        }
    }

    function getMessages() {
        fetch("../../src/controllers/action_get_messages.php?ticket_id=" + ticket_id)
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
            scrollToBottom();
        })
    }
    function scrollToBottom() {
        const chatWindow = document.querySelector("#ticket-chat-cont");
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
    function sendMessage(message) {
        console.log(message);
        fetch("../../src/controllers/action_send_message.php?user_id=" + user_id + "&ticket_id=" + ticket_id + "&message=" + message)
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
        let message = document.querySelector("#message").value;
        sendMessage(message);
        document.querySelector("#message").value = "";
    });

    setInterval(function() {
        getMessages();
    }, 5000);
});
