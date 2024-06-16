<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Ingreso de productos de la granja universitaria</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddFarmIncomeDay">Nuevo Ingreso de productos</el-button>
      </div>

      <div>
        <el-table v-loading="loading" :data="dataSaleDays" style="width: 100%" size="medium">
          <el-table-column prop="fec_tra" label="fecha" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="id_dia" label="dia" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa" :min-width="450"></el-table-column>
          <!--
          <el-table-column align="right" prop="com_fin" label="operaciones" :min-width="200"></el-table-column>          
          -->
          <el-table-column align="right" :min-width="350" fixed="right">
            <template slot-scope="scope">
              <el-button :disabled="dataSaleDays[scope.$index].estado == 'V'"
                @click="initCustomerIncomeDetail(scope.$index, scope.row)" type="warning" size="small" plain>realizar
                ingresos</el-button>                
                <el-button @click="initCustomerIncomeDetail(scope.$index, scope.row)" type="primary" size="small"
                :disabled="dataSaleDays[scope.$index].estado != 'V'"
                plain>resumen ingreso de productos</el-button>
            </template>
          </el-table-column>
        </el-table>
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
      tip_tra: 2,
      pagination: {
        page: 1,
      },
      loading: true,
      writtenTextParameter: '',
    };
  },
  mounted() {
    this.getFarmSaleDays(this.pagination.page);
  },
  methods: {
    test() {
    },
    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja
    async getFarmSaleDays(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getFarmSaleDays", {
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

    initCustomerIncomeDetail(index, row) {
      console.log(index, row);
      let id_dia = row.id;
      this.$router.push({
        name: "addfarmincomeday",
        params: {
          id: id_dia,
        },
      });
    },

    initAddFarmIncomeDay() {
      this.$router.push({
        name: "addfarmsaleday",
      });
    },
    //  * G13. Imprimir el reporte del ingreso actual.
    initIncomeDetailReport(index, row) {
      let transaccion = row;
      axios({
        url: "/api/customerIncomeDetailReport/",
        params: {
          id: transaccion.id,
          tipo: transaccion.tip_tra,
          gestion: transaccion.gestion,
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