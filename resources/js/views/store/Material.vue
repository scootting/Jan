<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>lista de materiales</span>
        <!--
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddMaterial">nuevo material</el-button>
        -->
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select">
          <el-button slot="append" icon="el-icon-search" @click="getMaterials(1)"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column width="175" label="codigo">
            <template slot-scope="scope">

              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.mat_cod }}</el-tag>
              </div>
            </template>
          </el-table-column>

          <el-table-column prop="mat_des" width="450" label="descripcion"></el-table-column>
          <el-table-column width="350" label="unidad">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.mat_uni_med }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Operaciones">
            <template slot-scope="scope">
              <el-button @click="initMovementOfMaterial(scope.$index, scope.row)" type="warning" size="mini" plain>
                movimiento
              </el-button>
              <el-button @click="initEditMaterial(scope.$index, scope.row)" type="success" size="mini" plain>
                editar
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
    //  * M1. Obtiene la lista de materiales con una breve descripcion
    async getMaterials(page) {
      this.loading = true;
      let app = this;
      let description = app.writtenTextParameter;
      app.loading = true;
      try {
        let response = await axios.post("/api/material", {
          description: description,
          page: page,
        });
        app.data = Object.values(response.data.data);
        app.pagination = response.data;
        app.loading = false;
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    initEditMaterial(index, row) {
      this.$router.push({
        name: "editmaterial",
        params: {
          id: row.mat_cod.trim(),
        },
      });
    },

    initMovementOfMaterial(index, row) {
      this.$router.push({
        name: "movementmaterial",
        params: {
          id: row.mat_cod.trim(),
        },
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>

</style>
