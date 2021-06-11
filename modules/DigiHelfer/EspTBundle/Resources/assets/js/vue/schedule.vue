<template>
  <div>
    <h2>Termine</h2>

    <vue3-scheduler-lite :schedule-data="data" :setting="settings"/>

  </div>
</template>

<script>
import VueSchedulerLite from 'vue3-scheduler-lite'

export default {
  name: "schedule",
  data: function () {
    return {
      data: {
        title: "Test",
        noBusinessDate: ['Sun', 'Sat'],
        businessHours: [
          {
            start: "08:00",
            end: "12:00"
          },
          {
            start: "14:00",
            end: "20:00"
          }
        ],
        schedule: [
          {
            start: "14:00",
            end: "15:00",
            text: "Hello World"
          }
        ]
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
    'vue-scheduler-lite': VueSchedulerLite
  }
}
</script>