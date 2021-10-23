<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>verificar documento</span>
        <!--
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddPerson"
        >nueva persona</el-button>
        -->
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <!--
                <el-select v-model="selectParameter" slot="prepend" placeholder="Select">
                    <el-option v-for="item in parameters" :label="item.attribute" :value="item.value" :key="item.value"></el-option>
                </el-select>
          -->
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="initSearchDocumentByDescription"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="documents" style="width: 100%">
          <el-table-column prop="tag_doc" label="documento"></el-table-column>
          <el-table-column prop="des_doc" label="descripcion"></el-table-column>
          <el-table-column prop="ci_per" label="persona"></el-table-column>
          <el-table-column prop="des_per" label="descripcion"></el-table-column>
          <el-table-column
            prop="est_sol"
            label="estado"
            width="280"
          ></el-table-column>
          <el-table-column prop="zip" label="Operacion" width="120">
            <el-select v-model="value" placeholder="operacion">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              >
              </el-option>
            </el-select>
          </el-table-column>
          <el-table-column align="right" width="220">
            <template slot-scope="scope">
              <el-button
                @click="initShowHistoryDocument(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
                >Seguimiento</el-button
              >
              <el-button
                @click="initEditStateDocument(scope.$index, scope.row)"
                type="primary"
                size="mini"
                plain
                >Guardar</el-button
              >
              <el-button
                @click="initPrintReportDocument(scope.$index, scope.row)"
                type="danger"
                plain
                size="mini"
                >Imprimir</el-button
              >
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getDataPageSelected"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Personas",
  data() {
    return {
      //selectParameter: "",
      /*
      parameters: [
        {attribute: "carnet de identidad", value: "personal",},
        {attribute: "apellido paterno", value: "paterno",},
        {attribute: "apellido materno", value: "materno",},
        {attribute: "nombres", value: "nombres",},
      ],*/

      messages: {},
      people: [],
      documents: [],
      options: [],
/*
      options: [
        {
          value: "Aprobado",
          label: "Aprobado",
        },
        {
          value: "Observado",
          label: "Observado",
        },
      ],*/
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      loading: true,
    };
  },
  mounted() {
    let app = this;
    initSearchDocumentByDescription();
  },
  
  methods: {

    getDataPageSelected(page) {
      let app = this;
      app.loading = true;
      axios
        .post("/api/documents", {
          description: app.writtenTextParameter,
          soa: app.writtenTextParameter,
          year: app.writtenTextParameter,
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.documents = Object.values(response.data.data);
          app.pagination = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    initShowHistoryDocument() {
      this.$router.push({
        name: "addperson",
      });
    },
    initEditStateDocument(index, row) {
      console.log(index, row);
      let personal = row.personal;
      this.$router.push({
        name: "editperson",
        params: {
          id: personal.trim(),
        },
      });
    },
    initSearchDocumentByDescription() {
      let app = this;
      app.loading = true;
      axios
        .post("/api/documents", {
          description: app.writtenTextParameter,
          soa: app.writtenTextParameter,
          year: app.writtenTextParameter,
        })
        .then((response) => {
          app.loading = false;
          app.documents = response.data.data;
          console.log(response);
          app.pagination = response.data;
        })
        .catch((error) => {
          this.error = error;
          this.$notify.error({
            title: "Error",
            message: this.error.message,
          });
        });
    },
    initPrintReportDocument(index, row) {
      let personal = row.nro_dip;
      //router.push({ name: 'editperson', params: { userId: personal }})
    },
  },
};
</script>

<style scoped>
.el-input .el-select {
  width: 180px;
}
</style>
