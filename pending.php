<?php

// Biodata pending section for admin
function user_biodata_pending_section() {
    ob_start();

    if (current_user_can('manage_options')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'biodata';
        $biodata_list = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'pending'");

        if ($biodata_list) {
            echo '<h3>Biodata Pending Approval</h3>';
            echo '<table>';
            echo '<tr><th>Name</th><th>Email</th><th>Occupation</th><th>Action</th></tr>';
            foreach ($biodata_list as $biodata) {
                echo '<tr>';
                echo '<td>' . $biodata->name . '</td>';
                echo '<td>' . $biodata->email . '</td>';
                echo '<td>' . $biodata->occupation . '</td>';
                echo '<td><a href="' . admin_url('admin-post.php?action=approve_biodata&biodata_id=' . $biodata->id) . '">Approve</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No pending biodata.</p>';
        }
    } else {
        echo '<p>Access denied.</p>';
    }

    return ob_get_clean();
}
add_shortcode('user_biodata_pending', 'user_biodata_pending_section');

// Process biodata submission
function user_biodata_process_submission() {
    if (isset($_POST['submit_biodata'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'biodata';
        $user_id = get_current_user_id();
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $education = sanitize_textarea_field($_POST['education']);
        $experience = sanitize_textarea_field($_POST['experience']);
        $about = sanitize_textarea_field($_POST['about']);
        $occupation = sanitize_text_field($_POST['occupation']);
        $status = 'pending';

        $existing_biodata = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = $user_id");

        if ($existing_biodata) {
            // Update existing biodata
            $wpdb->update(
                $table_name,
                array(
                    'name' => $name,
                    'email' => $email,
                    'education' => $education,
                    'experience' => $experience,
                    'about' => $about,
                    'occupation' => $occupation,
                    'status' => $status,
                ),
                array('id' => $existing_biodata->id)
            );
        } else {
            // Insert new biodata
            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'name' => $name,
                    'email' => $email,
                    'education' => $education,
                    'experience' => $experience,
                    'about' => $about,
                    'occupation' => $occupation,
                    'status' => $status,
                )
            );
        }
    }
}
add_action('init', 'user_biodata_process_submission');