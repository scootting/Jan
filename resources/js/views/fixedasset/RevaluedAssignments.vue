<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span><strong>documento de revaluo de activos fijos no. {{ dataAssignment.idc }}</strong></span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="24">
            <div class="grid-content bg-purple">
              <p>datos generales del documento</p>
              <el-form ref="form" :model="dataAssignment" label-width="380px" size="small">
                <el-form-item label="fecha">
                  {{ dataAssignment.fecha }}
                </el-form-item>
                <el-form-item label="unidad academica o administrativa">
                  {{ dataAssignment.des_prg }}
                </el-form-item>
                <el-form-item label="responsable">
                  {{ dataAssignment.des_resp }}
                </el-form-item>
                <el-form-item label="estado">
                  <el-tag effect="dark" type="info" size="medium">{{ dataAssignment.estado }}</el-tag>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataFixedAsset" label-width="180px" size="small">
                <p>datos generales del activo
                </p>
                <el-form-item label="">
                  <el-button @click="dialogFormVisibleActive = true" type="info" size="small">buscar activo fijo
                  </el-button>
                </el-form-item>
                <el-form-item label="fecha de adquisicion" prop="fecha">
                  {{ dataFixedAsset.fecha_adquisicion }}
                </el-form-item>
                <el-form-item label="codigo" prop="fecha">
                  {{ dataFixedAsset.codigo }}
                </el-form-item>
                <el-form-item label="descripcion" prop="fecha">
                  {{ dataFixedAsset.descripcion }}
                </el-form-item>
                <el-form-item label="valor neto">
                  {{ dataFixedAsset.valor_neto }}
                </el-form-item>
                <el-form-item label="tipo de adquisicion">
                  {{ dataFixedAsset.tipo_adq }}
                </el-form-item>
              </el-form>
            </div>
          </el-col>

          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="fixedAsset" label-width="180px" size="small">
                <p>datos generales del activo</p>
                <el-form-item label="fecha de registro" prop="fecha">
                  <el-date-picker type="date" placeholder="seleccione una fecha" v-model="fixedAsset.fecha_adquisicion"
                    style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item label="partida contable">
                  <el-select v-model="fixedAsset.id_contable" value-key="id_contable" size="small"
                    placeholder="seleccione la partida contable" @change="OnchangeContable">
                    <el-option v-for="item in dataAccountingItem" :key="item.con_cod" :label="item.con_des"
                      :value="item.con_cod">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="partida presupuestaria">
                  <el-select v-model="fixedAsset.id_presupuesto" value-key="id_presupuesto" size="small"
                    placeholder="seleccione la partida presupuestaria" @change="OnchangePresupuesto">
                    <el-option v-for="item in dataBudgetItem" :key="item.act_par_cod" :label="item.par_des"
                      :value="item.act_par_cod">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="unidad de medida">
                  <el-select v-model="fixedAsset.medida" value-key="medida" size="small"
                    placeholder="seleccione el tipo de medida" @change="OnchangeMedida">
                    <el-option v-for="item in dataMeasurement" :key="item.uni_des_cor" :label="item.uni_des_det"
                      :value="item.uni_des_cor">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="cantidad">
                  <el-input v-model="fixedAsset.cantidad" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="importe">
                  <el-input v-model="fixedAsset.importe" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="descripcion general">
                  <el-input type="textarea" placeholder="Ingrese una descripcion general"
                    v-model="fixedAsset.descripcion">
                  </el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>informacion adicional del activo fijo</p>
              <el-table :data="fixedAsset.adicional" style="width: 100%" size="small">
                <el-table-column prop="cantidad" label="cantidad" width="90"></el-table-column>
                <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
                <el-table-column align="right" width="200">
                  <template slot-scope="scope">
                    <el-button @click="initEditAditionalDescription(scope.$index, scope.row)" type="primary"
                      size="mini">Editar</el-button>
                    <el-button @click="initRemoveDetails(scope.$index, scope.row)" type="primary"
                      size="mini">Quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initAddAditionalDescription" type="primary" size="small">agregar
              </el-button>
            </div>
          </el-col>
        </el-row>
        <br>
        <el-button @click="initAddDataFixed" type="success" size="small">agregar a la lista de activos
        </el-button>
        <p></p>
        <br>
      </div>
      <el-col :span="24">
        <div class="grid-content bg-purple">
          <p>lista de activos</p>
          <el-table :data="dataAssignmentDetails" style="width: 100%" size="small">
            <el-table-column prop="fecha_adquisicion" label="fecha" width="90"></el-table-column>
            <el-table-column prop="descripcion" label="descripcion" width="220"></el-table-column>
            <el-table-column prop="des_contable" label="partida" width="220"></el-table-column>
            <el-table-column prop="cantidad" label="cantidad" width="220"></el-table-column>
            <el-table-column prop="importe" label="importe" width="220"></el-table-column>
            <el-table-column align="right" width="200">
              <template slot-scope="scope">
                <el-button @click="initEditDataFixed(scope.$index, scope.row)" type="primary"
                  size="mini">Editar</el-button>
                <el-button @click="initRemoveDataFixed(scope.$index, scope.row)" type="primary"
                  size="mini">Quitar</el-button>
              </template>
            </el-table-column>
          </el-table>
          <p></p>
          <el-button @click="initStoreDataAssignmentDetails" type="primary" size="small">guardar cambios
          </el-button>
          <el-button @click="initVerifyDataAssignmentDetails" type="info" size="small"
            :disabled="verifyVisible == false">verificar
            documento
          </el-button>
          <el-button @click="initPrintPurchaseAssignment" type="info" size="small"
            :disabled="verifyVisible == false">imprimir
            documento
          </el-button>
        </div>
      </el-col>
    </el-card>

    <!-- Busqueda de activos fijos en la tabla de actualizacion y depreciacion de activos fijos -->
    <el-dialog title="buscar activos fijos" :visible.sync="dialogFormVisibleActive">
      <el-input placeholder="ingrese una descripcion" v-model="description" class="input-with-select" size="small">
        <el-button slot="append" icon="el-icon-search" @click="getDataFixedAssetDetails">BUSCAR</el-button>
      </el-input>
      <p></p>
      <el-table :data="dataFixedAssetRevalued" style="width: 100%" height="400">
        <el-table-column label="codigo" width="100px">
          <template slot-scope="scope">
            <div slot="reference" class="name-wrapper">
              <el-tag size="medium">{{ scope.row.codigo }}</el-tag>
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="descripcion" label="descripcion" width="600px"></el-table-column>
        <el-table-column label="" width="120px" fixed="right">
          <template slot-scope="scope">
            <el-button @click="AddDataFixedAssetRevalued(scope.$index, scope.row)" size="small">Agregar</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>
    <!-- Fin Busqueda de activos fijos en la tabla de actualizacion y depreciacion de activos fijos -->

    <!-- Form Add Document to Archive-->
    <el-dialog title="accesorios" :visible.sync="dialogFormVisible">
      <el-form :model="dataAditional" label-width="220px" size="small">
        <el-form-item label="cantidad">
          <el-input v-model="dataAditional.cantidad" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="descripcion">
          <el-input type="textarea" v-model="dataAditional.descripcion" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="small" plain @click="initStoreAditionalDescription">agregar</el-button>
        <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
      </span>
    </el-dialog>
    <!-- Form Add Document to Archive-->

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
      user: this.$store.state.user,
      id: this.$route.params.id,

      // variables para buscar activos.
      dialogFormVisibleActive: false,
      dataFixedAssetRevalued: [],
      description: '',
      dataFixedAsset: {},

      marker: 'registrar',
      dialogFormVisible: false,

      verifyVisible: false,
      dataAssignment: {},
      dataBudgetItem: [],         //partida presupuestaria
      dataAccountingItem: [],     //partida contable
      dataMeasurement: [],        //unidades de medida

      dataAssignmentDetails: [],
      fixedAsset: {
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
      dataAditional: {
        cantidad: 0,
        descripcion: '',
      },
    };
  },
  mounted() {
    console.log(this.user);
    this.getDataAssignmentDetails();
  },

  methods: {

    //  *  AF20. Obtiene un activo fijo de la lista de actualizaciones y depreciaciones
    async getDataFixedAssetDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDataFixedAssetDetails", {
          description: app.description,
          year: app.user.gestion,
        });
        app.dataFixedAssetRevalued = response.data.dataFixedAssetRevalued;
        console.log(app.dataFixedAssetRevalued);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    AddDataFixedAssetRevalued(idx, row) {
      this.dialogFormVisibleActive = false;
      this.description = '';
      this.dataFixedAsset = row;
      this.fixedAsset.id_contable = row.id_contable;
      this.fixedAsset.des_contable = row.des_contable;

      console.log(this.dataFixedAsset);
      console.log(this.fixedAsset);
      this.dataFixedAssetRevalued = [];
    },
    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    async getDataAssignmentDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDataAssignmentDetails", {
          id: app.id,
          year: app.user.gestion,
        });
        app.dataAssignment = response.data.dataAssignment[0]; // todo del documento de entrega
        console.log(response);
        app.dataBudgetItem = response.data.dataBudgetItem; // partida presupuestaria    
        app.dataAccountingItem = response.data.dataAccountingItem; // codigo contable
        app.dataMeasurement = response.data.dataMeasurement; // tipo de activo
        app.data = response.data.dataAssignmentDetails;
        app.dataAssignmentDetails = response.data.dataAssignmentsDetails;
        if (app.dataAssignmentDetails.length != 0) {
          app.verifyVisible = "true"
        }
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    OnchangeContable(idx) {
      let resultado = this.dataAccountingItem.find(tipo => tipo.con_cod == idx);
      this.fixedAsset.id_contable = resultado.con_cod;
      this.fixedAsset.des_contable = resultado.con_des;
    },
    OnchangePresupuesto(idx) {
      let resultado = this.dataBudgetItem.find(tipo => tipo.act_par_cod == idx);
      this.fixedAsset.id_presupuesto = resultado.act_par_cod;
      this.fixedAsset.des_presupuesto = resultado.par_des;
    },
    OnchangeMedida(idx) {
      let resultado = this.dataMeasurement.find(tipo => tipo.uni_des_cor == idx);
      this.fixedAsset.medida = resultado.uni_des_det;
    },

    initAddAditionalDescription() {
      this.dialogFormVisible = true;
      this.stateStore = "añadir";
    },

    initEditAditionalDescription(idx, row) {
      this.dialogFormVisible = true;
      this.dataAditional = row;
      this.stateStore = "editar";
    },

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento       
    async initStoreDataAssignmentDetails() {
      console.log(this.fixedAsset);
      var app = this;
      try {
        let response = axios
          .post("/api/storeDataAssignmentDetails", {
            assignment: app.dataAssignment,
            assignmentDetails: app.dataAssignmentDetails,
            user: app.user,
            marker: 'registrar',
          });
        app.$alert("Se ha registrado correctamente los cambios al documento", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        this.getDataAssignmentDetails();
      } catch (error) {
        app.$alert("Se ha registrado correctamente la informacion", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };

    },

    initStoreAditionalDescription(idx) {
      this.dialogFormVisible = false;
      if (this.stateStore == "añadir") {
        let variable = this.dataAditional;
        this.fixedAsset.adicional.push(variable);
      }
      else {
        let variable = this.dataAditional;
        this.fixedAsset.adicional[idx] = variable;
      }
      this.dataAditional = {
        cantidad: 0,
        descripcion: '',
      };
    },

    initRemoveDetails(idx, row) {
      this.fixedAsset.adicional.splice(idx, 1);
    },

    initAddDataFixed() {
      let variable = this.fixedAsset;
      console.log(variable);
      this.dataAssignmentDetails.push(variable);
      this.fixedAsset = {
        idx: 0,
        codigo: '',
        codigo_anterior: '',
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
      };
    },
    initRemoveDataFixed(idx, row) {
      this.dataAssignmentDetails.splice(idx, 1);
    },
    initEditDataFixed(idx, row) {
      let variable = row;
      this.fixedAsset = variable;
    },

    initVerifyDataAssignmentDetails() {
      var app = this;
      try {
        let response = axios
          .post("/api/verifyDataAssignmentDetails", {
            assignment: app.dataAssignment,
            user: app.user,
            marker: 'verificar',
          });
        app.$alert("Se ha registrado correctamente los cambios al documento", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        this.getDataAssignmentDetails();
      } catch (error) {
        app.$alert("Se ha registrado correctamente la informacion", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },
    //  *  AF18. imprimir reporte de un documento de compra       
    initPrintPurchaseAssignment() {
      let app = this;
      axios({
        url: "/api/printPurchaseAssignment/",
        params: {
          id: app.dataAssignment.id,
          reporte: "AssignmentPurchase",
        },
        method: "GET",
        responseType: "arraybuffer",
      }).then((response) => {
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        let url = window.URL.createObjectURL(blob);
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

.el-form .el-select {
  width: 100%;
}
</style>