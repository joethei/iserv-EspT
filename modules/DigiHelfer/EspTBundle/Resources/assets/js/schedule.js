import Vue from 'vue';
import Schedule from './vue/schedule';

IServ.register({
    init: () => {
        if (document.getElementById('schedule')) {
            new Vue({
                el: '#schedule',
                render: h => h(Schedule)
            });
        }
    }
});
