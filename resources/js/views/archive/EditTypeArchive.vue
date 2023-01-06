<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>editar tipo de archivo</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form
              ref="form"
              :model="person"
              :rules="rules"
              label-width="260px"
            >
              <el-form-item
                size="small"
                label="codificacion"
                prop="cod"
              >
                <el-input
                  size="small"
                  v-model="archive.cod"
                  disabled
                ></el-input>
              </el-form-item>
              <el-form-item size="small" label="descripcion" prop="descr">
                <el-input size="small" v-model="archive.descr"></el-input>
              </el-form-item>
              <el-form-item size="small" label="tipo" prop="tipo">
                <el-input size="small" v-model="archive.tipo"></el-input>
              </el-form-item>
              <el-form-item>
                <el-button
                  size="small"
                  type="primary"
                  @click.prevent="saveArchive"
                  plain
                  >Guardar</el-button
                >
                <el-button size="small" type="danger" @click="noArchive" plain
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
  name: "AniadirArchive",
  data() {
    return {
      archive: {},
      rules: {
        cod: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        descr: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        tipo: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
      },
    };
  },
  async mounted() {
    let app = this;
    let id = app.$route.params.id;
    try {
      let response = await axios.get("/api/archive/" + id);
      app.archive = response.data[0];
      console.log(app.archive);
    } catch (error) {
      this.error = error.response.data;
      app.$alert(this.error.message, "Gestor de errores", {
        dangerouslyUseHTMLString: true,
      });
    }
  },
  methods: {
    async saveArchive() {
      var app = this;
      var newArchive = app.archive;
      try {
        let response = await axios.post("/api/archive", {
          persona: newArchive,
          marker: "editar",
        });
        alert("se ha creado el registro de la persona");
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    noArchive() {
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
