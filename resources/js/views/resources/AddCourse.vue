<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo curso postgrado</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="12" :offset="6">
            <div class="grid-content bg-purple">

              <el-form :model="dataCourse" label-width="220px" size="small">
                <!--
                <el-form-item size="small" label="numeracion">
                  <el-radio-group v-model="numerable" size="small" @change="OnchangeNumerable">
                    <el-radio-button label="1">automatica</el-radio-button>
                    <el-radio-button label="2">manualmente</el-radio-button>
                  </el-radio-group>
                </el-form-item>
                <el-form-item label="Numero de contenedor">
                  <el-input v-model="dataCourse.codigo" autocomplete="off" :disabled="isNumerable"></el-input>
                </el-form-item>
                <el-form-item label="fecha del registro">
                  <el-date-picker type="date" v-model="dataCourse.fecha" placeholder="seleccione una fecha"
                    style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                -->
                <el-form-item label="curso" prop="details">
                  <el-input placeholder="" v-model="prg.details" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchCourse">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="valor" prop="details">
                  <el-input placeholder="" v-model="valore.details" class="input-with-select">
                    <el-button slot="append" icon="el-icon-search" @click="initSearchValues">BUSCAR</el-button>
                  </el-input>
                </el-form-item>
                <el-form-item label="descripcion">
                  <el-input type="textarea" v-model="dataCourse.detalle" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="initStoreCourse" plain>Guardar</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
      </div>
      <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
    </el-card>

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
      user: this.$store.state.user,
      isVisible: false,           //componente campo visible
      tag: '',                    //componente que informacion desea traer
      flag: '',                   //deudor, responsable, categoria programatica

      dataCourse: {},
      prg:{},
      valore:{},
    };
  },
  mounted() { },
  methods: {
    initSearchCourse() {
      this.isVisible = true;
      this.tag = 'categoria';
      this.flag = 'categoria';
    },
    initSearchValues() {
      this.isVisible = true;
      this.tag = 'valores';
      this.flag = 'valores';
    },

    updateIsVisible(visible, data) {
      this.isVisible = visible;
      this.data = data;
      console.log(this.isVisible + " " + this.data);
      switch (this.flag) {
        case 'categoria':
          this.prg = data;
          break;
        case 'valores':
          this.valore = data;
          break;
        default:
          break;
      }
    },


  },
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
  