<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>editar persona</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form ref="form" :model="person" :rules="rules" label-width="260px">
              <el-form-item size="small" label="numero de identificacion" prop="personal">
                <el-input size="small" v-model="person.nro_dip" disabled></el-input>
              </el-form-item>
              <el-form-item size="small" label="nombres" prop="nombres">
                <el-input size="small" v-model="person.nombres"></el-input>
              </el-form-item>
              <el-form-item size="small" label="apellido paterno">
                <el-input size="small" v-model="person.paterno"></el-input>
              </el-form-item>
              <el-form-item size="small" label="apellido materno" prop="materno">
                <el-input size="small" v-model="person.materno"></el-input>
              </el-form-item>
              <el-form-item size="small" label="fecha de nacimiento" prop="nacimiento">
                <el-date-picker size="small" type="date" v-model="person.fec_nacimiento"
                  style="width: 100%"></el-date-picker>
              </el-form-item>
              <el-form-item size="small" label="genero">
                <el-radio-group v-model="person.id_sexo" size="small">
                  <el-radio-button label="M"></el-radio-button>
                  <el-radio-button label="F"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button size="small" type="primary" @click.prevent="savePerson" plain>Guardar</el-button>
                <el-button size="small" type="danger" @click="noPerson" plain>Cancelar</el-button>
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
  name: "AniadirPersona",
  data() {
    return {
      person: {},
      rules: {
        nro_dip: [
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
        nombres: [
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
        materno: [
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
        fec_nacimiento: [
          {
            required: true,
            message: "El campo no puede estar vacio",
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
      let response = await axios.get("/api/person/" + id);
      app.person = response.data[0];
      console.log(app.person);
    } catch (error) {
      this.error = error.response.data;
      app.$alert(this.error.message, "Gestor de errores", {
        dangerouslyUseHTMLString: true,
      });
    }
  },
  methods: {
    async savePerson() {
      var app = this;
      var newPerson = app.person;
      try {
        let response = await axios.post("/api/person", {
          persona: newPerson,
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

    noPerson() {
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
