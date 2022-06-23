<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>editar material</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form
              ref="form"
              :model="material"
              :rules="rules"
              label-width="260px"
            >
              <el-form-item
                size="small"
                label="numero de identificacion"
                prop="material"
              >
                <el-input
                  size="small"
                  v-model="material.mat_cod"
                  disabled
                ></el-input>
              </el-form-item>
              <el-form-item size="small" label="codigo" prop="mat_cod">
                <el-input size="small" v-model="material.mat_cod"></el-input>
              </el-form-item>
              <el-form-item size="small" label="descripcion">
                <el-input size="small" v-model="material.mat_des"></el-input>
              </el-form-item>
              <el-form-item size="small" label="unidad">
                <el-input size="small" v-model="material.mat_uni_med"></el-input>
          
              </el-form-item>
            
              <el-form-item>
                <el-button
                  size="small"
                  type="primary"
                  @click.prevent="saveMaterial"
                  plain
                  >Guardar</el-button
                >
                <el-button size="small" type="danger" @click="noMaterial" plain
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
  name: "AniadirMaterial",
  data() {
    return {
      material: {},
      rules: {
        mat_cod: [
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
        mat_des: [
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
        mat_uni_med: [
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
      let response = await axios.get("/api/EditMaterial/" + id);
      app.material = response.data[0];
      console.log(app.material);
    } catch (error) {
      this.error = error.response.data;
      app.$alert(this.error.message, "Gestor de errores", {
        dangerouslyUseHTMLString: true,
      });
    }
  },
  methods: {
    async saveMaterial() {
      var app = this;
      var newMaterial = app.material;
      try {
        let response = await axios.post("/api/editMaterial", {
          material: newMaterial,
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

    noMaterial() {
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
