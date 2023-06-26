<?php

// Save additional details as custom posts
function user_biodata_save_details() {
    if (isset($_POST['submit_biodata'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $occupation = sanitize_text_field($_POST['occupation']);
        $education = sanitize_textarea_field($_POST['education']);
        $experience = sanitize_textarea_field($_POST['experience']);
        $about_me = sanitize_textarea_field($_POST['about']);

        $post_data = array(
            'post_title' => $name,
            'post_type' => 'biodata',
            'post_status' => 'pending',
            'meta_input' => array(
                'email' => $email,
                'occupation' => $occupation,
                'education' => $education,
                'experience' => $experience,
                'about_me' => $about_me,
                'approved' => false,
            ),
        );

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            echo '<p>Details submitted successfully. They will be reviewed by the admin.</p>';
        } else {
            echo '<p>Failed to submit details. Please try again.</p>';
        }
    }
}

add_action('init', 'user_biodata_save_details');

// Custom post type for additional details
function user_biodata_custom_post_type() {
    $labels = array(
        'name' => 'Biodata',
        'singular_name' => 'Biodatum',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Biodatum',
        'edit_item' => 'Edit Biodatum',
        'new_item' => 'New Biodatum',
        'view_item' => 'View Biodatum',
        'search_items' => 'Search Biodata',
        'not_found' => 'No biodata found',
        'not_found_in_trash' => 'No biodata found in trash',
        'parent_item_colon' => 'Parent Biodatum:',
        'menu_name' => 'Biodata',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-admin-users',
        'rewrite' => array('slug' => 'biodata'),
        'capability_type' => 'post',
        'has_archive' => true,
        'supports' => array('title', 'author'),
    );

    register_post_type('biodata', $args);
}

add_action('init', 'user_biodata_custom_post_type');

// Add custom fields to custom post type
function user_biodata_custom_fields() {
    add_meta_box(
        'biodata_details',
        'Biodata Details',
        'user_biodata_details_callback',
        'biodata',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'user_biodata_custom_fields');

// Custom fields callback function
function user_biodata_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'biodata_details_nonce');

    $email = get_post_meta($post->ID, 'email', true);
    $occupation = get_post_meta($post->ID, 'occupation', true);
    $education = get_post_meta($post->ID, 'education', true);
    $experience = get_post_meta($post->ID, 'experience', true);
    $about_me = get_post_meta($post->ID, 'about_me', true);
    $approved = get_post_meta($post->ID, 'approved', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="email">Email</label></th>
            <td>
                <input type="email" name="email" id="email" value="<?php echo esc_attr($email); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="occupation">Occupation</label></th>
            <td>
                <input type="text" name="occupation" id="occupation" value="<?php echo esc_attr($occupation); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th><label for="education">Education</label></th>
            <td>
                <textarea name="education" id="education" rows="4" class="regular-text"><?php echo esc_textarea($education); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="experience">Experience</label></th>
            <td>
                <textarea name="experience" id="experience" rows="4" class="regular-text"><?php echo esc_textarea($experience); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="about_me">About Me</label></th>
            <td>
                <textarea name="about_me" id="about_me" rows="4" class="regular-text"><?php echo esc_textarea($about_me); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="approved">Approval</label></th>
            <td>
                <input type="checkbox" name="approved" id="approved" <?php checked($approved, '1'); ?> value="1">
                <label for="approved">Approve</label>
            </td>
        </tr>
    </table>
    <?php
}

// Save custom fields data
function user_biodata_save_custom_fields($post_id) {
    if (!isset($_POST['biodata_details_nonce']) || !wp_verify_nonce($_POST['biodata_details_nonce'], basename(__FILE__))) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $email = sanitize_email($_POST['email']);
    $occupation = sanitize_text_field($_POST['occupation']);
    $education = sanitize_textarea_field($_POST['education']);
    $experience = sanitize_textarea_field($_POST['experience']);
    $about_me = sanitize_textarea_field($_POST['about_me']);
    $approved = isset($_POST['approved']) ? '1' : '0';

    update_post_meta($post_id, 'email', $email);
    update_post_meta($post_id, 'occupation', $occupation);
    update_post_meta($post_id, 'education', $education);
    update_post_meta($post_id, 'experience', $experience);
    update_post_meta($post_id, 'about_me', $about_me);
    update_post_meta($post_id, 'approved', $approved);

    
    global $wpdb;
    $table_name = $wpdb->prefix . 'biodata';
    // printf($table_name);
    $biodata = $wpdb->get_results("SELECT * FROM $table_name WHERE email = '$email'");
    // print_r($biodata);
    // $user_id = $biodata->id;
    // die();
    foreach ($biodata as $biodata){
        $id = $biodata->id;
        
    }
    // print_r($id);
    if ($approved == 1){
        $status = "approved";
    }
    else{
        $status = "pending";
    }

    $wpdb->update(
    $table_name,
        array('status' => $status),
        array('id' => $id) 
    ); 
}

add_action('save_post_biodata', 'user_biodata_save_custom_fields');
