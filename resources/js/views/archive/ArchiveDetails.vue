<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del archivo</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-form :model="archive" label-width="220px" size="small" disabled="true">
            <el-col :span="12">
              <el-form-item label="codigo">
                <el-input v-model="archive.id_doc"></el-input>
              </el-form-item>
              <el-form-item label="fecha">
                <el-input v-model="archive.fecha"></el-input>
              </el-form-item>
              <el-form-item label="glosa">
                <el-input type="textarea" v-model="archive.glosa"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="gestion">
                <el-input v-model="archive.gestion"></el-input>
              </el-form-item>
              <el-form-item label="estado">
                <el-input v-model="archive.estado"></el-input>
              </el-form-item>
              <el-form-item label="tipo">
                <el-input type="textarea" v-model="archive.descr"></el-input>
              </el-form-item>
            </el-col>
          </el-form>
          <el-button size="small" type="primary" @click.prevent="test" plain>Ver Contenedor</el-button>
          <el-button size="small" type="primary" @click.prevent="test" plain>Liberar</el-button>
        </el-row>
      </div>
      <br>

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
                  <el-button :disabled="scope.row.guardado === true" type="primary" plain size="mini"
                    @click="initEditDocumentOfArchive(scope.$index, scope.row)">editar</el-button>
                  <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                    @click="DeleteDocumentOfArchive(scope.$index, scope.row)">quitar</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
      <!-- Form Add Document to Archive-->
      <el-button type="primary" @click="initAddDocumentOfArchive" size="small">Agregar nuevo archivo</el-button>
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
      <el-button type="success" @click="initStoreArchivesOfDocument" size="small">Guardar documento</el-button>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      user: this.$store.state.user,
      id: null,                    //identificador del documento 
      documentsArchive: [],       //lista de archivos pertenecientes a un documento
      typesDocument: [],          //diferentes tipos de archivos que pertenecen a un documento
      dialogFormVisible: false,   //para el dialogo
      stateStore: "",             //estado para ver si se aniade o se edita
      archive: {},                //informacion principal del documento
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
    };
  },
  mounted() {
    let app = this;
    app.id = app.$route.params.id;
    app.getTypesDocument();
    app.getDocumentsbyArchive();
  },
  methods: {
    test() {
      alert("test");
    },

    //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    async getTypesDocument() {
      let app = this;
      try {
        let response = await axios.post("/api/getTypesDocumentById", {
          id_type: "A",
        });
        app.typesDocument = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    async getDocumentsbyArchive() {
      let app = this;
      try {
        let response = await axios.post("/api/getDocumentsbyArchive", {
          id: app.id,
          year: app.user.gestion,
        });
        app.documentsArchive = response.data.data;
        app.archive = response.data.archive[0];
        console.log(app.documentsArchive);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
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
            marker: "editar",//editar
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
