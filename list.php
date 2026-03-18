<?php
/**
 * Saved Reports Dashboard
 * Path: /local/mp/list.php
 */

require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();

$PAGE->set_url(new moodle_url('/local/mp/list.php'));
$PAGE->set_context($context);
$PAGE->set_title('Saved Student Reports');
$PAGE->set_heading('Report Management Dashboard');

echo $OUTPUT->header();

// 1. Add a "Create New Report" button at the top.
echo $OUTPUT->single_button(new moodle_url('/local/mp/index.php'), 'Generate New Report', 'get');

// 2. Fetch all reports from our custom table.
global $DB;
$reports = $DB->get_records('local_mp_reports', null, 'timecreated DESC');

if (empty($reports)) {
    echo $OUTPUT->notification('No reports found. Create one to get started!', 'info');
} else {
    // 3. Set up the Moodle Table.
    $table = new html_table();
    $table->head = ['Date', 'Report Name', 'Student', 'Course', 'Actions'];
    $table->attributes['class'] = 'generaltable mt-3';

    foreach ($reports as $report) {
        // Fetch names for display.
        $studentname = $DB->get_field('user', $DB->sql_fullname(), ['id' => $report->userid]);
        $coursename  = $DB->get_field('course', 'fullname', ['id' => $report->courseid]);
        $date = userdate($report->timecreated, '%d %b %Y, %I:%M %p');

        // 4. Create Action Buttons.
        $viewurl  = new moodle_url('/local/mp/view_pdf.php', ['id' => $report->id]);
        $emailurl = new moodle_url('/local/mp/email_report.php', ['id' => $report->id]);

        $actions = $OUTPUT->action_link($viewurl, 'View PDF', null, ['class' => 'btn btn-secondary btn-sm mr-2']);
        $actions .= $OUTPUT->action_link($emailurl, 'Email Parent', null, ['class' => 'btn btn-primary btn-sm']);

        $table->data[] = [
            $date,
            $report->reportname,
            $studentname,
            $coursename,
            $actions
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();