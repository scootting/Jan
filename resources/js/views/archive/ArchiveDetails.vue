<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del archivo</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <el-row :gutter="50">
        <!--
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-form
              ref="form"
              :model="this.personal"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="carnet de identidad">
                <el-input v-model="personal.nro_dip" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="personal.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="personal.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="personal.nombres" disabled></el-input>
              </el-form-item>
            </el-form>
          </div></el-col
        >
        -->
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-table
              :data="documentsArchive"
              border
              style="width: 100%"
              size="small"
            >
              <el-table-column
                prop="indice"
                label="indice"
                align="right"
                width="50"
              >
              </el-table-column>
              <el-table-column
                prop="fecha"
                label="fecha"
                width="100"
                align="center"
              >
              </el-table-column>
              <el-table-column
                prop="numeral"
                label="no. documento"
                width="100"
                align="right"
              >
              </el-table-column>
              <el-table-column prop="id_tipo" label="documento" width="120">
              </el-table-column>
              <el-table-column prop="glosa" label="descripcion" width="750">
              </el-table-column>
              <el-table-column
                align="right-center"
                label="operaciones"
                width="200"
              >
                <template slot-scope="scope">
                  <el-button
                    :disabled="scope.row.guardado === true"
                    type="text"
                    size="mini"
                    @click="initEditDocumentOfArchive(scope.$index, scope.row)"
                    >editar</el-button
                  >
                  <el-button
                    :disabled="scope.row.guardado === true"
                    type="text"
                    size="mini"
                    @click="DeleteDocumentOfArchive(scope.$index, scope.row)"
                    >quitar</el-button
                  >
                </template>
              </el-table-column>
            </el-table>
          </div></el-col
        >
      </el-row>

      <!-- Form -->
      <el-button type="text" @click="initAddDocumentOfArchive"
        >Agregar nuevo documento</el-button
      >

      <el-dialog
        title="detalle del documento"
        :visible.sync="dialogFormVisible"
      >
        <el-form :model="document" label-width="220px" size="small">
          <el-form-item label="Numero del documento">
            <el-input v-model="document.numeral" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="fecha del registro">
            <el-date-picker
              type="date"
              v-model="document.fecha"
              placeholder="seleccione una fecha"
              style="width: 100%"
              format="yyyy/MM/dd"
              value-format="yyyy-MM-dd"
            ></el-date-picker>
          </el-form-item>
          <el-form-item label="glosa o descripcion">
            <el-input
              type="textarea"
              v-model="document.glosa"
              autocomplete="off"
            ></el-input>
          </el-form-item>
          <!--
        -->
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button
            type="primary"
            size="small"
            plain
            @click="AddDocumetOfArchive"
            >Confirmar</el-button
          >
          <el-button
            type="danger"
            size="small"
            plain
            @click="dialogFormVisible = false"
            >Cancelar</el-button
          >
        </span>
      </el-dialog>
      <!-- Form -->
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      user: this.$store.state.user,
      id_archive: null,
      documentsArchive: [],
      dialogFormVisible: false,
      stateStore: '',
      document: {
        id_doc: "",
        numeral: "",
        indice: "",
        glosa: "",
        fecha: "",
        id_tipo: null,
        gestion: null,
      },
    };
  },
  mounted() {
    let app = this;
    app.id_archive = app.$route.params.id;
    app.getDocumentsbyArchive();
  },
  methods: {
    test() {
      alert("test");
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
    initEditDocumentOfArchive(idx, row) {
      this.document = row;
      this.stateStore = 'editar'
      console.log(this.document);
      this.dialogFormVisible = true;
    },
    initAddDocumentOfArchive() {
      this.document = {
        id_doc: "",
        numeral: "",
        indice: "",
        glosa: "",
        fecha: "",
        id_tipo: null,
        gestion: null,
      };
      this.stateStore = 'añadir'
      this.dialogFormVisible = true;
    },
    AddDocumetOfArchive(){
        if(this.stateStore == 'añadir')
            this.documentsArchive.push(this.document);
        this.dialogFormVisible = false;
        console.log(this.documentsArchive);
    },
    DeleteDocumentOfArchive(idx, row){
        this.documentsArchive.splice(idx, 1);
        console.log(this.documentsArchive);
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
</style>
