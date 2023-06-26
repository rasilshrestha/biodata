<?php
/*
Plugin Name: User Biodata Plugin
Version: 1.0
Author: Rasil Shrestha
*/

// Create custom tables during plugin activation
function user_biodata_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'biodata';
    $charset_collate = $wpdb->get_charset_collate();


    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        education text NOT NULL,
        experience text NOT NULL,
        about text NOT NULL,
        occupation varchar(255) NOT NULL,
        status varchar(20) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate; ";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'user_biodata_plugin_activate');


// Register and Login section
function user_biodata_register_login_section() {
    ob_start();

    if (!is_user_logged_in()) {
        if (isset($_POST['submit_signup'])) {
            $username = sanitize_user($_POST['username']);
            $email = sanitize_email($_POST['email']);
            $password = $_POST['password'];

            $userdata = array(
                'user_login'  =>  $username,
                'user_email'  =>  $email,
                'user_pass'   =>  $password,
            );

            $user_id = wp_insert_user($userdata);

            if (!is_wp_error($user_id)) {
                echo '<p>Registration successful. Please log in to continue.</p>';
            } else {
                echo '<p>Registration failed. Please try again.</p>';
            }
        }

        echo '<h3>Register</h3>';
        ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

           <br/><label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <br/><label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" name="submit_signup" value="Sign Up">
        </form>

        <h3>Login</h3>
        <?php
        wp_login_form();

        // Add signup button below login form
        // signup_btn();
        add_action('login_form_bottom', 'user_biodata_signup_button');
    } else {
        echo '<p>Welcome, ' . wp_get_current_user()->user_login . '</p>';
        echo '<a href="' . wp_logout_url(home_url()) . '">Logout</a>';
    }

    return ob_get_clean();
}
add_shortcode('user_biodata_register_login', 'user_biodata_register_login_section');

// Signup button HTML
function user_biodata_signup_button() {

    echo '<p>Don\'t have an account? <a href="' . wp_registration_url() . '">Sign Up</a></p>';
}


include(plugin_dir_path( __FILE__ ) . 'edit.php') ;
include(plugin_dir_path( __FILE__ ) . 'custom_save.php') ;
include(plugin_dir_path( __FILE__ ) . 'taxonomy.php') ;
include(plugin_dir_path( __FILE__ ) . 'pending.php') ;
include(plugin_dir_path( __FILE__ ) . 'approve.php') ;
include(plugin_dir_path( __FILE__ ) . 'list_candidates.php') ;






