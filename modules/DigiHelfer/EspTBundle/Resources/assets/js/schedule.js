import Schedule from './vue/schedule';
import {vueSchedulerLite} from './vue-scheduler-lite';
import Vue from 'vue';

new Vue({
    el: '#schedule',
    render: h => h(Schedule),
    components: {
        'vue-scheduler-lite': vueSchedulerLite
    }
});