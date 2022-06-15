<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nueva material</span>
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
                <el-input size="small" v-model="material.mat_cod"></el-input>
              </el-form-item>
              <el-form-item size="small" label="descripcion" prop="descripcion">
                <el-input size="small" v-model="material.mat_des"></el-input>
              </el-form-item>
              <el-form-item size="small" label="unidad">
                <el-input size="small" v-model="material.mat_uni_med"></el-input>
              </el-form-item>
              <el-form-item
                size="small"
                label="apellido materno"
                prop="materno"
              >
                <el-input size="small" v-model="person.materno"></el-input>
              </el-form-item>
              <el-form-item
                size="small"
                label="fecha de nacimiento"
                prop="nacimiento"
              >
                <el-date-picker
                  size="small"
                  type="date"
                  placeholder="seleccione una fecha"
                  v-model="person.fec_nacimiento"
                  style="width: 100%"
                ></el-date-picker>
              </el-form-item>
              <el-form-item size="small" label="genero">
                <el-radio-group v-model="person.id_sexo" size="small">
                  <el-radio-button label="M"></el-radio-button>
                  <el-radio-button label="F"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button
                  size="small"
                  type="primary"
                  @click.prevent="saveMaterial"
                  plain
                  >Guardar</el-button
                >
                <el-button
                  size="small"
                  type="primary"
                  @click.prevent="resetMaterial"
                  plain
                  >Nuevo</el-button
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
      messages: {},
      material: {
        mat_cod: "",
        mat_des: "",
        mat_uni_med: "",
      },
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
  mounted() {},
  methods: {
    //  * S2. Guarda los datos una nueva persona que no se encuentra registrada.
    async saveMaterial() {
      var app = this;
      var newMaterial = app.material;
      try {
        let response = await axios.post("/api/addMaterial", {
          material: newMaterial,
          marker: "registrar",
        });
        alert("se ha creado el registro del material");
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

    resetMaterial() {
      (this.materiales.mat_cod = ""),
        (this.materiales.mat_des = ""),
        (this.materiales.mat_uni_med = "");
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
