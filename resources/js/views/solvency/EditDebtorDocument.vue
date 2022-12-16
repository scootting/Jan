<template>
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>bienvenido</span>
          <el-button style="float: right; padding: 3px 0" type="text"
            >ayuda</el-button
          >
        </div>
        <h1>bienvenido/a</h1>
        <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
        <el-button type="primary" @click="reporte">Reporte</el-button>
      </el-card>
    </div>
  </template>
  
  <script>
  
  export default {
    name: "Bienvenido",
    data() {
      return {
        isValued: false,
        isVisible: false,
        messages: {},
        data: {},
      };
    },
    mounted() {},
    methods: {
      test() {
        alert("bienvenido al modulo");
      },
      openModalPerson() {
        this.isVisible = true;
      },
      openModalValued() {
        this.isValued = true;
      },
      update(isVisible) {
        this.isVisible = isVisible;
      },
      reporte() {
        axios
          .get("/api/reportSelectedFixedAssets2/", {
            responseType: "arraybuffer",
          })
          .then((response) => {
            let blob = new Blob([response.data], { type: "application/pdf" });
            let link = document.createElement("a");
            //link.href = window.URL.createObjectURL(blob);
            //link.download = "test.pdf";
            //link.click();
            let url = window.URL.createObjectURL(blob);
            window.open(url);
          });
      },
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped></style>
  