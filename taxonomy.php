<?php
// Create occupation taxonomy
function user_biodata_create_occupation_taxonomy() {
    $labels = array(
        'name' => 'Occupations',
        'singular_name' => 'Occupation',
        'search_items' => 'Search Occupations',
        'all_items' => 'All Occupations',
        'parent_item' => 'Parent Occupation',
        'parent_item_colon' => 'Parent Occupation:',
        'edit_item' => 'Edit Occupation',
        'update_item' => 'Update Occupation',
        'add_new_item' => 'Add New Occupation',
        'new_item_name' => 'New Occupation Name',
        'menu_name' => 'Occupation',
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'occupation'),
    );

    register_taxonomy('occupation', 'biodata', $args);
}
add_action('init', 'user_biodata_create_occupation_taxonomy');