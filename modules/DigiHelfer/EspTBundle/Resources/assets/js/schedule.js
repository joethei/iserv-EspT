import Schedule from './vue/schedule';
import './vue-scheduler-lite'
import Vue from 'vue';

new Vue({
    el: '#schedule',
    render: h => h(Schedule),
    components: {
        'scheduler': vueSchedulerLite
    }
});