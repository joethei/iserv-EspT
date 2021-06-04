"use strict";

import App from 'IServ.App';

import {Calendar} from '@fullcalendar/core';
import fcInteraction from '@fullcalendar/interaction';
import fcDayGrid from '@fullcalendar/daygrid';
import fcTimeGrid from '@fullcalendar/timegrid';
import fcList from '@fullcalendar/list';

DigiHelfer.EspT = IServ.register(function() {

    function initialize() {

    }

    // Public API
    return {
        init: initialize,
    };

}());

export default DigiHelfer.EspT;
