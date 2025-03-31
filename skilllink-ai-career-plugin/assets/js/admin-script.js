document.addEventListener("DOMContentLoaded", function () {
    const apiKeyInput = document.querySelector('input[name="skilllink_ai_api_key"]');
    const form = apiKeyInput ? apiKeyInput.closest("form") : null;

    if (!apiKeyInput || !form) return;

    // Check if a message div already exists
    let messageDiv = apiKeyInput.nextElementSibling;
    if (!messageDiv || !messageDiv.classList.contains("api-key-message")) {
        messageDiv = document.createElement("div");
        messageDiv.classList.add("api-key-message");
        messageDiv.style.marginTop = "10px";
        apiKeyInput.parentNode.appendChild(messageDiv);
    }

    // Function to validate API key format
    function validateApiKey(apiKey) {
        const apiKeyPattern = /^sk-(proj-)?[A-Za-z0-9_\-]{30,}$/;
        return apiKeyPattern.test(apiKey);
    }

    // Real-time validation on input change
    apiKeyInput.addEventListener("input", function () {
        const apiKey = apiKeyInput.value.trim();
        if (validateApiKey(apiKey)) {
            messageDiv.textContent = "✅ API Key format looks good!";
            messageDiv.style.color = "green";
        } else {
            messageDiv.textContent = "❌ Invalid API Key format! Ensure it starts with 'sk-' and is correct. testing";
            messageDiv.style.color = "red";
        }
    });

    // Prevent form submission if API key is invalid
    form.addEventListener("submit", function (event) {
        if (!validateApiKey(apiKeyInput.value.trim())) {
            event.preventDefault();
            alert("Please enter a valid OpenAI API key before saving.");
        }
    });
});
