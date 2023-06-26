<?php 

// Add/Edit biodata section
function user_biodata_add_edit_section() {
    ob_start();

    if (is_user_logged_in()) {
        // Check if biodata already exists for the user
        global $wpdb;
        $table_name = $wpdb->prefix . 'biodata';
        $user_id = get_current_user_id();
        $biodata = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = $user_id");

        if ($biodata) {
            echo '<h3>Edit Biodata</h3>';
        } else {
            echo '<h3>Add Biodata</h3>';
        }
        ?>
        <form method="post" action="">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?php echo $biodata ? $biodata->name : ''; ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo $biodata ? $biodata->email : ''; ?>" required>

            <label for="education">Education</label>
            <textarea name="education" id="education" rows="5" required><?php echo $biodata ? $biodata->education : ''; ?></textarea>

            <label for="experience">Experience</label>
            <textarea name="experience" id="experience" rows="5" required><?php echo $biodata ? $biodata->experience : ''; ?></textarea>

            <label for="about">About Me</label>
            <textarea name="about" id="about" rows="5" required><?php echo $biodata ? $biodata->about : ''; ?></textarea>

            <label for="occupation">Occupation</label>
            <?php
            $terms = get_terms(array(
                'taxonomy' => 'occupation',
                'hide_empty' => false,
            ));
            ?>
            <select name="occupation" id="occupation" required>
                <option value="">Select Occupation</option>
                <?php foreach ($terms as $term) { ?>
                    <option value="<?php echo $term->slug; ?>" <?php selected($biodata ? $biodata->occupation : '', $term->slug); ?>><?php echo $term->name; ?></option>
                <?php } ?>
            </select>

            <input type="submit" name="submit_biodata" value="Submit">
        </form>
        <?php
    } else {
        echo '<p>Please log in to add or edit your biodata.</p>';
    }

    return ob_get_clean();
}
add_shortcode('user_biodata_add_edit', 'user_biodata_add_edit_section');