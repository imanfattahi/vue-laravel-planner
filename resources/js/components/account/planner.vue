<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 id="title" class="float-left">Calendar Planner</h5>
            <div class="btn-group float-right">
              <button type="button" class="btn btn-success" @click.prevent="showModal()">New Plan</button>
            </div>
          </div>
          <div class="card-body">
            <!-- vuecal componnet -->
            <vue-cal :events="plans" style="height: 600px" :on-event-click="showDetails"></vue-cal>
          </div>
        </div>
      </div>
    </div>

    <!-- Set new plan modal -->
    <modal v-if="planModal" :large="true" :hcolor="'rgb(255, 205, 46)'">
      <h5 slot="title">{{ this.edit === true ? 'Edit Plan' : 'Set New Plan' }}</h5>
      <div slot="body">
        <div class="form-group">
          <label for="title">Select Date:</label>
          <date-picker
            min="window.moment().format('YYYY-MM-DD HH:mm')"
            v-model="plan.start"
            locale="en,fa"
            format="YYYY-MM-DD HH:mm"
            type="datetime"
            :jumpMinute="60"
            :roundMinute="true"
          />
        </div>

        <div class="form-group">
          <label for="title">Title:</label>
          <input
            v-model="plan.title"
            type="text"
            class="form-control"
            id="title"
            placeholder="Enter a short title"
          />
        </div>

        <div class="form-group">
          <label for="content">Description:</label>
          <textarea v-model="plan.content" class="form-control" id="content"></textarea>
        </div>

        <div class="form-group">
          <label for="content">Select Color:</label>
          <color-selector v-model="plan.class"></color-selector>
        </div>
      </div>
      <div class="col-12" slot="footer">
        <button type="button" class="btn btn-success float-right" @click="setPlan()">Set Plan</button>
        <button
          v-if="edit"
          type="button"
          class="btn btn-danger float-right"
          @click="deletePlan()"
          style="margin-right: 25px"
        >Delete</button>
        <button @click="planModal = false" type="button" class="btn btn-info">Cancel</button>
      </div>
    </modal>
  </div>
</template>

<script>
import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import datePicker from "vue-persian-datetime-picker";
import modal from "../_custom/modal";
import colorSelector from "../_custom/color-selector";

export default {
  components: { VueCal, datePicker, modal, colorSelector },
  beforeMount() {
    this.getPlans();
  },
  data: function() {
    return {
      index: "",
      edit: false,
      plan: {
        start: null,
        end: null,
        title: "",
        content: "",
        class: ""
      },
      planModal: false,
      plans: []
    };
  },
  methods: {
    // get all plans
    getPlans: function() {
      var self = this;
      self.$http
        .post("/account/planner/all")
        .then(function(response) {
          if ((response.status = 200)) {
            self.plans = JSON.parse(response.data);
          } else {
            throw new Error("Somthing went wrong!");
          }
        })
        .catch(function(e) {
          console.error(e);
          self.$toasted.show("Somthing went wrong!", {
            duration: 1500,
            type: "info"
          });
        });
    },
    // save plan changes
    setPlan: function() {
      var self = this;
      self.plan.end = window
        .moment(self.plan.start, "YYYY-MM-DD HH:mm")
        .add("hours", 1)
        .format("YYYY-MM-DD HH:mm");
      if (self.validate()) {
        if (!self.edit) {
          self.plans.push(self.plan);
        }
        self.$http
          .post("/account/planner/set", { plans: self.plans })
          .then(function(response) {
            if ((response.status = 200)) {
              self.planModal = false;
              self.$toasted.show("Plan was set successfuly!", {
                duration: 1500,
                type: "success"
              });
            } else {
              throw new Error("Somthing went wrong!");
            }
          })
          .catch(function(e) {
            console.error(e);
            self.$toasted.show("Somthing went wrong!", {
              duration: 1500,
              type: "info"
            });
          });
      } else {
        alert("Validation error!");
      }
    },
    // Delete selected plan
    deletePlan: function() {
      var self = this;
      self.plans.splice(self.index, 1);
      self.planModal = false;
      self.setPlan(false);
    },
    // show and select clicked plan
    showDetails: function(plan, e) {
      var self = this;
      self.edit = true;
      self.planModal = true;
      var PLAN = self.plans.filter(function(x) {
        return (
          x.start == plan.start && x.end == plan.end && x.title == plan.title
        );
      });
      if (PLAN.length) {
        PLAN = PLAN[0];
        self.index = self.plans.indexOf(PLAN);
        self.plan = self.plans[self.index];
      }
      // Show modal - it used for setting and editing plans
    },
    showModal: function() {
      this.plan = {
        start: null,
        end: null,
        title: "",
        content: "",
        class: ""
      };
      this.edit = false;
      this.planModal = true;
    },
    validate: function() {
      if (
        !this.plan.start ||
        !this.plan.end ||
        !this.plan.title ||
        !this.plan.class
      ) return false
        if (
          !this.validateDate(this.plan.start) ||
          !this.validateDate(this.plan.end)
        ) return false

        return true
    },
    validateDate: function (date) {
      const regex = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/gm;
      return regex.test(date)
    }
  }
};
</script>

<style scope>
#title {
  line-height: 40px;
  margin: 0;
}

/*  todo => Is better to seprated it to file */
/* Clanedar Green-theme. */
.vuecal__menu,
.vuecal__cell-events-count {
  background-color: #42b983;
}
.vuecal__menu button {
  border-bottom-color: #fff;
  color: #fff;
}
.vuecal__menu button.active {
  background-color: rgba(255, 255, 255, 0.15);
}
.vuecal__title-bar {
  background-color: #e4f5ef;
}
.vuecal__cell.today,
.vuecal__cell.current {
  background-color: rgba(240, 240, 255, 0.4);
}
.vuecal:not(.vuecal--day-view) .vuecal__cell.selected {
  background-color: rgba(235, 255, 245, 0.4);
}
.vuecal__cell.selected:before {
  border-color: rgba(66, 185, 131, 0.5);
}

/* Different color for different event types. */
.vuecal__event.black {
  background-color: black;
  border: 1px solid black;
  color: #fff;
}
.vuecal__event.white {
  background-color: white;
  border: 1px solid white;
  color: #000;
}

.vuecal__event.lightgray {
  background-color: lightgray;
  border: 1px solid lightgray;
  color: #000;
}

.vuecal__event.red {
  background-color: red;
  border: 1px solid red;
  color: #fff;
}

.vuecal__event.blue {
  background-color: blue;
  border: 1px solid blue;
  color: #fff;
}

.vuecal__event.green {
  background-color: green;
  border: 1px solid green;
  color: #fff;
}

.vuecal__event.purple {
  background-color: purple;
  border: 1px solid purple;
  color: #fff;
}

.vuecal__event.yellow {
  background-color: yellow;
  border: 1px solid yellow;
  color: #000;
}
.vuecal__event.lime {
  background-color: lime;
  border: 1px solid lime;
  color: #000;
}
</style>
