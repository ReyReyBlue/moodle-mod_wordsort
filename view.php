<?php
require('../../config.php');

//--------------------------------------------------
// Load activity
//--------------------------------------------------

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

// Get words for this activity.
$words = $DB->get_records(
    'wordsort_words',
    ['wordsortid' => $wordsort->id],
    'sortorder ASC'
);
$firstword = reset($words);


require_login($course, true, $cm);

$context = context_module::instance($cm->id);

//--------------------------------------------------
// Page setup
//--------------------------------------------------

$PAGE->set_url('/mod/wordsort/view.php', ['id' => $cm->id]);
$PAGE->set_context($context);

$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));

// JavaScript.
$PAGE->requires->js_call_amd(
    'mod_wordsort/view',
    'init'
);

echo $OUTPUT->header();

//--------------------------------------------------
// Teacher toolbar
//--------------------------------------------------

if (has_capability('moodle/course:manageactivities', $context)) {

    echo $OUTPUT->heading(
        get_string('teachertools', 'wordsort'),
        4
    );

    echo html_writer::start_div(
        'wordsort-teacher-toolbar mb-4'
    );

    // Add word.
    echo html_writer::link(
        new moodle_url('/mod/wordsort/editword.php', ['id' => $cm->id]),
        get_string('addword', 'wordsort'),
        ['class' => 'btn btn-secondary me-2']
    );

    // Bulk add.
    echo html_writer::link(
        new moodle_url('/mod/wordsort/bulkadd.php', ['id' => $cm->id]),
        get_string('bulkaddwords', 'wordsort'),
        ['class' => 'btn btn-secondary me-2']
    );

    // Manage words.
    echo html_writer::link(
        new moodle_url('/mod/wordsort/managewords.php', ['id' => $cm->id]),
        get_string('managewords', 'wordsort'),
        ['class' => 'btn btn-secondary me-2']
    );

    // Edit settings.
    echo html_writer::link(
        new moodle_url('/course/modedit.php', ['update' => $cm->id]),
        get_string('editsettings'),
        ['class' => 'btn btn-secondary']
    );

    echo html_writer::end_div();

    echo html_writer::empty_tag('hr');
}

//--------------------------------------------------
// Game
//--------------------------------------------------

echo html_writer::start_div('wordsort-game');

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

echo html_writer::start_div('card-body text-center');

// Game information.

echo html_writer::div(
    '<strong>' .
    get_string('attemptslabel', 'wordsort') .
    ':</strong> ' .
    $wordsort->maxattempts,
    'mb-3'
);

if ($wordsort->timingmode != 0) {

    echo html_writer::div(
        '<strong>' .
        get_string('timelimitlabel', 'wordsort') .
        ':</strong> ' .
        $wordsort->timevalue .
        ' ' .
        get_string('seconds'),
        'mb-4'
    );
}

// Start button.

echo html_writer::tag(
    'button',
    get_string('start', 'wordsort'),
    [
        'id' => 'wordsort-start',
        'class' => 'btn btn-primary px-4 py-2'
    ]
);

echo html_writer::end_div(); // card-body

echo html_writer::end_div(); // card

echo html_writer::end_div(); // wordsort-start-screen

//--------------------------------------------------
// Activity screen
//--------------------------------------------------

echo html_writer::start_div(
    'wordsort-activity-screen hidden',
    [
        'id' => 'wordsort-activity-screen'
    ]
);

echo html_writer::start_div('card');

echo html_writer::start_div('card-body text-center');

//--------------------------------------------------
// Item
//--------------------------------------------------

echo html_writer::start_div(
    'wordsort-item',
    [
        'id' => 'wordsort-item'
    ]
);

$displayword = $firstword
    ? format_string($firstword->word)
    : get_string('nowords', 'wordsort');

echo html_writer::div(
    $displayword,
    'wordsort-word',
    [
        'id' => 'wordsort-word'
    ]
);

echo html_writer::end_div(); // wordsort-item

// Category buttons.
echo html_writer::start_div('wordsort-choices mt-4');

// Left.
echo html_writer::start_div('wordsort-choice-button');

echo html_writer::tag(
    'button',
    format_string($wordsort->categoryleft),
    [
        'class' => 'wordsort-choice wordsort-choice-left',
        'id' => 'choice-left'
    ]
);

echo html_writer::end_div(); // wordsort-choice-button

// Right.
echo html_writer::start_div('wordsort-choice-button');

echo html_writer::tag(
    'button',
    format_string($wordsort->categoryright),
    [
        'class' => 'wordsort-choice wordsort-choice-right',
        'id' => 'choice-right'
    ]
);

echo html_writer::end_div(); // wordsort-choice-button

echo html_writer::end_div(); // wordsort-choices
echo html_writer::end_div(); // Card body.
echo html_writer::end_div(); // card
echo html_writer::end_div(); // activity-screen

//--------------------------------------------------
// Finish screen
//--------------------------------------------------

// TODO: Implement finish screen.

echo html_writer::end_div(); // wordsort-game

echo $OUTPUT->footer();