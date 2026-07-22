<?php

require('../../config.php');

use mod_wordsort\form\word_form;

require_once($CFG->dirroot . '/mod/wordsort/classes/form/word_form.php');

$id = optional_param('id', 0, PARAM_INT);

if (!$id) {
    throw new moodle_exception('missingparam', '', '', 'id');
}

$wordid = optional_param('wordid', 0, PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);
$wordrecord = null;

if ($wordid) {
    $wordrecord = $DB->get_record(
        'wordsort_words',
        [
            'id' => $wordid,
            'wordsortid' => $wordsort->id
        ],
        '*',
        MUST_EXIST
    );
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/editword.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('word', 'wordsort'));
$PAGE->set_heading(format_string($course->fullname));

$form = new word_form(
    new moodle_url('/mod/wordsort/editword.php', [
    'id' => $cm->id,
    'wordid' => $wordid
]),
    [
        'cmid' => $cm->id,
        'categoryleft' => $wordsort->categoryleft,
        'categoryright' => $wordsort->categoryright
    ]
);

if ($wordrecord) {
    $wordrecord->id = $cm->id;
    $form->set_data($wordrecord);
}

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/wordsort/managewords.php', [
        'id' => $cm->id
    ]));
}

if ($data = $form->get_data()) {

    $record = new stdClass();

if ($wordid) {
    // Update existing word.
    $record->id = $wordid;
} else {
    // Create new word.
    $record->wordsortid = $wordsort->id;
    $maxsortorder = $DB->get_field_sql(
    "SELECT MAX(sortorder)
       FROM {wordsort_words}
      WHERE wordsortid = ?",
    [$wordsort->id]
);

echo '<pre>';
echo 'Max sortorder: ';

$record->sortorder = is_null($maxsortorder) ? 0 : $maxsortorder + 1;
}

$record->word = trim($data->word);
$record->correctside = $data->correctside;

if ($wordid) {
    $DB->update_record('wordsort_words', $record);
} else {
    $DB->insert_record('wordsort_words', $record);
}

    redirect(
        new moodle_url('/mod/wordsort/managewords.php', [
            'id' => $cm->id
        ]),
        get_string('wordsaved', 'wordsort')
    );
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('addword', 'wordsort'));

$form->display();

echo $OUTPUT->footer();