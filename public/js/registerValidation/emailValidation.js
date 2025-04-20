document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const emailRequirements = document.querySelector(".email-requirements");
    const emailFeedback = document.getElementById("email-feedback");
    const emailFeedbackText = document.getElementById("email-feedback-text");
    const validEmailRequirement = document.getElementById("valid-email");

    if (emailInput && emailRequirements) {
        // Show the email requirements when the user starts typing
        emailInput.addEventListener("input", () => {
            emailRequirements.style.display = "block";
        });

        // Hide the requirements if the email field is empty
        emailInput.addEventListener("blur", () => {
            if (emailInput.value === "") {
                emailRequirements.style.display = "none";
            }
        });

        // Validate email requirements
        emailInput.addEventListener("input", () => {
            const email = emailInput.value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@1utar\.my$/;

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

            // Check the email requirement
            const isEmailValid = handleRequirement(validEmailRequirement, emailRegex.test(email), "Must be a valid email format (e.g., example@1utar.my)");

            // If the email is valid, show green feedback
            if (isEmailValid) {
                emailInput.classList.remove("is-invalid");
                emailInput.classList.add("valid-input");
                emailFeedback.classList.remove("invalid-feedback");
                emailFeedback.classList.add("valid-feedback");
                emailFeedbackText.textContent = "Email is valid!";
                emailFeedback.style.display = "block";
            } else {
                // Show red feedback if invalid
                emailInput.classList.remove("valid-input");
                emailInput.classList.add("is-invalid");
                emailFeedback.classList.remove("valid-feedback");
                emailFeedback.classList.add("invalid-feedback");
                emailFeedbackText.textContent = "Email does not meet the requirements.";
                emailFeedback.style.display = "block";
            }
        });
    }
});