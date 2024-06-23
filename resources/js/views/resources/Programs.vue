<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>cursos postgrado</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="addCourse">nuevo curso</el-button>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="dataPrograms" style="width: 100%" fixed>
          <el-table-column width="120" label="tipo">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.detalle }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="fecha" width="100" label="fecha"></el-table-column>
          <el-table-column width="250" label="descripcion">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.descripcion }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="costo" width="100" label="importe" align="right"></el-table-column>
          <el-table-column align="right-center" width="320" fixed="right">
            <template slot-scope="scope">
              <el-button @click="initAddStudentProgram(scope.row.id_programa)" type="primary" size="small">agregar estudiante
              </el-button>
              <el-button @click="initAddSaleProgram(scope.row.id_programa)" type="danger" size="small">realizar pago
              </el-button>
            </template>
          </el-table-column>
          <!--
          -->
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next" :current-page="pagination.current_page"
          :total="pagination.total" @current-change="getCourses"></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dataPrograms: [],
      user: this.$store.state.user,
      loading: true,
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    this.getCourses(1);
  },
  methods: {
    //  * RP1. Obtener la lista de cursos de posgrado.
    async getCourses(page) {
      let app = this;
      try {
        let response = await axios.post("/api/getPrograms", {
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        console.log(response);
        app.dataPrograms = Object.values(response.data.data);
        app.pagination = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    addCourse() {
      this.$router.push({
        name: "addprogram",
      });
    },

    initAddStudentProgram(id) {
      this.$router.push({
        name: "addstudentprogram",
        params: {
          id: id,
        },
      });
    },

    initAddSaleProgram(id) {
      this.$router.push({
        name: "addsaleprogram",
        params: {
          id: id,
        },
      });
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
