<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

require_capability('mod/wordsort:viewreports', $context);

$PAGE->set_url('/mod/wordsort/report.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('attemptsreport', 'mod_wordsort'));

$attempts = $DB->get_records(
    'wordsort_attempts',
    ['wordsortid' => $wordsort->id],
    'timecreated DESC'
);

$table = new html_table();

$table->head = [
    get_string('student', 'mod_wordsort'),
    get_string('attempt', 'mod_wordsort'),
    get_string('status', 'mod_wordsort'),
    get_string('score', 'mod_wordsort'),
    get_string('percentage', 'mod_wordsort'),
    get_string('time', 'mod_wordsort'),
    get_string('submitted', 'mod_wordsort'),
    get_string('review', 'mod_wordsort'),
];

foreach ($attempts as $attempt) {

    $user = $DB->get_record('user', ['id' => $attempt->userid]);

    $table->data[] = [
        fullname($user),
        $attempt->attempt,
        get_string('status' . $attempt->status, 'mod_wordsort'),
        $attempt->score . '/' . $attempt->totalwords,
        round($attempt->percentage, 1) . '%',
        $attempt->timeused . ' s',
        userdate($attempt->timecreated),
        html_writer::link(
            new moodle_url(
                '/mod/wordsort/review.php',
                [
                    'id' => $cm->id,
                    'attemptid' => $attempt->id
                ]
            ),
            get_string('viewanswers', 'mod_wordsort')
        )
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();