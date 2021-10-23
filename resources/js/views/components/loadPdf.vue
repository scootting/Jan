<template>
  <div>
    <el-row :gutter="20" type="flex" justify="space-around">
      <el-col v-for="pdf in list" :key="pdf.key" :span="4" :offset="0">
        <div style="width: 100%; height: 100px" @click="onClickPdf(pdf)">
          <img
            v-if="file[pdf.key]"
            style="height: 100%; width: 100%; object-fit: cover"
            :src="file[pdf.key]"
          />
           <div
            v-else
            style="
              width: 100%;
              height: 100px;
              display: flex;
              justify-content: center;
              align-items: center;
              background-color:#C6DFF6;
              color:black;"
          >CARGAR PDF
          </div> 
        </div>
      </el-col>
    </el-row> 
    <br/><br/>
    <div v-if="loadPdf">
      <el-row :gutter="20" type="flex" justify="center">
        {{ loadPdf.label }}
      </el-row>
      <br/>
      <el-row :gutter="20" type="flex" justify="center">
          <pdf-load :info="info" @on-success="onLoadPdf"/>
      </el-row>
      <el-row :gutter="20" type="flex" justify="center">
        <el-button type="danger" plain size="mini" @click="loadPdf = null"
          >Cancelar</el-button
        >
      </el-row>
    </div>
  </div>
</template>
<script>
import pdfLoad from "./pdfLoad.vue";
export default {
  components: { pdfLoad },
  name: "LoadPdf",
  props: ["info", "defaultValues"],
  data() {
    return {
      list: [
        {
          key: "path",
          label: "Convocatoria",
        },
      ],
      file: {
          path:null,
      }, 
      loadingPdf: false,
      loadPdf: null,
    };
  },
  watch: {
    defaultValues(newVal) {
      this.list.forEach((e) => {
        if (newVal[e.key] != "") this.file[e.key] = newVal[e.key];
      });
      var lista = this.list;
      this.list = [];
      this.list = lista;
    },
  },
  methods: {
    onLoadPdf(resp) {
      this.file[this.loadPdf.key] = resp.path;
      this.loadPdf = null;
    },
    onClickPdf(pdfName) {
      if (!this.loadPdf) this.loadPdf = pdfName;
    },
  },
};
</script>
<style>
</style>