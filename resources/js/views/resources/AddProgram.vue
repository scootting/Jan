<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>agregar programa academico</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="18" :offset="3">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataProgram" label-width="220px" size="small">
                <el-form-item label="tipo de programa academico">
                  <el-select v-model="dataProgram.des_tipo" value-key="descr" size="small"
                    placeholder="seleccione el tipo de programa academico" @change="OnchangeTypeProgram">
                    <el-option v-for="item in typesPrograms" :key="item.id" :label="item.detalle" :value="item.id">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="descripcion">
                  <el-input type="textarea" v-model="dataProgram.descripcion"></el-input>
                </el-form-item>
                <el-form-item label="monto">
                  <el-input-number v-model="dataProgram.costo" controls-position="right" :min="1"
                    :step="1"></el-input-number>
                </el-form-item>
                <el-form-item size="small" label="fecha">
                  <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                    v-model="dataProgram.fecha" style="width: 100%"></el-date-picker>
                </el-form-item>
                <el-form-item label="identidad" prop="ci_per">
                  <el-input placeholder="ingrese carnet de identidad" v-model="tutor.id" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchTutor">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="descripcion" prop="details">
                  <el-input v-model="tutor.details"></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="initStoreProgram" plain>Guardar Programa
                    Academico</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
      </div>
    </el-card>
    <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
  </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
  components: {
    information,
  },

  data() {
    return {
      isVisible: false,                                           //componente campo visible
      tag: 'persona',                                             //componente que informacion desea traer

      dataProgram: {
        descripcion: "",
        costo: 0.00,
        fecha: "",
        metodo_pago: "",
        ci_tutor: "",
        des_tutor: "",
        id_tipo: "",
        des_tipo: "",
      },
      tutor: {},                                                 //cliente
      typesPrograms: [],          //diferentes tipos de archivos que pertenecen a un documento
    };
  },
  mounted() {
    this.getTypesOfProgram();
  },
  methods: {

    initPrograms() {
      this.$router.push({
        name: "programs",
      });
    },

    /* busca al tutor del curso */
    initSearchTutor() {
      this.isVisible = true;
      this.tag = 'persona';
    },

    updateIsVisible(visible, data) {
      this.isVisible = visible;
      this.tutor = data;
      this.dataProgram.ci_tutor = data.id;
      this.dataProgram.des_tutor = data.details;
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

    OnchangeTypeProgram(idx) {
      //* actualizar un componente al hacer la seleccion nueva *//
      let resultado = this.typesPrograms.find(tipo => tipo.id == idx);
      this.dataProgram.id_tipo = resultado.id;
      this.dataProgram.des_tipo = resultado.detalle;
    },
    //  * RP12. Guardar un curso de postgrado.
    async initStoreProgram() {
      var app = this;
      try {
        let response = await axios.post("/api/storeProgram", {
          programa: app.dataProgram,
          usuario: app.user,
          tutor: app.tutor,
          marker: "registrar",
        });
        alert("se ha creado un nuevo curso academico");
        this.initPrograms();
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
<style scoped></style>