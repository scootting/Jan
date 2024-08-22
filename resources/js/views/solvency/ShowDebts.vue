<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>DETALLE DEL DOCUMENTO DE DEUDA</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos de la solicitud</p>
              <el-form ref="form" :model="debtorDocument" label-width="160px" size="small">
                <el-form-item label="numero" prop="idc">
                  {{ debtorDocument.idc }}
                </el-form-item>
                <el-form-item label="fecha">
                  {{ debtorDocument.fecha }}
                </el-form-item>
                <el-form-item label="responsable">
                  {{ debtorDocument.des_resp }}
                </el-form-item>
                <el-form-item label="elaborado">
                  {{ debtorDocument.des_elab }}
                </el-form-item>
              </el-form>
              <el-form ref="form" :model="debt" label-width="160px" size="small">
                <el-form-item label="detalle">
                  {{ debt.detalle }}
                </el-form-item>
                <el-form-item label="estado">
                  <div v-if="debt.estado2 !== 'Regularizado'">
                    <el-tag type="danger" effect="dark">{{ debt.estado2 }}</el-tag>
                  </div>
                  <div v-else>
                    <el-tag type="success" effect="dark">{{ debt.estado2 }}</el-tag>
                  </div>
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
                </el-table-column>
              </el-table>
            </div>
          </el-col>
          <br>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>documentacion</p>
              <el-table :data="digital" style="width: 100%" size="small">
                <el-table-column prop="des_per" label="referencia" width="220"></el-table-column>
                <el-table-column align="right">
                  <template slot-scope="scope">
                    <el-button @click="initRemoveDebtors(scope.$index, scope.row)" type="primary" plain
                      size="small">Visualizar</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-col>
        </el-row>
        <el-row>
        </el-row>
      </div>

    </el-card>
  </div>
</template>

<script>

export default {
  data() {
    return {
      user: this.$store.state.user,
      id: this.$route.params.id,
      debtors: [],                //deudores
      digital: [],                //deudores
      debt: {},                   //un solo deudor
      debtorDocument: {},         //documento de deuda
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
        app.debtorDocument = response.data.document[0];
        app.debtors = response.data.documentDetails;
        app.debt = response.data.documentDetails[0];
        app.digital = response.data.documentDigital;
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
