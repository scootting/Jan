<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>unidades academicas y administrativas</span>
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="ingrese una descripcion" v-model="writtenTextParameter" class="input-with-select"
          @keyup.enter.native="getCategoryProgramatic">
          <el-button slot="append" icon="el-icon-search" @click="getCategoryProgramatic(1)"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="codigo">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="des_prg" label="descripcion"></el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button @click="initAddFixedAssets(scope.$index, scope.row)" type="primary" size="small">Regularizar
                activo</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getCategoryProgramatic"></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      loading:false,
      data: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
    };
  },
  mounted() {
    this.getCategoryProgramatic(1);
  },
  methods: {
    
    test() {
      alert("bienvenido al modulo");
    },

    async getCategoryProgramatic(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getCategoryProgramatic", {
          description: app.writtenTextParameter,
          year: app.user.gestion,
          page: page,
        });
        app.data = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
      app.loading = false;
    },

    initAddFixedAssets(idx, row) {
      this.$router.push({
        name: "regularizefixedassetsdetails",
        params: {
          id: row.cod_prg,
        },
      });
    },
  },
};
</script>

<style></style>