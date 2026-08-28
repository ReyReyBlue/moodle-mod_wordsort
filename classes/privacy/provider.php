<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

namespace mod_wordsort\privacy;

defined('MOODLE_INTERNAL') || die();

use context;
use context_module;
use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use core_privacy\local\request\transform;
use core_privacy\local\request\helper;

/**
 * Privacy provider for mod_wordsort.
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider,
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Describe the personal data stored by Word Sort.
     *
     * @param collection $items
     * @return collection
     */
    public static function get_metadata(collection $items): collection {
        $items->add_database_table(
            'wordsort_attempts',
            [
                'userid' => 'privacy:metadata:wordsort_attempts:userid',
                'attempt' => 'privacy:metadata:wordsort_attempts:attempt',
                'score' => 'privacy:metadata:wordsort_attempts:score',
                'totalwords' => 'privacy:metadata:wordsort_attempts:totalwords',
                'percentage' => 'privacy:metadata:wordsort_attempts:percentage',
                'timeused' => 'privacy:metadata:wordsort_attempts:timeused',
                'timecreated' => 'privacy:metadata:wordsort_attempts:timecreated',
                'answers' => 'privacy:metadata:wordsort_attempts:answers',
                'status' => 'privacy:metadata:wordsort_attempts:status',
                'finalsubmission' => 'privacy:metadata:wordsort_attempts:finalsubmission',
            ],
            'privacy:metadata:wordsort_attempts'
        );

        return $items;
    }

    /**
     * Get the list of contexts containing user data.
     *
     * @param int $userid
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        global $DB;

        $contextlist = new contextlist();

        $sql = "SELECT ctx.id
                  FROM {context} ctx
                  JOIN {course_modules} cm
                    ON cm.id = ctx.instanceid
                  JOIN {wordsort} ws
                    ON ws.id = cm.instance
                  JOIN {wordsort_attempts} wa
                    ON wa.wordsortid = ws.id
                 WHERE ctx.contextlevel = :contextlevel
                   AND wa.userid = :userid";

        $params = [
            'contextlevel' => CONTEXT_MODULE,
            'userid' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export user data for the approved contexts.
     *
     * @param approved_contextlist $contextlist
     * @return void
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;

        if (!$contextlist->count()) {
            return;
        }

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }

            $cm = get_coursemodule_from_id(
                'wordsort',
                $context->instanceid,
                0,
                false,
                IGNORE_MISSING
            );

            if (!$cm) {
                continue;
            }

            $wordsort = $DB->get_record(
                'wordsort',
                ['id' => $cm->instance],
                '*',
                IGNORE_MISSING
            );

            if (!$wordsort) {
                continue;
            }

            $attempts = $DB->get_records(
                'wordsort_attempts',
                [
                    'wordsortid' => $wordsort->id,
                    'userid' => $userid,
                ],
                'timecreated ASC'
            );

            if (!$attempts) {
                continue;
            }

            $data = [];

            foreach ($attempts as $attempt) {
                $data[] = [
                    'attempt' => $attempt->attempt,
                    'score' => $attempt->score,
                    'totalwords' => $attempt->totalwords,
                    'percentage' => $attempt->percentage,
                    'timeused' => $attempt->timeused,
                    'timecreated' => transform::datetime($attempt->timecreated),
                    'answers' => $attempt->answers,
                    'status' => $attempt->status,
                    'finalsubmission' => $attempt->finalsubmission,
                ];
            }

            $subcontext = [
                get_string('privacy:attempts', 'wordsort'),
            ];

            writer::with_context($context)
                ->export_data($subcontext, (object)[
                    'attempts' => $data,
                ]);
        }
    }

    /**
     * Delete all user data in the specified contexts.
     *
     * @param approved_contextlist $contextlist
     * @return void
     */
    public static function delete_data_for_all_users_in_context(
        approved_contextlist $contextlist
    ): void {
        global $DB;

        foreach ($contextlist as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }

            $cm = get_coursemodule_from_id(
                'wordsort',
                $context->instanceid,
                0,
                false,
                IGNORE_MISSING
            );

            if (!$cm) {
                continue;
            }

            $DB->delete_records(
                'wordsort_attempts',
                ['wordsortid' => $cm->instance]
            );
        }
    }

    /**
     * Delete data for a specific user in the specified contexts.
     *
     * @param approved_contextlist $contextlist
     * @return void
     */
    public static function delete_data_for_user(
        approved_contextlist $contextlist
    ): void {
        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist as $context) {
            if ($context->contextlevel != CONTEXT_MODULE) {
                continue;
            }

            $cm = get_coursemodule_from_id(
                'wordsort',
                $context->instanceid,
                0,
                false,
                IGNORE_MISSING
            );

            if (!$cm) {
                continue;
            }

            $DB->delete_records(
                'wordsort_attempts',
                [
                    'wordsortid' => $cm->instance,
                    'userid' => $userid,
                ]
            );
        }
    }

    /**
     * Get the list of users with data in a context.
     *
     * @param userlist $userlist
     * @return void
     */
    public static function get_users_in_context(userlist $userlist): void {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $cm = get_coursemodule_from_id(
            'wordsort',
            $context->instanceid,
            0,
            false,
            IGNORE_MISSING
        );

        if (!$cm) {
            return;
        }

        $sql = "SELECT userid
                  FROM {wordsort_attempts}
                 WHERE wordsortid = :wordsortid";

        $params = [
            'wordsortid' => $cm->instance,
        ];

        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Delete data for specific users in a context.
     *
     * @param approved_userlist $userlist
     * @return void
     */
    public static function delete_data_for_users(
        approved_userlist $userlist
    ): void {
        global $DB;

        $context = $userlist->get_context();

        if ($context->contextlevel != CONTEXT_MODULE) {
            return;
        }

        $cm = get_coursemodule_from_id(
            'wordsort',
            $context->instanceid,
            0,
            false,
            IGNORE_MISSING
        );

        if (!$cm) {
            return;
        }

        $userids = $userlist->get_userids();

        if (!$userids) {
            return;
        }

        [$insql, $params] = $DB->get_in_or_equal(
            $userids,
            SQL_PARAMS_NAMED
        );

        $params['wordsortid'] = $cm->instance;

        $DB->delete_records_select(
            'wordsort_attempts',
            "wordsortid = :wordsortid AND userid {$insql}",
            $params
        );
    }
}