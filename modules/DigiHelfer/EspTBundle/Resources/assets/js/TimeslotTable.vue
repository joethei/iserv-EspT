<template>
  <div>
    <p>{{ _('espt_your_events') }}</p>

    <table class="table table-striped">
      <thead>
      <tr>
        <th>{{ _('Time') }}</th>
        <th>{{ _('Teacher') }}</th>
        <th>{{ _('Room') }}</th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="timeslot in timeslots">
          <td>{{ timeslot['start'] }} - {{ timeslot['end'] }}</td>
          <td>{{ timeslot['group'] }}</td>
          <td>{{ timeslot['room'] }}</td>
        </tr>
      </tbody>
    </table>
  </div>

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

    document.addEventListener("updateData", () => {
      this.updateData();
    });
  },
  data: () => {
    return {
      timeslots: {}
    }
  }
}



</script>