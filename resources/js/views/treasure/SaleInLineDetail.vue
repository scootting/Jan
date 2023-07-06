<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>ventas en linea del dia: {{ id }}</span>
        <el-button size="small" type="success" icon="el-icon-plus" @click="initAllVerifyRequest"
          style="text-align: right; float: right">
          verificar todas las solicitudes de la venta de valores en linea</el-button>
      </div>
      <br />
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-col :span="24">
            <span>solicitudes por verificar</span>
            <el-table v-loading="loading" :data="requests" style="width: 100%">
              <el-table-column label="numero" width="150">
                <template slot-scope="scope">
                  <div slot="reference" class="name-wrapper">
                    <el-tag size="medium" effect="dark">{{ scope.row.idc }}</el-tag>
                  </div>
                </template>
              </el-table-column>
              <el-table-column prop="estado" label="estado" width="150" align="center">
                <template slot-scope="scope">
                  <div slot="reference" class="name-wrapper">
                    <el-tag size="medium" effect="dark" type="danger">{{ scope.row.estado }}</el-tag>
                  </div>
                </template>
              </el-table-column>
              <el-table-column prop="ci_per" label="ci"></el-table-column>
              <el-table-column prop="des_per" label="descripcion" width="280"></el-table-column>
              <el-table-column align="right" width="220">
                <template slot-scope="scope">
                  <el-button @click="initVerifyRequestSaleInLine(scope.$index, scope.row)" type="success" size="mini"
                    plain>
                    Verificar
                  </el-button>
                  <el-button @click="initEditRequestSaleInLine(scope.$index, scope.row)" type="warning" plain size="mini">
                    Imprimir</el-button>
                </template>
              </el-table-column>
            </el-table>
            <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
              :current-page="pagination.current_page" :total="pagination.total" @current-change="getSaleInLineDetail">
            </el-pagination>
          </el-col>
        </el-row>
      </div>
      <br>
      <br>
      <div>
        <el-row :gutter="20">
          <el-col :span="24">
            <div class="grid-content bg-purple">
              <span>transacciones verificadas</span>
              <div>
                <el-table :data="transactions" border style="width: 100%" size="small">
                  <el-table-column prop="fec_tra" label="fecha" width="100" align="center">
                  </el-table-column>
                  <el-table-column prop="cod_val" label="codigo" width="250" align="center">
                    <template slot-scope="scope">
                      <el-tag size="medium">{{
                        scope.row.cod_val
                      }}</el-tag>
                    </template>
                  </el-table-column>
                  <el-table-column prop="ci_per" label="carnet" width="100" align="right">
                  </el-table-column>
                  <el-table-column prop="des_per" label="persona" width="500" align="right">
                  </el-table-column>
                  <el-table-column align="right-center" label="operaciones" width="150">
                    <template slot-scope="scope">
                      <el-button :disabled="scope.row.guardado === true" type="danger" plain size="mini"
                        @click="test()">ver desglose</el-button>
                    </template>
                  </el-table-column>
                </el-table>
              </div>
            </div>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>
  
<script>
export default {
  name: "Personas",
  data() {
    return {
      loading: true,
      id: this.$route.params.id,
      user: this.$store.state.user,
      requests: [],
      transactions:[],
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    let app = this;
    this.getSaleInLineDetail(app.pagination.page);
    this.getTransactionsByDay();

  },
  methods: {
    test() {
      alert("en proceso de desarrollo");
    },
    //  * T3. Obtener la lista de solicitudes bancarias en estado solicitado.
    //  * {year: año de ingreso}
    async getTransactionsByDay(){
      let app = this;
      try {
        let response = await axios.post("/api/getTransactionsByDay", {
          id: app.id,
        });
        app.loading = false;
        app.transactions = Object.values(response.data);
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
      }
    },

    async getSaleInLineDetail(page) {
      let app = this;
      try {
        let response = await axios.post("/api/getSaleInLineDetail", {
          user: app.user.usuario,
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        app.requests = Object.values(response.data.data);
        console.log(app.requests);
        app.pagination = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    initVerifyRequestSaleInLine(idx, row) {
      console.log(idx, row);
      let app = this;
      this.$router.push({
        name: "verifysaleinlinedetail",
        params: {
          id: app.id,
          request: row.id,
        },
      });
    },
    async initAllVerifyRequest() {


    }
  },
};
</script>
  
<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
  