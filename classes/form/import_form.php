<?php
namespace mod_wordsort\form;

defined('MOODLE_INTERNAL') || die();


require_once($CFG->libdir . '/formslib.php');

class import_form extends \moodleform {

    public function definition() {

        $mform = $this->_form;

        $mform->addElement(
            'filepicker',
            'csvfile',
            get_string('csvfile', 'mod_wordsort'),
            null,
            [
                'accepted_types' => ['.csv'],
                'maxbytes' => 0,
            ]
        );

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