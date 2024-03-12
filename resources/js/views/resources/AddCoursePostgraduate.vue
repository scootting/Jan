<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>AGREGAR CURSO</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="12" :offset="6">
            <div class="grid-content bg-purple">

              <el-form ref="form" :model="form" label-width="220px" size="small">
                <el-form-item label="Nombre del curso" >
                 
                  <el-input v-model="form.nomb"></el-input>
                </el-form-item>
                <el-form-item label="monto" >
                 
                 <el-input v-model="form.mont"></el-input>
               </el-form-item>
               <el-form-item label="Tipo">
                <el-select v-model="form.tip" placeholder="selecciona el tipo de curso">
                  <el-option label="curso 1" value="shanghai"></el-option>
                  <el-option label="curso 2" value="beijing"></el-option>
                </el-select>
              </el-form-item>
              
              <el-form-item size="small" label="fecha" prop="nacimiento">
                <el-date-picker size="small" type="date" placeholder="seleccione una fecha"
                  v-model="form.fec" style="width: 100%"></el-date-picker>
              </el-form-item>
              <el-form-item>
                <el-button size="small" type="primary" @click.prevent="saveForm" plain>Guardar</el-button>
               
                <el-button size="small" type="danger" @click="noForm" plain>Cancelar</el-button>
              </el-form-item>

              </el-form>
              


            </div>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>

<script>

export default {
    data() {
      return {
        mensaje: {},
        form: {
          nomb: "",
          mont: "",
          tip: "",
          fec:"",
        },
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
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>