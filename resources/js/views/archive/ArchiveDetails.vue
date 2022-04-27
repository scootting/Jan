<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>detalle del archivo</span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <el-row :gutter="50">
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-form
              ref="form"
              :model="this.personal"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="carnet de identidad">
                <el-input v-model="personal.nro_dip" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="personal.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="personal.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="personal.nombres" disabled></el-input>
              </el-form-item>
            </el-form></div
        ></el-col>
        <el-col :span="24"
          ><div class="grid-content bg-purple">
            <el-table
              :data="transactions"
              border
              style="width: 100%"
              size="small"
            >
              <el-table-column prop="fec_tra" label="fecha" width="100">
              </el-table-column>
              <el-table-column prop="gestion" label="gestion" width="100">
              </el-table-column>
              <el-table-column prop="id_dia" label="id" width="100">
              </el-table-column>
              <el-table-column prop="des_tip" label="tipo" width="150">
              </el-table-column>
              <el-table-column prop="nro_com" label="papeleta" width="100">
              </el-table-column>
              <el-table-column prop="cod_val" label="cod." width="65">
              </el-table-column>
              <el-table-column prop="des_val" label="descripcion" width="550">
              </el-table-column>
              <el-table-column prop="imp_val" label="Precio" align="right">
              </el-table-column>
            </el-table></div
        ></el-col>
      </el-row>
      <!--
      <el-button type="primary" size="small" @click="printTransactions()"
        >imprimir</el-button
      >
      -->
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      user: this.$store.state.user,
      id_archive: null,
      documentsArchive: [],
    };
  },
  mounted() {
    let app = this;
    app.id_archive = app.$route.params.id;
    getDocumentsbyArchive();
  },
  methods: {
    test() {
      alert("test");
    },

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    async getDocumentsbyArchive() {
      let app = this;
      let id = app.writtenTextParameter;
      try {
        let responseTransactions = await axios.post(
          "/api/getDocumentsbyArchive",
          {
            id: app.id_archive,
            year: app.user.gestion,
          }
        );
        app.documentsArchive = responseTransactions.data;
        console.log(app.documentsArchive);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    initPrintDocumentsbyArchive() {
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-row {
  margin-bottom: 20px;
}
.el-col {
  border-radius: 4px;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>
