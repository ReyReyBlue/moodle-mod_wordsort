<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('wordsort', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$wordsort = $DB->get_record('wordsort', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

require_capability('mod/wordsort:viewreports', $context);

$PAGE->set_url('/mod/wordsort/import.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('importwords', 'mod_wordsort'));
$PAGE->set_heading(format_string($course->fullname));

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('importwords', 'mod_wordsort'));

echo html_writer::div('Import page works!');

echo $OUTPUT->footer();