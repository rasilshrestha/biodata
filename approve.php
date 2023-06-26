<?php
// Approve biodata
function user_biodata_approve_biodata() {
    if (isset($_GET['action']) && $_GET['action'] === 'approve_biodata' && isset($_GET['biodata_id'])) {
        if (current_user_can('manage_options')) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'biodata';
            $biodata_id = intval($_GET['biodata_id']);

            $wpdb->update(
                $table_name,
                array('status' => 'approved'),
                array('id' => $biodata_id)
            );
        }
    }
}
add_action('admin_post_approve_biodata', 'user_biodata_approve_biodata');