<template>
  <ScheduleView v-bind:schedules="this.schedules" v-bind:settings="this.settings" @onClickEvent="(event) => onClick(event)"/>
</template>

<script>

import Confirm from 'IServ.Confirm';
import Routing from 'IServ.Routing';
import Message from 'IServ.Message';
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
    onClick: (event) => {
      //only open invite dialog when div is specified
      if ($("#teacher").length) {
        let inviteModal = IServ.Modal.createFromForm({
          remote: IServ.Routing.generate('espt_invite', {id: event.id}),
          id: 'espt_invite',
          title: _('espt_timeslot_type_invite'),
          onLoad: function ($modal, options) {
            console.log("loading");
          },
          onSuccess: function ($modal, data) {
            console.log("success" + data);
          },
          onError: function($modal, data) {
            console.log("error + " + data);
          }
        });
        //inviteModal.show();
        window.location.href = Routing.generate('espt_invite', {'id': event.id});
      }else {
        //only confirmation if is allowed to book
        if(event.color !== 'green' && event.color !== 'yellow') {
          return;
        }

        Confirm.confirm({
          title: _('espt_confirm'),
          content: event.color === 'green' ? _('espt_confirm_text') : _('espt_confirm_cancel_text'),
          buttons: {
            confirmButton: {
              text: _('OK'),
              btnClass: 'btn-primary',
              action: () => {
                $.post(Routing.generate('espt_timeslots_reserve'), {id: event.id}, (data) => {
                  if(data.success !== undefined && data.success === true) {
                    Message.success(_('espt_registered'), 5000, false);

                    let event = new Event("updateData", {bubbles: true});
                    document.dispatchEvent(event);
                  }else {
                    Message.error(_('Error') + " " + data.error, false);
                    this.updateData();
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
      $.getJSON(Routing.generate('espt_timeslots')).done(data => {
          this.settings = data.settings;
          this.schedules = data.schedules;
      });
    }
  },
  created: function () {
    this.updateData();
    document.addEventListener("updateData", () => {
      this.updateData();
    });
    //update data every minute
    this.timer = setInterval(() => {
      this.updateData();
    }, 1000 * 60)
  },
  beforeDestroy: function () {
    clearInterval(this.timer);
  },
  data: () => {
    return {
      timer: null,
      settings: {
        start: new Date(2021, 8, 20, 15, 30, 0),
        end: new Date(2021, 8, 20, 18, 0, 0),
        scaleFactor: 2
      },
      schedules: [
        {},
      ]
    };
  }
}

</script>