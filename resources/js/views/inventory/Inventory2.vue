
<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Inventario</span>
      </div>
      <div style="margin-top: 15px">
        <el-input placeholder="INSERTE UNA DESCRIPCION" v-model="writtenTextParameter" class="input-with-select"
          @keyup.enter.native="getOffices">
          <el-button slot="append" icon="el-icon-search" @click="getInventories"></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column width="75" label="No.">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.no_cod }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="ofc_cod" width="100" label="Cat. prog.">
          </el-table-column>
          <el-table-column prop="ofc_des" width="450" label="descripcion categoria programatica"></el-table-column>
          <el-table-column width="150" label="Estado">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Operaciones">
            <template slot-scope="scope">
              <el-button :disabled="data[scope.$index].verificado == true"
                @click="editInventory(scope.$index, scope.row)" type="success" plain size="mini">Editar</el-button>
              <el-button :disabled="data[scope.$index].verificado == true"
                @click="listActivesByDocument(scope.row.no_cod)" type="success" plain size="mini">Ver lista
              </el-button>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Informe">
            <template slot-scope="scope">
              <el-button :disabled="data[scope.$index].verificado == false" @click="generateReportGral(scope.row)"
                type="primary" plain size="mini">General</el-button>
              <el-button @click="initPrintDetailsInventory(scope.row)" type="primary" plain size="mini">Detallado
              </el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total" @current-change="getInventories">
        </el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: "Inventarios2",
  data() {
    return {
      //todo lo revisado en variables
      loading: true,
      user: this.$store.state.user,
      data: [],
      pagination: {
        page: 1,
      },

      messages: {},
      verificado: false,
      showReportes: false,
      writtenTextParameter: "",
    };
  },
  mounted() {
    let app = this;
    this.getInventories(app.pagination.page);
  },
  methods: {
    //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
     async getInventories(page) {
      let app = this;
      try {
        let response = await axios.post("/api/inventory2", {
          user: app.user.usuario,
          year: app.user.gestion,
          page: page,
          //descripcion: app.writtenTextParameter.toUpperCase(),
        });
          app.loading = false;
          console.log(response);
          app.data = Object.values(response.data.data);
          app.pagination = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    //  * 2. Imprimir el reporte del inventario general o detallado de el recurso utilizado.
    initPrintDetailsInventory(row) {
      console.log(row);
      axios({
        url: "/api/inventoryReport/",
        params: {
          office: row.ofc_cod,
          document: row.no_cod,
          year: row.gestion,
          report: "InventoryDetails",
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

    editInventory(index, row) {
      this.$router.push({
        name: "editinventory2",
        params: {
          id: row.id,
        },
      });
    },
    listActivesByDocument(document) {
      this.$router.push({
        name: "inventory2detail",
        params: {
          id_inventory: document,
        },
      });
    },
    generateReportGral(no_cod) {
      axios({
        url: "/api/inventoryReportGral/",
        params: {
          no_doc: no_cod.no_cod,
        },
        method: "GET",
        responseType: "arraybuffer",
      }).then((response) => {
        let blob = new Blob([response.data], {
          type: "application/pdf",
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        console.log(blob);
        let url = window.URL.createObjectURL(blob);
        window.open(url);
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
</style>