<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo contenedor</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="12" :offset="6">
            <div class="grid-content bg-purple">

              <el-form :model="filecontainer" label-width="220px" size="small">
                <el-form-item label="tipo de contenedor">
                  <el-select v-model="filecontainer.descr" value-key="descr" size="small"
                    placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
                    <el-option v-for="item in typesDocument" :key="item.id" :label="item.descr" :value="item.id">
                    </el-option>
                  </el-select>
                </el-form-item>
                <el-form-item size="small" label="numeracion">
                  <el-radio-group v-model="numerable" size="small" @change="OnchangeNumerable">
                    <el-radio-button label="1">automatica</el-radio-button>
                    <el-radio-button label="2">manualmente</el-radio-button>
                  </el-radio-group>
                </el-form-item>
                <el-form-item label="Numero de contenedor">
                  <el-input v-model="filecontainer.codigo" autocomplete="off" :disabled="isNumerable"></el-input>
                </el-form-item>
                <el-form-item label="fecha del registro">
                  <el-date-picker type="date" v-model="filecontainer.fecha" placeholder="seleccione una fecha"
                    style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
                </el-form-item>
                <el-form-item label="glosa o descripcion">
                  <el-input type="textarea" v-model="filecontainer.glosa" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="initStoreFileContainer" plain>Guardar</el-button>
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
import { Alert } from 'element-ui';

export default {
  data() {
    return {
      user: this.$store.state.user,
      filecontainer: {codigo:'', glosa:''},
      numerable: 1,
      typesDocument: [],
      isNumerable: true,
    };
  },
  mounted() {
    let app = this;
    app.getTypesDocument();
  },
  methods: {
    //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    async getTypesDocument() {
      let app = this;
      try {
        let response = await axios.post("/api/getTypesDocumentById", {
          id_type: "C",
        });
        app.typesDocument = response.data;
      } catch (error) {
        console.log(error);
      }
    },

    //  * A7. Guardar el nuevo contenedor funcion 11 posiblemente
    async initStoreFileContainer() {
      var app = this;
      var newFileContainer = app.filecontainer;
      var newYear = app.user.gestion;
      try {
        console.log(newFileContainer);
        let response = axios
          .post("/api/storeFileContainer", {
            container: newFileContainer,  //add
            year: newYear,
            marker: "registrar", //editar
          });
        app.$alert("Se ha registrado correctamente los archivos del documento", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        this.$router.push({
          name: "filecontainer",
        });
      } catch (error) {
        app.$alert("No se registro nada", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },


    //* actualizar un componente al hacer la seleccion nueva *//
    OnchangeTypeDocument(idx) {
      let resultado = this.typesDocument.find(tipo => tipo.id == idx);
      this.filecontainer.id_arch = resultado.id;
      this.filecontainer.descr = resultado.descr;
    },
    OnchangeNumerable(idx) {
      if (idx != 1)
        this.isNumerable = false;
      else {
        this.isNumerable = true;
        this.filecontainer.numeral = '';
      }
      console.log(idx);
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

.el-form .el-select {
  width: 100%;
}
</style>
  