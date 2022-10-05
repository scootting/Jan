<template>
  <div>
    <el-dialog title="buscar" :visible.sync="dialogVisible" width="30%" center>
      <!--
      <span>Inserte id aca:</span>
      <el-input placeholder="Please input" v-model="isMessage.id"></el-input>
      <span>Inserte texto aca:</span>
      <el-input placeholder="Please input" v-model="isMessage.info"></el-input>
-->
      <div style="margin-top: 15px;">
        <el-input placeholder="inserte una descripcion" v-model="writtenTextParameter" class="input-with-select">
          <el-button type="success" slot="append" icon="el-icon-search" @click="getPersons"></el-button>
        </el-input>
      </div>
      <br>
      <div style="margin-top: 15px;">
        <el-table v-loading="loading" :data="dataPersons" style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column prop="id" label="id">
            <template slot-scope="scope">
              <el-tag size="success" type="info">{{ scope.row.id }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="desc" label="descripcion"></el-table-column>
        </el-table>

      </div>
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
      type: Object,
      default: null,
    },
    writtenTextParameter: String,
    dataPersons: Object,
    loading: false,
  },
  mounted() {


  },
  methods: {
    closeModal() {
      this.dialogVisible = false;
      this.$emit("update-visible", this.dialogVisible);
    },
    getPersons() {

    },

    handleSelectionChange() { },
    closeModalWithInfo() {
      this.dialogVisible = false;
      this.$emit("update-info", this.dialogVisible, this.isMessage);
    }
  },
  watch: {
    centerDialogVisible: function () {
      this.dialogVisible = this.centerDialogVisible;
    },/*
    message: function () {
      this.isMessage = this.message;
    }*/
  },
};
</script>

<style>

</style>