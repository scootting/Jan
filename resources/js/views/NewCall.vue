<template>
  <div>
    <el-card>
      <div slot="header" class="clearfix">
        <span>Modulo de Nueva convocatoria </span>
        <el-button
          style="float: right; padding: 3px 0"
          type="text"
          @click="test"
          >ayuda</el-button
        >
      </div>
      <div>
        <el-form ref="form" :model="form" label-width="120px">
          <el-form-item label="Codigo" prop="codigo">
            <el-input
                disabled
                type="text"
                v-model="form.codigo"
              ></el-input>
          </el-form-item>
          <el-form-item label="Descripcion">
            <el-input type="textarea" v-model="form.descripcion"></el-input>
          </el-form-item>

          <el-form-item label="Documento PDF">
            <el-upload
              class="upload-demo"
              :on-preview="handlePreview"
              :on-remove="handleRemove"
              multiple
              :limit="3"
              :on-exceed="handleExceed"
              :file-list="fileList"
            >
              <el-button size="small" type="primary"
                >Clic para subir archivo</el-button
              >
            </el-upload>
          </el-form-item>
          <el-form-item label="Valido hasta:">
            <el-col :span="11">
              <el-date-picker
                type="date"
                placeholder="seleccionar fecha"
                v-model="form.date1"
                style="width: 100%"
              ></el-date-picker>
            </el-col>
          </el-form-item>

          <el-form-item>
            <el-button type="primary" @click="saveCall">Guardar</el-button>
            <el-button>Cancelar</el-button>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "nuevaConvocatoria",
  data() {
    return {
      nro: null,
      form: {
        codigo: "",
        descripcion: "",
        date1: "",
        fileList: [
        ]
      },
    };
  },
  mounted() {
    console.log(
      "mensaje de recuperacion de datos desde Crear nueva convocatoria "
    );
    this.getNumero();
  },
  computed:{
      getNroDoc(){
      return this.nro;
    }
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },
    getNumero() {
      axios
        .get("/api/newCall/nro/")
        .then((data) => {
          this.nro = data.data;
          this.form.codigo = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    saveCall() {
      axios
        .post("/api/newCall/save", this.form)
        .then((data) => {
          this.$notify.success({
            title: "Convocatoria registrada exitosamente!",
            message: "Se realizó el registro de la convocatoria",
            duration: 3000,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `El límite es 3, haz seleccionado ${
          files.length
        } archivos esta vez, añade hasta ${files.length + fileList.length}`
      );
    },
  },
};
</script>

<style>
</style>