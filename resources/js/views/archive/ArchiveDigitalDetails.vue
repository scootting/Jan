<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del archivo</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-form :model="archive" label-width="220px" size="small">
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
              <el-table-column align="right-center" label="operaciones" width="200">
                <template slot-scope="scope">
                  <el-button :disabled="scope.row.guardado === true" type="primary" plain size="mini"
                    @click="initEditDocumentOfArchive(scope.$index, scope.row)">digitalizar</el-button>
                  <el-button :disabled="scope.row.guardado === true" type="primary" plain size="mini"
                    @click="getDigitalDocumentById(scope.$index, scope.row)">digital</el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
      <!-- Form Add Document to Archive-->
      <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
        <el-form :model="document" label-width="220px" size="small" enable='true'>
          <el-form-item label="Numero del documento">
            <el-input v-model="document.descr" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="Numero del documento">
            <el-input v-model="document.numeral" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="Numero del documento">
            <el-input v-model="document.fecha" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="glosa o descripcion">
            <el-input type="textarea" v-model="document.glosa" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item>
            <el-upload ref="upload" action="/api/storeDigitalDocument" :auto-upload="false" :file-list="digitalDocument"
              :multiple="false" :limit="1" :data="document" accept=".pdf" :headers="requestHeaders"
              :on-success="handleSuccessBoucher" :on-remove="test">
              <!---->
              <p></p>
              <el-button slot="trigger" size="small" type="primary">subir archivo digitalizado</el-button>
              <div slot="tip" class="el-upload__tip">
                Solo puede subir archivos pdf
              </div>
            </el-upload>
          </el-form-item>

          <!--
        -->
        </el-form>
        <span slot="footer" class="dialog-footer">
          <el-button type="success" size="small" @click="storeDigitalDocument()">guardar archivo digitalizado</el-button>
          <el-button type="danger" size="small" @click="dialogFormVisible = false">Cancelar</el-button>
        </span>
      </el-dialog>
      <!-- Form Add Document to Archive
      <el-button type="success" @click="initStoreArchivesOfDocument" size="small">Guardar documento</el-button>
      -->
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
      requestHeaders: {
        "X-CSRF-TOKEN": window.axios.defaults.headers.common["X-CSRF-TOKEN"],
        Authorization: "Bearer " + this.$store.state.token,
      },
      archive: {},
      digitalDocument: [],       //lista de documentos digitales 
      documentsArchive: [],       //lista de archivos pertenecientes a un documento
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


      typesDocument: [],          //diferentes tipos de archivos que pertenecen a un documento
      dialogFormVisible: false,   //para el dialogo
      stateStore: "",             //estado para ver si se aniade o se edita
    };
  },
  mounted() {
    let app = this;
    app.id = app.$route.params.id;
    //app.getTypesDocument();
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

    //  * Inicia la edicion de un documento
    initEditDocumentOfArchive(idx, row) {
      this.document = row;
      this.stateStore = "añadir";
      this.dialogFormVisible = true;
    },

    storeDigitalDocument() {
      var app = this;
      this.$refs.upload.submit();
    },
    handleSuccessBoucher(response, file, fileList) {
      this.$alert('Gracias, acaba de subir el documento digital ' + file.name + ' satifactoriamente.', 'confirmacion', {
        confirmButtonText: 'bueno',
      });
      //this.document = {};
      this.digitalDocument = [];
      /*this.getDataRequestById();*/
      console.log(response, file, fileList);
      this.fileList = fileList;

    },

    getDigitalDocumentById(idx, row) {
      let app = this;
      axios({
        url: "/api/getDigitalDocumentById/",
        params: {
          id: row.id_doc,
          year: app.user.gestion,
        },
        method: "GET",
        responseType: "arraybuffer",
      }).then((response) => {
        let pdfData = response.data;
        let blob = new Blob(base64ToArrayBuffer([pdfData]), { type: 'application/pdf' });
        let url = URL.createObjectURL(blob);
        window.open(url);
      });
    },
    base64ToArrayBuffer(base64) {
      let binaryString;
      let buffer;

      if (typeof window !== 'undefined') {
        // Navegador
        binaryString = window.atob(base64);
      } else {
        // Node.js
        binaryString = Buffer.from(base64, 'base64').toString('binary');
      }

      const length = binaryString.length;
      const bytes = new Uint8Array(length);

      for (let i = 0; i < length; i++) {
        bytes[i] = binaryString.charCodeAt(i);
      }

      buffer = bytes.buffer;

      return buffer;
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
