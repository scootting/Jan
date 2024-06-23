<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>gestionar programa</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="addStudentProgram">agregar estudiante</el-button>
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
                  <el-button size="small" type="primary" @click.prevent="initCloseProgram" plain>Cerrar programa
                    Academico</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
      </div>
      <div>
        <el-table v-loading="loading" :data="dataStudents" style="width: 100%" fixed>
          <el-table-column width="120" label="no. de carnet">
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

          <el-table-column prop="deuda" width="100" label="deuda" align="right"></el-table-column>
          <el-table-column prop="pago" width="100" label="cancelado" align="right"></el-table-column>
          <el-table-column prop="saldo" width="100" label="saldo" align="right"></el-table-column>
          <el-table-column align="right-center" width="320" fixed="right">
            <template slot-scope="scope">
              <el-button @click="initStudentDetails(scope.row.ci_per)" type="primary" size="small">ver pagos
              </el-button>
              <el-button @click="initStudentCertificate(scope.row.ci_per)" type="danger" size="small">imprimir certificacion
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </el-card>
    <information :visible="isVisible" :tag='tag' @update-visible="updateIsVisible"></information>
  </div>
</template>

<script>
import information from "../components/Information.vue";

export default {
  components: {
    information,
  },
  data() {
    return {
      dataStudents: [],
      student: { ci_per: '', des_per: '', deuda: 0.0, pago: 0.0, saldo: 0.0 },
      dataProgram: {},
      dataTransactions: [],
      id: this.$route.params.id,
      user: this.$store.state.user,
      loading: true,
      isVisible: false,                                           //componente campo visible
      tag: 'persona',                                             //componente que informacion desea traer      
    };
  },
  mounted() {
    this.getProgramById();
    this.getStudentsByProgram();
  },
  methods: {
    addStudentProgram() {
      this.isVisible = true;
      this.tag = 'persona';
    },

    updateIsVisible(visible, data) {
      this.isVisible = visible;
      let temporal = data;
      this.student.ci_per = temporal.id;
      this.student.des_per = temporal.details;
      this.deuda =this.dataProgram.costo;
      this.storeStudentProgram();
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
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP13. Obtener la lista de estudiantes por cursos de posgrado.
    async getStudentsByProgram() {
      let app = this;
      try {
        let response = await axios.post("/api/getStudentsByProgram", {
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

    //  * RP14. Agregar un grupo de estudiantes al curso.
    async storeStudentProgram() {
      var app = this;
      try {
        let response = await axios.post("/api/storeStudentByProgram", {
          programa: app.id,
          estudiante: app.student,
          marker: "registrar",
        });
        alert("se ha agregado un nuevo estudiante al curso");
        app.getStudentsByProgram();
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP15. Cerrar el curso.
    async closeFinallyProgram() {
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

    //  * RP16. mostrar los pagos de un estudiante.
    async initStudentDetails(id) {
      let app = this;
      let id_student = id;
      try {
        let response = await axios.post("/api/getStudentDetails", {
          id: app.id,
          id_student: id_student,
        });
        app.loading = false;
        console.log(response);
        app.dataTransactions = Object.values(response.data);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    //  * RP17. mostrar los pagos de un estudiante.
    initStudentCertificate(id) {
      let id_student = id;
      axios({
        url: "/api/studentProgramCertificate/",
        params: {
          id: app.id,
          id_student: id_student,
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

  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
