"use strict";

import Schedule from './Schedule.vue';
import TimeslotTable from "./TimeslotTable";
import Vue from 'vue';


if($('#espt-table').length) {
    new Vue({
        el: '#espt-table',
        render: h => h(TimeslotTable)
    });
}

if($('#espt-schedule').length) {
    new Vue({
        el: '#espt-schedule',
        render: h => h(Schedule)
    });
}
