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

$draftitemid = file_get_submitted_draft_itemid('csvfile');

    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'mod_wordsort',
        'import',
        0,
        [
            'subdirs' => 0,
            'maxfiles' => 1,
        ]
    );

$form = new \mod_wordsort\form\import_form(
    new moodle_url('/mod/wordsort/import.php', [
        'id' => $cm->id
    ]),
    [
        'draftitemid' => $draftitemid,
    ]
);

if ($form->is_cancelled()) {
    redirect(new moodle_url('/mod/wordsort/manage.php', [
        'id' => $cm->id
    ]));
}

if ($data = $form->get_data()) {

    $draftitemid = $data->csvfile;

    $usercontext = context_user::instance($USER->id);

    $fs = get_file_storage();

    $files = $fs->get_area_files(
        $usercontext->id,
        'user',
        'draft',
        $draftitemid,
        'id',
        false
    );

    $file = reset($files);

    $content = $file->get_content();

    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $content);
    rewind($handle);

    // Read and discard the header.
    $header = fgetcsv($handle, 0, ',');

    while (($row = fgetcsv($handle, 0, ',')) !== false) {

        $leftcategory = trim($row[0]);
        $rightcategory = trim($row[1]);
        $word = trim($row[2]);
        $correct = trim($row[3]);

        if ($correct !== $leftcategory && $correct !== $rightcategory) {
            throw new moodle_exception(
                'invalidcategory',
                'mod_wordsort',
                '',
                $correct
            );
        }

        echo "<br>";
        echo $word . " -> " . $correct;
    }

//    echo '<pre>';
//    echo htmlspecialchars($content);
//    echo '</pre>';

}

$form->display();

echo $OUTPUT->footer();