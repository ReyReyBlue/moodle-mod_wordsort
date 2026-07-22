<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);
$wordid = required_param('wordid', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$PAGE->set_url('/mod/wordsort/deleteword.php', [
    'id' => $cm->id,
    'wordid' => $wordid
]);

$PAGE->set_context($context);
$PAGE->activityheader->set_description('');
$PAGE->set_title(get_string('deleteword', 'wordsort'));
$PAGE->set_heading(format_string($course->fullname));

$word = $DB->get_record(
    'wordsort_words',
    [
        'id' => $wordid,
        'wordsortid' => $wordsort->id
    ],
    '*',
    MUST_EXIST
);

$yesurl = new moodle_url('/mod/wordsort/deleteword.php', [
    'id' => $cm->id,
    'wordid' => $wordid,
    'confirm' => 1
]);

$nourl = new moodle_url('/mod/wordsort/managewords.php', [
    'id' => $cm->id
]);

if (optional_param('confirm', 0, PARAM_BOOL)) {
    $DB->delete_records('wordsort_words', [
    'id' => $wordid,
    'wordsortid' => $wordsort->id
]);

    redirect(
        $nourl,
        get_string('worddeleted', 'wordsort')
    );
}

echo $OUTPUT->header();

echo $OUTPUT->confirm(
    get_string('confirmdeleteword', 'wordsort', s($word->word)),
    $yesurl,
    $nourl
);

echo $OUTPUT->footer();