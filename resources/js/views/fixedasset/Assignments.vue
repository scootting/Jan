<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Documentos</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddAssignment">nuevo documento</el-button>
          <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddAssignment2">no funcionara asi</el-button>
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="ingrese una descripcion" v-model="writtenTextParameter" class="input-with-select"
          @keyup.enter.native="getAssignments">
          <el-button slot="append" icon="el-icon-search" @click="getAssignments(1)"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column prop="fecha" label="fecha"></el-table-column>
          <el-table-column label="unidad academica o administrativa" width="320">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.des_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="idc" label="no. documento"></el-table-column>
          <el-table-column align="right" width="320">
            <template slot-scope="scope">
              <el-button @click="initAddFixedAssets(scope.$index, scope.row)" type="primary" size="small">Asignar</el-button>
                <el-button @click="initEditFixedAssets(scope.$index, scope.row)" type="info" size="small">editar e imprimir </el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total"
          @current-change="getAssignments"></el-pagination>
      </div>
    </el-card>

    <!-- Form Add Document to Archive-->
    <el-dialog title="detalle del documento" :visible.sync="dialogFormVisible">
      <el-form :model="document" label-width="270px" size="small">
        <el-form-item label="Unidad academica o administrativa">
          <el-autocomplete class="inline-input" v-model="document.des_prg" :fetch-suggestions="querySearch"
            style="width: 100%;" placeholder="ingrese: la descripcion y seleccione" :trigger-on-focus="false"
            @select="handleSelect"></el-autocomplete>
        </el-form-item>
        <el-form-item label="fecha del registro">
          <el-date-picker type="date" v-model="document.fecha" placeholder="seleccione una fecha" style="width: 100%"
            format="yyyy/MM/dd" value-format="yyyy-MM-dd"></el-date-picker>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="small" plain @click="initStoreAssignments">guardar</el-button>
        <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$store.state.user,
      loading: false,
      data: [],
      dataPrograms: [],
      document: {},
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
      dialogFormVisible: false,
    };
  },
  mounted() {
    this.getDataPrograms();
    this.getAssignments(1);
  },
  methods: {
    test() {
      alert("bienvenido al modulo");
    },

    //  * AC3. Guardar la nueva asignacion
    async initStoreAssignments() {
      var app = this;
      try {
        let response = axios
          .post("/api/storeAssignments", {
            document: app.document, 
            user: app.user,
            marker: "registrar",
          });
        app.$alert("Se ha registrado correctamente la documentacion", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
        app.dialogFormVisible = false;
        app.getAssignments();
      } catch (error) {
        app.$alert("No se registro nada", 'Gestor de mensajes', {
          dangerouslyUseHTMLString: true
        });
      };
    },

    //  *  AC2. Obtiene la lista de asignaciones
    async getAssignments(page) {
      this.loading = true;
      let app = this;
      try {
        let response = await axios.post("/api/getAssignments", {
          description: app.writtenTextParameter,
          year: app.user.gestion,
          page: page,
        });
        app.data = Object.values(response.data.data);
        app.pagination = response.data;
        console.log(response);
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
      app.loading = false;
    },

    //  *  AC1. Obtiene la lista de categorias programaticas    
    async getDataPrograms() {
      var app = this;
      try {
        let response = await axios.post("/api/getDataPrograms", {
          year: app.user.gestion,
        });
        app.dataPrograms = response.data;
        console.log(app.dataPrograms);
      } catch (error) {
        this.error = error.response.data;
        alert(error);
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },

    querySearch(queryString, cb) {
      var links = this.dataPrograms;
      var results = queryString ? links.filter(this.createFilter(queryString)) : links;
      cb(results);
    },
    createFilter(queryString) {
      return (link) => {
        return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
      };
    },
    handleSelect(item) {
      this.document.cod_prg = item.cod_prg;
      this.document.des_prg = item.cat_des;
      console.log(this.document);
    },

    initAddAssignment2() {
      this.dialogFormVisible = true;
    },

    initAddAssignment() {
      this.$router.push({
        name: "addassignment",
        params: {
          id: '0',
        },
      });
    },

    storeAssignment() {

    },

    initAddFixedAssets(idx, row) {
      this.$router.push({
        name: "assignmentsdetails",
        params: {
          id: row.id,
        },
      });
    },
    initEditFixedAssets(idx, row) {
      this.$router.push({
        name: "editassignmentsdetails",
        params: {
          id: row.id,
        },
      });
    },
  },
};
</script>

<style></style>