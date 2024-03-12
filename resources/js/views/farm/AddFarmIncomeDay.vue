<template>
    <div>
      <el-card class="box-card">  
      </el-card>
    </div>
  </template>
  
  <script>
  
  export default {
    data() {
      return {
        mensaje: '',
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
  