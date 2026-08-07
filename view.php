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

// Check if the activity is ready.
$activityready = !empty($words);

// Prepare words for JavaScript.
$jswords = [];

foreach ($words as $word) {
    $jswords[] = [
    'word' => $word->word,
    'correctside' => (int)$word->correctside,
];
}

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
    'init',
    [
        $wordsort->id,
        $wordsort->categoryleft,
        $wordsort->categoryright,
        $wordsort->timingmode,
        $wordsort->timevalue,
        $wordsort->maxattempts,
        $wordsort->shufflewords,
        $wordsort->feedbackmode,

        get_string('result', 'mod_wordsort'),
        get_string('word', 'mod_wordsort'),
        get_string('youranswer', 'mod_wordsort'),
        get_string('correctanswer', 'mod_wordsort')
    ]
);

echo html_writer::tag(
    'div',
    '',
    [
        'id' => 'wordsort-data',
        'data-words' => json_encode($jswords),
        'style' => 'display:none;'
    ]
);

echo $OUTPUT->header();

if (!$activityready) {

    echo html_writer::start_div('card mt-4');

    echo html_writer::start_div('card-body');

    echo $OUTPUT->heading(
        get_string('activitysetup', 'mod_wordsort'),
        2
    );

    echo html_writer::div(
        get_string('activitysetupdesc', 'mod_wordsort'),
        'mb-4'
    );

    echo html_writer::div(
        '✓ ' . get_string('categoriesconfigured', 'mod_wordsort'),
        'mb-2'
    );

    echo html_writer::div(
        '✗ ' . get_string('wordsnotadded', 'mod_wordsort'),
        'mb-4'
    );

    echo html_writer::div(
        get_string('activitysetupnext', 'mod_wordsort'),
        'mb-3'
    );

    echo html_writer::link(
        new moodle_url('/mod/wordsort/managewords.php', [
            'id' => $cm->id
        ]),
        get_string('managewords', 'mod_wordsort'),
        ['class' => 'btn btn-primary']
    );

    echo html_writer::end_div(); // card-body
    echo html_writer::end_div(); // card

    echo $OUTPUT->footer();
    exit;
}

//--------------------------------------------------
// Teacher tools
//--------------------------------------------------

if (has_capability('moodle/course:manageactivities', $context)) {

    echo html_writer::start_div(
        'wordsort-teacher-toolbar mb-4'
    );

    echo html_writer::tag(
        'strong',
        get_string('teachertools', 'mod_wordsort') . ': ',
        ['class' => 'me-2']
    );

    // Manage words.
    echo html_writer::link(
        new moodle_url('/mod/wordsort/managewords.php', ['id' => $cm->id]),
        get_string('managewords', 'mod_wordsort'),
        ['class' => 'me-3']
    );

    // Reports.
    echo html_writer::link(
        new moodle_url('/mod/wordsort/report.php', ['id' => $cm->id]),
        get_string('viewattempts', 'mod_wordsort'),
        ['class' => 'me-3']
    );

    echo html_writer::end_div();

    echo html_writer::empty_tag('hr');
}

//--------------------------------------------------
// Game
//--------------------------------------------------

echo html_writer::start_div('wordsort-game');

// ------------------------------------------------------------
// Start screen
// ------------------------------------------------------------

// Check whether the user has attempts left.
$attemptcount = $DB->count_records(
    'wordsort_attempts',
    [
        'wordsortid' => $wordsort->id,
        'userid' => $USER->id,
    ]
);

$hasattemptsleft = ($attemptcount < $wordsort->maxattempts);

// Get the latest attempt number.
$currentattempt = $DB->get_field_sql(
    "SELECT MAX(attempt)
       FROM {wordsort_attempts}
      WHERE wordsortid = ?
        AND userid = ?",
    [$wordsort->id, $USER->id]
);

$currentattempt = $currentattempt ?: 0;

// Calculate the next attempt.
$nextattempt = $currentattempt + 1;

// Check if the user still has attempts left.
$hasattemptsleft = ($nextattempt <= $wordsort->maxattempts);

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
    get_string('attempt', 'mod_wordsort') .
    ':</strong> ' .
    min($nextattempt, $wordsort->maxattempts) .
    ' / ' .
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
if ($hasattemptsleft) {
    echo html_writer::tag(
        'button',
        get_string('start', 'wordsort'),
        [
            'id' => 'wordsort-start',
            'class' => 'btn btn-primary px-4 py-2'
        ]
    );
} else {
    echo html_writer::tag(
        'p',
        get_string('nomoreattempts', 'wordsort'),
        ['class' => 'text-danger']
    );
}

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

echo html_writer::div(
    '',
    'wordsort-timer',
    [
        'id' => 'wordsort-timer'
    ]
);

echo html_writer::start_div(
    'wordsort-item',
    [
        'id' => 'wordsort-item'
    ]
);

echo html_writer::div(
    '',
    'wordsort-word',
    [
        'id' => 'wordsort-word'
    ]
);

echo html_writer::div(
    '',
    'wordsort-feedback',
    [
        'id' => 'wordsort-feedback'
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

echo html_writer::start_div(
    'wordsort-results-screen',
    [
        'id' => 'wordsort-results-screen',
        'style' => 'display:none;'
    ]
);

echo html_writer::tag('h2', get_string('finished', 'mod_wordsort'));

echo html_writer::div('', 'wordsort-result-attempt', [
    'id' => 'wordsort-result-attempt'
]);

echo html_writer::div('', 'wordsort-result-attempts', [
    'id' => 'wordsort-result-attempts'
]);

echo html_writer::div('', 'wordsort-result-score', [
    'id' => 'wordsort-result-score'
]);

echo html_writer::div('', 'wordsort-result-bestscore', [
    'id' => 'wordsort-result-bestscore'
]);

echo html_writer::div('', 'wordsort-result-time', [
    'id' => 'wordsort-result-time'
]);

echo html_writer::start_div('wordsort-result-buttons', [
    'id' => 'wordsort-result-buttons'
]);

echo html_writer::tag(
    'button',
    get_string('tryagain', 'mod_wordsort'),
    [
        'id' => 'wordsort-tryagain',
        'type' => 'button',
        'class' => 'btn btn-secondary'
    ]
);

echo html_writer::tag(
    'button',
    get_string('submit', 'mod_wordsort'),
    [
        'id' => 'wordsort-submit',
        'type' => 'button',
        'class' => 'btn btn-primary'
    ]
);

echo html_writer::end_div(); // wordsort-result-buttons

echo html_writer::end_div(); // wordsort-results-screen

//--------------------------------------------------
// Submission review screen
//--------------------------------------------------

echo html_writer::start_div(
    'wordsort-submission-screen',
    [
        'id' => 'wordsort-submission-screen',
        'style' => 'display:none;'
    ]
);

echo html_writer::tag(
    'h2',
    get_string('submissionsummary', 'mod_wordsort')
);

echo html_writer::div('', 'wordsort-submission-bestscore', [
    'id' => 'wordsort-submission-bestscore'
]);

echo html_writer::div('', 'wordsort-submission-attempts', [
    'id' => 'wordsort-submission-attempts'
]);

echo html_writer::div('', 'wordsort-submission-bestattempt', [
    'id' => 'wordsort-submission-bestattempt'
]);

echo html_writer::div('', 'wordsort-submission-time', [
    'id' => 'wordsort-submission-time'
]);

echo html_writer::div('', 'wordsort-submission-answers', [
    'id' => 'wordsort-submission-answers'
]);

echo html_writer::end_div(); // wordsort-submission-screen

echo $OUTPUT->footer();