<?php
/**
 * Student Report Generator - Main Controller
 * Path: /local/mp/index.php
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/pdflib.php');

// Security: Ensure the user is logged in.
require_login();
$context = context_system::instance();

// Page Setup.
$PAGE->set_url(new moodle_url('/local/mp/index.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_mp'));
$PAGE->set_heading('Student Report Generator');

// 1. Capture the course ID from the dropdown selection.
$courseid = optional_param('course_id', 0, PARAM_INT);

// 2. Initialize the form, passing the course ID into custom data.
$form = new \local_mp\form\report_form(null, ['course_id' => $courseid]);

// 3. Handle Form Submission.
if ($data = $form->get_data()) {
    
    // Check if the 'submitbutton' was the specific trigger.
    if (isset($_POST['submitbutton'])) {
        global $DB, $USER;

        // Prepare the database record.
        $record = new stdClass();
        $record->courseid    = $data->course_id;
        $record->userid      = $data->student_id;
        $record->reportname  = $data->reportname;
        $record->lessons     = $data->lessons;
        $record->comments    = $data->comments;
        $record->timecreated = time();

        try {
            // Insert into local_mp_reports table.
            $reportid = $DB->insert_record('local_mp_reports', $record);

            // Move files from draft area to permanent storage if needed.
            // For now, we notify the user and redirect to a list of reports.
            \core\notification::success("Report '{$data->reportname}' has been saved to the database.");
            
            // Redirect to a list page (we'll create this next) to see all saved reports.
            redirect(new moodle_url('/local/mp/list.php'));

        } catch (Exception $e) {
            \core\notification::error("Database Error: " . $e->getMessage());
        }
    }
    
    // Note: If 'submitbutton' wasn't set, it means the course dropdown triggered the submit.
    // In that case, we fall through to the display code, and Moodle automatically 
    // keeps the typed data (comments, title) in the form fields.
}

// 4. Render the Page.
echo $OUTPUT->header();

// Optional: Link to the Dashboard/List view so teachers can find old reports.
echo $OUTPUT->single_button(new moodle_url('/local/mp/list.php'), 'View Saved Reports', 'get');

$form->display();

echo $OUTPUT->footer();