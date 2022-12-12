<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>material</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddMaterial">nuevo material</el-button>
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select">
          <el-button slot="append" icon="el-icon-search" @click="getMaterials(1)"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column width="75" label="No.">
            <template slot-scope="scope">

              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.mat_cod }}</el-tag>
              </div>
            </template>
          </el-table-column>

          <el-table-column prop="mat_des" width="450" label="descripcion del material"></el-table-column>
          <el-table-column width="150" label="unidad">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.mat_uni_med }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Operaciones">
            <template slot-scope="scope">
              <el-button @click="initEditMaterial(scope.$index, scope.row)" type="success" size="mini" plain>Ver
                materiales
              </el-button>
            </template>
          </el-table-column>

        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getMaterials"></el-pagination>
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
    this.getMaterials();
  },
  methods: {
    //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
    getMaterials(page) {
      this.loading = true;
      let app = this;
      axios
        .post("/api/material", {

          description: app.writtenTextParameter.toUpperCase(),
          page: page,
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
        });
    },

    initEditMaterial(index, row) {
      console.log(index, row);
      let mat_cod = row.mat_cod;
      this.$router.push({
        name: "editMaterial",
        params: {
          id: mat_cod.trim(),
        },
      });
    },
    initAddMaterial() {
      this.$router.push({
        name: "addMaterial",

      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>

</style>
