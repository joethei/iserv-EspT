<template>
  <div>
    <h2>Termine</h2>

    <vue-scheduler-lite :schedule-data="data" :setting="settings"/>

  </div>
</template>

<script>
import VueSchedulerLite from './../vue-scheduler-lite';

export default {
  name: "schedule",
  data: function() {
    return {
      data: {
        title: "Test",

      },
      settings: {
        startDate: '2020/04/20',
        endDate: '2020/04/26',
        weekdayText: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        unit: 60,
        borderW: 1,
        dateDivH: 25,
        timeDivH: 25,
        unitDivW: 25,
        titleDivW: 200,
        rowH: 135,
      }
    }
  },
  components: {
    VueSchedulerLite
  }
}
</script>