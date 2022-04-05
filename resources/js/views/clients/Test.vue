<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>pruebas de Lionel</span>
      </div>
      <div>
          <el-button>Default</el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
    };
  },
  mounted() {
    let app = this;
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
