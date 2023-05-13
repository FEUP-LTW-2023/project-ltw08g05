document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Validate email format (example@example.example)
     */
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');

    function validateEmail() {
        const emailValue = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailValue === '') {
        emailError.textContent = 'Email is required';
        
        } else if (!emailRegex.test(emailValue)) {
        emailError.textContent = 'Invalid email format (example@example.com)';
        } else {
        emailError.textContent = '';
        }
    }

    emailInput.addEventListener('input', validateEmail);

    // function validateForm() {
    //     validateEmail();
    //     // Add validation for other fields here
    //     // ...
    // }

    // const form = document.querySelector('form');
    // form.addEventListener('submit', function(event) {
    //     event.preventDefault(); 
    //     validateForm(); 
    //     if (form.checkValidity()) {
    //     form.submit();
    //     }
    // });



    /**
     * Check if all required fields are filled
     */
    const registerForm = document.querySelector('.centered form');
    const requiredInputs = registerForm.querySelectorAll('input[required], textarea[required]');
    
    function checkInputs() {
      let allFieldsFilled = true;
  
      requiredInputs.forEach(input => {
        if (input.value.trim() === '') {
          allFieldsFilled = false;
        }
      });
  
      const registerButton = registerForm.querySelector('button');
      if (allFieldsFilled) {
        registerButton.removeAttribute('disabled');
        registerButton.removeAttribute('title');
      } else {
        registerButton.setAttribute('disabled', 'disabled');
        registerButton.setAttribute('title', 'Fields are not filled');
      }
    }
  
    registerForm.addEventListener('input', checkInputs);
  });