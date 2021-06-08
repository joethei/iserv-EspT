import Schedule from './vue/schedule';
import VueSchedulerLite from './vue-scheduler-lite';
import Vue from 'vue';

new Vue({
    el: '#schedule',
    render: h => h(Schedule),
    components: {
        VueSchedulerLite
    }
});

$(function() {
   var $sc = jQuery("#schedule").timeSchedule({
       startTime: "07:00", // schedule start time(HH:ii)
       endTime: "21:00",   // schedule end time(HH:ii)
       widthTime: 60 * 10,  // cell timestamp example 10 minutes
       timeLineY: 60,       // height(px)
       verticalScrollbar: 20,   // scrollbar (px)
       timeLineBorder: 2,   // border(top and bottom)
       bundleMoveWidth: 6,  // width to move all schedules to the right of the clicked time line cell
       draggable: false,
       resizable: false,
       resizableLeft: true,
       rows : {
           '0': {
               title: 'Title Area1',
               schedule: [
                   {
                       start: '09:00',
                       end: '12:00',
                       text: 'Text Area',
                       data: {}
                   },
                   {
                       start: '11:00',
                       end: '14:00',
                       text: 'Text Area',
                       data: {}
                   }
               ]
           },
           '1': {
               title: 'Title Area2',
               schedule: [
                   {
                       start: '16:00',
                       end: '17:00',
                       text: 'Text Area',
                       data: {}
                   }
               ]
           }
       }
   });
});