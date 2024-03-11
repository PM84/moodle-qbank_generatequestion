<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'qbank_generatequestion', language 'en'
 *
 * @package    qbank_generatequestion
 * @copyright  ISB Bayern, 2024
 * @author     Dr. Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['accept_and_import'] = 'Accept and Import Question';
$string['generate_question'] = 'Generate Question';
$string['nav_title_generatequestion'] = 'AI question generation';
$string['please_select'] = 'Please select!';
$string['pluginname'] = 'Generate questions';
$string['privacy:metadata'] = 'The generate questions question bank plugin generates questions with LLMs and does not store any personal data.';
$string['prompt'] = 'Prompt';
$string['xml_response'] = 'XML Response';
$string['select_category'] = 'Select the category in which the question is to be inserted';
$string['wait_for_result'] = 'Please wait, this may take a few seconds!';

$string['mathequations'] = 'Does the question contain mathematical equations?';
$string['whichquestiontype'] = 'Which question type should be used?';
$string['defaultprompt'] = 'Generate a question in moodle xml format for import into moodle. The question should be imported '
    . 'into moodle with the output and contain xml headers etc. Remove the wrapping markdown codeblock tag of type xml.';
$string['question_help'] = 'Formulate the question:';
$string['question_title_help'] = 'What title should the question have?';
$string['defaultprompt_math'] = 'Output math names in mathjax format. Use $$ as the latex separator.';
$string['question_title_wrapperprompt'] = 'The title of the question shoult be "{$a}".';
$string['qtype_wrapperprompt'] = 'The question should use the question type "{$a}"';
$string['defaultpromt_postfix'] = 'Entferne die Markcdown Codeblock Kennzeichnung vom '
. 'Typ xml. Die Bewertungsfraktionen müssen von 1 bis 100 angesetzt werden.';
$string['question_wrapperprompt'] = 'The subject of the question should be "{$a}"';
$string['qtype_multichoice_special_prompt'] = 'Gib mindestens 2 Antwortmöglichkeiten an.';
$string['qtype_multianswer_special_prompt'] = 'Die zu Lücken sind im Prompt mit ** umschlossen. Kennzeichne die Lücken im Cloze Format. Die Separatoren sollen anschließend nicht in der Angabe ausgegeben werden.';
