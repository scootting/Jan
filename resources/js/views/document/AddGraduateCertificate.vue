<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nueva certificacion de diplomado</span>
        <el-button style="float: right; padding: 3px 0" type="text"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-row>
          <el-col :span="24">
            <el-form
              ref="form"
              :model="certificate"
              :rules="rules"
              label-width="260px"
            >
              <el-form-item
                size="small"
                label="numero de identificacion"
                prop="nro_doc"
              >
                <el-input size="small" v-model="certificate.nro_doc"></el-input>
              </el-form-item>
              <el-form-item
                size="small"
                label="fecha de emision del memorial"
                prop="fec_memo"
              >
                <el-date-picker
                  size="small"
                  type="date"
                  placeholder="seleccione una fecha"
                  v-model="certificate.fec_memo"
                  style="width: 100%"
                ></el-date-picker>
              </el-form-item>
              <el-form-item
                size="small"
                label="numero de D.V.R."
                prop="nro_dvr"
              >
                <el-input size="small" v-model="certificate.nro_dvr"></el-input>
              </el-form-item>
              <el-form-item
                size="small"
                label="fecha de emision del proveido"
                prop="fec_prov"
              >
                <el-date-picker
                  size="small"
                  type="date"
                  placeholder="seleccione una fecha"
                  v-model="certificate.fec_prov"
                  style="width: 100%"
                ></el-date-picker>
              </el-form-item>
              <el-form-item size="small" label="carnet de identidad">
                <el-col :span="8">
                  <el-input
                    v-model="certificate.ci_per"
                    ref="ci_per"
                    @keyup.enter.native="initSearchPerson"
                  ></el-input>
                </el-col>
                <el-col :span="16">
                  <el-input v-model="certificate.des_per" disabled></el-input>
                </el-col>
              </el-form-item>
              <el-form-item size="small" label="descripcion del diplomado">
                <el-input
                  size="small"
                  v-model="certificate.des_dip"
                  ref="des_dip"
                ></el-input>
              </el-form-item>
              <el-form-item size="small" label="importe total (en bs.)">
                <el-input size="small" v-model="certificate.imp_dip"></el-input>
              </el-form-item>
              <el-form-item size="small" label="estado del certificado">
                <el-radio-group v-model="certificate.est_doc" size="small">
                  <el-radio-button label="V"></el-radio-button>
                  <el-radio-button label="S"></el-radio-button>
                </el-radio-group>
              </el-form-item>
              <el-form-item>
                <el-button
                  size="small"
                  type="primary"
                  @click.prevent="saveGraduateCertificate"
                  plain
                  >Guardar</el-button
                >
                <el-button
                  size="small"
                  type="danger"
                  @click="noGraduateCertificate"
                  plain
                  >Cancelar</el-button
                >
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
  name: "AniadirPersona",
  data() {
    return {
      messages: {},
      certificate: {
        nro_doc: "",
        nro_dvr: "",
        fec_memo: "",
        fec_prov: "",
        ci_per: "",
        ci_auth: "",
        des_per: "",
        des_dip: "",
        imp_dip: "",
        est_doc: "S",
        fec_cre: "",
        usr_cre: "",
      },
      selectedPerson: [],
      rules: {
        nro_doc: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        nro_dvr: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        fec_memo: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            type: "date",
            required: true,
            message: "el campo debe ser una fecha",
            trigger: "blur",
          },
        ],
        fec_prov: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            type: "date",
            required: true,
            message: "el campo debe ser una fecha",
            trigger: "blur",
          },
        ],

        nombres: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
        materno: [
          {
            required: true,
            message: "El campo no puede estar vacio",
            trigger: "blur",
          },
          {
            min: 2,
            max: 100,
            message: "el tamaño no puede ser menos de 2 o mas de 100",
            trigger: "blur",
          },
        ],
      },
    };
  },
  mounted() {},
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    saveGraduateCertificate() {
      var app = this;
      var newPerson = app.person;
      /*
      axios
        .post("/api/person", {
          persona: newPerson,
          marker: "registrar",
        })
        .then(function (response) {
          alert("se ha creado el registro de la persona");
        })
        .catch(function (response) {
          console.log(response);
          alert("no se puede crear el registro de la persona");
        });*/
    },

    noGraduateCertificate() {
      this.$router.push("/api");
    },

    initSearchPerson() {
      let app = this;
      let id = this.certificate.ci_per;
      axios
        .get("/api/person/" + id)
        .then(function (response) {
          console.log(response.data);
          app.selectedPerson = response.data[0];
          app.certificate.des_per = app.selectedPerson.des_per;
          app.$nextTick(() => app.$refs.des_dip.focus());
        })
        .catch(function () {
          alert("No se puede hallar el registro de la persona indicada");
        });
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
