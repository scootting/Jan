<template>
  <div>
    <el-dialog title="buscar" :visible.sync="dialogVisible" width="36%" center :before-close="closeModal">
      <!--
      <span>Inserte id aca:</span>
      <el-input placeholder="Please input" v-model="isMessage.id"></el-input>
      <span>Inserte texto aca:</span>
      <el-input placeholder="Please input" v-model="isMessage.info"></el-input>
-->
      <div style="margin-top: 15px">
        <el-input placeholder="inserte una descripcion" v-model="writtenTextParameter" class="input-with-select">
          <el-button type="success" slot="append" icon="el-icon-search" @click="getPersonByDescription"></el-button>
        </el-input>
      </div>
      <br />
      <div style="margin-top: 15px">
        <el-table v-loading="loading" :data="dataPersons" height="250" style="width: 100%">
          <el-table-column prop="nro_dip" label="id" width="120">
            <template slot-scope="scope">
              <el-tag>{{ scope.row.nro_dip }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="des_per" label="descripcion"></el-table-column>
          <el-table-column align="right-center" label="seleccion">
            <template slot-scope="scope">
              <el-button @click="closeModalWithInfo(scope.$index, scope.row)" type="text" size="mini">seleccionar
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
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
      person: this.dataPerson,
      writtenTextParameter: "",
      dataPersons: [],
      loading: false,
    };
  },
  //variables del componente padre
  props: {
    centerDialogVisible: {
      required: true,
      type: Boolean,
      default: false,
    },
    dataPerson: {
      required: true,
      type: Object,
      default: null,
    },
  },
  mounted() { },
  methods: {
    closeModal() {
      this.dialogVisible = false;
      this.$emit("update-visible", this.dialogVisible);
    },
    closeModalWithInfo(id, row) {
      console.log(row);
      this.dialogVisible = false;
      this.person = row;
      this.$emit("update-info", this.dialogVisible, this.person);
    },
    close(){
      this.dialogVisible = false;
      this.$emit("update-visible", this.dialogVisible);    
    },

    //  * COM1. Obtener una lista de personas que coinciden con la descripcion.
    async getPersonByDescription() {
      let app = this;
      let description = app.writtenTextParameter;
      app.loading = true;
      try {
        let response = await axios.post("/api/getPersonsByDescriptionWithPagination", {
          description: description,
        });
        app.dataPersons = Object.values(response.data);
        app.loading = false;
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
  },
  watch: {
    centerDialogVisible: function () {
      this.dialogVisible = this.centerDialogVisible;
    },
  },
};
</script>

<style>

</style>
