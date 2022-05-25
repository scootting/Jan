<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo documento, contenedor, ubicacion</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form
              ref="form"
              :model="archivo"
              label-width="260px"
              size="small"
            >
              <el-form-item label="codigo" prop="no_doc">
                <el-input v-model="archivo.nro_doc" :disabled="true"></el-input>
              </el-form-item>
              <el-form-item label="descripcion" prop="glosa">
                <el-input  type="textarea" v-model="archivo.glosa"></el-input>
              </el-form-item>
              <el-form-item label="tipo">
                <el-radio-group v-model="archivo.tipo">
                  <el-radio-button label="Ubicacion"></el-radio-button>
                  <el-radio-button label="Contenedor"></el-radio-button>
                  <el-radio-button label="Documento"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" @click.prevent="saveFileContainer" plain
                  >Guardar</el-button
                >
                <el-button size="small" type="danger" @click="noFileContainer" plain
                  >Cancelar</el-button
                >
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      archivo: {
        nro_doc: "",
        glosa: "ninguna",
        tipo: "Documento",
        gestion:null,
      },
    };
  },
  mounted() {
    let app = this;
    app.archivo.tipo = app.$route.params.type;
    app.archivo.gestion = app.user.gestion;
  },
  methods: {
    //  * A7. Guardar un nuevo archivo, contenedor o ubicacion
    async saveFileContainer() {
      var app = this;
      var newArchive = app.archivo;
      try {
        let response = await axios.post("/api/file", {
          archivo: newArchive,
          marker: "registrar",
        });
        alert("se ha creado el registro del nuevo " + app.archivo.tipo);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    noFileContainer() {
      this.$router.push("/api");
    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
}
.el-form {
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 60px;
}
</style>
