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

    echo html_writer::start_div('wordsort-teacher-toolbar mb-4');

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
    echo html_writer::empty_tag('hr');
}

//--------------------------------------------------
// Start screen
//--------------------------------------------------

echo html_writer::start_div(
    'wordsort-start-screen mt-4',
    [
        'id' => 'wordsort-start-screen'
    ]
);

echo html_writer::start_div('card');

echo html_writer::start_div('card-body');

// Activity information.

echo html_writer::div(
    get_string('attemptslabel', 'wordsort') .
    ': ' .
    $wordsort->maxattempts,
    'mb-3'
);

if ($wordsort->timingmode != 0) {

    echo html_writer::div(
        get_string('timelimitlabel', 'wordsort') .
        ': ' .
        $wordsort->timevalue .
        ' ' .
        get_string('seconds'),
        'mb-4'
    );
}

// Start button.

echo html_writer::start_div('text-center');

echo html_writer::tag(
    'button',
    get_string('start', 'wordsort'),
    [
        'id' => 'wordsort-start',
        'class' => 'btn btn-primary px-4 py-2'
    ]
);

echo html_writer::end_div(); // text-center

echo html_writer::end_div(); // card-body

echo html_writer::end_div(); // card

echo html_writer::end_div(); // wordsort-start-screen

//--------------------------------------------------
// Activity screen
//--------------------------------------------------

echo html_writer::start_div(
    'wordsort-activity-screen',
    [
        'id' => 'wordsort-activity-screen',
        'style' => 'display:none;'
    ]
);

echo html_writer::start_div('card');

echo html_writer::start_div('card-body text-center');

// Temporary placeholder.

echo $OUTPUT->heading('Activity screen', 3);

echo html_writer::div(
    'The first word will appear here.',
    'mt-3'
);

echo html_writer::end_div(); // card-body

echo html_writer::end_div(); // card

echo html_writer::end_div(); // activity-screen

//--------------------------------------------------
// Finish screen
//--------------------------------------------------
$PAGE->requires->js_call_amd(
    'mod_wordsort/view',
    'init'
);

echo $OUTPUT->footer();