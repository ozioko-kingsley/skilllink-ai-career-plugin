document.addEventListener("DOMContentLoaded", function () {
    const apiKeyInput = document.querySelector('input[name="skilllink_ai_api_key"]');
    const form = document.querySelector('form');
    
    if (!apiKeyInput || !form) return;

    // Create a message div for feedback
    let messageDiv = document.createElement("div");
    messageDiv.style.marginTop = "10px";
    apiKeyInput.parentNode.appendChild(messageDiv);

    // Function to validate API key format
    function validateApiKey(apiKey) {
        const apiKeyPattern = /^sk-[A-Za-z0-9]{32,}$/; // OpenAI API keys start with "sk-"
        return apiKeyPattern.test(apiKey);
    }

    // Real-time validation on input change
    apiKeyInput.addEventListener("input", function () {
        if (validateApiKey(apiKeyInput.value)) {
            messageDiv.textContent = "✅ API Key format looks good!";
            messageDiv.style.color = "green";
        } else {
            messageDiv.textContent = "❌ Invalid API Key format! Ensure it starts with 'sk-' and is correct.";
            messageDiv.style.color = "red";
        }
    });

    // Prevent form submission if API key is invalid
    form.addEventListener("submit", function (event) {
        if (!validateApiKey(apiKeyInput.value)) {
            event.preventDefault();
            alert("Please enter a valid OpenAI API key before saving.");
        }
    });
});
