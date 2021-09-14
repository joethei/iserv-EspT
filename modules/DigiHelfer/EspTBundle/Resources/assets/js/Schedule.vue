<template>
  <ScheduleView v-bind:schedules="this.schedules" v-bind:settings="this.settings" @onClickEvent="(id) => onClick(id)"/>
</template>

<script>

import Confirm from 'IServ.Confirm';
import Locale from 'IServ.Locale';
import Routing from 'IServ.Routing';
import Message from 'IServ.Message';
import Modal from 'IServ.Modal';
import ScheduleView from "./ScheduleView";

import Vue from 'vue';
import vToolTip from "v-tooltip";

Vue.use(vToolTip);

export default {
  name: "Schedule",
  components: {
    ScheduleView
  },
  methods: {
    onClick: function(id) {
      console.log("clicked event #" + id);

      if ($("#invite").length) {
        const modal = Modal.createFromForm({
          'remote': Routing.generate('espt_invite', {id: id}),
          'id': 'espt_invite',
          'title': _('espt_timeslot_type_invite'),
          'onSuccess': function ($modal, data, options) {
            switch (data.status) {
              case 'success':
                modal.hide();
                Message.success(_('espt_invited'), 5000, false);
                break;
              case 'error':
                Message.error(data.message, false, false);
                break;
              default:
                Message.error(_('Unknown response!'), false, false);
                break;
            }
          },
        });
        modal.show();
      }else {
        Confirm.confirm({
          title: _('espt_confirm'),
          content: _('espt_confirm_text'),
          buttons: {
            confirmButton: {
              text: _('OK'),
              btnClass: 'btn-primary',
              action: function () {
                $.post(Routing.generate('espt_timeslots_reserve'), {id: id}, (data) => {
                  if(data.success !== undefined && data.success === true) {
                    Message.success(_('espt_registered'), 5000, false);
                  }
                });
              }
            },
            cancelButton: {
              text: _('Cancel'),
              action: function () {
              }
            }
          }
        });
      }
    },
    updateData() {
      $.getJSON({url: Routing.generate('espt_timeslots')}).done(data => {
          this.settings = data.settings;
          this.schedules = data.schedules;
      });
    }
  },
  created: function () {
    this.updateData();
  },
  data: () => {
    return {
      settings: {
        start: new Date(2021, 8, 20, 15, 30, 0),
        end: new Date(2021, 8, 20, 18, 0, 0),
        scaleFactor: 2
      },
      schedules: [
        {
          id: 0,
          title: "Max Mustermann",
          subtitle: "206",
          events: [
            {
              id: 1,
              name: "",
              start: new Date(2021, 8, 20, 15, 30, 0),
              end: new Date(2021, 8, 20, 15, 40, 0),
              color: "red",
            },
            {
              id: 2,
              name: "",
              start: new Date(2021, 8, 20, 15, 40, 0),
              end: new Date(2021, 8, 20, 16, 0, 0),
              color: "red",
            },
          ]
        },
        {
          id: 1,
          title: "Maria Mustermann & Test Lehrer",
          subtitle: "303",
          events: [
            {
              id: 1,
              name: "BELEGT",
              start: new Date(2021, 8, 20, 15, 30, 0),
              end: new Date(2021, 8, 20, 15, 40, 0),
              color: "red",
            },
            {
              id: 2,
              name: "BELEGT",
              start: new Date(2021, 8, 20, 15, 40, 0),
              end: new Date(2021, 8, 20, 16, 0, 0),
              color: "red",
            },
          ]
        },
      ]
    };
  }
}

</script>