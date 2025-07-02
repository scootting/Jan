<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>regularizar documento de deuda no. {{ id }}</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos del documentode regularizacion</p>
              <el-form ref="form" :model="debtorDocument" label-width="120px" size="small">
                <el-form-item label="numero" prop="idc">
                  {{ debtorDocument.idc }}
                </el-form-item>
                <el-form-item label="fecha" prop="fecha">
                  {{ debtorDocument.fecha }}
                </el-form-item>
                <el-form-item label="unidad" prop="des_prg">
                  {{ debt.des_prg }}
                </el-form-item>
                <el-form-item label="responsable" prop="des_resp">
                  {{ debtorDocument.des_resp }}
                </el-form-item>
                <el-form-item label="detalle" prop="detalle">
                  {{ debt.detalle }}
                </el-form-item>
              </el-form>
            </div>
            <p></p>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>acreedores</p>
              <el-table :data="debtors" style="width: 100%" size="small">
                <el-table-column prop="ci_per" label="ci" width="120"></el-table-column>
                <el-table-column prop="referencia" label="referencia" width="220"></el-table-column>
                <el-table-column align="right">
                </el-table-column>
              </el-table>
              <p></p>
              <p>documentacion</p>
              <el-table :data="digitales" style="width: 100%" size="small">
                <el-table-column prop="referencia" label="referencia" width="220"></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary" plain size="small">ver
                      documento</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initEditDocumentOfArchive" type="primary" size="small">Agregar documento digitalizado
              </el-button>
            </div>
          </el-col>
        </el-row>
      </div>
      <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
      <!-- Form Add Document to Archive-->
      <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible2">
        <el-form :model="digital" label-width="220px" size="small" enable='true' :rules="rules" ref="documentForm">
          <el-form-item label="Numero del documento">
            <el-date-picker type="date" placeholder="seleccione una fecha" v-model="digital.fecha" format="yyyy-MM-DD"
              style="width: 100%"></el-date-picker>
          </el-form-item>
          <el-form-item label="glosa o descripcion">
            <el-input type="textarea" v-model="digital.referencia" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item>
            <el-upload ref="upload" action="/api/storeDigitalDocumentSolvency" :auto-upload="false"
              :file-list="digitalDocument" :multiple="false" :limit="1" :data="digital" accept=".pdf"
              :headers="requestHeaders" :on-success="handleSuccessBoucher" :on-remove="test">
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
          <el-button type="success" size="small" @click="storeDigitalDocumentSolvency('documentForm')">guardar archivo
            digitalizado</el-button>
          <el-button type="danger" size="small" @click="dialogFormVisible2 = false">Cerrar</el-button>
        </span>
      </el-dialog>
    </el-card>
  </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
  components: {
    information,
  },
  data() {
    return {
      requestHeaders: {
        "X-CSRF-TOKEN": window.axios.defaults.headers.common["X-CSRF-TOKEN"],
        Authorization: "Bearer " + this.$store.state.token,
      },
      user: this.$store.state.user,
      id: this.$route.params.id,
      isVisible: false,           //componente campo visible
      tag: '',                    //componente que informacion desea traer
      flag: '',                   //deudor, responsable, categoria programatica
      dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
      dialogFormVisible2: false,   //hace visible el formulario de cosas adeudadas
      digitales: [],                //deudores
      digitalDocument: [],      
      debtors: [],                //deudores
      creditors: [],              //acreedores
      debt: {},                   //un solo deudor
      debtorDocument: {},         //documento de deuda
      creditorDocument: {},         //documento de deuda
      digital: {
        id: '',
        fecha: '',
        referencia: '',
      },
      manager: {},                //responsable (director de carrera, jefe de division)
      prg: {},                    //categoria programatica
      numero: 0,
      rules: {
        id: [
          { required: true, message: 'Debe agregar un numero', trigger: 'blur' },
          { type: 'number', message: 'Lo agregado no es numero', trigger: 'blur' }
        ],
        referencia: [
          { required: true, message: 'Debe agregar texto', trigger: 'blur' },
          { min: 5, max: 100, message: 'El texto debe contener minimo 5 letras', trigger: 'blur' }
        ]
      },
    };
  },
  mounted() {
    this.getDocumentRegDetails();
  },
  methods: {
    test() {

    },
    async getDocumentRegDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDocumentRegDetails", {
          id: app.id,
          typed: 'R',
        });
        app.debtorDocument = response.data.document[0];   //documento
        app.debtors = response.data.documentDetails;      //detalle del documento
        app.debt = response.data.documentDetails[0];      //la primera fila del deudor
        app.digitales = response.data.documentDigital;
        console.log(app.debtorDocument);
        console.log(app.debtors);
        console.log(app.debt);
        app.digital.id = app.debtorDocument.id_documento;
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * S2. Guardar la informacion de un nuevo documento de deuda.
    async storeDebtorDocument() {
      var app = this;
      try {
        let response = await axios.post("/api/storeCreditorDocument", {
          usuario: app.user,
          documento: app.debtorDocument,
          responsable: app.manager,
          deudores: app.debtors,
          acredores: app.creditors,
          documento2: app.creditorDocument,
          marker: "registrar",
        });
        app.numero = response.data;
        this.$confirm('Cuenta con la documentacion para liberar la deuda?', 'Proceso de Verificacion', {
          confirmButtonText: 'Continuar',
          cancelButtonText: 'Cancelar',
          type: 'success'
        }).then(() => {
          /*pasa directamente al editar*/
          this.$router.push({
            name: "editcreditor",
            params: {
              id: response.data,
            },
          });

        }).catch(() => {
          /*pasa directamente a la lista de deudas*/
          this.$router.push({ name: "creditsDocument" });
        });

      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    noDebtorDocument() {
      this.$router.push("/api");
    },

    initSearchPrg() {
      this.isVisible = true;
      this.tag = 'categoria';
      this.flag = 'categoria';
    },

    initSearchDebtor() {
      this.isVisible = true;
      this.tag = 'persona';
      this.flag = 'deudor';
    },
    initSearchManager() {
      this.isVisible = true;
      this.tag = 'persona';
      this.flag = 'responsable';
    },

    updateIsVisible(visible, data) {
      this.isVisible = visible;
      this.data = data;
      console.log(this.isVisible + " " + this.data);
      if (data != null) {
        switch (this.flag) {
          case 'categoria':
            this.prg = data;
            break;
          case 'deudor':
            this.debtors.push(data);
            break;
          case 'responsable':
            this.manager = data;
            break;
          default:
            break;
        }
      }
    },

    /* remueve de la lista de deudores */
    initAddRegularDebtors(index, row) {
      let temporal = row;
      console.log(temporal);

      let existe = this.creditors.some(item => item.ci_per === temporal.ci_per);

      if (!existe) {
        this.creditors.push(temporal);
        console.log("Fila agregada");
      } else {
        alert("No puede agregar dos veces a la misma persona");
      }
    },

    //  * Inicia la edicion de un documento
    initEditDocumentOfArchive(idx, row) {
      this.document = row;
      console.log("editar:  ");
      console.log(this.document);
      this.stateStore = "añadir";
      this.dialogFormVisible2 = true;
    },

    handleSuccessBoucher(response, file, fileList) {
      this.$alert('Gracias, acaba de subir el documento digital ' + file.name + ' satifactoriamente.', 'confirmacion', {
        confirmButtonText: 'bueno',
      });
      this.digitalDocument = [];
      console.log(response, file, fileList);
      this.fileList = fileList;
    },

    //  * EF3. Guarda los documentos digitalizados
    storeDigitalDocumentSolvency(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          var app = this;
          this.$refs.upload.submit();
          app.dialogFormVisible2 = false;
          app.getDocumentDetails();
          app.resetForm(formName);
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },

    /* remueve de la lista de deudores */
    initRemoveDebtors(idx, row) {
      let app = this;
      console.log(row);
      axios({
        url: "/api/getDigitalSolvencyDocument/",
        params: {
          id: row.id,
        },
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        let pdfData = response.data;
        console.log(response);
        let blob = new Blob([pdfData], { type: 'application/pdf' });
        let url = URL.createObjectURL(blob);
        window.open(url);
      });
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
