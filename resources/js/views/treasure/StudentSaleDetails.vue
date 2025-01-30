<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>dia de venta de valores no. {{ day }}</span>
        <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="INSERTE EL NUMERO DE CARNET DE IDENTIDAD" v-model="writtenTextParameter"
          class="input-with-select">
          <el-button slot="append" icon="el-icon-search" @click="initGetDataOfStudent">Buscar</el-button>
        </el-input>
      </div>
      <br />
      <el-row :gutter="20">
        <el-col :span="11">
          <div class="grid-content bg-purple">
            <el-form ref="form" :model="this.postulations" label-width="200px" size="mini">
              <p>Datos generales</p>
              <el-form-item label="carnet de identidad">
                {{ postulations.nro_dip }}
              </el-form-item>
              <el-form-item label="apellido paterno">
                {{ postulations.paterno }}
              </el-form-item>
              <el-form-item label="apellido materno">
                {{ postulations.materno }}
              </el-form-item>
              <el-form-item label="nombres">
                {{ postulations.nombres }}
              </el-form-item>
              <el-form-item label="modalidad de ingreso">
                {{ postulations.modalidad }}
              </el-form-item>
              <el-form-item label="programa">
                {{ postulations.programa }}
              </el-form-item>
            </el-form>
          </div>
        </el-col>
        <el-col :span="13">
          <div class="grid-content bg-purple">
            <p>Valores a adquirir</p>
            <el-table :data="valuesPostulations" border show-summary style="width: 100%" size="small">
              <el-table-column align="left" fixed="left" width="95">
                <template slot-scope="scope">
                  <el-button :disabled="valuesPostulations[scope.$index].numerable == 'N'"
                    @click="initAddNumeration(scope.$index, scope.row)" size="mini" type="danger">agregar</el-button>
                </template>
              </el-table-column>
              <el-table-column prop="cod_val" label="cod." width="65"> </el-table-column>
              <el-table-column prop="des_val" label="descripcion" width="300">
              </el-table-column>
              <el-table-column prop="descripcion" label="tipo" width="150">
              </el-table-column>
              <el-table-column prop="pre_uni_val" sortable label="Precio" align="right">
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
      <el-button type="success" size="small" @click="saveTransaction()"
        :disabled="this.DisabledStore">guardar</el-button>
      <el-button type="primary" size="small" @click="printTransactions()"
        :disabled="this.DisabledPrint">imprimir</el-button>
      <el-button size="small" @click="resetTransaction()">nuevo</el-button>
      <el-row> </el-row>
    </el-card>

    <el-dialog title="admisiones" :visible.sync="visible" width="50%" center :before-close="handleClose" size="mini">
      <div style="margin-top: 15px">
        <el-tag type="danger">
          La persona cuenta con varias admisiones, debe escoger una para continuar con el proceso.
        </el-tag>
        <hr>
        <el-table :data="muchPostulations" height="250" style="width: 100%">
          <el-table-column prop="modalidad" label="modalidad">
            <template slot-scope="scope">
              <el-tag>{{ scope.row.modalidad }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="programa" label="programa"></el-table-column>
          <el-table-column align="right-center" label="">
            <template slot-scope="scope">
              <el-button @click="SelectedPostulation(scope.$index, scope.row)" size="mini" type="primary">seleccionar
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
      </span>
    </el-dialog>


    <el-dialog title="Valores Fisicos" :visible.sync="visibleKardex">
      <el-form :model="form">
        <el-form-item label="Numero del folder amarillo">
          <el-input v-model="form.kardex" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="visibleKardex = false">cancelar</el-button>
        <el-button type="primary" @click="visibleKardex = false">confirmar</el-button>
      </span>
    </el-dialog>
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
      kardex: 0,
      saleOfDay: {},
      valuesPostulations: [],
      muchPostulations: [],
      visible: false,
      visibleKardex: false,
      form: {
        kardex: 0,
      },
      dataKardex: {},
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
    console.log(app.user);
    axios
      .post("/api/getSaleOfDayById", {
        id: app.day,
        user: app.user.usuario,
        year: app.user.gestion,
      })
      .then(function (response) {
        console.log(response);
        app.saleOfDay = response.data[0];
        if (app.saleOfDay.estado == "V")
          app.$router.push({
            name: "salestudents",
          });
      });
  },
  methods: {
    test() {
      alert("presiono el foco");
    },
    closeModal() {
      this.visible = false;
    },
    handleClose(done) {
      this.visible = false;
    },
    //coloca la numeracion al kardex
    initAddNumeration(index, row) {
      this.visibleKardex = true;
    },

    async saveTransaction() {
      var app = this;
      var newDayTransactions = app.saleOfDay;
      var newUser = app.user;
      var newPostulations = app.postulations;
      var newValuesPostulations = app.valuesPostulations;
      try {
        let response = await axios.post("/api/getVerifyKardex", {
          kardex: app.form.kardex,
          gestion: app.user.gestion
        });
        this.dataKardex = response.data[0];
        console.log(this.dataKardex);
        if (app.dataKardex.id = '0' && app.dataKardex.desde != '0' && app.dataKardex.hasta != '0') {
          //el kardex no se utilizo
          let response2 = await axios.post("/api/storeTransactionsByStudents", {
            dayTransactions: newDayTransactions,
            postulations: newPostulations,
            valuesPostulations: newValuesPostulations,
            kardex: app.dataKardex.desde,
            user: newUser,
            marker: "registrar",
          });
          this.dataKardex = response2.data;
          console.log(this.dataKardex);
          app.$alert("Se ha registrado correctamente al estudiante", 'Gestor de mensajes', {
            dangerouslyUseHTMLString: true
          });
          app.DisabledStore = true;
          app.DisabledPrint = false;
        } else {
          //el kardex se utilizo o no esta en el rango  
          alert(app.dataKardex.mensaje);        
          this.visibleKardex = true;
          this.form.kardex = 0;
        }
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true
        });
      }
    },
    /*
    saveTransaction() {
      var app = this;
      var newDayTransactions = app.saleOfDay;
      var newUser = app.user;
      var newPostulations = app.postulations;
      var newValuesPostulations = app.valuesPostulations;
      if ((app.form.kardex >= 87444 && app.form.kardex <= 87450) || (app.form.kardex >= 87452 && app.form.kardex <= 87460) || (app.form.kardex >= 87524 && app.form.kardex <= 87850) || (app.form.kardex >= 88651 && app.form.kardex <= 93650)) {
        axios
          .post("/api/storeTransactionsByStudents", {
            dayTransactions: newDayTransactions,
            postulations: newPostulations,
            valuesPostulations: newValuesPostulations,
            kardex: app.form.kardex,
            user: newUser,
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
      }
      else {
        this.visibleKardex = true;
        this.form.kardex = 0;
      }
    },
    */

    //funcion para rescatar la informacion, modalidad de ingreso y valores del estudiante que se va a registrar
    initGetDataOfStudent() {
      let app = this;
      //alert(app.user.gestion);
      axios
        .post("/api/getDataOfStudentById", {
          id: app.writtenTextParameter,
          user: app.user.usuario,
          year: app.user.gestion,
        })
        .then((response) => {
          console.log(response.data);
          if (response.data.length > 1) {
            app.visible = true;
            app.muchPostulations = response.data;
          } else {
            app.postulations = response.data[0];
            app.texto = JSON.stringify(app.postulations);
            app.searchPostulationsValues();
          }
        })
        .catch((error) => {
          this.error = error.response.data;
          app.$alert(this.error.message, 'Gestor de errores', {
            dangerouslyUseHTMLString: true
          });
        });
    },
    async searchPostulationsValues() {
      let app = this;
      try {
        let response = await axios.post("/api/valuesprocedure", {
          id: app.postulations.id_modalidad,
          year: app.user.gestion,
          id_programa: app.postulations.id_programa,
        });
        app.valuesPostulations = response.data;
        app.DisabledStore = false;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
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
    SelectedPostulation(index, row) {
      let app = this;
      app.postulations = row;
      app.texto = JSON.stringify(app.postulations);
      app.searchPostulationsValues();
      this.visible = false;
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
      app.form.kardex = 0;
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
