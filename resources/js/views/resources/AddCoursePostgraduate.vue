<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>agregar programa academico</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="12" :offset="6">
            <div class="grid-content bg-purple">

              <el-form ref="form" :model="form" label-width="220px" size="small">
                <el-form-item label="Nombre del curso">
                  <el-input v-model="form.descripcion"></el-input>
                </el-form-item>
                <el-form-item label="monto">

                  <el-input v-model="form.monto"></el-input>
                </el-form-item>
                <el-form-item label="tipo de programa">
                  <el-select v-model="form.detalle" value-key="detalle" size="small"
                    placeholder="seleccione el tipo de programa" @change="OnchangeTypeProgram">
                    <el-option v-for="item in typesPrograms" :key="item.id" :label="item.detalle" :value="item.id">
                    </el-option>
                  </el-select>
                </el-form-item>

                <el-form-item size="small" label="fecha">
                  <el-date-picker size="small" type="date" placeholder="seleccione una fecha" v-model="form.fecha_inscripcion"
                    style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="initStoreProgram" plain>Guardar</el-button>
                  <el-button size="small" type="danger" @click="test" plain>Cancelar</el-button>
                </el-form-item>
              </el-form>
            </div>
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
      mensaje: {},
      form: {
        descripcion: "",
        costo: 0.00,
        fecha_inscripcion: "",
        metodo_pago: "",
        tipo_pago: "",
        detalle: "",
        id_tipo: "",
      },
      typesPrograms: [],          //diferentes tipos de archivos que pertenecen a un documento

    };
  },
  mounted() {

    this.getTypesOfProgram();
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    //  * RP11. Obtener la lista de programas academicos 
    async getTypesOfProgram() {
      let app = this;
      try {
        let response = await axios.post("/api/getTypesOfProgram", {
        });
        app.typesPrograms = response.data;
      } catch (error) {
        console.log(error);
      }
    },

    //  * RP12. Guarda un programas academicos 
    initStoreProgram(){

    },
    //* actualizar un componente al hacer la seleccion nueva *//
    OnchangeTypeProgram(idx) {
      let resultado = this.typesPrograms.find(tipo => tipo.id == idx);
      this.form.id_tipo = resultado.id;
    }
  },
};
</script>
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>