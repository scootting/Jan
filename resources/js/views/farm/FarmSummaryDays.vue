<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Resumen de ventas en efectivo y al credito de la granja universitaria</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddFarmSummaryDay">Nuevo resumen de ventas</el-button>
      </div>

      <div>
        <el-table v-loading="loading" :data="dataSaleDays" style="width: 100%" size="medium">
          <el-table-column prop="fec_ini" label="fecha inicial" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_ini }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="fec_fin" label="fecha final" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_fin }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="id_dia" label="nro." :min-width="100">
            <template slot-scope="scope">
              <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa" :min-width="480"></el-table-column>
          <el-table-column align="right" :min-width="350" fixed="right">
            <template slot-scope="scope">
              <el-button :disabled="dataSaleDays[scope.$index].estado == 'V'"
                @click="initCustomerResumeSaleDetailDaysReport(scope.$index, scope.row)" type="warning" size="small" plain>imprimir
                resumen</el-button>
              <el-button @click="initSummaryReport(scope.$index, scope.row)" type="primary" size="small"
                :disabled="dataSaleDays[scope.$index].estado != 'V'" plain>cerrar resumen</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getFarmSummaryDays"></el-pagination>
      </div>
    </el-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      dataSaleDays: [],
      tip_tra: 6,
      pagination: {
        page: 1,
      },
      loading: true,
      writtenTextParameter: '',
    };
  },
  mounted() {
    this.getFarmSummaryDays(this.pagination.page);
  },
  methods: {
    test() {
    },
    //  * G25. Obtiene la lista de los dias de venta de los productos de la granja
    async getFarmSummaryDays(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getFarmSummaryDays", {
          gestion: app.user.gestion,
          transaccion: app.tip_tra,
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

    initSummaryDetail(index, row) {
      console.log(index, row);
      let id_dia = row.id;
      this.$router.push({
        name: "addfarmincomeday",
        params: {
          id: id_dia,
        },
      });
    },

    initAddFarmSummaryDay() {
      this.$router.push({
        name: "addfarmsummaryday",
      });
    },

    //  * G21. Imprimir el reporte de movimientos de las ventas de los dias.
    initCustomerResumeSaleDetailDaysReport(index, row) {
      let app = this;
      let data = row;
      console.log(row);
      axios({
        url: "/api/customerResumeSaleDetailDaysReport/",
        params: {
          dataResumen: data,
          id_resumen: data.id,
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
<style scoped></style>