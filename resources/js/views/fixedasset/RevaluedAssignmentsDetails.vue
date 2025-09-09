<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span><strong>indicadores para el revaluo del activos fijos</strong></span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataFixedAsset" label-width="180px" size="small">
                <p>datos generales del activo</p>
                <el-form-item label="fecha de adquisicion" prop="fecha">
                  {{ dataFixedAsset.fecha_adquisicion }}
                </el-form-item>
                <el-form-item label="codigo" prop="fecha">
                  {{ dataFixedAsset.codigo }}
                </el-form-item>
                <el-form-item label="descripcion" prop="fecha">
                  {{ dataFixedAsset.descripcion }}
                </el-form-item>
                <el-form-item label="tipo de adquisicion">
                  {{ dataFixedAsset.tipo_adq }}
                </el-form-item>
                <el-form-item label="valor neto">
                  {{ dataFixedAsset.valor_neto }}
                </el-form-item>
              </el-form>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataTechnicals" label-width="350px" size="small">
                <p>factores tecnicos</p>
                <p>tabla de factores y coeficientes</p>
                <el-form-item label="perdida natural de unidad de mercado">
                  <el-input-number v-model="dataTechnicals.perdida" :min="0" :step="0.1" :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="funcionalidad">
                  <el-input-number v-model="dataTechnicals.funcionalidad" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="obsolescencia tecnologica">
                  <el-input-number v-model="dataTechnicals.obsolescencia" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="nivel de conservacion">
                  <el-input-number v-model="dataTechnicals.conservacion" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="frecuencia de uso">
                  <el-input-number v-model="dataTechnicals.uso" :min="0" :step="0.1" :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="mantenimiento">
                  <el-input-number v-model="dataTechnicals.mantenimiento" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="coeficiente conforme a factores aplicados">
                  <el-input placeholder="Please input" v-model="dataTechnicals.resultado"></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>determinacion segun revaluo</p>
              <p>cotizaciones</p>
              <el-table :data="dataQuotes" style="width: 100%" size="small">
                <el-table-column prop="id" label="no." width="90"></el-table-column>
                <el-table-column prop="cantidad" label="cantidad" width="220"></el-table-column>
                <el-table-column align="right" width="200">
                  <template slot-scope="scope">
                    <el-button @click="initEditDataQuotes(scope.$index, scope.row)" type="primary"
                      size="mini">Editar</el-button>
                    <el-button @click="initRemoveDataQuotes(scope.$index, scope.row)" type="primary"
                      size="mini">Quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initAddDataQuote" type="primary" size="small">agregar
              </el-button>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataResult" label-width="350px" size="small">
                <p>determinacion segun revaluo</p>
                <p>resultados</p>
                <el-form-item label="estado tecnico">
                  <el-input placeholder="" v-model="dataResult.estado"></el-input>
                </el-form-item>
                <el-form-item label="años de vida util">
                  <el-input placeholder="" v-model="dataResult.vida"></el-input>
                </el-form-item>
                <el-form-item label="valor residual">
                  <el-input placeholder="" v-model="dataResult.valor_residual"></el-input>
                </el-form-item>
                <el-form-item label="valor segun revaluo">
                  <el-input placeholder="" v-model="dataResult.valor_revaluo"></el-input>
                </el-form-item>
                <el-form-item label="saldo por revalorizar">
                  <el-input placeholder="" v-model="dataResult.valor_saldo"></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <br>
        <el-button @click="initStoreDataRevaluedDetails" type="primary" size="small">calcular datos del revaluo
        </el-button>
        <p></p>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataAuditor" label-width="350px" size="small">
                <p>evaluacion del auditor</p>
                <el-form-item label="actualizacion">
                  <el-input type="textarea" placeholder="ingrese aca su informacion"
                    v-model="dataAuditor.actualizacion"></el-input>
                </el-form-item>
                <el-form-item label="seguridad">
                  <el-input type="textarea" placeholder="ingrese aca su informacion"
                    v-model="dataAuditor.seguridad"></el-input>
                </el-form-item>
                <el-form-item label="proteccion patrimonial">
                  <el-input type="textarea" placeholder="ingrese aca su informacion"
                    v-model="dataAuditor.proteccion"></el-input>
                </el-form-item>
                <el-form-item label="capacitacion">
                  <el-input type="textarea" placeholder="ingrese aca su informacion"
                    v-model="dataAuditor.capacitacion"></el-input>
                </el-form-item>
                <p></p>
                <el-button @click="initAddDataAuditor" type="primary" size="small">guardar evaluacion
                </el-button>
              </el-form>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>imagenes actuales del activo</p>
              <el-table :data="imageDocuments" border style="width: 100%" size="small">
                <el-table-column prop="descripcion" label="descripcion" width="250">
                </el-table-column>
                <el-table-column align="right-center" label="operaciones" width="250" fixed="right">
                  <template slot-scope="scope">
                    <el-button :disabled="scope.row.guardado === true" type="primary" size="mini"
                      @click="getDigitalDocumentById(scope.$index, scope.row)">ver
                      documento</el-button>
                    <el-button :disabled="scope.row.guardado === true" type="danger" size="mini"
                      @click="DeleteDocumentById(scope.$index, scope.row)">quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <div>
                <el-button type="success" @click="initAddDocument" size="small">agregar imagen</el-button>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </el-card>

    <!-- Form Add Document to Archive-->
    <el-dialog title="cotizaciones" :visible.sync="dialogFormVisible">
      <el-form :model="dataQuote" label-width="220px" size="small">
        <el-form-item label="cantidad">
          <el-input v-model="dataQuote.cantidad" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="small" plain @click="initStoreDataQuotes">agregar</el-button>
        <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
      </span>
    </el-dialog>
    <!-- Form Add Document to Archive-->

    <!-- Form Add Document to Archive Imagen-->
    <el-dialog title="detalle de la imagen" :visible.sync="dialogImageVisible">
      <el-form :model="document" label-width="220px" size="small" ref="documentForm">
        <el-form-item label="descripcion" prop="descripcion">
          <el-input type="textarea" v-model="document.descripcion" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item>
          <el-upload ref="upload" action="/api/storeDigitalDocument" :auto-upload="false" :file-list="imageDocuments"
            :multiple="false" :limit="1" :data="document" accept=".jpeg" :headers="requestHeaders"
            :on-success="handleSuccessBoucher" :on-remove="test">
            <!---->
            <p></p>
            <el-button slot="trigger" size="small" type="primary">subir archivo digitalizado</el-button>
            <div slot="tip" class="el-upload__tip">
              Solo puede subir imagenes
            </div>
          </el-upload>
        </el-form-item>
        <!--
          -->
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="mini" @click="storeDigitalDocument('documentForm')">Confirmar</el-button>
        <el-button type="danger" size="mini" plain @click="dialogFormVisible = false">Cancelar</el-button>
      </span>
    </el-dialog>
    <!-- Form Add Document to Archive-->

  </div>
