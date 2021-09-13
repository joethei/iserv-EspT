<template>
  <div id="app">
    <ScheduleView v-bind:schedules="this.schedules" v-bind:settings="this.settings" @onClickEvent="(id) => click(id)"/>
  </div>
</template>

<script lang="ts">
import ScheduleView from './ScheduleView'

import Vue from 'vue';
import vToolTip from "v-tooltip";

Vue.use(vToolTip);

export default {
  name: 'ScheduleTimelineView',
  components: {
    ScheduleView,
  },
  props: {
    schedules: Array,
    settings: {
      start: Date,
      end: Date,
      scaleFactor: Number
    }
  },
  methods: {
    click: (id) => {
      this.$emit('onClickEvent', id);
    }
  },
}
</script>
