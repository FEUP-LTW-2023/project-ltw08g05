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



    /**
     * Confirm the password
     */
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');
    const passwordConfirmError = document.getElementById('password-confirm-error');
     
    function validatePasswordConfirm() {
        const passwordValue = passwordInput.value.trim();
        const passwordConfirmValue = passwordConfirmInput.value.trim();
    
        if (passwordConfirmValue === '') {
            passwordConfirmError.textContent = 'Please confirm your password';
        } else if (passwordValue !== passwordConfirmValue) {
            passwordConfirmError.textContent = 'Passwords do not match';
        } else {
            passwordConfirmError.textContent = '';
        }
    }
     
    passwordConfirmInput.addEventListener('input', validatePasswordConfirm);
     
    
    /**
     * Check password strength
     */
    const passwordStrengthError = document.getElementById('password-strength-error');

    function checkPasswordStrength() {
        const passwordValue = passwordInput.value.trim();
        const passwordStrength = getPasswordStrength(passwordValue);

        if (passwordValue === '') {
            passwordStrengthError.textContent = 'Password is required';
        } else if (passwordStrength === 'weak') {
            passwordStrengthError.textContent = 'Password is too weak';
        } else if (passwordStrength === 'medium') {
            passwordStrengthError.textContent = 'Password strength is medium';
        } else if (passwordStrength === 'strong') {
            passwordStrengthError.textContent = 'Password strength is strong';
        } else {
            passwordStrengthError.textContent = '';
        }
    }

    function getPasswordStrength(password) {
        const strengthThresholds = {
            weak: 6,
            medium: 8
        };

        if (password.length < strengthThresholds.weak) {
            return 'weak';
        } else if (password.length < strengthThresholds.medium) {
            return 'medium';
        } else {
            return 'strong';
        }
    }

    passwordInput.addEventListener('input', checkPasswordStrength);




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




    /**
     * Validate form
     */
     function validateForm() {
      validateEmail();
      validatePasswordConfirm();
      checkPasswordStrength();
      checkInputs();
  }

  const form = document.querySelector('form');
  form.addEventListener('submit', function(event) {
      event.preventDefault(); 
      validateForm(); 
      if (form.checkValidity()) {
      form.submit();
      }
  });

  });