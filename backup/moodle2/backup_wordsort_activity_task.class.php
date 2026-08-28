<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/wordsort/backup/moodle2/backup_wordsort_stepslib.php');

/**
 * Defines the backup task for the Word Sort activity.
 *
 * @package    mod_wordsort
 * @copyright  2026 Reelika Pihl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the backup task for mod_wordsort.
 */
class backup_wordsort_activity_task extends backup_activity_task {

    /**
     * Defines the backup settings.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Defines the backup steps.
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(
            new backup_wordsort_activity_structure_step(
                'wordsort_structure',
                'wordsort'
            )
        );
    }

    /**
     * Defines how activity links are encoded in the backup.
     *
     * @param string $content
     * @return string
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        $search = '/(' . $base . '\/mod\/wordsort\/view\.php\?id\=)([0-9]+)/';

        return preg_replace_callback($search, function($matches) {
            return '$@WORDSORT*$1$@$' . $matches[2];
        }, $content);
    }
}