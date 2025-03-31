<?php
/**
 * Plugin Name: SkillLink AI Career Plugin
 * Plugin URI: https://github.com/ozioko-kingsley/skilllink-ai-career-plugin/tree/main/skilllink-ai-career-plugin
 * Description: AI-powered career recommendation plugin for SkillLink.
 * Version: 1.0
 * Author: Kingsley Ozioko
 * Author URI: https://github.com/ozioko-kingsley
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Define plugin constants
define('SKILLLINK_AI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SKILLLINK_AI_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Enqueue admin scripts
 */
function skilllink_ai_admin_scripts($hook) {
    if ($hook !== 'settings_page_skilllink-ai-career-settings') {
        return;
    }
    wp_enqueue_script('skilllink-ai-admin-js', SKILLLINK_AI_PLUGIN_URL . 'assets/js/admin-script.js', [], '1.0', true);
}
add_action('admin_enqueue_scripts', 'skilllink_ai_admin_scripts');

/**
 * Add settings page in WordPress admin menu
 */
function skilllink_ai_add_admin_menu() {
    add_options_page(
        'SkillLink AI Career Settings',
        'AI Career Plugin',
        'manage_options',
        'skilllink-ai-career-settings',
        'skilllink_ai_render_admin_page'
    );
}
add_action('admin_menu', 'skilllink_ai_add_admin_menu');

/**
 * Render the settings page
 */
function skilllink_ai_render_admin_page() {
    ?>
    <div class="wrap">
        <h2>SkillLink AI Career Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('skilllink_ai_settings_group');
            do_settings_sections('skilllink-ai-career-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Register settings
 */
function skilllink_ai_register_settings() {
    register_setting('skilllink_ai_settings_group', 'skilllink_ai_api_key');

    add_settings_section(
        'skilllink_ai_main_section',
        'API Settings',
        null,
        'skilllink-ai-career-settings'
    );

    add_settings_field(
        'skilllink_ai_api_key',
        'OpenAI API Key',
        'skilllink_ai_api_key_callback',
        'skilllink-ai-career-settings',
        'skilllink_ai_main_section'
    );
}
add_action('admin_init', 'skilllink_ai_register_settings');

/**
 * API Key input field
 */
function skilllink_ai_api_key_callback() {
    $api_key = get_option('skilllink_ai_api_key', '');
    echo '<input type="text" name="skilllink_ai_api_key" value="' . esc_attr($api_key) . '" style="width: 300px;" />';
}

/**
 * Fetch AI-powered career recommendations
 */
function skilllink_ai_get_career_recommendation($user_input) {
    $api_key = get_option('skilllink_ai_api_key');
    if (!$api_key) {
        return 'API key is missing. Please set it in the plugin settings.';
    }

    $api_url = "https://api.openai.com/v1/completions";
    $prompt = "Suggest career paths for someone interested in: $user_input";
    
    $args = [
        'body'    => json_encode([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 150,
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key,
        ],
        'timeout' => 20,
    ];

    $response = wp_remote_post($api_url, $args);
    
    if (is_wp_error($response)) {
        return 'Error fetching career recommendations.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data['choices'][0]['text'] ?? 'No recommendations found.';
}

/**
 * Register shortcode for front-end display
 */
function skilllink_ai_career_shortcode() {
    ob_start();
    include SKILLLINK_AI_PLUGIN_PATH . 'templates/career-recommendation.php';
    return ob_get_clean();
}
add_shortcode('skilllink_ai_career', 'skilllink_ai_career_shortcode');


/**
 * Handle AJAX request for career recommendation
 */
function skilllink_ai_ajax_career_recommendation() {
    if (!isset($_POST['user_input'])) {
        wp_send_json_error(['message' => 'Invalid request.'], 400);
    }

    $user_input = sanitize_text_field($_POST['user_input']);
    $recommendations = skilllink_ai_get_career_recommendation($user_input);

    wp_send_json_success(['recommendations' => esc_html($recommendations)]);
}
add_action('wp_ajax_get_career_recommendation', 'skilllink_ai_ajax_career_recommendation');
add_action('wp_ajax_nopriv_get_career_recommendation', 'skilllink_ai_ajax_career_recommendation');

/* Adding the github link to the pllugins list */
function skilllink_ai_plugin_links($links) {
    $github_link = '<a href="https://github.com/ozioko-kingsley/skilllink-ai-career-plugin/tree/main/skilllink-ai-career-plugin" target="_blank">GitHub</a>';
    array_push($links, $github_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'skilllink_ai_plugin_links');


