<template>
  <ScheduleView v-bind:schedules="this.schedules" v-bind:settings="this.settings" @onClickEvent="(id) => onClick(id)"/>
</template>

<script>

import Confirm from 'IServ.Confirm';
import Locale from 'IServ.Locale';
import Routing from 'IServ.Routing';
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
      //const modal = Modal.createFromPage({'remote': path('espt_timeslot_edit')});
      //modal.show();
      Confirm.confirm({
        title: _('espt_confirm'),
        content: _('espt_confirm_text'),
        buttons: {
          confirmButton: {
            text: _('OK'),
            btnClass: 'btn-primary',
            action: function () {
            }
          },
          cancelButton: {
            text: _('Cancel'),
            action: function () {
            }
          }
        }
      });
    },
    updateData() {
      $.ajax({url: Routing.generate('espt_timeslots'), success: (result) => {
          this.$set(this.settings, result.settings, true);
          this.$set(this.schedules, result.schedules, true);
        }});
    }
  },
  created: function () {
    this.updateData();
  },
  updated: function() {
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
            {
              id: 3,
              name: "",
              start: new Date(2021, 8, 20, 16, 0, 0),
              end: new Date(2021, 8, 20, 16, 20, 0),
              color: "gray",
            },
            {
              id: 4,
              name: "",
              start: new Date(2021, 8, 20, 16, 20, 0),
              end: new Date(2021, 8, 20, 16, 40, 0),
              color: "gray",
            },
            {
              id: 5,
              name: "",
              start: new Date(2021, 8, 20, 16, 40, 0),
              end: new Date(2021, 8, 20, 17, 0, 0),
              color: "gray",
            },
            {
              id: 6,
              name: "",
              start: new Date(2021, 8, 20, 17, 0, 0),
              end: new Date(2021, 8, 20, 17, 10, 0),
              color: "green",
            },
            {
              id: 7,
              name: "",
              start: new Date(2021, 8, 20, 17, 10, 0),
              end: new Date(2021, 8, 20, 17, 30, 0),
              color: "red",
            },
            {
              id: 8,
              name: "",
              start: new Date(2021, 8, 20, 17, 30, 0),
              end: new Date(2021, 8, 20, 17, 40, 0),
              color: "green",
            },
            {
              id: 9,
              name: "",
              start: new Date(2021, 8, 20, 17, 40, 0),
              end: new Date(2021, 8, 20, 18, 0, 0),
              color: "green",
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
            {
              id: 3,
              name: "PAUSE",
              start: new Date(2021, 8, 20, 16, 0, 0),
              end: new Date(2021, 8, 20, 16, 20, 0),
              color: "gray",
            },
            {
              id: 4,
              name: "PAUSE",
              start: new Date(2021, 8, 20, 16, 20, 0),
              end: new Date(2021, 8, 20, 16, 40, 0),
              color: "gray",
            },
            {
              id: 5,
              name: "PAUSE",
              start: new Date(2021, 8, 20, 16, 40, 0),
              end: new Date(2021, 8, 20, 17, 0, 0),
              color: "gray",
            },
            {
              id: 6,
              name: "RESERVIERT",
              start: new Date(2021, 8, 20, 17, 0, 0),
              end: new Date(2021, 8, 20, 17, 10, 0),
              color: "green",
            },
            {
              id: 7,
              name: "BELEGT",
              start: new Date(2021, 8, 20, 17, 10, 0),
              end: new Date(2021, 8, 20, 17, 30, 0),
              color: "red",
            },
            {
              id: 8,
              name: "FREI",
              start: new Date(2021, 8, 20, 17, 30, 0),
              end: new Date(2021, 8, 20, 17, 40, 0),
              color: "lightgreen",
            },
            {
              id: 9,
              name: "FREI",
              start: new Date(2021, 8, 20, 17, 40, 0),
              end: new Date(2021, 8, 20, 18, 0, 0),
              color: "lightgreen",
            },

          ]
        },
      ]
    };
  }
}

</script>