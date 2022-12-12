<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo tipo de archivo</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <div>
        <el-form ref="form" :model="archive" label-width="120px" size="small">
          <el-form-item label="Tipo">
            <el-radio-group v-model="archive.type">
              <el-radio-button label="Archivo" index="A"></el-radio-button>
              <el-radio-button label="Contenedor" index="C"></el-radio-button>
              <el-radio-button label="Ubicacion" index="U"></el-radio-button>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="Codificacion">
            <el-input v-model="archive.codification"></el-input>
          </el-form-item>
          <el-form-item label="Descripcion">
            <el-input type="textarea" v-model="archive.description"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onStoreTypeArchive">Guardar</el-button>
            <el-button>Cancelar</el-button>
          </el-form-item>
        </el-form>
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
      archive: {
        description: '',
        codification: '',
        type: 'Archivo',
      }
    };
  },
  mounted() { },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    //  * A10. Guardar un nuevo tipo de archivo 
    async onStoreTypeArchive() {
      let app = this;
      try {
        let response = await axios.post("/api/onStoreTypeArchive", {
          archive: app.archive,
        });
        alert("el archivo se guardo correctamente");
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
  },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
  