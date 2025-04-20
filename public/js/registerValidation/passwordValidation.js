document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const passwordRequirements = document.querySelector(".password-requirements");
    const passwordFeedback = document.getElementById("password-feedback");
    const passwordFeedbackText = document.getElementById("password-feedback-text");

    if (passwordInput && passwordRequirements) {
        // Show the password requirements when the user starts typing
        passwordInput.addEventListener("input", () => {
            passwordRequirements.style.display = "block";
        });

        // Hide the requirements if the password field is empty
        passwordInput.addEventListener("blur", () => {
            if (passwordInput.value === "") {
                passwordRequirements.style.display = "none";
            }
        });

        // Validate password requirements
        const lengthRequirement = document.getElementById("length");
        const uppercaseRequirement = document.getElementById("uppercase");
        const lowercaseRequirement = document.getElementById("lowercase");
        const numberRequirement = document.getElementById("number");
        const specialRequirement = document.getElementById("special");

        passwordInput.addEventListener("input", () => {
            const password = passwordInput.value;

            // Helper function to handle animations
            const handleRequirement = (requirement, condition, text) => {
                if (condition) {
                    requirement.textContent = `✔ ${text}`;
                    requirement.classList.remove("text-danger");
                    requirement.classList.add("text-success");

                    // Trigger animation if not already crossed out
                    if (!requirement.classList.contains("cross-out")) {
                        requirement.classList.add("cross-out");

                        // Remove the requirement after the animation
                        setTimeout(() => {
                            requirement.style.display = "none";
                        }, 1000); // Match the animation duration (1s)
                    }
                } else {
                    requirement.textContent = `✘ ${text}`;
                    requirement.classList.remove("text-success", "cross-out");
                    requirement.classList.add("text-danger");
                    requirement.style.display = "block"; // Ensure it reappears if invalid
                }
                return condition; // Return whether the condition is met
            };

            // Check each requirement
            const isLengthValid = handleRequirement(lengthRequirement, password.length >= 8, "Minimum 8 characters");
            const isUppercaseValid = handleRequirement(uppercaseRequirement, /[A-Z]/.test(password), "At least 1 uppercase letter (A–Z)");
            const isLowercaseValid = handleRequirement(lowercaseRequirement, /[a-z]/.test(password), "At least 1 lowercase letter (a–z)");
            const isNumberValid = handleRequirement(numberRequirement, /\d/.test(password), "At least 1 number (0–9)");
            const isSpecialValid = handleRequirement(specialRequirement, /[$@!%*?&]/.test(password), "At least 1 special character (! @ # $ % ^ & *)");

            // If all requirements are met, add the green border
            if (isLengthValid && isUppercaseValid && isLowercaseValid && isNumberValid && isSpecialValid) {
                passwordInput.classList.remove("is-invalid");
                passwordInput.classList.add("valid-input");
                passwordFeedback.classList.remove("invalid-feedback");
                passwordFeedback.classList.add("valid-feedback");
                passwordFeedbackText.textContent = "Password is valid!";
                passwordFeedback.style.display = "block";
            } else {
                // Show red feedback if invalid
                passwordInput.classList.remove("valid-input");
                passwordInput.classList.add("is-invalid");
                passwordFeedback.classList.remove("valid-feedback");
                passwordFeedback.classList.add("invalid-feedback");
                passwordFeedbackText.textContent = "Password does not meet the requirements.";
                passwordFeedback.style.display = "block";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("password-confirm");
    const confirmPasswordFeedback = document.getElementById("confirm-password-feedback");
    const confirmPasswordFeedbackText = document.getElementById("confirm-password-feedback-text");

    if (passwordInput && confirmPasswordInput) {
        confirmPasswordInput.addEventListener("input", () => {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword === password) {
                confirmPasswordInput.classList.remove("is-invalid");
                confirmPasswordInput.classList.add("valid-input");
                confirmPasswordFeedback.classList.remove("invalid-feedback");
                confirmPasswordFeedback.classList.add("valid-feedback");
                confirmPasswordFeedbackText.textContent = "Passwords match!";
                confirmPasswordFeedback.style.display = "block";
            } else {
                confirmPasswordInput.classList.remove("valid-input");
                confirmPasswordInput.classList.add("is-invalid");
                confirmPasswordFeedback.classList.remove("valid-feedback");
                confirmPasswordFeedback.classList.add("invalid-feedback");
                confirmPasswordFeedbackText.textContent = "Passwords do not match.";
                confirmPasswordFeedback.style.display = "block";
            }
        });
    }
});