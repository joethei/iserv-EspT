"use strict";

import App from 'IServ.App';

IServ.EspT = IServ.register(function() {

    function initialize() {
        console.log("debug mode enabled? " + App.isDebug());
    }

    // Public API
    return {
        init: initialize,
    };

}());

export default IServ.EspT;
