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
              <p>datos del documento  de regularizacion<</p>
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
              <p>deudores</p>
              <el-table :data="debtors" style="width: 100%" size="small">
                <el-table-column prop="ci_per" label="ci" width="120"></el-table-column>
                <el-table-column prop="des_per" label="apellidos y nombres" width="220"></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button @click="initAddRegularDebtors(scope.$index, scope.row)" type="success" plain
                      size="small">Agregar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <p>documentacion</p>
              <el-table :data="digital" style="width: 100%" size="small">
                <el-table-column prop="des_per" label="referencia" width="220"></el-table-column>
              </el-table>
              <p></p>
            </div>
          </el-col>
        </el-row>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos del documento de regularizacion</p>
              <el-form ref="form" :model="creditorDocument" label-width="120px" size="small">
                <el-form-item label="responsable" prop="resp">
                  <el-input placeholder="" v-model="manager.details" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchManager">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="fecha" prop="fecha">
                  <el-date-picker type="date" placeholder="seleccione una fecha" v-model="creditorDocument.fecha"
                    style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item label="referencia" prop="referencia">
                  <el-input type="textarea" autosize placeholder="Ingrese una referencia"
                    v-model="creditorDocument.detalle">
                  </el-input>
                </el-form-item>
              </el-form>
            </div>
            <p></p>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>acreedores</p>
              <el-table :data="creditors" style="width: 100%" size="small">
                <el-table-column prop="ci_per" label="ci" width="120"></el-table-column>
                <el-table-column prop="des_per" label="apellidos y nombres" width="220"></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button @click="initRemoveCreditors(scope.$index, scope.row)" type="primary" plain
                      size="small">Quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
            </div>
          </el-col>
        </el-row>
        <el-row>
          <el-button @click="storeDebtorDocument" type="primary" size="small">guardar informacion
          </el-button>
        </el-row>
      </div>
      <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
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
      user: this.$store.state.user,
      id: this.$route.params.id,
      isVisible: false,           //componente campo visible
      tag: '',                    //componente que informacion desea traer
      flag: '',                   //deudor, responsable, categoria programatica
      dialogFormVisible: false,   //hace visible el formulario de cosas adeudadas
      digital: [],                //deudores
      debtors: [],                //deudores
      creditors: [],              //acreedores
      debt: {},                   //un solo deudor
      debtorDocument: {},         //documento de deuda
      creditorDocument: {},         //documento de deuda

      manager: {},                //responsable (director de carrera, jefe de division)
      prg: {},                    //categoria programatica
      numero: 0,
    };
  },
  mounted() {
    this.getDocumentDetails();
  },
  methods: {
    test() {

    },
    async getDocumentDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDocumentDetails", {
          id: app.id,
          typed: 'D',
        });
        app.debtorDocument = response.data.document[0];   //documento
        app.debtors = response.data.documentDetails;      //detalle del documento
        app.debt = response.data.documentDetails[0];      //la primera fila del deudor
        app.digital = response.data.documentDigital;
        console.log(app.debtorDocument);
        console.log(app.debtors);
        console.log(app.debt);

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
            name: "editregularize",
            params: {
              id: response.data,
            },
          });

        }).catch(() => {
          /*pasa directamente a la lista de deudas*/
          this.$router.push({ name: "creditors" });
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

    /* remueve de la lista de deudores */
    initRemoveCreditors(index, row) {
      this.creditors.splice(index, 1);
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
