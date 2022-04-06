<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>pruebas de jessi</span>
      </div>
      <el-input placeholder="ingrese numero" v-model="input"></el-input>
      <div>
          <el-button @click="test">Default</el-button>
      </div>
      </div>
      <el-input placeholder="ingrese numero" v-model="input"></el-input>
      <div>
          <el-button @click="test">Default</el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      input: '',
    };
  },
  mounted() {
    let app = this;
  },
  methods: {
    test() {
      alert("hola "+this.input);
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
