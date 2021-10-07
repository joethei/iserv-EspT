"use strict";

import Schedule from './Schedule.vue';
import TimeslotTable from "./TimeslotTable";
import Vue from 'vue';
import Confirm from 'IServ.Confirm';


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
if($('#creation_form').length) {
    $('#creation_form').on('submit', function(e) {
        e.preventDefault();
        Confirm.confirm('confirm_creation', {
            title: _('espt_confirm'),
            content: _('espt_confirm_creation'),
            buttons: {
                confirmButton: {
                    text: _('OK'),
                    btnClass: 'btn-primary',
                    action: function () {
                        $('#creation_form').submit();}
                }
                },
                cancelButton: {
                    text: _('Cancel'),
                    btnClass: 'btn-default',
                    action: function () {}
                }
            });
    });
}