</template>

<script>
import { forEach } from "lodash";
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
      imageDocument: [],
      document: { id:0, descripcion: '' },
      imageDocuments: [],
      user: this.$store.state.user,
      id: this.$route.params.id,
      dialogImageVisible: false,
      dialogFormVisible: false,
      dataQuotes: [],
      id_revalued: 0,
      dataTechnicals: {
        perdida: 0,
        funcionalidad: 0,
        obsolescencia: 0,
        conservacion: 0,
        uso: 0,
        mantenimiento: 0,
        resultado: 0,
      },
      dataResult: {
        estado: '',
        vida: 0,
        valor_residual: 0,
        valor_revaluo: 0,
        valor_saldo: 0,
      },
      dataFixedAsset: {
        id: 0,
        idx: 0,
        descripcion: '',
        medida: '',
        cantidad: 1,
        importe: 0,
        fecha_adquisicion: '',
        id_contable: '',
        des_contable: '',
        id_presupuesto: '',
        des_presupuesto: '',
        estado: '',
        cod_prg: '',
        des_prg: '',
        ci_resp: '',
        id_asignaciones: '',
        adicional: [],
      },
      dataQuoteState: 'registrar',
      dataQuote: {
        id: 0,
        cantidad: 0,
      },
      dataAuditor: {
        actualizacion: '',
        seguridad: '',
        proteccion: '',
        capacitacion: '',
      }
    };
  },
  mounted() {
    this.getDataRevaluedDetails();
    console.log(this.dataFixedAsset);
  },

  methods: {

    test() { },

    initAddDataAuditor() {
      console.log(this.dataAuditor);
      console.log(this.id_revalued);
      var app = this;
      try {
        let response = axios
          .post("/api/storeDataAuditorDetails", {
            id: this.id_revalued,
            dataAuditor: this.dataAuditor,
            user: app.user,
            marker: 'registrar',
          });
        app.$alert("Se ha registrado correctamente los cambios del auditor", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        this.getDataRevaluedDetails();
      } catch (error) {
        app.$alert(error, 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },

    handleSuccessBoucher(response, file, fileList) {
      this.$alert('Gracias, acaba de subir la imagen ' + file.name + ' satifactoriamente.', 'confirmacion', {
        confirmButtonText: 'bueno',
      });
      this.imageDocument = [];
      console.log(response, file, fileList);
      this.fileList = fileList;
    },

    //  * AF27. Obtiene la lista de imagenes que pertenecen a un activo
    async getImagenByRevaluedAssignment() {
      let app = this;
      
      try {
        let response = await axios.post("/api/getImagenByRevaluedAssignment", {
          id: app.id,
          year: app.user.gestion,
        });
        app.documents = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },


    //  * EF3. Guarda los documentos digitalizados
    storeDigitalDocument(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          var app = this;
          app.document.id = app.id;
          this.$refs.upload.submit();
          app.dialogFormVisible = false;
          /*
          app.getImagenByRevaluedAssignment();*/
          app.resetForm(formName);
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },



    getDigitalDocumentById() { },
    DeleteDocumentById() { },
    initAddDocument() {
      this.dialogImageVisible = true;
    },
    //  *  AF23. Obtiene un activo fijo de la lista del documento de revaluo
    async getDataRevaluedDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDataRevaluedDetails", {
          id: app.id,
          year: app.user.gestion,
        });
        console.log(response.data);
        app.dataFixedAsset = response.data.dataFixedAsset[0];
        if (response.data.dataRevalued.length > 0) {
          app.dataRevalued = response.data.dataRevalued[0];
          console.log("datos revaluo");
          console.log(app.dataRevalued);
          app.id_revalued = app.dataRevalued.id;
          app.dataTechnicals.perdida = app.dataRevalued.perdida;
          app.dataTechnicals.funcionalidad = app.dataRevalued.funcionalidad;
          app.dataTechnicals.obsolescencia = app.dataRevalued.obsolescencia;
          app.dataTechnicals.conservacion = app.dataRevalued.conservacion;
          app.dataTechnicals.uso = app.dataRevalued.uso;
          app.dataTechnicals.mantenimiento = app.dataRevalued.mantenimiento;
          app.dataTechnicals.resultado = app.dataRevalued.resultado;
          app.dataResult.estado = app.dataRevalued.estado;
          app.dataResult.vida = app.dataRevalued.vida;
          app.dataResult.valor_residual = app.dataRevalued.valor_residual;
          app.dataResult.valor_revaluo = app.dataRevalued.valor_revaluo;
          app.dataResult.valor_saldo = app.dataRevalued.valor_saldo;
          app.dataQuotes = app.dataRevalued.cotizaciones;
          app.dataAuditor.actualizacion = app.dataRevalued.actualizacion;
          app.dataAuditor.seguridad = app.dataRevalued.seguridad;
          app.dataAuditor.proteccion = app.dataRevalued.proteccion;
          app.dataAuditor.capacitacion = app.dataRevalued.capacitacion;
          app.marker = 'editar';
        }
        else {
          app.marker = 'registrar';
        }
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  *  AF24. Guarda la informacion necesaria para los datos de revaluo del activos fijos dentro de un documento de revaluo       
    async initStoreDataRevaluedDetails() {
      console.log(this.dataFixedAsset);
      console.log(this.id_revalued);
      var app = this;
      try {
        let response = axios
          .post("/api/storeDataThecnicalRevaluedDetails", {
            id_revalued: this.id_revalued,
            dataFixedAsset: this.dataFixedAsset,
            dataTechnicals: this.dataTechnicals,
            dataResult: this.dataResult,
            dataQuotes: this.dataQuotes,
            user: app.user,
            marker: app.marker,
          });
        app.$alert("Se ha registrado correctamente los cambios al revaluo", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        this.getDataRevaluedDetails();
      } catch (error) {
        app.$alert(error, 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },



    /* cotizaciones */
    initAddDataQuote() {
      this.dialogFormVisible = true;
      this.dataQuoteState = 'registrar';
    },

    initStoreDataQuotes() {
      if (this.dataQuoteState == 'registrar') {
        let variable = this.dataQuote;
        this.dataQuotes.push(variable);
        let idn = 1;
        this.dataQuotes.forEach(element => {
          element.id = idn;
          idn = idn + 1;
        });
      } else {

      }
      console.log(this.dataQuotes);
      this.dialogFormVisible = false;
      this.dataQuote = {
        id: 0,
        cantidad: 0,
      };
    },

    initRemoveDataQuotes(idx, row) {
      this.dataQuotes.splice(idx, 1);
      let idn = 1;
      this.dataQuotes.forEach(element => {
        element.id = idn;
        idn = idn + 1;
      });
      console.log(this.dataQuotes);
    },

    initEditDataQuotes(idx, row) {
      this.dataQuote = row;
      this.dataQuoteState = 'editar';
      this.dialogFormVisible = true;
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