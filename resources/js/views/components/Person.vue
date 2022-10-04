<template>
  <div>
    <el-dialog title="Warning" :visible.sync="dialogVisible" width="30%" center>
      <span>Inserte texto aca:</span>
      <el-input placeholder="Please input" v-model="isMessage"></el-input>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" @click="closeModalWithInfo">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: "Person",
  //variables locales
  data() {
    return {
      dialogVisible: this.centerDialogVisible,
      isMessage: this.message,
    };
  },
  //variables del componente padre
  props: {
    centerDialogVisible: {
      required: true,
      type: Boolean,
      default: false,
    },
    message: {
      required: true,
      type: String,
      default: '',
    }
  },


  methods: {
    closeModal() {
      this.dialogVisible = false;
      this.$emit("update-visible", this.dialogVisible);
    },
    closeModalWithInfo(){
      this.dialogVisible = false;
      this.$emit("update-info", this.dialogVisible, this.isMessage);
    }
  },
  watch: {
    centerDialogVisible: function () {
      this.dialogVisible = this.centerDialogVisible;
    },
    message: function(){
      this.isMessage = this.message;
    }
  },
};
</script>

<style>

</style>