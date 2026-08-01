<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/report.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(format_string($wordsort->name));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

echo $OUTPUT->heading('Word Sort Attempts');

$attempts = $DB->get_records('wordsort_attempts', [
    'wordsortid' => $wordsort->id
]);

$table = new html_table();

$table->head = [
    'Student',
    'Attempt',
    'Score',
    'Percentage',
    'Time',
    'Submitted'
];

foreach ($attempts as $attempt) {

    $user = $DB->get_record('user', ['id' => $attempt->userid]);

    $table->data[] = [
        fullname($user),
        $attempt->attempt,
        $attempt->score . '/' . $attempt->totalwords,
        round($attempt->percentage, 1) . '%',
        $attempt->timeused . ' s',
        userdate($attempt->timecreated)
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();