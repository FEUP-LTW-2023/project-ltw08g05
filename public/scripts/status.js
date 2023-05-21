// status.js

function updateTicketStatus(event) {
    const statusValue = event.target.getAttribute('data-status');
    const ticketId = event.target.getAttribute('data-ticket-id');
    const csrfToken = event.target.getAttribute('data-csrf');
    //console.log(statusValue);
    //console.log(ticketId);
    // Send an AJAX request to update the status
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../src/controllers/action_change_status.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        
        // Update the UI if the status was successfully updated
        const ticketStatusElement = document.getElementById('ticket-status');
        if (ticketStatusElement) {
          ticketStatusElement.textContent = statusValue;
        }
        location.reload();
      }
    };
    console.log(statusValue);
    xhr.send('id=' + ticketId + '&status=' + statusValue + '&csrf=' + csrfToken);

    // Hide the status buttons
    const statusButtonsElement = document.querySelector('.status-buttons');
    statusButtonsElement.style.display = 'none';
}
  
// Add an event listener to each status button individually
const statusButtons = document.getElementsByClassName('status-button');
for (let i = 0; i < statusButtons.length; i++) {
    statusButtons[i].addEventListener('click', updateTicketStatus);
}