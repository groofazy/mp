<?php
namespace local_mp\form;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class report_form extends \moodleform {

public function definition() {
    global $DB, $CFG;

    $mform = $this->_form;
    $mform->addElement('header', 'mainheader', 'Report Details');

    // 1. Course Selection (Triggers a page reload)
    $courses = $DB->get_records_menu('course', [], 'fullname', 'id, fullname');
    $mform->addElement('select', 'course_id', 'Select Course', ['0' => 'Choose a course...'] + $courses);
    $mform->getElement('course_id')->updateAttributes(['onchange' => 'this.form.submit();']);

    // 2. Get the selected course from the custom data passed in index.php
    $selectedcourse = $this->_customdata['course_id'] ?? 0;

    // 3. Populate Lessons based on that course
    $lessons = [];
    if ($selectedcourse) {
        // Fetch real lessons from the database
        $lessons = $DB->get_records_menu('lesson', ['course' => $selectedcourse], 'name', 'id, name');
    }
    
    $mform->addElement('select', 'lessons', 'Select Lesson', $lessons);

    // 4. The rest of your fields
    $mform->addElement('text', 'reportname', 'Report Title');
    $mform->setType('reportname', PARAM_TEXT);

    $students = $DB->get_records_menu('user', ['deleted' => 0], 'lastname', 'id, ' . $DB->sql_fullname());
    $mform->addElement('select', 'student_id', 'Select Student', $students);

    $mform->addElement('filepicker', 'report_image', 'Upload Student Photo', null, [
        'maxbytes' => $CFG->maxbytes,
        'accepted_types' => ['.jpg', '.jpeg', '.png']
    ]);

    $mform->addElement('textarea', 'comments', 'Teacher Comments', 'rows="5" cols="50"');

    $this->add_action_buttons(true, 'Generate PDF Report');
}
}