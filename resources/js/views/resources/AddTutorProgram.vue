<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Honorarios Profesionales</span>
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataProgram" label-width="220px" size="small">
                <el-form-item label="tipo" prop="detalle">
                  <el-input v-model="dataProgram.detalle" disabled></el-input>
                </el-form-item>
                <el-form-item label="descripcion" prop="descripcion">
                  <el-input v-model="dataProgram.descripcion" disabled></el-input>
                </el-form-item>
                <el-form-item label="importe" prop="costo">
                  <el-input v-model="dataProgram.costo" disabled></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button size="small" type="primary" @click.prevent="initAddTutor" plain>Agregar pago</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
      </div>
      <br>
      <div class="grid-content bg-purple">
        <el-table v-loading="loading" :data="dataStudents" border style="width: 100%" fixed>
          <el-table-column prop="fecha" width="100" label="fecha" align="right"></el-table-column>
          <el-table-column width="120" label="no. de carnet" align="right">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.ci_per }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column width="250" label="apellidos y nombres">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.des_per }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="detalle" width="170" label="tipo de pago"></el-table-column>
          <el-table-column prop="importe" width="100" label="importe" align="right"></el-table-column>
          <el-table-column align="right-center" width="220" fixed="right">
            <template slot-scope="scope">
              <el-button @click="initStudentDetails(scope.row.ci_per)" type="primary" size="small">anular pago
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-card>
    <!--Modulo-->
    <el-dialog title="detalle del pago" :visible.sync="dialogFormVisible">
      <el-form :model="tutor" label-width="220px" size="small">
        <el-form-item label="ci" prop="ci_per">
          <el-input placeholder="ingrese el carnet de identidad" v-model="tutor.ci_per" class="input-with-select">
            <el-button slot="append" icon="el-icon-search" @click="getStudentProgramById">BUSCAR</el-button>
          </el-input>
        </el-form-item>
        <el-form-item label="apellidos y nombres" prop="des_per">
          <el-input v-model="tutor.des_per"></el-input>
        </el-form-item>
        <el-form-item label="importe">
          <el-input v-model="tutor.importe" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="fecha del registro">
          <el-date-picker type="date" v-model="tutor.fecha" placeholder="seleccione una fecha" style="width: 100%"
            format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
        </el-form-item>
        <el-form-item label="glosa">
          <el-input type="textarea" v-model="tutor.glosa"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="small" plain @click="storeSaleStudentProgram">Confirmar pago</el-button>
        <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar pago</el-button>
      </span>
    </el-dialog>
    <!--Modulo-->
  </div>
</template>

<script>

export default {
  data() {
    return {
      dataStudents: [],
      tutor: { fecha: '', ci_per: '', des_per: '', pago: '', monto: '' },
      dataProgram: {},
      dataSale: [],
      id: this.$route.params.id,
      user: this.$store.state.user,
      loading: true,
      dialogFormVisible: false,
    };
  },
  mounted() {
    this.getProgramById();
    this.getSaleStudentByProgram();
  },
  methods: {
    initAddTutor() {
      this.dialogFormVisible = true;
    },

    //  * RP2. Obtener un curso de posgrado.
    async getProgramById() {
      let app = this;
      try {
        let response = await axios.post("/api/getProgramById", {
          id: app.id,
        });
        app.loading = false;
        app.dataProgram = response.data[0];
        console.log(app.dataProgram);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP3. Obtiene un estudiante registrado en un curso.
    async getStudentProgramById() {
      let app = this;
      let temporal = {};
      try {
        let response = await axios.post("/api/getStudentProgramById", {
          id_programa: app.id,
          ci_per: app.student.ci_per,
        });
        console.log(response);
        temporal = response.data[0];
        app.student.ci_per = temporal.ci_per;
        app.student.des_per = temporal.des_per;
      } catch (error) {
        alert("La persona que busca, no se encuentra registrada.")
        app.student.ci_per = '';
      }
    },
    //  * RP18. Obtener los pagos de un curso de postgrado.
    async getSaleStudentByProgram() {
      let app = this;
      try {
        let response = await axios.post("/api/getSaleStudentByProgram", {
          id: app.id,
        });
        app.loading = false;
        app.dataStudents = Object.values(response.data);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP19. Agregar un pago de estudiantes al curso.
    async storeSaleStudentProgram() {
      var app = this;
      try {
        let response = await axios.post("/api/storeSaleStudentProgram", {
          programa: app.id,
          estudiante: app.student,
          marker: "registrar",
        });
        alert("Se ha agregado el pago de un estudiante al curso.");
        app.dialogFormVisible = false;
        app.getSaleStudentByProgram();
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP15. Cerrar el curso.
    async initCloseFinallyProgram() {
      let app = this;
      try {
        let response = await axios.post("/api/closeFinallyProgram", {
          id: app.id,
        });
        app.loading = false;
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
<style scoped></style>