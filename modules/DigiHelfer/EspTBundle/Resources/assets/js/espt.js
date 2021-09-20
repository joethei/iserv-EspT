"use strict";

import Schedule from './Schedule.vue';
import TimeslotTable from "./TimeslotTable";
import Vue from 'vue';

new Vue({
    el: '#espt-table',
    render: h => h(TimeslotTable)
});

new Vue({
    el: '#espt-schedule',
    render: h => h(Schedule)
});