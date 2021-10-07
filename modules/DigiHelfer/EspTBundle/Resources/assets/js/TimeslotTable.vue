<template>
  <div>
    <strong>{{ _('espt_your_events') }}</strong>

    <table class="table table-striped">
      <thead>
      <tr>
        <th>{{ _('Time') }}</th>
        <th>{{ isTeacher() ? _('Student') : _('Teacher') }}</th>
        <th>{{ _('Room') }}</th>
      </tr>
      </thead>
      <tbody>
        <tr v-for="timeslot in timeslots">
          <td>{{ timeslot['start'] }} - {{ timeslot['end'] }}</td>
          <td>{{ isTeacher() ? timeslot['user'] : timeslot['group'] }}</td>
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
    isTeacher() {
      return document.getElementById('teacher');
    },
    updateData() {
      $.getJSON(Routing.generate('espt_timeslots_user')).done(data => {
        this.timeslots = data;
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