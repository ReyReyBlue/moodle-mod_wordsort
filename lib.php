<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Library of interface functions and constants for module wordsort.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('WORDSORT_NOTIMER', 0);
define('WORDSORT_COUNTDOWN', 1);
define('WORDSORT_STOPWATCH', 2);
define('WORDSORT_FEEDBACKNONE', 0);
define('WORDSORT_FEEDBACKEACHMOVE', 1);
define('WORDSORT_FEEDBACKSUBMIT', 2);

/**
 * Creates a new Word Sort activity.
 *
 * @param stdClass $data
 * @param mod_wordsort_mod_form|null $mform
 * @return int
 */
function wordsort_add_instance($data, $mform = null) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = time();

    return $DB->insert_record('wordsort', $data);
}

/**
 * Updates an existing Word Sort activity.
 *
 * @param stdClass $data
 * @param mod_wordsort_mod_form|null $mform
 * @return bool
 */

function wordsort_update_instance($data, $mform = null) {
    global $DB;

    $data->id = $data->instance;
    $data->timemodified = time();

    return $DB->update_record('wordsort', $data);
}

/**
 * Deletes a Word Sort activity.
 *
 * @param int $id
 * @return bool
 */
function wordsort_delete_instance($id) {
    global $DB;

    if (!$wordsort = $DB->get_record('wordsort', ['id' => $id])) {
        return false;
    }

    $DB->delete_records('wordsort', ['id' => $wordsort->id]);

    return true;
}