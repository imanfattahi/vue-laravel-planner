<template>

<div class="modal show" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" :class="{'modal-sm': small, 'modal-lg': large}">
    <div class="modal-content">
      <div class="modal-header" :style="'background-color:' + (hcolor ? hcolor : '#fff')">
        <slot class="modal-title" name="title"></slot>
      </div>
      <div class="modal-body" :style="'background-color:' + (bcolor ? bcolor : '#fff')">
        <slot name="body"></slot>
      </div>
      <div class="modal-footer" :style="'background-color:' + (fcolor ? fcolor : '#fff')">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</div>

</template>

<script>
    export default {
      name: "custom-modal",
      props: [
        'small', 'large', 'full', 'hcolor', 'fcolor', 'bcolor'
      ],
      data: function () {
        return {

        }
      }, methods: {
        closeModal: function () {
          this.SHOW = false
        }
      }
    }
</script>

<style scoped>
  .show {
    opacity: 1;
    display: block;
  }
  .modal-content {
    top: 0px;
  }
  .modal-dialog {
    top: 0px;
  }
  .modal-header {
    text-align: center;
    padding-bottom: 0;
  }
  .modal-content {
    border-radius: 0;
  }
  .modal {
    background: rgba(0, 0, 0, 0.15);
    overflow-y: auto;
  }
</style>
