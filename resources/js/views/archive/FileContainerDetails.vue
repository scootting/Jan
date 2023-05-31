<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>contenido del contenedor</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-form :model="container" label-width="220px" size="small">
            <el-col :span="12">
              <el-form-item label="codigo">
                <el-input v-model="container.id_doc"></el-input>
              </el-form-item>
              <el-form-item label="fecha">
                <el-input v-model="container.fecha"></el-input>
              </el-form-item>
              <el-form-item label="glosa">
                <el-input type="textarea" v-model="container.glosa"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="gestion">
                <el-input v-model="container.gestion"></el-input>
              </el-form-item>
              <el-form-item label="estado">
                <el-input v-model="container.estado"></el-input>
                <el-button size="small" type="primary" @click.prevent="test" plain>Ver Contenedor</el-button>
                <el-button size="small" type="primary" @click.prevent="test" plain>Liberar</el-button>
              </el-form-item>
              <el-form-item label="tipo">
                <el-input type="textarea" v-model="container.descr"></el-input>
              </el-form-item>
            </el-col>
          </el-form>
        </el-row>
      </div>
      <br>
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

      <!-- Form Add Document and Containers -->
      <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
        <el-table :data="data" style="width: 100%" border @selection-change="handleSelectionChange">
          <el-table-column type="selection"> </el-table-column>
          <el-table-column prop="id_doc" label="no. de documento">
            <template slot-scope="scope">
              <el-tag size="success" type="info">{{ scope.row.id_doc }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa"></el-table-column>
        </el-table>
        <span slot="footer" class="dialog-footer">
          <el-button type="primary" size="small" plain @click="AddArchiveToContainer">Confirmar</el-button>
          <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
        </span>
      </el-dialog>
      <!-- Form Add Document to Archive-->
      <el-button type="primary" size="small" @click="initAddDocument">Agregar documento</el-button>
      <el-button type="success" @click="initAddContainer" size="small">Agregar contenedor</el-button>
      <el-button type="warning" @click="initStoreDocumentsAndContainers" size="small">Guardar cambios en el
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
      container: {},                //informacion del contenedor 
      documents: [],               //lista de documentos que pertenecen al contenedor
      fileContainer: [],           //lista de contenedores que pertenecen al contenedor
      dialogFormVisible: false,    //para el dialogo
      stateStore: "",              //estado para ver si se aniade o se edita
      dataDocuments: [],            //la posible lista de documentos que no estan en otro contenedor
      dataContainers: [],            //la posible lista de contenedores que no estan relacionados
      data: [],
      selected: [],         //documentos nuevos selecccionados
    };
  },
  mounted() {
    let app = this;
    app.id = app.$route.params.id;
    app.getDocumentAndFilesContainerById();
    app.getDocumentAndContainerFree();
  },

  beforeRouteUpdate(to, from, next) {
    if (to.params.id !== from.params.id) {
      this.id = to.params.id;
      this.getDocumentAndFilesContainerById();
      this.getDocumentAndContainerFree();
    }
    next();
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
        app.container = response.data.container[0];
        console.log(app.documents);
      } catch (error) {
        console.log(error);
      }
    },
    //  * A13. Obtiene la lista de documentos y contenedores que estan libres para su registro
    async getDocumentAndContainerFree() {
      let app = this;
      try {
        let response = await axios.post("/api/getDocumentAndContainerFree", {
          id: app.id,
        });
        app.dataDocuments = response.data.dataDocuments;
        app.dataContainers = response.data.dataContainers;
        console.log(app.dataDocuments);
      } catch (error) {
        console.log(error);
      }
    },
    //va a ver los archivos de un documento
    initCheckDocuments(idx, row) {
      alert(row);
      this.$router.push({
        name: "archivedetails",
        params: {
          id: row.id,
        },
      });
    },
    //va a ver lo que contiene un contenedor    
    initCheckFileContainers(idx, row) {
      this.$router.push({
        name: "filecontainerdetails",
        params: {
          id: row.id,
        },
      });
    },

    handleSelectionChange(val) {
      this.selected = val;
    },

    AddArchiveToContainer() {
      if (this.stateStore == 'Contenedor')
        this.fileContainer = this.fileContainer.concat(this.selected);
      else
        this.documents = this.documents.concat(this.selected);
      this.dialogFormVisible = false;
    },

    initAddDocument() {
      this.stateStore = 'Documento'
      this.data = this.dataDocuments;
      this.dialogFormVisible = true;
    },
    initAddContainer() {
      this.stateStore = 'Contenedor';
      this.data = this.dataContainers;
      this.dialogFormVisible = true;
    },

    //  * A14. Guardar los documentos y contenedores en el contenedor
    async initStoreDocumentsAndContainers() {
      var app = this;
      try {
        let response = axios
          .post("/api/storeDocumentsAndContainers", {
            documents: app.documents,
            fileContainer: app.fileContainer,
            username: app.user.usuario,
            year: app.user.gestion,
            id_container: app.container.id,
            id_type: app.container.id_tipo,
            //container: app.container,
            marker: "registrar",
          });
        console.log(response);
        app.$alert("Se ha registrado correctamente los archivos del documento", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      } catch (error) {
        console.log(error);
        app.$alert("No se registro nada", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
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

