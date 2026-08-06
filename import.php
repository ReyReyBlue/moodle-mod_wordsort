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

$form = new \mod_wordsort\form\import_form(
    new moodle_url('/mod/wordsort/import.php', [
        'id' => $cm->id
    ]),
    );

        if ($form->is_cancelled()) {
            redirect(new moodle_url('/mod/wordsort/managewords.php', [
                'id' => $cm->id
            ]));
        }

        $data = $form->get_data();

        if ($data) {

                $content = $form->get_file_content('csvfile');
                $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

                if ($content === false) {
                    throw new moodle_exception(
                        'cannotreadcsv',
                        'mod_wordsort'
                    );
                }

                $handle = fopen('php://temp', 'r+');
                fwrite($handle, $content);
                rewind($handle);

                $header = fgetcsv($handle, 0, ',');

                    if (count($header) !== 4) {
                        throw new moodle_exception(
                            'invalidcsv',
                            'mod_wordsort'
                        );
                    }

                $sortorder = $DB->get_field_sql(
                    "SELECT COALESCE(MAX(sortorder), -1)
                        FROM {wordsort_words}
                        WHERE wordsortid = ?",
                        [$wordsort->id]
                    );

                $imported = 0;
                $skipped = 0;

                while (($row = fgetcsv($handle, 0, ',')) !== false) {

                    if (count($row) < 4) {
                        continue;
                    }

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

                    $correctside = ($correct === $leftcategory) ? 0 : 1;

                    $exists = $DB->record_exists(
                        'wordsort_words',
                        [
                            'wordsortid' => $wordsort->id,
                            'word' => $word,
                            'correctside' => $correctside,
                        ]
                    );

                    if ($exists) {
                        $skipped++;
                        continue;
                    }

                    $sortorder++;

                    $record = new stdClass();

                    $record->wordsortid = $wordsort->id;
                    $record->word = $word;
                    $record->correctside = $correctside;
                    $record->sortorder = $sortorder;

                    $DB->insert_record('wordsort_words', $record);
                    $imported++;
                }

                fclose($handle);

                $message = get_string(
                    'importsummary',
                    'mod_wordsort',
                    [
                        'imported' => $imported,
                        'skipped' => $skipped,
                    ]
                );

                redirect(
                    new moodle_url('/mod/wordsort/managewords.php', [
                        'id' => $cm->id
                    ]),
                    $message
                );
         }

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('importwords', 'mod_wordsort'));

$form->display();

echo $OUTPUT->footer();