<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Bienes de uso para el inventario</span>
        <el-button style="float: right; padding: 3px 0" type="text">Ayuda</el-button>
      </div>
      <!--
      <div>
        <el-form
          v-if="doc_inv"
          :model="doc_inv"
          label-width="160px"
          :inline="false"
          size="mini"
        >
          <el-form-item label="Oficina:">
            <el-input
              v-model="doc_inv.oficina.descripcion"
              style="width: 200px"
              disabled
            ></el-input>
          </el-form-item>
          <el-form-item label="Sub Oficinas:">
            <el-tag
              v-for="sub_oficina in doc_inv.sub_oficinas"
              :key="sub_oficina.id"
              type="default"
              size="normal"
              effect="dark"
              >{{ sub_oficina.descripcion }}</el-tag
            >
          </el-form-item>
          <el-form-item label="Encargados de inventario:">
            <el-tag
              v-for="encargado in doc_inv.encargados"
              :key="encargado.nro_dip"
              type="default"
              size="normal"
              effect="dark"
              >{{ formatResponsable(encargado).responsable }}</el-tag
            >
          </el-form-item>
        </el-form>
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
          @keyup.enter.native="getActivesSearch"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getActivesSearch"
          ></el-button>
        </el-input>
      </div>
      <br />
      -->
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column label="codigo" width="130">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="small">{{ scope.row.cod_act }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="act_des" label="descripcion" width="450"></el-table-column>
          <el-table-column label="existencia" width="180">
            <template slot-scope="scope">
              <el-checkbox :disabled="scope.row.guardado === true" v-model="data[scope.$index].validacion"
                label="verificado" size="mini"></el-checkbox>
            </template>
          </el-table-column>
          <el-table-column label="estado" width="150">
            <el-select :disabled="scope.row.guardado === true" slot-scope="scope" v-model="data[scope.$index].est_act"
              value-key="desc" placeholder="seleccionar estado" size="mini">
              <el-option v-for="item in estados" :key="item.id" :label="item.desc" :value="item.id">
              </el-option>
            </el-select>
          </el-table-column>
          <el-table-column label="observaciones" width="320">
            <input :disabled="scope.row.guardado === true" type="text" slot-scope="scope"
              v-model="data[scope.$index].obs_est" style="width: 300px" @click="OpenObsAct(scope.$index)" size="mini" />
          </el-table-column>
          <el-table-column align="right-center" width="150" label="guardar">
            <template slot-scope="scope">
              <el-button :disabled="scope.row.guardado === true" type="success" plain size="mini"
                @click="saveActiveDetail(scope.$index, scope.row)">guardar</el-button>
              <!--
              <el-button
                :disabled="data[scope.$index].guardado == true"
                plain
                type="primary"
                size="mini"
                @click="cargarImagen(scope.$index, scope.row)"
                >IMAGEN</el-button
              >
              -->
            </template>
          </el-table-column>
        </el-table>
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next"
          :current-page="pagination.current_page" :total="pagination.total" @current-change="getActivesByInventory">
        </el-pagination>
        <div>
          <el-button style="margin: 10px; text-align: right; float: right" type="primary" size="small" plain
            @click="returnPage2" :disabled="verificado">VERIFICAR</el-button>
          <el-button style="margin: 10px; text-align: right; float: right" type="danger" size="small" plain
            @click="returnPage">ATRAS</el-button>
          <el-button style="margin: 10px; text-align: right; float: right" type="success" size="small" plain
            @click="showObservacionInventory = true">OBSERVACIONES</el-button>
        </div>
      </div>
    </el-card>
    <el-dialog title="Realizar observaciones del inventario" :visible.sync="showObservacionInventory" width="30%"
      @close="showObservacionInventory = false">
      <el-input type="textarea" :rows="2" placeholder="añada una observacion del inventario"
        v-model="inventario.observaciones">
      </el-input>
      <span slot="footer">
        <el-button @click="onCancelDialog">CANCELAR</el-button>
        <el-button type="primary" @click="onConfirmDialog">GUARDAR OBSERVACIONES</el-button>
      </span>
    </el-dialog>
    <el-dialog title="OBSERVACIONES DEL ACTIVO" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
      <el-input type="textarea" :rows="2" placeholder="INGRESE OBSERVACIÓN" v-model="activeObs">
      </el-input>
      <br />
      <br />
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="sendObs()">guardar</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: "DocumentoInventarioDetalle",
  data() {
    return {
      //todo lo revisado en variables
      writtenTextParameter: "",
      user: this.$store.state.user, //usuario que esta manejando el sistema
      id_inventory: null, // numero de la solicitud del inventario
      data: [], //lista de activos fijos por inventario
      pagination: {
        page: 1,
      }, //paginacion para los activos fijos
      estados: [], // lista de estados disponibles por activo fijo

      inventario: {
        estado: "VERIFICADO",
        observaciones: "",
      },
      control: null,
      activeObs: "",
      id_activo: null,
      verificado: false,
      showObservacionInventory: false,
      dialogVisible: false,
      addObservacion: "SIN OBSERVACIONES",
      guardado: false,
      doc_inv: null,
      loading: false,
      messages: {},
      index: null,
    };
  },
  mounted() {
    let app = this;
    app.id_inventory = app.$route.params.id_inventory;
    this.getActivesByInventory(app.pagination.page);
    this.getStatesByActive();
  },
  methods: {
    //  * 3. Obtener una lista de activos fijos para el inventario utilizado.
    async getActivesByInventory(page) {
      let app = this;
      try {
        let response = await axios.post("/api/getActivesByInventory", {
          id_inventory: app.id_inventory,
          year: app.user.gestion,
          page: page,
        });
        app.loading = false;
        console.log(response);
        app.data = Object.values(response.data.data);//response.data.data;
        app.pagination = response.data;
      } catch (error) {
        this.error = error.response.data;
        app.$alert(this.error.message, "Gestor de errores", {
          dangerouslyUseHTMLString: true,
        });
      }
    },
    //  * 4. Obtener una lista de estados por cada activo fijo utilizado.
    getStatesByActive() {
      axios
        .get("/api/getStatesByActive/")
        .then((response) => {
          this.estados = response.data;
          console.log("esto son los estados:" + this.estados);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    //  * I5. Guardar los detalles determinados para cada activo fijo del inventario.
    async saveActiveDetail(index, row) {
      let app = this;
      var newActiveDetail = row;
      console.log(newActiveDetail);
      try {
        let response = await axios.post("/api/storeActiveDetail/", {
          activeDetail: newActiveDetail,
          id: app.id_inventory,
          year: app.user.gestion,
          marker: "registrar",
        });
        console.log(response);
        app.$alert(
          "Se ha actualizado el detalle del activo fijo en el inventario",
          "Gestor de mensajes",
          {
            dangerouslyUseHTMLString: true,
          },
        );
        app.data[index]['guardado'] = true;
      } catch (error) {
        console.log(error);
      }
    },

    OpenObsAct(index) {
      this.index = index;
      this.dialogVisible = true;
      this.activeObs = this.data[this.index].obs_est;
    },
    sendObs() {
      if (this.index != null) this.data[this.index].obs_est = this.activeObs;
      this.dialogVisible = false;
      this.index = null;
    },
    whenDontHaveDocDetail() {
      return {
        est_act: 1,
      };
    },
    handleClose(done) {
      this.$confirm("esta seguro que desea cerrar la ventada de observacion?")
        .then((_) => {
          done();
        })
        .catch((_) => { });
    },
    getActivesSearch() {
      axios
        .get("/api/inventory2/search/" + this.doc_inv.no_cod, {
          params: {
            page: this.pagination.page,
            idOffice: this.doc_inv.oficina.cod_soa,
            idSubOffices: this.doc_inv.sub_ofc_cod,
            keyWord: this.writtenTextParameter.toUpperCase(),
          },
        })
        .then((data) => {
          this.loading = false;
          var info = Object.values(data.data.data);
          // this.data = info.map((a) => {
          //   if (!a.detalle_doc_act)
          //     a.detalle_doc_act = this.whenDontHaveDocDetail();
          //   a.detalle_doc_act.id_act = a.id;
          //   a.detalle_doc_act.id_des = a.per_tab;
          //   a.detalle_doc_act.doc_cod = this.doc_inv.no_cod;
          //   a.detalle_doc_act.cod_act = this.doc_inv.cod_nue;
          //   return a;
          // });
          this.data = Object.values(data.data.data);
          this.pagination = data.data;
          this.controlTrue();
        })
        .catch((err) => {
          console.log(err);
        });
    },
    controlTrue() {
      {
        axios
          .get("/api/activo/controlTrue", {
            params: {
              no_cod: this.doc_inv.no_cod,
            },
          })
          .then((data) => {
            this.control = data.data[0];
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },

    getActivesPaginate(page) {
      this.pagination.page = page;
      this.getActivesSearch();
    },
    formatResponsable(responsable) {
      return {
        responsable:
          responsable.paterno.trim() +
          " " +
          responsable.materno.trim() +
          " " +
          responsable.nombres.trim(),
        nro_dip: responsable.nro_dip,
      };
    },
    cargarImagen(index, row) {
      this.$router.push({
        name: "imgdetail",
        params: {
          id: row.id,
          no_cod: this.doc_inv.id,
        },
      });
    },
    onConfirmDialog() {
      this.showObservacionInventory = false;
    },
    onCancelDialog() {
      this.inventario.observaciones = this.addObservacion;
      this.showObservacionInventory = false;
    },
    updateState() {
      axios
        .post("/api/inventory2/verificar", {
          estado: this.inventario.estado,
          observaciones: this.inventario.observaciones,
          verificado: true,
          nro_cod: this.doc_inv.id,
        })
        .then((data) => {
          this.$notify.success({
            title: "Estado actualizado",
            message: "Se habilito el boton de imprimir",
            duration: 3000,
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    returnPage2() {
      if (this.pagination.total == this.control.guardado) {
        this.verificado = true;
        this.$router.push({
          name: "inventory2",
        });
        this.updateState();
      } else {
        this.$notify.info({
          title: "No puede Verificar Inventario",
          message: "Aun no a sido verificado todos los activos del inventario",
          duration: 3000,
        });
      }
    },
    returnPage() {
      this.$notify.info({
        title: "Edicion cancelada",
        message: "prueba de boton",
        duration: 3000,
      });
      this.$router.push({
        name: "inventory2",
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped>
.el-tag {
  margin: 2px;
}
</style>