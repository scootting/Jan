
<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Inventario</span>
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getArchives(1)"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
          <!--
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column width="75" label="No.">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.no_con }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column width="375" label="tipo">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium" type="warning">{{ scope.row.descr }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="des_con"
            width="450"
            label="descripcion del contenedor"
          ></el-table-column>
          <el-table-column width="150" label="Estado">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                @click="getFilesbyContainer(scope.row.no_con)"
                type="success"
                plain
                size="mini"
                >Ver archivos
              </el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getContainers"
        ></el-pagination>
        -->
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      user: this.$store.state.user,
      data: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
    };
  },
  mounted() {
    let app = this;
    app.id_archive = app.$route.params.id;
    this.getFilesContainer();
  },
  methods: {
    //  * A7. Obtiene la lista de archivos que se encuentran por contenedor
    getFilesContainer(page) {
      this.loading = true;
      let app = this;
      /*
      axios
        .post("/api/fileContainer", {
          year: app.user.gestion,
          description: app.writtenTextParameter.toUpperCase(),
          page:page,
        })
        .then((response) => {
          app.loading = false;
          app.data = Object.values(response.data.data);
          console.log(app.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });*/
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>