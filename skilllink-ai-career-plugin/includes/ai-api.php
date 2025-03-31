<?php

if (!defined('ABSPATH')) {
    exit;
}

function skilllink_ai_get_career_recommendation($user_input) {
    // ✅ Try getting the API key from WordPress settings
    $api_key = get_option('skilllink_ai_api_key');

    // ✅ If no API key is saved, use a hardcoded fallback (Optional)
    if (empty($api_key)) {
        $api_key = '';
        // ✅ Save it so it persists in settings
        update_option('skilllink_ai_api_key', $api_key);
    }

    if (empty($api_key)) {
        return "Error: API key is missing. Please set it in the plugin settings.";
    }

    $url = "https://api.openai.com/v1/completions";
    $headers = [
        "Authorization" => "Bearer " . $api_key,
        "Content-Type"  => "application/json"
    ];

    $data = [
        "model"       => "gpt-4",
        "prompt"      => "Suggest careers for someone interested in: " . sanitize_text_field($user_input),
        "max_tokens"  => 100
    ];

    $response = wp_remote_post($url, [
        'headers'    => $headers,
        'body'       => wp_json_encode($data),
        'method'     => 'POST',
        'timeout'    => 20,
    ]);

    if (is_wp_error($response)) {
        return "Error: Failed to fetch recommendations. " . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    if (!isset($result['choices'][0]['text'])) {
        return "Error: No recommendations available or invalid API response.";
    }

    return esc_html(trim($result['choices'][0]['text']));
}
?>
