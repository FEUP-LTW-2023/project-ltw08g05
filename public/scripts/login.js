document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginButton = document.querySelector('#login button');
  
    // Function to check if the input fields are filled
    function checkInputs() {
      const emailValue = emailInput.value.trim();
      const passwordValue = passwordInput.value.trim();
  
      if (emailValue !== '' && passwordValue !== '') {
        loginButton.removeAttribute('disabled');
        loginButton.removeAttribute('title');
      } else {
        loginButton.setAttribute('disabled', 'disabled');
        loginButton.setAttribute('title', 'Fields are not filled');
      }
    }
  
    // Event listeners for input fields
    emailInput.addEventListener('input', checkInputs);
    passwordInput.addEventListener('input', checkInputs);
  });
  