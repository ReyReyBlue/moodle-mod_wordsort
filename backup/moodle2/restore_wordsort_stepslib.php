<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Defines the restore structure for the Word Sort activity.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the restore structure for mod_wordsort.
 */
class restore_wordsort_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines the restore paths.
     *
     * @return array
     */
    protected function define_structure() {
        $paths = [];

        $paths[] = new restore_path_element(
            'wordsort',
            '/activity/wordsort'
        );

        $paths[] = new restore_path_element(
            'wordsort_word',
            '/activity/wordsort/words/word'
        );

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Processes the Word Sort activity.
     *
     * @param array $data The activity data.
     * @return void
     */
    protected function process_wordsort($data) {
        global $DB;

        $data = (object)$data;

        $oldid = $data->id;

        // The restored activity belongs to the destination course.
        $data->course = $this->get_courseid();

        // Restore timestamps relative to the destination course.
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        // Create the new Word Sort activity.
        $newitemid = $DB->insert_record('wordsort', $data);

        // Register the new activity instance with Moodle.
        $this->apply_activity_instance($newitemid);

        // Map the old activity ID to the new activity ID.
        $this->set_mapping('wordsort', $oldid, $newitemid);
    }

    /**
     * Processes an individual Word Sort word.
     *
     * @param array $data The word data.
     * @return void
     */
    protected function process_wordsort_word($data) {
        global $DB;

        $data = (object)$data;

        // Connect the word to the newly restored Word Sort activity.
        $data->wordsortid = $this->get_new_parentid('wordsort');

        // The database generates a new ID.
        unset($data->id);

        $DB->insert_record('wordsort_words', $data);
    }
}