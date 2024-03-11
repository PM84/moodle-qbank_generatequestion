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
 * qbank_generatequestion utils library.
 *
 * @copyright  ISB Bayern, 2024
 * @author     Dr. Peter Mayer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import { makeRequest } from 'local_ai_manager/make_request';
import Selectors from './selectors';

/**
 * Get the Chat result.
 */
export const getLLMresponse = () => {
    window.console.log("POS 1");
    var prompt = document.getElementById(Selectors.elements.taPrompt).value;

    // Shows the results box. This should happen before the real result is shown,
    // in order to inform the user, that we are working on it.
    // var prompt = document.getElementById(Selectors.elements.divresults).value();
    document.getElementById(Selectors.elements.divresults).classList.remove("hidden");
    document.getElementById(Selectors.elements.taResult).value = "Bitte warten, das kann ein paar Sekunden dauern!";

    const options = [];

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
    let result = await makeRequest(purpose, prompt, JSON.stringify(options));
    return result;
};
