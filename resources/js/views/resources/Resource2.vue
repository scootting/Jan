<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>recursos</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <h1>bienvenido/a</h1>
      <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
      <el-button type="primary" @click="test">Mensaje de bienvenida...</el-button>
      <!-- 
      -->
      <h1>{{data.id}}</h1>
      <h2>{{data.details}}</h2>

    </el-card>
  </div>
</template>

<script>

export default {
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
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
