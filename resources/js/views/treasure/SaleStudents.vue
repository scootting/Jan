<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores para estudiantes nuevos</span>
        <el-button style="text-align: right; float: right" size="mini" type="success" icon="el-icon-plus"
          @click="initStoreDayForSale">agregar dia para la venta de alumnos nuevos</el-button>
      </div>
      <div>
        <el-table v-loading="loading" :data="days" style="width: 100%">
          <el-table-column prop="id_dia" label="dia" :min-width="100">
            <template slot-scope="scope">
              <el-tag size="medium" type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="fec_tra" label="fecha" :min-width="100">
            <template slot-scope="scope">
              <el-tag size="medium" type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa" :min-width="450"></el-table-column>
          <el-table-column align="right" :min-width="320">
            <template slot-scope="scope">
              <el-button :disabled="days[scope.$index].estado == 'V'" @click="initSaleStudents(scope.$index, scope.row)"
                size="mini" type="warning">realizar venta de valores</el-button>
              <el-button @click="initDetailStudents(scope.$index, scope.row)" size="mini" type="primary">detalle de venta
                del dia</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next" :current-page="pagination.current_page"
          :total="pagination.total" @current-change="getSaleOfDays"></el-pagination>
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
      messages: {},
      days: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    this.getSaleOfDays();
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },

    async getSaleOfDays(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getSaleOfDaysByDescription", {
          description: app.writtenTextParameter,
          user: app.user.usuario,
          year: app.user.gestion,
          page: page,
        });
        app.days = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(app.days);
        app.loading = false;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * T22. Agrega un nuevo dia para la venta de valores para estudiantes nuevos
    async initStoreDayForSale() {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/storeDayForSale", {
          user: app.user.usuario,
          year: app.user.gestion,
        });
        console.log(response);
        alert("Se creo correctamente un dia para la venta de valores");
        app.getSaleOfDays();
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    initDetailStudents(index, row) {
      var app = this;
      let id = row.id_dia;
      axios({
        url: "/api/reportDetailStudents/",
        params: {
          id_dia: id,
          gestion: app.user.gestion,
          usr_cre: app.user.usuario,
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
    initSaleStudents(index, row) {
      let id = row.id_dia;
      this.$router.push({
        name: "students",
        params: {
          id: id,
        },
      });
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
