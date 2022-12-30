<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>bienvenido</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <h1>bienvenido/a</h1>
      <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
      <el-button type="primary" @click="test">Mensaje de bienvenida...</el-button>
      <el-button type="primary" @click="buscarPersona">Componente persona</el-button>
      <el-button type="primary" @click="buecarCategoria">Componente categoria</el-button>
      <information :visible="isVisible" :tag='mensaje' @update-visible="updateIsVisible"></information>
      <!-- 
      -->
      <h1>{{data.id}}</h1>
      <h2>{{data.details}}</h2>

    </el-card>
  </div>
</template>

<script>
import information from "./components/Information";

export default {
  components: {
    information,
  },
  data() {
    return {
      isVisible: false,
      mensaje: 'persona',
      data: {},
    };
  },
  mounted() { },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    updateIsVisible(visible, data) {
      this.isVisible = visible;
      this.data = data;
      console.log(this.isVisible+" "+this.data);
    },

    buscarPersona(){
      this.isVisible=true;
      this.mensaje = 'persona';
    },
    buecarCategoria(){
      this.isVisible=true;
      this.mensaje = 'categoria';
    }
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
