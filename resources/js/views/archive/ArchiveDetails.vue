<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del archivo</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <el-row :gutter="50">
        <el-col :span="24">
          <div>
            <el-table :data="documentsArchive" border style="width: 100%" size="small">
              <el-table-column prop="indice" label="indice" align="right" width="100">
              </el-table-column>
              <el-table-column prop="fecha" label="fecha" width="100" align="center">
              </el-table-column>
              <el-table-column prop="numeral" label="no. documento" width="100" align="right">
              </el-table-column>
              <el-table-column prop="descr" label="documento" width="250" align="center">
                <template slot-scope="scope">
                  <el-tag size="medium">{{
                    scope.row.descr
                  }}</el-tag>
                </template>
              </el-table-column>
              <el-table-column prop="glosa" label="descripcion" width="650">
              </el-table-column>
              <el-table-column align="right-center" label="operaciones" width="180">
                <template slot-scope="scope">
                  <el-button :disabled="scope.row.guardado === true" type="text" size="mini"
                    @click="initEditDocumentOfArchive(scope.$index, scope.row)">editar</el-button>
                  <el-button :disabled="scope.row.guardado === true" type="text" size="mini"
                    @click="DeleteDocumentOfArchive(scope.$index, scope.row)">quitar</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
      <!-- Form Add Document to Archive-->
      <el-button type="text" @click="initAddDocumentOfArchive">Agregar nuevo documento</el-button>
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
          <!--
        -->
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" size="small" plain @click="AddDocumetOfArchive">Confirmar</el-button>
          <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
        </span>
      </el-dialog>
      <!-- Form Add Document to Archive-->
      <!-- Form Add Container to Archive-->
      <el-button type="text" @click="initAddArchiveOfContainer">Agregar contenedor</el-button>
      <!-- Form Add Container to Archive-->
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      user: this.$store.state.user,
      id: null,
      documentsArchive: [],
      typesDocument: [],
      dialogFormVisible: false,
      stateStore: "",
      document: {
        id_doc: "",
        numeral: "",
        indice: "",
        glosa: "",
        fecha: "",
        id_tipo: null,
        descr: "",
        gestion: null,
      },
    };
  },
  mounted() {
    let app = this;
    app.id_archive = app.$route.params.id;
    app.getTypesDocument();
    app.getDocumentsbyArchive();
  },
  methods: {
    test() {
      alert("test");
    },
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    async getTypesDocument() {
      let app = this;
      try {
        let response = await axios.post("/api/getTypesDocument", {
          id_type: "A",
        });
        app.typesDocument = response.data;
        console.log(app.typesDocument);
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

    //  * Inicia la edicion de un documento
    initEditDocumentOfArchive(idx, row) {
      this.document = row;
      this.stateStore = "editar";
      console.log(this.document);
      this.dialogFormVisible = true;
    },
    
    //  * Inicia un nuevo documento
    initAddDocumentOfArchive() {
      this.document = {
        id_doc: "",
        numeral: "",
        indice: "",
        glosa: "",
        fecha: "",
        id_tipo: null,
        descr: "",
        gestion: null,
      };
      this.stateStore = "añadir";
      this.dialogFormVisible = true;
    },

    initAddArchiveOfContainer(idx, row) {
      alert('hola como estas?');
    },
    //  * Guarda los cambios de un nuevo documento sea nuevo o uno ya existente
    AddDocumetOfArchive() {
      if (this.stateStore == "añadir")
        this.documentsArchive.push(this.document);
      this.dialogFormVisible = false;
      console.log(this.documentsArchive);
    },

    //  * Quita un documento ya existente
    DeleteDocumentOfArchive(idx, row) {
      this.documentsArchive.splice(idx, 1);
      console.log(this.documentsArchive);
    },
    OnchangeTypeDocument(idx) {
      this.document.descr = this.typesDocument[idx - 1].descr;
    }
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
