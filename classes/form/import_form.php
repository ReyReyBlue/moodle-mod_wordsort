<?php
namespace mod_wordsort\form;

defined('MOODLE_INTERNAL') || die();


require_once($CFG->libdir . '/formslib.php');

class import_form extends \moodleform {

    public function definition() {

        $mform = $this->_form;

        $draftitemid = $this->_customdata['draftitemid'];

        $mform->addElement(
            'filemanager',
            'csvfile',
            get_string('csvfile', 'mod_wordsort'),
            null,
            [
                'subdirs' => 0,
                'maxfiles' => 1,
                'accepted_types' => ['.csv'],
                'maxbytes' => 0,
            ]
        );

        $mform->setDefault('csvfile', $draftitemid);

        $mform->addRule(
            'csvfile',
            null,
            'required'
        );

        $this->add_action_buttons(
            true,
            get_string('importwords', 'mod_wordsort')
        );
    }
}