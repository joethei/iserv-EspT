<template>
  <div>
    <div v-for="schedule in schedules">
      <ScheduleView v-bind:schedules="schedule.schedules" v-bind:settings="schedule.settings" @onClickEvent="(event) => onClick(event)"/>
    </div>
  </div>
</template>

<script>

import Confirm from 'IServ.Confirm';
import Routing from 'IServ.Routing';
import Message from 'IServ.Message';
import ScheduleView from "./ScheduleView";

import moment from "moment";

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
        //only show confirmation dialog if is allowed to book
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
        //split data into multiple copies, one for each day.
          data.schedules.forEach((schedule) => {
            schedule.events.forEach((event) => {
              let diff = moment.duration(moment(data.settings.start).diff(moment(event.start))).asDays();
              if(this.schedules[diff] === undefined) {
                this.schedules[diff] = {
                  settings: {},
                  schedules: {},
                };
                this.schedules[diff].schedules = {
                  id: schedule.id,
                  title: schedule.title,
                  subtitle: schedule.subtitle,
                  events: []
                };
              }
              this.schedules[diff].events.push(event);
            });
          });
          this.schedules.forEach((schedule) => {
            schedule.settings.scaleFactor = data.settings.scaleFactor;
            schedule.settings.start = moment.min(schedule.events.map(event => event.start));
            schedule.settings.end = moment.max(schedule.events.map(event => event.end));
          })
      });
    }
  },
  created: function () {
    this.updateData();
    document.addEventListener("updateData", () => {
      this.updateData();
    });
    //update data every two minutes
    this.timer = setInterval(() => {
      this.updateData();
    }, 2 * 1000 * 60)
  },
  beforeDestroy: function () {
    clearInterval(this.timer);
  },
  data: () => {
    return {
      timer: null,
      schedules: [],
    };
  }
}

</script>