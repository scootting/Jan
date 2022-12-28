<template>
  <div>
    <el-dialog title="buscar" :visible.sync="visible" width="36%" center :before-close="closeModal">
      <div style="margin-top: 15px">
        <el-input placeholder="inserte una descripcion" v-model="writtenTextParameter" class="input-with-select">
          <el-button type="success" slot="append" icon="el-icon-search" @click="getDataByDescription"></el-button>
        </el-input>
      </div>
      <br />
      <div style="margin-top: 15px">
        <el-table v-loading="loading" :data="data" height="200" style="width: 100%">
          <el-table-column prop="id" label="id" width="120">
            <template slot-scope="scope">
              <el-tag>{{ scope.row.id }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="details" label="detalle"></el-table-column>
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
  data() {
    return {
      data: [],
      loading: false,
      //person: this.dataPerson,
      writtenTextParameter: "",
    };
  },
  //variables del componente padre
  props: {
    visible: {
      required: true,
      type: Boolean,
      default: false,
    },
    tag: {
      required: true,
      type: String,
      default: '',
    },
    /*
    dataPerson: {
      required: true,
      type: Object,
      default: null,
    },*/
  },
  mounted() {
    console.log("estado de la propiedad visible:" + this.visible);
    console.log("estado de la propiedad tag:" + this.tag);
  },
  methods: {
    closeModal() {
      let isVisible = this.visible;
      isVisible = false;
      this.$emit("update-visible", isVisible);
    },
    /*
    closeModalWithInfo(id, row) {
      console.log(row);
      this.dialogVisible = false;
      this.person = row;
      this.$emit("update-info", this.dialogVisible, this.person);
    },
    close() {
      this.dialogVisible = false;
      this.$emit("update-visible", this.dialogVisible);
    },
    */
    //  * Obtener datos que coincidan con la descripcion.
    async getDataByDescription() {
      let app = this;
      let description = app.writtenTextParameter;
      app.loading = true;
      try {
        switch (app.tag) {
          //  * COM1. Obtener una lista de personas.
          case 'persona':
            app.url = '/api/getPersonsByDescriptionWithPagination';
            console.log("entro a personas");
            break;
          //  * COM2. Obtener una lista de categorias programaticas.
          case 'categoria':
            app.url = '/api/getProgramCategoryDescriptionWithPagination';
            console.log("entro a categorias");
          default:
            break;
        }
        let response = await axios.post(app.url, {
          description: description,
        });
        app.data = Object.values(response.data);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Mensajes", {
          dangerouslyUseHTMLString: true,
        });
      }
      app.loading = false;
    },
  },
  watch: {/*
    visible: function () {
      this.visible = false;
    },*/
  },
};
</script>

<style>

</style>
