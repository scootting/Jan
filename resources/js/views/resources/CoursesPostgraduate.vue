<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>LISTAR CURSO</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddProgram">Agregar programa</el-button>
      </div>
      <div>
        <el-table v-loading="loading" :data="dataPrograms" style="width: 100%">
          <el-table-column width="190" label="CODIGO">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column width="190" label="NOMBRE">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_val }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="monto" width="350" label="MONTO"></el-table-column>
          <el-table-column prop="tipo" width="150" label="TIPO"></el-table-column>

        </el-table>

        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getPrograms"></el-pagination>

      </div>
    </el-card>
  </div>
</template>

<script>
import { Table } from 'element-ui';


export default {
  data() {
    return {
      loading: true,
      user: this.$store.state.user,
      dataPrograms: [],
      pagination: {
        page: 1,
      },
    };
  },
  mounted() {
    this.getPrograms(this.pagination.page);
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    //  * RP10. Obtener la lista de programas academicos 
    async getPrograms(page) {
      let app = this;
      try {
        let response = await axios.post("/api/getPrograms", {
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        app.dataPrograms = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(response.data.data);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
        app.loading = false;
      }
    },

    initAddProgram() {
      this.$router.push({
        name: "addCoursePostgraduate",
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>