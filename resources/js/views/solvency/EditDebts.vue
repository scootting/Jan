<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>editar documento de deuda no.  {{ id }}</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos de la solicitud</p>
              <el-form ref="form" :model="debtorDocument" label-width="120px" size="small">
                <el-form-item label="numero" prop="idc">
                  {{ debtorDocument.idc }}
                </el-form-item>
                <el-form-item label="unidad" prop="details">
                  <el-input placeholder="" v-model="debt.des_prg" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchPrg">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="responsable" prop="des_resp">
                  <el-input placeholder="" v-model="debtorDocument.des_resp" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchManager">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="fecha" prop="fecha">
                  <el-date-picker type="date" placeholder="seleccione una fecha" v-model="debtorDocument.fecha"
                    style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item label="referencia" prop="referencia">
                  <el-input type="textarea" autosize placeholder="Ingrese una referencia"
                    v-model="debt.detalle">
                  </el-input>
                </el-form-item>
                <el-form-item>
                  <el-button @click="test" type="primary" size="small">Guardar Cambios
                  </el-button>
                </el-form-item>
                <!--
                -->
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
                    <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary" plain
                      size="small">Quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initSearchDebtor" type="primary" size="small">agregar deudor
              </el-button>
              <p>documentacion</p>
              <el-table :data="digital" style="width: 100%" size="small">
                <el-table-column prop="des_per" label="referencia" width="220"></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary" plain
                      size="small">Eliminar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initSearchDebtor" type="primary" size="small">Agregar documento digitalizado
              </el-button>
            </div>
          </el-col>
        </el-row>
      </div>
      <!--
      <el-button @click="storeDebtorDocument" type="primary" size="small">guardar cambios
      </el-button>
      -->
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
      debt: {},                   //un solo deudor
      debtorDocument: {},         //documento de deuda

      manager: {},                //responsable (director de carrera, jefe de division)
      prg: {},                    //categoria programatica
      numero: 0,
    };
  },
  mounted() {
    this.getDocumentDetails();
  },
  methods: {
    test(){

    },
    async getDocumentDetails() {
      var app = this;
      try {
        let response = await axios.post("/api/getDocumentDetails", {
          id: app.id,
          typed: 'D',
        });
        app.debtorDocument = response.data.document[0];
        app.debtors = response.data.documentDetails;
        app.debt = response.data.documentDetails[0];
        app.digital = response.data.documentDigital;
        console.log(app.debtorDocument);
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
        let response = await axios.post("/api/storeDebtorDocument", {
          usuario: app.user,
          documento: app.debtorDocument,
          deudores: app.debtors,
          responsable: app.manager,
          programa: app.prg,
          marker: "editar",
        });
        console.log(app.debtorDocument);
        console.log(app.debtors);
        console.log(app.manager);
        console.log(app.prg);
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
    initRemoveDebtors(index, row) {
      this.debtors.splice(index, 1);
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
