<?php



// Display list of biodata candidates with filters
function user_biodata_display_list() {
    ob_start();

    global $wpdb;
    $table_name = $wpdb->prefix . 'biodata';
    $biodata_list = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'approved'");

    $occupations = get_terms(array(
        'taxonomy' => 'occupation',
        'hide_empty' => false,
    ));

    $occupation_filter = isset($_GET['occupation_filter']) ? sanitize_text_field($_GET['occupation_filter']) : '';

    // Filter by occupation
    echo '<form method="get" action="">';
    echo '<label for="occupation_filter">Filter by Occupation:</label>';
    echo '<select name="occupation_filter" id="occupation_filter">';
    echo '<option value="">All Occupations</option>';
    foreach ($occupations as $occupation) {
        $selected = ($occupation_filter === $occupation->slug) ? 'selected' : '';
        echo '<option value="' . $occupation->slug . '" ' . $selected . '>' . $occupation->name . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" value="Filter">';
    echo '</form>';

    if ($biodata_list) {
        echo '<h3>List of Biodata Candidates</h3>';
        echo '<table>';
        echo '<tr><th>Name</th><th>Email</th><th>Occupation</th></tr>';

        foreach ($biodata_list as $biodata) {
            // Apply occupation filter
            if (!empty($occupation_filter) && $occupation_filter !== $biodata->occupation) {
                continue;
            }

            echo '<tr>';
            echo '<td>' . $biodata->name . '</td>';
            echo '<td>' . $biodata->email . '</td>';
            echo '<td>' . $biodata->occupation . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No approved biodata available.</p>';
    }

    return ob_get_clean();
}
add_shortcode('user_biodata_list', 'user_biodata_display_list');
