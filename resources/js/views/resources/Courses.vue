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
        <el-table v-loading="loading" :data="dataCourses" style="width: 100%">
          <el-table-column width="190" label="categoria programatica">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column width="190" label="codigo valor">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_val }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="detalle" width="350" label="descripcion del curso"></el-table-column>
          <el-table-column prop="pre_val" width="150" label="precio"></el-table-column>
          <el-table-column align="right-center" width="220" label="movimientos" fixed="right">
            <template slot-scope="scope">
              <el-button @click="initGetInputOfCourse(scope.row.id)" type="primary" size="small">ingresos
              </el-button>
              <el-button @click="initGetOutputOfCourse(scope.row.id)" type="danger" size="small">egresos
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
      dataCourses: [],
      user: this.$store.state.user,
      loading: true,
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    this.getCourses();
  },
  methods: {
    //  * RP1. Obtener la lista de cursos de posgrado.
    async getCourses(page) {
      console.log()
      let app = this;
      try {
        let response = await axios.post("/api/courses", {
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        console.log(response);

        app.dataCourses = Object.values(response.data.data);
        app.pagination = response.data;
      } catch (error) {
        console.log(error);
        this.error = error.response.data;

        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    addCourse() {
      this.$router.push({
        name: "addcourse",
      });
    },
    initGetInputOfCourse(id) {
      this.$router.push({
        name: "inputcoursedetails",
        params: {
          id: id,
        },
      });
    },
    initGetOutputOfCourse(id) {
      this.$router.push({
        name: "outputcoursedetails",
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
