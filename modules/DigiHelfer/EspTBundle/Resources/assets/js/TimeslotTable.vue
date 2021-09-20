<template>

  <p>{{ _('espt_your_events') }}</p>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>{{ _('Time') }}</th>
        <th>{{ _('Group') }}</th>
        <th>{{ _('Room') }}</th>
      </tr>
    </thead>
    <tr v-for="timeslot in timeslots">
      <td>{{ __('espt_start_end_time', timeslot['start'], timeslot['end']) }}</td>
      <td>{{ timeslot['group'] }}</td>
      <td>{{ timeslot['room'] }}</td>
    </tr>
  </table>

</template>

<script>


import DataTable from 'IServ.DataTable';
import Routing from 'IServ.Routing';

export default {
  name: "TimeslotTable",
  methods: {
    updateData() {
      $.getJSON(Routing.generate('espt_timeslots_user')).done(data => {
        this.timeslots = data
        DataTable.create($('.table'));
      });
    },
  },
  created: function () {
    this.updateData();
    //update data every minute
    this.timer = setInterval(() => {
      this.updateData();
    }, 1000 * 60);

    document.addEventListener("updateData", () => {
      this.updateData();
    });
  },
  beforeDestroy: function () {
    clearInterval(this.timer);
  },
  data: () => {
    return {
      timer: null,
      timeslots: {}
    }
  }
}



</script>