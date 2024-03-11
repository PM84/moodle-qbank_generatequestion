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
 * qbank_generatequestion generation module.
 *
 * @copyright  ISB Bayern, 2024
 * @author     Dr. Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Selectors from './selectors';
import {makeRequest} from 'local_ai_manager/make_request';
import {getString} from 'core/str';

export const init = () => {

    // Get submit button.
    const submitbtn = document.getElementById('qbank_qgen_start');

    if (!submitbtn) {
        return;
    }

    submitbtn.addEventListener("click", generateQuestion);

    var guidequstions = document.getElementsByClassName("qbank_generatequestion_guidequestions");

    for (var i = 0; i < guidequstions.length; i++) {
        guidequstions[i].addEventListener('change', generatePrompt, false);
    }
};

/**
 * Create new page.
 */
const generateQuestion = async () => {
    // Shows the results box. This should happen before the real result is shown,
    // in order to inform the user, that we are working on it.
    // var prompt = document.getElementById(Selectors.elements.divresults).value();
    document.getElementById(Selectors.elements.divresults).classList.remove("hidden");
    var waitString = await getString('wait_for_result', 'qbank_generatequestion').then((string) => { return string; }).catch();
    document.getElementById(Selectors.elements.taResult).value = waitString;

    var prompt = document.getElementById(Selectors.elements.taPrompt).value;

    let options = [];

    retrieveResult('chat', prompt, options).then(requestresult => {

        // Early exit if an error occured. Print out the error message to the output textarea.
        if (requestresult.string == 'error') {
            document.getElementById(Selectors.elements.taResult).value = requestresult.result;
            return;
        }

        document.getElementById(Selectors.elements.taResult).value = requestresult.result;
    });
};

/**
 * Get the async answer from the LLM.
 *
 * @param {string} purpose
 * @param {string} prompt
 * @param {array} options
 * @returns {string}
 */
const retrieveResult = async (purpose, prompt, options = []) => {
    const defaultprompt = await getString('defaultprompt', 'qbank_generatequestion').then((defaultprompt) => {
        return defaultprompt;
    });

    const defaultpromtPostfix = await getString('defaultpromt_postfix', 'qbank_generatequestion').then((defaultpromtPostfix) => {
        return defaultpromtPostfix;
    });

    prompt = defaultprompt + " " + prompt + " " + defaultpromtPostfix;

    window.console.log(prompt );
    let result = await makeRequest(purpose, prompt, JSON.stringify(options));
    return result;
};

const generatePrompt = async () => {

    const qtypehortname = document.getElementById(Selectors.elements.qtypeshortname).value;

    var  qtypeWrapperPrompt = await getString('qtype_wrapperprompt', 'qbank_generatequestion', 'qtype_' + qtypehortname).then((string) => { return string; }).catch();
    qtypeWrapperPrompt = qtypeWrapperPrompt + " " + await getString('qtype_' + qtypehortname + '_special_prompt', 'qbank_generatequestion', 'qtype_' + qtypehortname).then((string) => { return string; }).catch();

    const mathequation = document.getElementById(Selectors.elements.mathequation).value;
    var matheqdefaultprompt = "";
    if (mathequation == 1) {
        matheqdefaultprompt = await getString('defaultprompt_math', 'qbank_generatequestion').then((string) => { return string; }).catch();
    }

    const qTitle = document.getElementById(Selectors.elements.qtitle).value;
    const question = document.getElementById(Selectors.elements.question).value;
    const questionPrompt = await getString('question_wrapperprompt', 'qbank_generatequestion', question).then((string) => { return string; }).catch();

    // const defaultprompt = await getString('defaultprompt', 'qbank_generatequestion').then((string) => {return string;}).catch();
    const qTitleString = await getString('question_title_wrapperprompt', 'qbank_generatequestion', qTitle).then((string) => { return string; }).catch();

    var prompt = qtypeWrapperPrompt + " " + matheqdefaultprompt + " " + qTitleString + " " + questionPrompt;
    document.getElementById(Selectors.elements.taPrompt).value = prompt;
    // window.console.log(qtypeshortname);
    // window.console.log(matheqdefaultprompt);
    // window.console.log(qtitle);
    // window.console.log(question);
    // window.console.log(defaultprompt);
};
