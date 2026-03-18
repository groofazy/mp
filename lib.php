<?php
// local/mp/lib.php

/**
 * Extends the primary navigation to include the Course Report tool.
 */
function local_mp_extend_navigation_primary($navigation) {
    $url = new moodle_url('/local/mp/index.php');
    
    // Add a new node to the primary navigation bar.
    $navigation->add(
        get_string('pluginname', 'local_mp'), 
        $url, 
        navigation_node::TYPE_CUSTOM, 
        null, 
        'coursereport'
    );
}