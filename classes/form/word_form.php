<?php

namespace mod_wordsort\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class word_form extends \moodleform {

    public function definition() {
        $mform = $this->_form;
        $customdata = $this->_customdata;

        // Course module id.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $customdata['cmid']);

        // Word.
        $mform->addElement(
            'text',
            'word',
            get_string('word', 'wordsort')
        );
        $mform->setType('word', PARAM_TEXT);
        $mform->addRule('word', null, 'required', null, 'client');

        // Category.
        $categoryoptions = [];

        $categoryoptions[] = $mform->createElement(
            'radio',
            'correctside',
            '',
            $customdata['categoryleft'],
            0
        );

        $categoryoptions[] = $mform->createElement(
            'radio',
            'correctside',
            '',
            $customdata['categoryright'],
            1
        );

        $mform->addGroup(
            $categoryoptions,
            'categorygroup',
            get_string('category', 'wordsort'),
            ['<br>'],
            false
        );

        $mform->setDefault('correctside', 0);

        $this->add_action_buttons();
    }
}