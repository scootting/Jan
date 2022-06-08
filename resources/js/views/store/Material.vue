<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>aca debe ir el titulo</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >aca esta un boton</el-button
        >
      </div>
      <h1>Texto</h1>
      <h5>Mas Texto</h5>
      <el-button type="primary" @click="test">Reporte</el-button>
    </el-card>
  </div>
</template>

<script>

export default {
  name: "Bienvenido",
  data() {
    return {
      messages: {},
      data: {},
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
