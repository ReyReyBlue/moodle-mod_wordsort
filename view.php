<?php
require('../../config.php');

//--------------------------------------------------
// Load activity
//--------------------------------------------------

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

//--------------------------------------------------
// Page setup
//--------------------------------------------------

$PAGE->set_url('/mod/wordsort/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();

//--------------------------------------------------
// Teacher toolbar
//--------------------------------------------------

if (has_capability('moodle/course:manageactivities', $context)) {

    echo $OUTPUT->heading(
        get_string('teachertools', 'wordsort'),
        4
    );

    echo html_writer::start_div('wordsort-teacher-toolbar mb-3');

    // Buttons will go here.
    echo html_writer::link(
    new moodle_url('/mod/wordsort/editword.php', ['id' => $cm->id]),
    get_string('addword', 'wordsort'),
    ['class' => 'btn btn-secondary me-2']
);

echo html_writer::link(
    new moodle_url('/mod/wordsort/bulkadd.php', ['id' => $cm->id]),
    get_string('bulkaddwords', 'wordsort'),
    ['class' => 'btn btn-secondary me-2']
);

    echo html_writer::link(
    new moodle_url('/mod/wordsort/managewords.php', ['id' => $cm->id]),
    get_string('managewords', 'wordsort'),
    ['class' => 'btn btn-secondary me-2']
);

echo html_writer::link(
    new moodle_url('/course/modedit.php', ['update' => $cm->id]),
    get_string('editsettings'),
    ['class' => 'btn btn-secondary']
);

    echo html_writer::end_div();
}

//--------------------------------------------------
// Start screen
//--------------------------------------------------


//--------------------------------------------------
// Activity screen
//--------------------------------------------------


//--------------------------------------------------
// Finish screen
//--------------------------------------------------

echo $OUTPUT->footer();