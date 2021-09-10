<template>
  <div class="ScheduleView">
    <div class="timeline">
      <div class="hour" :style="timelineWidth" v-for="hour in hours" :key="hour">
        {{ hour }}
      </div>
    </div>
    <div class="schedule" v-for="schedule in schedules" :key="schedule.id">
      <div class="info">
        <span>{{ schedule.title }}</span>
        <br>
        <span>{{ schedule.subtitle }}</span>
      </div>
      <div class="events">
        <div class="event" v-bind:style="{width: firstMinute, backgroundColor: 'lightgray'}">
        </div>
        <div class="event" v-for="event in schedule.events" :key="event.id"
             v-bind:style="{width: (duration(event) / settings.scaleFactor) + '%', backgroundColor: event.color}"
             @click="click(event)"
             v-tooltip="format(event)"
        >
          <p>{{ event.name }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import moment from 'moment';

export default {
  name: 'ScheduleView',
  props: {
    schedules: [],
    settings: {
      start: Date,
      end: Date,
      scaleFactor: Number
    }
  },
  methods: {
    click: function (event) {
      this.$emit('onClickEvent', event.id);
    },
    duration: function (event) {
      let diff = moment(event.end).diff(moment(event.start));
      return moment.duration(diff).asMinutes();
    },
    format: function (event) {
      return moment(event.start).format('HH:mm') + " - " + moment(event.end).format('HH:mm');
    }
  },
  computed: {
    hours: function () {
      let start = moment(this.settings.start).hour();
      let end = moment(this.settings.end).hour();
      if(moment(this.settings.end).minute() === 0) {
        end--;
      }
      let result = [];
      for (let i = start; i <= end; i++) {
        result.push(i + ":00")
      }
      return result;
    },
    firstMinute: function () {
      let minute = moment(this.settings.start).minute();
      let duration = moment.duration(minute, "minutes").asMinutes();
      let width = duration / this.settings.scaleFactor;
      return width + "%";
    },
    timelineWidth: function () {
      return {
        '--timeline-width': (60 / this.settings.scaleFactor) + '%'
      }
    }
  }

}
</script>

<style scoped>
.schedule {
  overflow: hidden;
  position: relative;
  clear: both;
}

.schedule .info {
  background-color: lightgray;
  float: left;
  width: 10%;
  height: 50px;
  border-bottom: 2px solid black;
  border-top: 2px solid black;
  border-left: 2px solid black;
}

.schedule .events {
  display: flex;
  padding-bottom: 30px;
}

.schedule .events .event {
  text-align: center;
  min-height: 50px;
  box-sizing: border-box;
  border: 2px solid black;
}

.timeline {
  padding-left: 10%;
  padding-bottom: 60px;
  position: relative;
  clear: both;
}

.timeline .hour {
  width: var(--timeline-width);
  box-sizing: border-box;
  float: left;
  color: #aaa;
  text-indent: 5px;
  padding-top: 5px;
  padding-bottom: 5px;
  border-top: 1px solid lightgray;
  border-right: 2px solid black;
  border-bottom: 1px solid lightgray;
}

.tooltip {
  display: block !important;
  z-index: 10000;
}

.tooltip .tooltip-inner {
  background: black;
  color: white;
  border-radius: 16px;
  padding: 5px 10px 4px;
}

.tooltip .tooltip-arrow {
  width: 0;
  height: 0;
  border-style: solid;
  position: absolute;
  margin: 5px;
  border-color: black;
  z-index: 1;
}

.tooltip[x-placement^="top"] {
  margin-bottom: 5px;
}

.tooltip[x-placement^="top"] .tooltip-arrow {
  border-width: 5px 5px 0 5px;
  border-left-color: transparent !important;
  border-right-color: transparent !important;
  border-bottom-color: transparent !important;
  bottom: -5px;
  left: calc(50% - 5px);
  margin-top: 0;
  margin-bottom: 0;
}

.tooltip[x-placement^="bottom"] {
  margin-top: 5px;
}

.tooltip[x-placement^="bottom"] .tooltip-arrow {
  border-width: 0 5px 5px 5px;
  border-left-color: transparent !important;
  border-right-color: transparent !important;
  border-top-color: transparent !important;
  top: -5px;
  left: calc(50% - 5px);
  margin-top: 0;
  margin-bottom: 0;
}

.tooltip[x-placement^="right"] {
  margin-left: 5px;
}

.tooltip[x-placement^="right"] .tooltip-arrow {
  border-width: 5px 5px 5px 0;
  border-left-color: transparent !important;
  border-top-color: transparent !important;
  border-bottom-color: transparent !important;
  left: -5px;
  top: calc(50% - 5px);
  margin-left: 0;
  margin-right: 0;
}

.tooltip[x-placement^="left"] {
  margin-right: 5px;
}

.tooltip[x-placement^="left"] .tooltip-arrow {
  border-width: 5px 0 5px 5px;
  border-top-color: transparent !important;
  border-right-color: transparent !important;
  border-bottom-color: transparent !important;
  right: -5px;
  top: calc(50% - 5px);
  margin-left: 0;
  margin-right: 0;
}

.tooltip.popover .popover-inner {
  background: #f9f9f9;
  color: black;
  padding: 24px;
  border-radius: 5px;
  box-shadow: 0 5px 30px rgba(0, 0, 0, .1);
}

.tooltip.popover .popover-arrow {
  border-color: #f9f9f9;
}

.tooltip[aria-hidden='true'] {
  visibility: hidden;
  opacity: 0;
  transition: opacity .15s, visibility .15s;
}

.tooltip[aria-hidden='false'] {
  visibility: visible;
  opacity: 1;
  transition: opacity .15s;
}

</style>
