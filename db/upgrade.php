<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License.
//
// @package    mod_wordsort
// @copyright 2026 Reelika Pihl
// @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the Word Sort module.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_wordsort_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026072700) {

        // Define table wordsort_attempts.
        $table = new xmldb_table('wordsort_attempts');

        // Define fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('wordsortid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('attempt', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('score', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('totalwords', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('percentage', XMLDB_TYPE_NUMBER, '10,5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timeused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Define keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('wordsort_fk', XMLDB_KEY_FOREIGN, ['wordsortid'], 'wordsort', ['id']);

        // Define indexes.
        $table->add_index('userid_idx', XMLDB_INDEX_NOTUNIQUE, ['userid']);

        // Create table if it does not exist.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(true, 2026072700, 'wordsort');
    }

    return true;
}