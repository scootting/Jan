<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Archivo</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddTypeArchive">nuevo tipo de archivo</el-button>
      </div>
      <br />
       <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column prop="cod" label="codigo">
             <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.idt }} - {{ scope.row.idc }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="descr" label="descripcion"></el-table-column>
          <el-table-column prop="tipo" label="tipo">
             <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.tipo }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right" width="100">
            <template slot-scope="scope">
              <el-button
                @click="initEditTypesArchive(scope.$index, scope.row)"
                type="success"
                size="mini"
                plain
              >Editar</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getTypesDocument"
        ></el-pagination>
      </div>
    </el-card>
  </div>

</template>
  
<script>
export default {
  name: "tiposdearchivos",
  components: {
  },
  data() {
    return {
      message: 'Hola mundo',
      data: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() { 
    this.getTypesDocument();
  },
  methods: {
        async getTypesDocument(page) {
            this.loading = true;
            let app = this;
            try {
                let response = await axios.post("/api/getTypesDocument", {
                    description: app.writtenTextParameter,
                    page: page,
                });
                console.log(response);
                app.data = Object.values(response.data.data);
                app.pagination = response.data;
                app.loading = false;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },

    initAddTypeArchive() {
      this.$router.push({
        name: "addtypearchive",
      });
    },
    initEditTypesArchive(index, row) {
      console.log(index, row);
      let cod = row.cod;
      this.$router.push({
        name: "edittypearchive",
        params: {
          id: row.id,
        },
      });
    },
    
  },

};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-input .el-select {
  width: 180px;
}

</style>
  