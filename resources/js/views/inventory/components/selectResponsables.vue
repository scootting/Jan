<template>
  <el-select
    v-model="selected"
    placeholder="seleccione el Responsable"
    :multiple="multiple"
    :loading="responsablesLoading"
    @change="onChange()"
  >
    <el-option
      v-for="responsable in Responsables"
      :key="responsable.nro_dip"
      :label="responsable.paterno + ' ' + responsable.materno + ' ' + responsable.nombres"
      :value="responsable.nro_dip"
    >
    </el-option>
  </el-select>
</template>

<script>
export default {
  name: "selectResponsable",
  props: {
    ofcCod: {
      type: String,
      default: '',
    },
    multiple: {
      type: Boolean,
      default: false,
    },
    value: {
      default: null,
    },
  },
  data() {
    return {
      Responsables: [],
      responsablesLoading: false,
      selected:'',
    };
  },
  created() {
    this.getResponsables();
    this.selected = this.value;
  },
  watch: {
    ofcCod(newVal){
      if(newVal){
        this.getResponsables();
      }
      else{
        this.ofcCod='';
        this.getResponsables();
      }
    },
  },
  methods: {
    onChange() {
       this.$emit('input',this.selected)
    },
    getResponsables() {
      this.responsablesLoading = true;
      let cod_soa = this.ofcCod;
      axios
        .get("/api/inventory/responsables/" + cod_soa)
        .then((data) => {
          this.responsablesLoading = false;
          this.Responsables = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
  },
};
</script>

<style>
</style>