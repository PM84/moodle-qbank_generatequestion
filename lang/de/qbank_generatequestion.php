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

$string['accept_and_import'] = 'Frage akzeptieren und importieren';
$string['generate_question'] = 'Frage generieren';
$string['nav_title_generatequestion'] = 'KI-Frage generieren';
$string['please_select'] = 'Bitte auswählen!';
$string['pluginname'] = 'Fragen generieren';
$string['privacy:metadata'] = 'Das Fragenbank-Plugin generiert Fragen mit LLMs und speichert keine persönlichen Daten';
$string['prompt'] = 'Prompt';
$string['xml_response'] = 'XML-Antwort';
$string['select_category'] = 'Wählen Sie die Kategorie, in die die Frage eingefügt werden soll';
$string['wait_for_result'] = 'Bitte warten, das kann ein paar Sekunden dauern!';

$string['mathequations'] = 'Enthält die Frage mathematische Gleichungen?';
$string['whichquestiontype'] = 'Welcher Fragetyp soll verwendet werden?';
$string['defaultprompt'] = 'Generiere mir eine Frage im moodle xml Format zum Import in moodle. Die Frage soll in '
    . 'Moodle importiert werden und daher xml header und alle weiteren benötigten Tags enthalten. Die Bewertungsfraktionen müssen von 1 bis 100 angesetzt werden. ';
$string['defaultpromt_postfix'] = 'Entferne die umhüllende Markcdown Codeblock Kennzeichnung vom Typ xml';
$string['defaultprompt_math'] = 'Gib mathematische Bezeichnungen im mathjax format aus. Verwende als Latex Seperator $$.';
$string['question_help'] = 'Beschreiben Sie möglichst präzise, welchen Inhalt die Frage haben soll:';
$string['question_title_help'] = 'Welchen Titel soll die Frage haben?';
$string['question_title_wrapperprompt'] = 'Der Titel der Frage soll "{$a}" lauten.';
$string['question_wrapperprompt'] = 'Das Thema der Frage soll "{$a}" sein.';
$string['qtype_wrapperprompt'] = 'Die Frage soll den Fragentyp "{$a}" verwenden.';
$string['qtype_multichoice_special_prompt'] = 'Gib mindestens 2 Antwortmöglichkeiten an.';
