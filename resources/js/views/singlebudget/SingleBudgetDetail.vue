<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del presupuesto individual</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <!-- Form Add Container to Archive-->
      <!--
      <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
        <el-form :model="document" label-width="220px" size="small">
          <el-form-item label="tipo de documento">
            <el-select v-model="document.id_tipo" value-key="descr" size="small"
              placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
              <el-option v-for="item in typesDocument" :key="item.id" :label="item.descr" :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Numero del documento">
            <el-input v-model="document.numeral" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="fecha del registro">
            <el-date-picker type="date" v-model="document.fecha" placeholder="seleccione una fecha" style="width: 100%"
              format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
          </el-form-item>

          <el-form-item label="glosa o descripcion">
            <el-input type="textarea" v-model="document.glosa" autocomplete="off"></el-input>
          </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" size="small" plain @click="AddDocumetOfArchive">Confirmar</el-button>
          <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
        </span>
      </el-dialog>
      -->
      <!-- Form Add Document to Archive-->
      <!-- Form Add Container to Archive-->
      <!-- Form Add Document to Archive-->
      <h1>Este es el texto del hijo {{isMessage}}</h1>
      <el-button type="text" @click="initSearchPerson">Agregar persona</el-button>
      <person :centerDialogVisible="isVisible" :message="isMessage" @update-visible="update"  @update-info="update_2"></person>
    </el-card>
  </div>
</template>
  
<script>
import person from "../components/Person.vue";
export default {
  name: "",
  components: {
    person,
  },
  data() {
    return {
      user: this.$store.state.user,
      documentsArchive: [],
      isVisible: false,
      isMessage: 'Hola Mundo!!!',
      document: {
        nro_dip: "",
        paterno: "",
        materno: "",
        nombres: "",
        literal: "",
        literal2:"",
      },
    };
  },
  mounted() {
    let app = this;
    app.id = app.$route.params.id;
    //app.getTypesDocument();
    //app.getDocumentsbyArchive();
  },
  methods: {

    //  * Todas las funciones para el componente persona
    initSearchPerson() {
      this.isVisible = true;
    },
    update(isVisible) {
      this.isVisible = isVisible;
    },

    update_2(isVisible, isMessage){
      this.isVisible = isVisible;
      this.isMessage = isMessage;
      alert(isMessage);
    },






















    test(){
      console.log("Ayuda!!!");
    },
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    async getTypesDocument() {
      let app = this;
      try {
        let response = await axios.post("/api/getTypesDocument", {
          description: "Documento",
        });
        app.typesDocument = response.data;
        console.log(app.typesDocument);
        console.log(app.documentsArchive);
      } catch (error) {
        console.log(error);
      }
    },
    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    async getDocumentsbyArchive() {
      let app = this;
      try {
        let response = await axios.post("/api/getDocumentsbyArchive", {
          id: app.id_archive,
          year: app.user.gestion,
        });
        app.documentsArchive = response.data;
        console.log(app.documentsArchive);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

  },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-row {
  margin-bottom: 20px;
}

.el-col {
  border-radius: 4px;
}

.bg-purple-dark {
  background: #99a9bf;
}

.bg-purple {
  background: #d3dce6;
}

.bg-purple-light {
  background: #e5e9f2;
}

.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}

.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}

.el-form .el-select {
  width: 100%;
}
</style>