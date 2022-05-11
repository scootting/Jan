<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>pruebas de jessi</span>
      </div>
      <div>

          <el-input placeholder="inserte numero 1" v-model="num1"></el-input>
          <el-input placeholder="inserte numero 2" v-model="num2"></el-input>
      </div> 
      <div>
          <el-button @click="sum">Sumar</el-button>
          <el-button @click="rest" >Resta</el-button>

      </div>
    </el-card>
  </div>
</template>


<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      num1: '',
      num2: '',
      input: "",

    };
  },

  mounted() {
    let app = this;
  },
  methods: {
    test() {

      alert("la suma es: "+this.sum);
      alert("la resta es: "+this.rest);

      alert("hola " + this.input);

    },

   sum() {
  var suma = parseInt( this.num1)+ parseInt(this.num2);
   alert(suma);
  },
   rest() {
  var resta = parseInt( this.num1)- parseInt(this.num2);
   alert(resta);
  },

  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>