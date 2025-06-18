document.addEventListener("DOMContentLoaded", function() {
    // Get form elements
    const loginForm = document.getElementById("loginForm");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.getElementById("passwordToggle");
    const signInBtn = document.getElementById("signInBtn");
    const btnLoader = document.getElementById("btnLoader");

    // Password visibility toggle
    passwordToggle.addEventListener("click", function() {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        
        const icon = this.querySelector("i");
        if (type === "password") {
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });

    // Form validation
    function validateForm() {
        let isValid = true;
        
        // Clear previous error states
        clearErrors();
        
        // Validate username
        if (!usernameInput.value.trim()) {
            showError(usernameInput, "Username is required");
            isValid = false;
        } else if (usernameInput.value.trim().length < 3) {
            showError(usernameInput, "Username must be at least 3 characters");
            isValid = false;
        }
        
        // Validate password
        if (!passwordInput.value) {
            showError(passwordInput, "Password is required");
            isValid = false;
        } else if (passwordInput.value.length < 6) {
            showError(passwordInput, "Password must be at least 6 characters");
            isValid = false;
        }
        
        return isValid;
    }

    // Show error message
    function showError(input, message) {
        input.classList.add("error");
        
        // Create or update error message
        let errorElement = input.parentNode.querySelector(".error-message");
        if (!errorElement) {
            errorElement = document.createElement("div");
            errorElement.className = "error-message";
            input.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
        errorElement.classList.add("show");
    }

    // Clear all errors
    function clearErrors() {
        const inputs = [usernameInput, passwordInput];
        inputs.forEach(input => {
            input.classList.remove("error", "success");
            const errorElement = input.parentNode.querySelector(".error-message");
            if (errorElement) {
                errorElement.classList.remove("show");
            }
        });
    }

    // Show success state
    function showSuccess(input) {
        input.classList.remove("error");
        input.classList.add("success");
    }

    // Real-time validation
    usernameInput.addEventListener("blur", function() {
        if (this.value.trim()) {
            if (this.value.trim().length >= 3) {
                showSuccess(this);
            } else {
                showError(this, "Username must be at least 3 characters");
            }
        }
    });

    passwordInput.addEventListener("blur", function() {
        if (this.value) {
            if (this.value.length >= 6) {
                showSuccess(this);
            } else {
                showError(this, "Password must be at least 6 characters");
            }
        }
    });

    // Clear error on input
    usernameInput.addEventListener("input", function() {
        if (this.classList.contains("error")) {
            this.classList.remove("error");
            const errorElement = this.parentNode.querySelector(".error-message");
            if (errorElement) {
                errorElement.classList.remove("show");
            }
        }
    });

    passwordInput.addEventListener("input", function() {
        if (this.classList.contains("error")) {
            this.classList.remove("error");
            const errorElement = this.parentNode.querySelector(".error-message");
            if (errorElement) {
                errorElement.classList.remove("show");
            }
        }
    });

    // Form submission
    loginForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Show loading state
        signInBtn.classList.add("loading");
        signInBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            const username = usernameInput.value.trim();
            const password = passwordInput.value;
            
            // Simple demo authentication
            if (username === "Emmanuel-Lagos" && password === "123456") {
                // Success - redirect to dashboard
                window.location.href = "index.html";
            } else {
                // Show error
                showError(passwordInput, "Invalid username or password");
                
                // Reset button state
                signInBtn.classList.remove("loading");
                signInBtn.disabled = false;
            }
        }, 2000);
    });

    // Demo: Auto-fill form for testing
    function autoFillDemo() {
        usernameInput.value = "Emmanuel-Lagos";
        passwordInput.value = "123456";
        showSuccess(usernameInput);
        showSuccess(passwordInput);
    }

    // Keyboard shortcuts
    document.addEventListener("keydown", function(e) {
        // Alt + D to auto-fill demo credentials
        if (e.altKey && e.key === "d") {
            e.preventDefault();
            autoFillDemo();
        }
        
        // Enter key to submit form when focused on inputs
        if (e.key === "Enter" && (e.target === usernameInput || e.target === passwordInput)) {
            loginForm.dispatchEvent(new Event("submit"));
        }
    });

    // Contact support link
    document.querySelector(".contact-support").addEventListener("click", function(e) {
        e.preventDefault();
        alert("Contact Support: support@riverbirdcinemas.com\nPhone: +234 123 456 7890");
    });

    // Focus management
    usernameInput.focus();

    // Handle browser back button
    window.addEventListener("pageshow", function(e) {
        if (e.persisted) {
            // Reset form if page is loaded from cache
            loginForm.reset();
            clearErrors();
            signInBtn.classList.remove("loading");
            signInBtn.disabled = false;
        }
    });
});