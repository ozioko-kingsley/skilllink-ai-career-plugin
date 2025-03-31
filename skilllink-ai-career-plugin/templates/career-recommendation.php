<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="skilllink-career-container">
    <h2>Find Your Ideal Career Path</h2>
    <p>Enter your interests or skills below to get AI-powered career recommendations.</p>

    <form id="career-form" class="skilllink-career-form">
        <input type="text" id="career-interest" name="user_input" placeholder="e.g. Programming, Marketing, Design" required>
        <button type="submit">Get Recommendations</button>
    </form>

    <div id="career-result"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("career-form").addEventListener("submit", function (event) {
        event.preventDefault();
        
        const userInput = document.getElementById("career-interest").value.trim();
        const resultDiv = document.getElementById("career-result");
        
        if (!userInput) {
            resultDiv.innerHTML = "<p style='color:red;'>Please enter a skill or interest.</p>";
            return;
        }

        resultDiv.innerHTML = "<p>Fetching recommendations...</p>";

        fetch("<?php echo esc_url(admin_url('admin-ajax.php')); ?>", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=get_career_recommendation&user_input=" + encodeURIComponent(userInput),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = `<p><strong>Recommended Careers:</strong> ${data.data.recommendations}</p>`;
            } else {
                resultDiv.innerHTML = "<p style='color:red;'>Error: " + data.data.message + "</p>";
            }
        })
        .catch(error => {
            resultDiv.innerHTML = "<p style='color:red;'>Error fetching data. Please try again.</p>";
        });
    });
});
</script>

<style>
    .skilllink-career-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-family: Arial, sans-serif;
        background: #f9f9f9;
    }
    
    .skilllink-career-form input {
        width: 80%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .skilllink-career-form button {
        padding: 10px 20px;
        border: none;
        background-color: #0073aa;
        color: white;
        border-radius: 5px;
        cursor: pointer;
    }

    .skilllink-career-form button:hover {
        background-color: #005f8d;
    }

    #career-result {
        margin-top: 20px;
        padding: 15px;
        background-color: #eaf7ff;
        border-radius: 5px;
    }
</style>
