<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo contenedor</span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form :model="document" label-width="220px" size="small">

              <el-form-item label="tipo de contenedor">
                <el-select v-model="document.descr" value-key="descr" size="small"
                  placeholder="seleccione el tipo de documento" @change="OnchangeTypeDocument">
                  <el-option v-for="item in typesDocument" :key="item.id" :label="item.descr" :value="item.id">
                  </el-option>
                </el-select>
              </el-form-item>

              <el-form-item label="Numero de contenedor">
                <el-input v-model="document.numeral" autocomplete="off" disabled></el-input>
              </el-form-item>
              <el-form-item label="fecha del registro">
                <el-date-picker type="date" v-model="document.fecha" placeholder="seleccione una fecha"
                  style="width: 100%" format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
              </el-form-item>
              <el-form-item label="glosa o descripcion">
                <el-input type="textarea" v-model="document.glosa" autocomplete="off"></el-input>
              </el-form-item>
            </el-form>
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
      user: this.$store.state.user,
      document: {},
      typesDocument: [],
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
    //* actualizar un componente al hacer la seleccion nueva *//
    OnchangeTypeDocument(idx) {
    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
}

.el-form {
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 60px;
}
</style>
