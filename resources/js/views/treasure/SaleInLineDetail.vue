<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>ventas en linea del dia: {{ id }}</span>
        <el-button size="small" type="default" icon="el-icon-plus" @click="initAllVerifyRequest"
          style="text-align: right; float: right">
          verificar todas las solicitudes de la venta de valores en linea</el-button>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="requests" style="width: 100%">
          <el-table-column prop="tipo" label="tipo"></el-table-column>
          <el-table-column prop="idc" label="id"></el-table-column>
          <el-table-column prop="ci_per" label="ci"></el-table-column>
          <el-table-column prop="des_per" label="descripcion" width="280"></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button @click="initVerifyRequestSaleInLine(scope.$index, scope.row)" type="warning" size="mini" plain>
                Verificar
              </el-button>
              <el-button @click="initEditRequestSaleInLine(scope.$index, scope.row)" type="success" plain size="mini">
                Imprimir</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total" @current-change="getSaleInLineDetail">
        </el-pagination>
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
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    let app = this;
    this.getSaleInLineDetail(app.pagination.page);
  },
  methods: {
    test() {
      alert("en proceso de desarrollo");
    },
    //  * T3. Obtener la lista de solicitudes bancarias en estado solicitado.
    //  * {year: año de ingreso}
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
    async initAllVerifyRequest(){


    }
  },
};
</script>
  
<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
  