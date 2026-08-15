<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

namespace mod_wordsort;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/wordsort/lib.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * External functions for Word Sort.
 */
class external extends external_api {

    /**
     * Parameters for start_attempt().
     *
     * @return external_function_parameters
     */
    public static function start_attempt_parameters() {
        return new external_function_parameters([
            'wordsortid' => new external_value(PARAM_INT, 'Word Sort activity ID'),
        ]);
    }

        /**
     * Starts a new Word Sort attempt.
     *
     * @param int $wordsortid
     * @return array
     */
        public static function start_attempt(int $wordsortid) {

            global $DB, $USER;

            $params = self::validate_parameters(
                self::start_attempt_parameters(),
                [
                    'wordsortid' => $wordsortid,
                ]
            );

            // Mark previous in-progress attempts as abandoned.
            $DB->set_field(
                'wordsort_attempts',
                'status',
                'abandoned',
                [
                    'wordsortid' => $params['wordsortid'],
                    'userid' => $USER->id,
                    'status' => 'inprogress',
                ]
            );

            // Count previous attempts for this student.
            $attemptcount = $DB->count_records(
                'wordsort_attempts',
                [
                    'wordsortid' => $params['wordsortid'],
                    'userid' => $USER->id,
                ]
            );

            $record = new \stdClass();
            $record->wordsortid = $params['wordsortid'];
            $record->userid = $USER->id;
            $record->attempt = $attemptcount + 1;
            $record->score = 0;
            $record->totalwords = 0;
            $record->percentage = 0;
            $record->timeused = 0;
            $record->timecreated = time();
            $record->answers = null;
            $record->status = 'inprogress';
            $record->finalsubmission = 0;

            $attemptid = $DB->insert_record('wordsort_attempts', $record);

            return [
                'attemptid' => $attemptid,
                'attemptnumber' => $record->attempt,
            ];
        }

            /**
         * Return values for start_attempt().
         *
         * @return external_single_structure
         */
        public static function start_attempt_returns() {

            return new external_single_structure([
                'attemptid' => new external_value(PARAM_INT, 'Database ID'),
                'attemptnumber' => new external_value(PARAM_INT, 'Attempt number'),
            ]);
        }

        /**
         * Parameters for save_attempt().
         *
         * @return external_function_parameters
         */
            public static function save_attempt_parameters() {
                return new external_function_parameters([
                    'attemptid' => new external_value(PARAM_INT, 'Attempt ID'),
                    'wordsortid' => new external_value(PARAM_INT, 'Word Sort activity ID'),
                    'score' => new external_value(PARAM_INT, 'Student score'),
                    'totalwords' => new external_value(PARAM_INT, 'Total number of words'),
                    'percentage' => new external_value(PARAM_FLOAT, 'Percentage score'),
                    'timeused' => new external_value(PARAM_INT, 'Time used in seconds'),
                    'answers' => new external_value(PARAM_RAW, 'Attempt answers as JSON'),
                    'finalsubmission' => new external_value(PARAM_BOOL, 'Whether the student has finished the activity'),
                ]);
            }

            /**
             * Save a Word Sort attempt.
             * 
             * @param int $attemptid
             * @param int $wordsortid
             * @param int $score
             * @param int $totalwords
             * @param float $percentage
             * @param int $timeused
             * @param string $answers
             * @param bool $finalsubmission
             * @return array
             */

        public static function save_attempt(
            $attemptid,
            $wordsortid,
            $score,
            $totalwords,
            $percentage,
            $timeused,
            $answers,
            $finalsubmission
        ) {

            global $DB, $USER;
            error_log('WORDSORT: save_attempt START');

            $params = self::validate_parameters(
                self::save_attempt_parameters(),
                [
                    'attemptid' => $attemptid,
                    'wordsortid' => $wordsortid,
                    'score' => $score,
                    'totalwords' => $totalwords,
                    'percentage' => $percentage,
                    'timeused' => $timeused,
                    'answers' => $answers,
                    'finalsubmission' => $finalsubmission,
                ]
            );

            $record = $DB->get_record(
                'wordsort_attempts',
                ['id' => $params['attemptid']],
                '*',
                MUST_EXIST
            );

            $record->score = $params['score'];
            $record->totalwords = $params['totalwords'];
            $record->percentage = $params['percentage'];
            $record->timeused = $params['timeused'];
            $record->answers = $params['answers'];
            $record->status = 'submitted';
            $record->finalsubmission = $params['finalsubmission'] ? 1 : 0;

            $DB->update_record('wordsort_attempts', $record);

            error_log('WORDSORT: save_attempt BEFORE RETURN');
            return [
                'success' => true,
            ];
        }
    /**
     * Return structure for save_attempt().
     *
     * @return external_single_structure
     */
    public static function save_attempt_returns() {
        return new external_single_structure([
            'success' => new external_value(
                PARAM_BOOL,
                'Whether the attempt was saved successfully'
            ),
        ]);
    }

}