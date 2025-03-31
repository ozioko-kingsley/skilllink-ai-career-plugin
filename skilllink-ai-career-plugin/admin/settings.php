<?php

if (!defined('ABSPATH')) {
    exit;
}

// Add settings menu in WordPress admin
function skilllink_ai_add_admin_menu() {
    add_options_page(
        'SkillLink AI Career Plugin', 
        'AI Career Plugin', 
        'manage_options', 
        'skilllink-ai-career-settings', 
        'skilllink_ai_settings_page'
    );
}
add_action('admin_menu', 'skilllink_ai_add_admin_menu');

// Register settings
function skilllink_ai_register_settings() {
    register_setting('skilllink_ai_settings_group', 'skilllink_ai_api_key');

    add_settings_section(
        'skilllink_ai_settings_section', 
        'API Configuration', 
        '__return_null', 
        'skilllink-ai-career-settings'
    );

    add_settings_field(
        'skilllink_ai_api_key', 
        'OpenAI API Key', 
        'skilllink_ai_api_key_field_callback', 
        'skilllink-ai-career-settings', 
        'skilllink_ai_settings_section'
    );
}
add_action('admin_init', 'skilllink_ai_register_settings');

// Callback function for API Key input field
function skilllink_ai_api_key_field_callback() {
    $api_key = get_option('skilllink_ai_api_key', '');
    echo '<input type="text" name="skilllink_ai_api_key" value="' . esc_attr($api_key) . '" size="50" />';
}

// Display settings page
function skilllink_ai_settings_page() { ?>
    <div class="wrap">
        <h1>SkillLink AI Career Recommendation</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('skilllink_ai_settings_group');
            do_settings_sections('skilllink-ai-career-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php } ?>
