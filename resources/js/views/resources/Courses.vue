<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>cursos postgrado</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="addNewCourse">nuevo curso</el-button>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="dataCourses" style="width: 100%">
          <el-table-column width="90" label="No.">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.id_doc }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="fecha" width="100" label="fecha"></el-table-column>
          <el-table-column width="250" label="tipo">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium" type="danger">{{ scope.row.descr }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" width="650" label="descripcion del documento"></el-table-column>
          <el-table-column width="100" label="Estado">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="220" label="Operaciones" fixed="right">
            <template slot-scope="scope">
              <el-button @click="getArchivesByDocument(scope.row.id)" type="success" plain size="mini">ver archivos
              </el-button>
              <el-button @click="getArchivesDigitalByDocument(scope.row.id)" type="info" plain size="mini">digitalizar
              </el-button>
            </template>
          </el-table-column>
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
    //  * 1. Obtener la lista de cursos de posgrado.
    async getCourses(page) {
      let app = this;
            try {
                let response = await axios.post("/api/courses", {
                    year: app.user.gestion,
                    page: page,
                });
                app.loading = false;
                app.dataCourses = Object.values(response.data.data);
                app.pagination = response.data;
                console.log(response.data.data);
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
    },
    addNewCourse() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
