<template>
  <div class="ScheduleView">
    <h3 class="text-center">{{ formatDate(settings.start) }}</h3>
    <div class="timeline">
      <div class="hour" :style="timelineWidth" v-for="hour in hours" :key="hour">
        {{ hour }}
      </div>
    </div>
    <div class="schedule" v-for="schedule in schedules" :key="schedule.id">
      <div class="info text-center">
        <b>{{ schedule.title }}</b>
        <br>
        <span>{{ schedule.subtitle }}</span>
      </div>
      <div class="events">
        <div class="event break" v-bind:style="{width: firstMinute}">
        </div>
        <div class="event {{event.color}}" v-for="event in schedule.events" :key="event.id"
             v-bind:style="{width: (duration(event) / settings.scaleFactor) + '%'}"
             @click="click(event)"
             v-tooltip="format(event)">
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
    schedules: Array,
    settings: {
      start: Date,
      end: Date,
      scaleFactor: Number
    }
  },
  methods: {
    formatDate: function(date) {
      return moment(date).format('dddd D. MMMM');
    },
    click: function (event) {
      this.$emit('onClickEvent', event);
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