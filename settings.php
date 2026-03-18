<?php
defined('MOODLE_INTERNAL') || die();

// Only show this to users who have permission to configure the site.
if ($hassiteconfig) {
    // 1. Create the link (External Page).
    $url = new moodle_url('/local/mp/index.php');
    $pagename = 'local_mp_report'; // Unique internal name
    $pagetitle = "Student Report Generator"; // Display name

    $temp = new admin_externalpage($pagename, $pagetitle, $url);

    // 2. Add it to the "Local Plugins" section of Site Admin.
    $ADMIN->add('localplugins', $temp);
}