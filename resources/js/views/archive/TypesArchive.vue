<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Archivo</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddTypeArchive">nuevo tipo de archivo</el-button>
      </div>
      <br />
    </el-card>
  </div>

</template>
  
<script>
export default {
  name: "tiposdearchivos",
  components: {
  },
  data() {
    return {
      message: 'Hola mundo',
    };
  },
  mounted() { },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    initAddTypeArchive() {
      this.$router.push({
        name: "addtypearchive",
        /*

        params: {
          id: id_archive,
        },*/
      });
    }

  },

};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
  