<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>venta de valores en linea</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="success"
          icon="el-icon-carter"
          @click="initAddDay" plain
          >habilitar nuevo dia</el-button
        >
      </div>
      <div>
        <el-table v-loading="loading" :data="days" style="width: 100%">
          <el-table-column prop="id_dia" label="dia" :min-width="80">
            <template slot-scope="scope">
              <el-tag size="medium" type="primary">{{
                scope.row.id_dia
              }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="fec_tra" label="fecha" :min-width="90">
            <template slot-scope="scope">
              <el-tag size="medium" type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="glosa"
            label="glosa"
            :min-width="450"
          ></el-table-column>
          <el-table-column align="right" :min-width="250">
            <template slot-scope="scope">
              <el-button
                :disabled="days[scope.$index].estado == 'V'"
                @click="initSaleInLineDetail(scope.$index, scope.row)"
                size="mini"
                type="warning" plain
                >realizar ventas</el-button
              >
              <el-button
                @click="initDetailStudents(scope.$index, scope.row)"
                size="mini"
                type="primary" plain
                >imprimir ventas</el-button
              >
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getSaleLineDays"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      days: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    let app = this;
    this.getSaleLineDays(app.pagination.page);
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    async getSaleLineDays(page) {
      let app = this;
      try {
        let response = await axios.post("/api/getSaleOfDaysByDescription", {
          description: "",
          user: app.user.usuario,
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        app.days = Object.values(response.data.data);
        app.pagination = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    initAddDay() {
      alert("el modulo esta aun en contruccion");
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
    initSaleInLineDetail(index, row) {
      let id = row.id_dia;
      this.$router.push({
        name: "saleinlinedetail",
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
