<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>contenido del contenedor</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <el-row :gutter="20">
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <span>documentos</span>
            <div>
              <el-table :data="documents" border style="width: 100%" size="small">
                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                </el-table-column>
                <el-table-column prop="id_doc" label="codigo" width="100" align="center">
                  <template slot-scope="scope">
                    <el-tag size="medium">{{
                      scope.row.id_doc
                    }}</el-tag>
                  </template>
                </el-table-column>
                <el-table-column prop="glosa" label="glosa" width="300" align="right">
                </el-table-column>
                <el-table-column align="right-center" label="operaciones" width="120">
                  <template slot-scope="scope">
                    <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                      @click="initCheckDocuments(scope.$index, scope.row)">ver archivos</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="grid-content bg-purple">
            <span>contenedores</span>
            <div>
              <el-table :data="fileContainer" border style="width: 100%" size="small">
                <el-table-column prop="fecha" label="fecha" width="100" align="center">
                </el-table-column>
                <el-table-column prop="id_doc" label="codigo" width="250" align="center">
                  <template slot-scope="scope">
                    <el-tag size="medium">{{
                      scope.row.id_doc
                    }}</el-tag>
                  </template>
                </el-table-column>
                <el-table-column prop="glosa" label="descripcion" width="100" align="right">
                </el-table-column>
                <el-table-column align="right-center" label="operaciones" width="150">
                  <template slot-scope="scope">
                    <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                      @click="initCheckFileContainers(scope.$index, scope.row)">ver contenido</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>

          </div>
        </el-col>
      </el-row>
      <!-- Form Add Document to Archive-->
      <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
        <el-form :model="document" label-width="220px" size="small">

          <el-form-item label="tipo de documento">
            <el-select v-model="document.descr" value-key="descr" size="small"
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
          <el-button type="primary" size="small" plain @click="AddArchiveToDocument">Confirmar</el-button>
          <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
        </span>
      </el-dialog>
      <!-- Form Add Document to Archive-->
      <el-button type="primary" size="small" @click="initAddDocumentOfArchive">Agregar documento</el-button>
      <el-button type="success" @click="initStoreArchivesOfDocument" size="small">Agregar contenedor</el-button>
      <el-button type="warning" @click="initStoreArchivesOfDocument" size="small">Guardar cambios en el
        contenedor</el-button>
    </el-card>
  </div>
</template>
<script>
export default {
  name: "",
  data() {
    return {
      user: this.$store.state.user,
      id: null,                    //identificador del contenedor 
      documents: [],               //lista de documentos
      fileContainer: [],           //lista de contenedores
      dialogFormVisible: false,    //para el dialogo
      stateStore: "",              //estado para ver si se aniade o se edita
      document: {
        id_doc: "",
        indice: 0,
        numeral: "",
        glosa: "",
        fecha: "",
        id_tipo: 'A',
        id_arch: null,
        descr: "",
        gestion: "",
      },
      documentsArchive: [],
      typesDocument: [],
    };
  },
  mounted() {
    let app = this;
    app.id = app.$route.params.id;
    app.getDocumentAndFilesContainerById();
  },
  methods: {
    test() {
      alert("test");
    },

    //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual
    async getDocumentAndFilesContainerById() {
      let app = this;
      try {
        let response = await axios.post("/api/getDocumentAndFilesContainerById", {
          id: app.id,
        });
        app.documents = response.data.documents;
        app.fileContainer = response.data.fileContainer;
        console.log(app.documents);
        console.log(app.fileContainer);
      } catch (error) {
        console.log(error);
      }
    },

    initCheckDocuments(idx, row) {
      this.$router.push({
        name: "archivedetails",
        params: {
          id: row.id_rama,
        },
      });
    },

    initCheckFileContainers(idx, row) {
      this.$router.push({
        name: "filecontainerdetails",
        params: {
          id: row.id_rama,
        },
      });
    },

    beforeRouteUpdate(to, from, next) {
      alert("hola entramos");
      next();
      // called when the route that renders this component has changed.
      // This component being reused (by using an explicit `key`) in the new route or not doesn't change anything.
      // For example, for a route with dynamic params `/foo/:id`, when we
      // navigate between `/foo/1` and `/foo/2`, the same `Foo` component instance
      // will be reused (unless you provided a `key` to `<router-view>`), and this hook will be called when that happens.
      // has access to `this` component instance.
    },

    //  * A11. Guardar los archivos del documento
    async initStoreArchivesOfDocument() {
      var app = this;
      var newArchivesOfDocument = app.documentsArchive;
      var newDocument = app.id;
      var newYear = app.user.gestion;
      try {
        console.log(newArchivesOfDocument);
        let response = axios
          .post("/api/storeArchivesOfDocument", {
            archivesOfDocument: newArchivesOfDocument,
            document: newDocument,
            year: newYear,
            marker: "registrar",
          });
        console.log(response);
        app.$alert("Se ha registrado correctamente los archivos del documento", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      } catch (error) {
        app.$alert("No se registro nada", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },
    //  * Inicia la edicion de un documento
    initEditDocumentOfArchive(idx, row) {
      this.document = row;
      this.stateStore = "editar";
      this.dialogFormVisible = true;
    },

    //  * Iniciar el cuadro de dialogo para insertar un nuevo archivo
    initAddDocumentOfArchive() {
      this.stateStore = "añadir";
      this.dialogFormVisible = true;
    },

    //  * Guarda los cambios de un nuevo documento sea nuevo o uno ya existente

    AddArchiveToDocument() {
      this.dialogFormVisible = false;
      if (this.stateStore == "añadir") {
        let variable = this.document;
        this.documentsArchive.push(variable);
      }
      else {

      }
      this.document = { id_doc: "", indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: "" };
      console.log(this.documentsArchive);
      this.OnUpdateIndex();
    },

    //  * Quita un documento ya existente
    DeleteDocumentOfArchive(idx, row) {
      this.documentsArchive.splice(idx, 1);
      console.log(this.documentsArchive);
      this.OnUpdateIndex();
    },

    //  * Actualiza el indice de la lista
    OnUpdateIndex() {
      let indice = 0;
      this.documentsArchive.forEach(element => {
        indice += 1;
        element.indice = indice;
      });
    },

    //* actualizar un componente al hacer la seleccion nueva *//
    OnchangeTypeDocument(idx) {
      console.log(this.document);
      console.log(idx);
      let resultado = this.typesDocument.find(tipo => tipo.id == idx);
      this.document.id_arch = resultado.id;
      this.document.descr = resultado.descr;
      console.log(resultado);
      console.log(this.document);
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

