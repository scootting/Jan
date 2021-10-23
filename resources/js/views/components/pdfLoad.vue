<template>
  <div>
    <el-upload
      class="upload-demo"
      drag
      action="/api/doc/upload"
      accept=".pdf"
      :on-success="handleSuccessFile"
      :on-remove="handleRemove"
      :headers="requestHeaders"
      :data="{ datasource: JSON.stringify(info) }"
      :show-file-list="false"
      multiple
    >
      <i class="el-icon-upload"></i>
      <div class="el-upload__text">
        Suelta tu archivo aquí o
        <em>haz clic para cargar el documento</em>
      </div>
    </el-upload>
  </div>
</template>
<script>
export default {
  name: "PdfLoad",
  props: ["info"],
  data() {
    return {
      messages: {},
      fileList: [],
      file: "",
      files: "",
      path:"",
    };
  },
  computed: {
    csrfToken: function () {
      return window.axios.defaults.headers.common["X-CSRF-TOKEN"];
    },
    AuthorizationToken(){
      return 'Bearer '+this.$store.state.token;
    },
    requestHeaders(){
      return {
        'X-CSRF-TOKEN': this.csrfToken,
        Authorization: this.AuthorizationToken,
      };
    }
  },
  methods: {
    /* *** Cuando se eliminar el archivo satisfactoriamente *** */
    handleRemove(file, fileList) {
      console.log(file, fileList);
      this.fileList = FileList;
    },
    /* *** Cuando se agrega el archivo satisfactoriamente *** */
    handleSuccessFile(response, file) {
      this.$message({
        message: "Gracias, acaba de subir el archivo " + file.name + ".",
        type: "success",
      });
      //esto hace que remplace el valor en el otro componente
      this.$emit('on-success',response);
    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
