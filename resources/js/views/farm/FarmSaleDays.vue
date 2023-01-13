<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>diario de venta de productos de la granja universitaria</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddFarmSaleDay">nuevo dia de venta de productos</el-button>
      </div>
      <div>
        <el-table v-loading="loading" :data="dataSaleDays" style="width: 100%" size="medium">
          <el-table-column prop="id_dia" label="dia" :min-width="100">            
            <el-table-column prop="fec_tra" label="fecha" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
            <template slot-scope="scope">
              <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa" :min-width="450"></el-table-column>
          <el-table-column prop="com_ini" label="comprobante" :min-width="100"></el-table-column>
          <el-table-column prop="com_fin" label="comprobante" :min-width="100"></el-table-column>
          <el-table-column align="right" :min-width="320">
            <template slot-scope="scope">
              <el-button :disabled="dataSaleDays[scope.$index].estado == 'V'" @click="initCustomerSaleDetail(scope.$index, scope.row)"
                type="warning" size="small" plain>realizar venta</el-button>
              <el-button @click="initDetailStudents(scope.$index, scope.row)" type="primary" size="small" plain>detalle de
                la venta</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getFarmSaleDays"></el-pagination>
      </div>
    </el-card>
  </div>
</template>
  
<script>
export default {
  name: "Dias",
  data() {
    return {
      user: this.$store.state.user,
      dataSaleDays: [],
      pagination: {
        page: 1,
      },
      loading: true,
      writtenTextParameter: '',
    };
  },
  mounted() {
    this.getFarmSaleDays();
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    async getFarmSaleDays(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getFarmSaleDays", {
          gestion: app.user.gestion,
          page: page,
        });
        app.dataSaleDays = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(app.dataSaleDays);
        app.loading = false;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    initAddFarmSaleDay() {
      this.$router.push({
        name: "addfarmsaleday",
      });
    },
    initCustomerSaleDetail(index, row){
      console.log(index, row);
      let id_dia = row.id;
      this.$router.push({
        name: "storecustomersaledetail",
        params: {
          id: id_dia,
        },
      });
    }
  }
};
</script>
  
<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
  