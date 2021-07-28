"use strict";
import Schedule from "./Schedule.vue";

IServ.EspT = IServ.register(function ($) {
    'use strict';

    return {
        init: function () {
            new Vue({
                el: '#espt-schedule',
                template: '<Schedule/>',
                components: {Schedule},
                data: {
                },
            })
        }
    };
}(jQuery));


export default IServ.EspT;
