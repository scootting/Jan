<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>dia de venta de valores no. {{ day }}</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test"
          >ayuda</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE EL NUMERO DE CARNET DE IDENTIDAD"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="initGetDataOfStudent"
          >Buscar</el-button>
        </el-input>
      </div>
      <br />
      <el-row :gutter="20">
        <el-col :span="11"
          ><div class="grid-content bg-purple">
            <el-form
              ref="form"
              :model="this.postulations"
              label-width="200px"
              size="mini"
            >
              <el-form-item label="carnet de identidad">
                <el-input v-model="postulations.nro_dip" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido paterno">
                <el-input v-model="postulations.paterno" disabled></el-input>
              </el-form-item>
              <el-form-item label="apellido materno">
                <el-input v-model="postulations.materno" disabled></el-input>
              </el-form-item>
              <el-form-item label="nombres">
                <el-input v-model="postulations.nombres" disabled></el-input>
              </el-form-item>
              <el-form-item label="modalidad de ingreso">
                <el-input v-model="postulations.modalidad" disabled></el-input>
              </el-form-item>
            </el-form></div
        ></el-col>
        <el-col :span="13"
          ><div class="grid-content bg-purple">
            <el-table
              :data="valuesPostulations"
              border
              show-summary
              style="width: 100%"
              size="small"
            >
              <el-table-column prop="cod_val" label="cod." width="65"> </el-table-column>
              <el-table-column prop="des_val" label="descripcion" width="550">
              </el-table-column>
              <el-table-column prop="pre_uni_val" sortable label="Precio" align="right">
              </el-table-column>
            </el-table></div
        ></el-col>
      </el-row>
      <el-button type="success" size="small" @click="saveTransaction()" :disabled= "this.DisabledStore"
        >guardar</el-button
      >
      <el-button type="primary" size="small" @click="printTransactions()" :disabled= "this.DisabledPrint"
        >imprimir</el-button
      >
      <el-button size="small" @click="resetTransaction()">nuevo</el-button>
      <el-row> </el-row>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "",
  data() {
    return {
      //controles de los botones
      DisabledStore: true,
      DisabledPrint: true,
      //
      writtenTextParameter: "",
      user: this.$store.state.user,
      day: "",
      saleOfDay: [],
      valuesPostulations: [],
      postulations: {
        nro_dip: "",
        paterno: "",
        materno: "",
        nombres: "",
        modalidad: "",
        id_modalidad: "",
      },
      texto: "",
    };
  },
  mounted() {
    let app = this;
    app.day = app.$route.params.id;
    axios
      .post("/api/getSaleOfDayById", {
        id: app.day,
        user: app.user.usuario,
        year: app.user.gestion,
      })
      .then(function (response) {
        app.saleOfDay = response.data[0];
        if (app.saleOfDay.estado == "V")
          app.$router.push({
            name: "salestudents",
          });
        //alert("El dia ya esta verificado");
      })
      .catch(function (response) {
        alert("no se puede crear el registro de los valores del estudiante");
      });
  },
  methods: {
    test(){
      alert("presiono el foco");
    },
    //Guardar la informacion necesaria para los alumnos nuevos
    saveTransaction() {
      var app = this;
      var newDayTransactions = app.saleOfDay;
      var newPostulations = app.postulations;
      var newValuesPostulations = app.valuesPostulations;
      axios
        .post("/api/storeTransactionsByStudents", {
          dayTransactions: newDayTransactions,
          postulations: newPostulations,
          valuesPostulations: newValuesPostulations,
          marker: "registrar",
        })
        .then(function (response) {
              app.$alert("Se ha registrado correctamente a la persona", 'Gestor de mensajes', {
                dangerouslyUseHTMLString: true
              });              
        })
        .catch(function (error) {
              app.$alert("Se ha registrado correctamente a la persona sin errores", 'Gestor de mensajes', {
                dangerouslyUseHTMLString: true
              });              
        });
        app.DisabledStore = true;
        app.DisabledPrint = false;
    },

    //funcion para rescatar la informacion, modalidad de ingreso y valores del estudiante que se va a registrar
    initGetDataOfStudent() {
      let app = this;
      //alert(app.user.gestion);
      axios
        .post("/api/getDataOfStudentById", {
          id: app.writtenTextParameter,
          year: app.user.gestion,
        })
        .then((response) => {
          app.postulations = response.data[0];
          app.texto = JSON.stringify(app.postulations);
          /*de acuerdo a la postulacion se debe imprimir los valores*/
          axios
            .post("/api/valuesprocedure", {
              id: app.postulations.id_modalidad,
              year: app.user.gestion,
            })
            .then((response) => {
              app.valuesPostulations = response.data;
              app.DisabledStore = false;
              /*de acuerdo a la postulacion se debe imprimir los valores*/
            })
            .catch((error) => {
              this.error = error.response.data;
              app.$alert(this.error.message, 'Gestor de errores', {
                dangerouslyUseHTMLString: true
              });              
            });
        })
        .catch((error) => {
          this.error = error.response.data;
          app.$alert(this.error.message, 'Gestor de errores', {
              dangerouslyUseHTMLString: true
          });              
        });
    },
    ///api/reports/
    printTransactions() {
      var app = this;
      app.ci_per = app.postulations.nro_dip; 
      axios({
        url: "/api/reports/",
        params: {
          id_dia: app.day,   
          ci_per: app.ci_per,
          gestion: app.user.gestion,
          usr_cre: app.user.usuario,
        },
        method: "GET",
        responseType: "arraybuffer",
      }).then((response) => {
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
      });
    },
    resetTransaction() {
      let app = this;
      (this.writtenTextParameter = ""),
        (this.valuesPostulations = []),
        (this.postulations = {
          nro_dip: "",
          paterno: "",
          materno: "",
          nombres: "",
          modalidad: "",
          id_modalidad: "",
        });
        app.DisabledStore = true;
        app.DisabledPrint = true;
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
