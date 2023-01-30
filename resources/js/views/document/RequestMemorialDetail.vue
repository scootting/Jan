<template>
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>informacion personal</span>
          <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
        </div>
        <el-row :gutter="20">
          <p>
            <el-alert
              title="El cambio de la direccion, telefono, correo implica que sus nuevas solicitudes tendrán esta nueva informacion."
              type="success">
            </el-alert>
          </p>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos personales</p>
              <el-form ref="form" :model="this.client" label-width="200px" size="mini">
                <el-form-item label="carnet de identidad">
                  <el-input v-model="client.nodip" disabled></el-input>
                </el-form-item>
                <el-form-item label="apellido paterno">
                  <el-input v-model="client.paterno" disabled></el-input>
                </el-form-item>
                <el-form-item label="apellido materno">
                  <el-input v-model="client.materno" disabled></el-input>
                </el-form-item>
                <el-form-item label="nombres">
                  <el-input v-model="client.nombres" disabled></el-input>
                </el-form-item>
                <el-form-item label="fecha de nacimiento">
                  <el-input v-model="client.nacimiento" disabled></el-input>
                </el-form-item>
                <el-form-item label="sexo">
                  <el-input v-model="client.sexo" disabled></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>datos adicionales</p>
              <el-form ref="form" :model="this.client" label-width="200px" size="mini">
                <el-form-item label="direccion">
                  <el-input v-model="client.direccion"></el-input>
                </el-form-item>
                <el-form-item label="telefono/celular">
                  <el-input v-model="client.telefono"></el-input>
                </el-form-item>
                <el-form-item label="correo electronico">
                  <el-input v-model="client.correo"></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-button type="primary" size="small" @click="updatePersonInformation()">Verificar la solicitud</el-button>
        <el-button type="warning" size="small" @click="updatePersonPassword()">Imprimir el memorial</el-button>
        <el-row> </el-row>
      </el-card>
    </div>
  </template>
  
  <script>
  export default {
    name: "",
    data() {
      return {
        client: this.$store.state.user,
        id: this.$route.params.id,
      };
    },
    mounted() {

    },
    methods: {
      test() {
        alert("bienvenido al modulo");
      },

    //  * M2. Obtener la lista de memoriales para su verificacion 
    async getRequestMemorialById() {
        var app = this;
        try {
          let response = await axios.post("/api/getRequestMemorialById", {
            persona: app.id,
            gestion: client.gestion,
            marker: "editar",
          });
          console.log(response);
        } catch (error) {
          this.error = error.response.data;
          app.$alert(this.error.message, "Gestor de errores", {
            dangerouslyUseHTMLString: true,
          });
        }
      },
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped>
  .el-row {
    margin-bottom: 20px;
  }
  
  .el-col {
    border-radius: 4px;
  }
  
  .bg-purple-dark {
    background: #99a9bf;
  }
  
  .bg-purple {
    background: #d3dce6;
  }
  
  .bg-purple-light {
    background: #e5e9f2;
  }
  
  .grid-content {
    border-radius: 4px;
    padding: 15px;
    min-height: 36px;
  }
  
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  </style>
  