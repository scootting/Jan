<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Documentos</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddAssignment">nuevo documento</el-button>
        <!--
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddAssignment2">no funcionara asi</el-button>
          -->
      </div>
      <!--
      <div style="margin-top: 15px">
        <el-input placeholder="ingrese una descripcion" v-model="writtenTextParameter" class="input-with-select"
          @keyup.enter.native="getAssignments">
          <el-button slot="append" icon="el-icon-search" @click="getAssignments(1)"></el-button>
        </el-input>
      </div>
      -->
      <br />
      <div>
        <el-table v-loading="loading" :data="dataAssignment" size="medium" style="width: 100%">
          <el-table-column prop="fecha" label="fecha"></el-table-column>
          <el-table-column prop="idc" label="no. documento"></el-table-column>
          <el-table-column prop="des_resp" label="responsable"></el-table-column>
          <el-table-column label="unidad academica o administrativa" width="320">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.des_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right" width="320">
            <template slot-scope="scope">
              <el-button @click="initAddFixedAssets(scope.$index, scope.row)" type="success"
                size="small">Editar</el-button>
              <el-button @click="initEditFixedAssets(scope.$index, scope.row)" type="danger" size="small">
                detalle de activos fijos </el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getAssignments"></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      loading: false,
      dataAssignment: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
    };
  },
  mounted() {
    this.getAssignments(1);
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },

    //  *  AF12. Obtiene la lista de documentos    
    async getAssignments(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getDataAssignments", {
          tipo: 'C',
          gestion: app.user.gestion,
          page: page,
        });
        app.dataAssignment = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(app.dataAssignment);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
      app.loading = false;
    },

    //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
    initAddAssignment() {
      this.$router.push({
        name: "addassignment",
        params: {
          id: '0',
        },
      });
    },

    initAddFixedAssets(idx, row) {
      this.$router.push({
        name: "purchaseassignment",
        params: {
          id: row.id,
        },
      });
    },

    initEditFixedAssets(idx, row) {
      this.$router.push({
        name: "assignmentdetails2",
        params: {
          id: row.id,
        },
      });
    },
  },
};
</script>

<style></style>