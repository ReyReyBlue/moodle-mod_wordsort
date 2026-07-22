<?php

require('../../config.php');
require_once($CFG->dirroot . '/mod/wordsort/lib.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/wordsort/bulkadd.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('bulkaddwords', 'wordsort'));
$PAGE->set_heading(format_string($course->fullname));

//
// Process form submission BEFORE any output.
//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $leftwords = optional_param('leftwords', '', PARAM_RAW);
    $rightwords = optional_param('rightwords', '', PARAM_RAW);

    $leftlist = preg_split('/\r\n|\r|\n/', $leftwords);
    $rightlist = preg_split('/\r\n|\r|\n/', $rightwords);
    $leftlist = array_map('trim', $leftlist);
    $leftlist = array_filter($leftlist);
    $leftlist = array_unique($leftlist);
    $rightlist = array_map('trim', $rightlist);
    $rightlist = array_filter($rightlist);
    $rightlist = array_unique($rightlist);

    $sortorder = $DB->get_field_sql(
        "SELECT MAX(sortorder)
           FROM {wordsort_words}
          WHERE wordsortid = ?",
        [$wordsort->id]
    );

    $sortorder = is_null($sortorder) ? 0 : $sortorder + 1;

    $addedcount = 0;

    // Left category
    foreach ($leftlist as $word) {

        $word = trim($word);

        if ($word === '') {
            continue;
        }

        $record = new stdClass();
        $record->wordsortid = $wordsort->id;
        $record->word = $word;
        $record->correctside = 0;
        $record->sortorder = $sortorder++;

        $DB->insert_record('wordsort_words', $record);
        $addedcount++;
    }

    // Right category
    foreach ($rightlist as $word) {

        $word = trim($word);

        if ($word === '') {
            continue;
        }

        $record = new stdClass();
        $record->wordsortid = $wordsort->id;
        $record->word = $word;
        $record->correctside = 1;
        $record->sortorder = $sortorder++;

        $DB->insert_record('wordsort_words', $record);
        $addedcount++;
    }

    redirect(
    new moodle_url(
        '/mod/wordsort/managewords.php',
        ['id' => $cm->id]
    ),
    get_string('bulkwordsadded', 'wordsort', $addedcount)
);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('bulkaddwords', 'wordsort'));

echo '<form method="post">';

echo '<h3>' . format_string($wordsort->categoryleft) . '</h3>';
echo '<textarea name="leftwords" rows="12" cols="50"></textarea>';

echo '<br><br>';

echo '<h3>' . format_string($wordsort->categoryright) . '</h3>';
echo '<textarea name="rightwords" rows="12" cols="50"></textarea>';

echo '<br><br>';

echo html_writer::empty_tag('input', [
    'type' => 'submit',
    'value' => get_string('savechanges'),
    'class' => 'btn btn-primary'
]);

echo ' ';

echo html_writer::link(
    new moodle_url('/mod/wordsort/managewords.php', ['id' => $cm->id]),
    get_string('cancel'),
    ['class' => 'btn btn-secondary']
);

echo '</form>';

echo $OUTPUT->footer();