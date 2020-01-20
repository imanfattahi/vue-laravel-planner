<template>

    <div id="palette">
        <input v-model="selectedColor" @change="$emit('input', $event.target.value)" name="selected_color" type="hidden">
        <a :title="'Seleced Color: ' + selectedColor " :style="{'background-color': selectedColor}" id="palletHandle" href="#" @click.prevent="openPallet()"></a>
        <ul :class="{'show': palette}">
            <li :title="color" :style="{'background-color': color}" @click="selectColor(color)" v-for="color in colors" v-bind:key="color"></li>
        </ul>
    </div>

</template>

<script>
    export default {
      name: "color-selector",
      props: [
        'value'
      ],
      mounted: function () {
          this.selectedColor = this.selectedColor
          this.$emit('input', this.selectedColor)
      },
      data: function () {
        return {
            palette: false,
            selectedColor: this.value ? this.value : 'lightgray',
            colors: [
                'black',
                'white',
                'lightgray',
                'red',
                'blue',
                'green',
                'purple',
                'yellow',
                'lime',
            ]
        }
      }, methods: {
        selectColor: function (color) {
          this.selectedColor = color
          this.palette = false
        },
        openPallet: function () {
            this.palette = !this.palette
        }
      }, watch: {
          selectedColor: function () {
              this.$emit('input', this.selectedColor)
          }
      }
    }
</script>

<style scoped>
    #palette {
        position: relative;
    }
    #palette ul {
        position: absolute;
        display: none;
        width: 105px;
        padding: 0;
        margin: 0;
        bottom: -28px;
        left: -34px;
    }
    #palette ul.show {
        display: inline-block !important;
    }
    #palette ul li {
        display: inline-block;
        width: 35px;
        height: 35px;
        float: left;
        cursor: pointer;
    }
    #palletHandle {
        display: inline-block;
        width: 35px;
        height: 35px;
        border: 2px solid #42b983;
    }
</style>
