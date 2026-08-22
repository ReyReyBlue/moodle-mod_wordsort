<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);
$attemptid = required_param('attemptid', PARAM_INT);
$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/wordsort:viewreports', $context);

$PAGE->set_url('/mod/wordsort/review.php', [
    'id' => $id,
    'attemptid' => $attemptid
]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('review', 'mod_wordsort'));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->requires->css('/mod/wordsort/styles.css');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('review', 'mod_wordsort'));

    $attempt = $DB->get_record(
        'wordsort_attempts',
        ['id' => $attemptid],
        '*',
        MUST_EXIST
    );

$backicon = $OUTPUT->pix_icon(
    'i/arrow-left',
    get_string('back', 'moodle')
);

echo html_writer::link(
    new moodle_url('/mod/wordsort/report.php', ['id' => $id]),
    $backicon . ' ' . get_string('backtoreport', 'mod_wordsort')
);

echo html_writer::start_div('card mb-4');

echo html_writer::start_div('card-body');

echo html_writer::tag(
    'h3',
    get_string('attemptreviewtitle', 'mod_wordsort', $attempt->attempt)
);

echo html_writer::div(
    get_string('status', 'mod_wordsort') . ': ' .
    get_string('status' . $attempt->status, 'mod_wordsort'),
    'mb-2'
);


echo html_writer::div(
    get_string('score', 'mod_wordsort') . ': ' .
    $attempt->score .
    '/' .
    $attempt->totalwords .
    ' (' .
    round($attempt->percentage, 1) .
    '%)',
    'mb-2'
);

echo html_writer::div(
    get_string('time') . ': ' .
    $attempt->timeused . ' ' .
    get_string('seconds'),
    'mb-2'
);


echo html_writer::end_div(); // card-body
echo html_writer::end_div(); // card

$answers = json_decode($attempt->answers ?? '[]');

$table = new html_table();

$table->head = [
    get_string('word', 'mod_wordsort'),
    get_string('correctcategory', 'mod_wordsort'),
    get_string('studentanswer', 'mod_wordsort'),
    get_string('result', 'mod_wordsort'),
];

$table->align = [
    'left',
    'left',
    'left',
    'center',
];

foreach ($answers as $answer) {

    $wordrecord = $DB->get_record(
        'wordsort_words',
        [
            'wordsortid' => $wordsort->id,
            'word' => $answer->word
        ],
        '*',
        MUST_EXIST
    );

    if ($wordrecord->correctside == 0) {
        $correctanswer = $wordsort->categoryleft;
    } else {
        $correctanswer = $wordsort->categoryright;
    }

    if ($answer->selected == 0) {
        $studentanswer = $wordsort->categoryleft;
    } else {
        $studentanswer = $wordsort->categoryright;
    }

    if ((int)$answer->selected === (int)$answer->correct) {
        $result = $OUTPUT->pix_icon(
            'i/grade_correct',
            get_string('correct', 'mod_wordsort')
        );
    } else {
        $result = $OUTPUT->pix_icon(
            'i/grade_incorrect',
            get_string('incorrect', 'mod_wordsort')
        );
    }

    $table->data[] = [
        $answer->word,
        $correctanswer,
        $studentanswer,
        $result,
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();