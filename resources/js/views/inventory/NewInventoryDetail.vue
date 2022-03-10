<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>nuevo inventario para {{ oficina.descripcion }}</span>
      </div>
      <br />
      <br />
      <el-form label-width="160px" :inline="true" size="normal"> </el-form>
      <div class="grid-content bg-purple">
        <el-row :gutter="20">
          <el-col :span="24" :offset="0">
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item label="todo/subunidad:">
                <el-select
                  placeholder="Seleccione"
                  v-model="filtro.tipo"
                  @change="onChangeFilterType()"
                >
                  <el-option
                    v-for="item in filtros"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
            </el-form>
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item v-if="filtro.tipo != 'todo'" label="Seleccionar:">
                <template v-if="filtro.tipo == 'subUnidad'">
                  <el-select
                    v-model="NewInvent.subUnidades"
                    placeholder="Seleccione las Subunidades"
                    multiple
                    @change="reloadC()"
                  >
                    <el-option
                      v-for="so in noRepeatSO"
                      :key="so.id"
                      :label="so.desc"
                      :value="so.id"
                    >
                    </el-option>
                  </el-select>
                </template>
                <template v-else>
                  <el-select
                    v-model="NewInvent.cargos"
                    placeholder="Seleccione los cargos"
                    multiple
                    @change="reloadSO()"
                  >
                    <el-option
                      v-for="c in noRepeatC"
                      :key="c.id"
                      :label="c.desc"
                      :value="c.id"
                    >
                    </el-option>
                  </el-select>
                </template>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
      <div>
        <el-divider content-position="left">Ingresar datos</el-divider>
        <el-row :gutter="20">
          <el-col :span="24" :offset="0">
            <el-form label-width="180px" :inline="false" size="small">
              <el-form-item label="Fecha de creación">
                <el-date-picker
                  v-model="NewInvent.date"
                  type="date"
                  placeholder="seleccione una fecha"
                >
                </el-date-picker>
              </el-form-item>
              <el-form-item size="small" label="Encargados de Inventario">
                <el-select
                  class="enc-select"
                  v-model="NewInvent.encargados"
                  multiple
                  placeholder="Seleccione encargados a realizar inventario"
                  style="width: 250px"
                  maxlength="30"
                >
                  <el-option
                    v-for="(item, index) in encargados"
                    :key="index"
                    :label="
                      item.nro_dip + '-' + item.paterno + ' ' + item.nombres
                    "
                    :value="item.nro_dip"
                  >
                  </el-option>
                </el-select>
                <el-button
                  type="primary"
                  size="mini"
                  @click="showDialogEncargado = true"
                  >Buscar</el-button
                >
              </el-form-item>
            </el-form>
            <el-form label-width="180px" :inline="true" size="small">
              <el-form-item size="small" label="Entregado por:">
                <el-input
                  v-model="NewInvent.per_inv"
                  size="mini"
                  type="text"
                  class="input-with-select"
                  ><el-button
                    slot="append"
                    icon="el-icon-search"
                    @click="getEntregadoPor(NewInvent.per_inv)"
                  ></el-button
                ></el-input>
              </el-form-item>
              <el-form-item size="mini" label="NOMBRE:">
                <el-input
                  :value="entregadoPor"
                  disabled
                  placeholder="NOMBRE DE RESPONSABLE"
                  size="mini"
                  readonly
                  style="width: 100%"
                />
              </el-form-item>
              <el-form-item size="small" label="Cargo:">
                <el-select
                  v-model="NewInvent.car_per"
                  value-key="id"
                  placeholder="Determinar Cargo"
                >
                  <el-option
                    v-for="item in cargos1"
                    :key="item.id"
                    :label="item.descripcion"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <br /><br />
              <el-form-item size="small" label="Recibido por:">
                <el-select
                  v-model="NewInvent.new_per"
                  placeholder="Busque un carnet"
                  :loading="searchNewPerLoading"
                  clearable
                  filterable
                  remote
                  :remote-method="getNewPer"
                >
                  <el-option
                    v-for="item in searchNewPer"
                    :key="item.nro_dip"
                    :label="item.paterno + ' ' + item.nombres"
                    :value="item.nro_dip"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <el-form-item size="small" label="Cargo:">
                <el-select
                  v-model="NewInvent.new_car_per"
                  value-key="id"
                  placeholder="Determinar Cargo"
                >
                  <el-option
                    v-for="item in cargos1"
                    :key="item.id"
                    :label="item.descripcion"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <br />
              <el-form-item>
                <el-checkbox v-model="VoBo"
                  >Visto bueno por autoridad Superior</el-checkbox
                >
              </el-form-item>
              <br />
              <el-form-item size="small" label="Superior:">
                <el-select
                  class="enc-select"
                  v-model="NewInvent.sup"
                  multiple
                  placeholder="Seleccione Superior"
                  style="width: 250px"
                  maxlength="30"
                  :disabled="VoBo == false"
                >
                  <el-option
                    v-for="(item, index) in superior"
                    :key="index"
                    :label="
                      item.nro_dip + '-' + item.paterno + ' ' + item.nombres
                    "
                    :value="item.nro_dip"
                  >
                  </el-option>
                </el-select>
                <el-button
                  :disabled="VoBo == false"
                  type="primary"
                  size="mini"
                  @click="showDialogSuperior = true"
                  >Buscar</el-button
                >
              </el-form-item>
              <el-form-item size="small" label="Cargo:">
                <el-select
                  v-model="NewInvent.car_per_sup"
                  value-key="id"
                  placeholder="Determinar Cargo"
                  :disabled="VoBo == false"
                >
                  <el-option
                    v-for="item in cargos1"
                    :key="item.id"
                    :label="item.descripcion"
                    :value="item.id"
                  >
                  </el-option>
                </el-select>
              </el-form-item>
              <br /><br /><br />
              <el-form-item
                style="margin: 10px; text-align: right; float: right"
                size="small"
              >
                <el-button type="primary" size="mini" @click="saveInventory"
                  >Guardar información</el-button
                >
                <el-button type="danger" size="mini" @click="returnPage"
                  >Cancelar</el-button
                >
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </div>
    </el-card>
    <el-dialog
      title="Buscar Encargado"
      :visible.sync="showDialogEncargado"
      width="30%"
      @close="showDialogEncargado = false"
    >
      <el-select
        v-model="selectEncargado"
        placeholder="Busque un carnet"
        :loading="searchEncargadoLoading"
        clearable
        filterable
        remote
        :remote-method="getEncargados"
      >
        <el-option
          v-for="item in searchEncargados"
          :key="item.nro_dip"
          :label="item.paterno + ' ' + item.nombres"
          :value="item.nro_dip"
        >
        </el-option>
      </el-select>
      <span slot="footer">
        <el-button @click="onCancelDialog">Cancel</el-button>
        <el-button type="primary" @click="onConfirmDialog">CONFIRMAR</el-button>
      </span>
    </el-dialog>

    <el-dialog
      title="Buscar Superior"
      :visible.sync="showDialogSuperior"
      width="30%"
      @close="showDialogSuperior = false"
    >
      <el-select
        v-model="selectSuperior"
        placeholder="Busque un carnet"
        :loading="searchSuperiorLoading"
        clearable
        filterable
        remote
        :remote-method="getSuperior"
      >
        <el-option
          v-for="item in searchSuperior"
          :key="item.nro_dip"
          :label="item.paterno + ' ' + item.nombres"
          :value="item.nro_dip"
        >
        </el-option>
      </el-select>
      <span slot="footer">
        <el-button @click="onCancelDialogSuperior">Cancel</el-button>
        <el-button type="primary" @click="onConfirmDialogSuperior"
          >CONFIRMAR</el-button
        >
      </span>
    </el-dialog>
  </div>
</template>

<script>
//Importar Componentes creados
import selectSubUnidad from "./components/selectSubUnidad";
import selectCargos from "./components/selectCargos";
export default {
  name: "NewInventoryDetail",
  components: {
    selectSubUnidad,
    selectCargos,
  },
  data() {
    return {
      reporte: {
        tipo: "general",
      },
      loading: false,
      user: this.$store.state.user,
      oficina: {},
      NewInvent: {
        date: "",
        unidad: "",
        subUnidades: [],
        responsables: [],
        encargados: [],
        cargos: [],
        nombres: "",
        per_inv: "",
        sup: [],
        car_per_sup: null,
        new_per: null,
        nombres1: "",
        materno1: "",
        paterno1: "",
      },
      No_Doc: null,
      subUnidades: [],
      unidades: [],
      cargos: [],
      responsables: [],
      encargados: [],
      superior: [],
      per_inv: [],
      new_per: [],
      VoBo: false,
      cargos1: [],
      recibido: null,
      searchEncargados: [],
      searchPerInv: {},
      searchNewPer: [],
      searchSuperior: [],

      selectPerInv: null,
      selectNewPer: null,
      selectEncargado: null,
      selectSuperior: null,

      searchEncargadoLoading: false,
      searchSuperiorLoading: false,
      searchNewPerLoading: false,

      subUnidadesLoading: false,
      unidadLoading: false,
      cargosLoading: false,
      responsablesLoading: false,

      showDialogEncargado: false,
      showDialogSuperior: false,

      guardado: false,
      //filtro elegido para obtener los activos
      filtro: {
        tipo: "todo",
        values: [],
      },
      //tipos de filtros
      filtros: [
        {
          id: "todo",
          label: "TODO",
        },
        {
          id: "sub unidad",
          label: "SUB UNIDAD",
        },
      ],
    };
  },
  created() {
    let cod_soa = this.$route.params.soa;
    axios
      .get("/api/inventory/show/" + cod_soa)
      .then((data) => {
        this.oficina = data.data;
        this.selectAll();
        this.NewInvent.unidad = this.oficina.cod_soa;
        this.NewInvent.responsables = this.responsables.nro_dip;
      })
      .catch((err) => {});
  },
  mounted() {
    this.getCargos();
  },
  computed: {
    noRepeatSO() {
      if (this.oficina.so_cargos)
        return this.oficina.so_cargos.reduce((v, so_c) => {
          if (!v.map((so) => so.id).includes(so_c.id_s))
            v.push({
              id: so_c.id_s,
              desc: so_c.descripcion_s,
            });
          return v;
        }, []);
      return [];
    },
    noRepeatC() {
      if (this.oficina.so_cargos)
        return this.oficina.so_cargos.reduce((v, so_c) => {
          if (!v.map((c) => c.id).includes(so_c.id_c))
            v.push({
              id: so_c.id_c,
              desc: so_c.descripcion_c,
            });
          return v;
        }, []);
      return [];
    },
    getNombre() {
      return (
        this.responsables.nombres +
        " " +
        this.responsables.paterno +
        " " +
        this.responsables.materno
      );
    },
    entregadoPor() {
      return (
        this.NewInvent.nombres1 +
        " " +
        this.NewInvent.paterno1 +
        " " +
        this.NewInvent.materno1
      );
    },
  },

  methods: {
    getCargos() {
      axios
        .get("/api/activo/cargos/")
        .then((data) => {
          this.cargos1 = data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    reloadSO() {
      this.NewInvent.subUnidades = [];
      if (this.NewInvent.cargos.length > 0) {
        this.oficina.so_cargos.forEach((el) => {
          if (
            this.NewInvent.cargos.includes(el.id_c) &&
            !this.NewInvent.subUnidades.includes(el.id_s)
          ) {
            this.NewInvent.subUnidades.push(el.id_s);
          }
        });
      }
    },
    reloadC() {
      this.NewInvent.cargos = [];
      if (this.NewInvent.subUnidades.length > 0) {
        this.oficina.so_cargos.forEach((el) => {
          if (
            this.NewInvent.subUnidades.includes(el.id_s) &&
            !this.NewInvent.cargos.includes(el.id_c)
          ) {
            this.NewInvent.cargos.push(el.id_c);
          }
        });
      }
    },
    selectAll() {
      this.NewInvent.subUnidades = this.noRepeatSO.map((so) => so.id);
      this.NewInvent.cargos = this.noRepeatC.map((c) => c.id);
    },
    onChangeFilterType() {
      if (this.filtro.tipo === "todo") {
        this.selectAll();
      } else {
        this.NewInvent.cargos = [];
        this.NewInvent.subUnidades = [];
      }
    },
    getResponsables() {
      this.cargosLoading = true;
      this.responsablesLoading = true;
      axios
        .get("/api/inventory2/responsables", {
          params: {
            cod_soa: this.oficina.cod_soa,
            cargos: this.NewInvent.cargos,
            sub_uni: this.NewInvent.subUnidades,
          },
        })
        .then((data) => {
          this.responsablesLoading = false;
          this.responsables = data.data;
          this.NewInvent.responsables = this.responsables.map(
            (resp) => resp.nro_dip
          );
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getSubUnidades(cod_soa) {
      this.unidadesLoading = true;
      this.subUnidadesLoading = true;
      axios
        .get("/api/inventory2/sub_unidad", {
          params: { cod_soa: cod_soa },
        })
        .then((data) => {
          this.subUnidadesLoading = false;
          this.unidadesLoading = false;
          this.subUnidades = data.data;
          this.NewInvent.subUnidades = this.subUnidades.map((su) => su.id);
          this.getCargosResp(this.NewInvent.unidad, this.NewInvent.subUnidades);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCargosResp(cod_soa, subUnidades) {
      this.cargosLoading = true;
      this.subUnidadesLoading = true;
      axios
        .get("/api/inventory2/cargos", {
          params: {
            cod_soa: cod_soa,
            sub_unidades: subUnidades,
          },
        })
        .then((data) => {
          this.cargosLoading = false;
          this.subUnidadesLoading = false;
          this.cargos = data.data;
          this.NewInvent.cargos = this.cargos.map((car) => car.id);
          this.getResponsables(this.NewInvent.unidad, this.NewInvent.cargos);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getEncargados(nro_dip) {
      this.searchEncargadoLoading = true;
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchEncargadoLoading = false;
          this.searchEncargados = Object.values(data.data.data);
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getEntregadoPor(nro_dip) {
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchPerInv = Object.values(data.data.data);
          this.NewInvent.nombres1 = this.searchPerInv[0].nombres;
          this.NewInvent.paterno1 = this.searchPerInv[0].paterno;
          this.NewInvent.materno1 = this.searchPerInv[0].materno;
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getNewPer(nro_dip) {
      this.searchNewPerLoading = true;
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchNewPerLoading = false;
          this.searchNewPer = Object.values(data.data.data);
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getSuperior(nro_dip) {
      this.searchSuperiorLoading = true;
      axios
        .get("/api/inventory2/encargados", {
          params: { nro_dip: nro_dip },
        })
        .then((data) => {
          this.searchSuperiorLoading = false;
          this.searchSuperior = Object.values(data.data.data);
          console.log(data);
        })
        .catch((err) => {
          console.log(err);
        });
    },
    formatResponsable(responsable) {
      return {
        cargo: responsable.descripcion,
        responsable:
          responsable.paterno.trim() +
          " " +
          responsable.materno.trim() +
          " " +
          responsable.nombres.trim(),
        nro_dip: responsable.nro_dip,
      };
    },
    saveInventory() {
      //this.NewInvent.responsables= this.NewInvent.responsables.map(r => this.formatResponsable(this.responsables.filter(r2=> r===r2.nro_dip)[0]));
      //tratar de guardar los responsables como un json
      axios
        .post("/api/inventory2/save", this.NewInvent)
        .then((data) => {
          this.$message({
            message: "Inventario creado exitosamente",
            type: "success",
            duration: 3000,
            showClose: true,
          });
          this.No_Doc = data.data.no_doc;
          this.guardado = true;
          this.saveDataDetail();
          this.$router.push({
            name: "inventory2",
          });
        })
        .catch((err) => {
          console.log(err);
        });
    },
    saveDataDetail() {
      axios
        .post("/api/inventory2/saveDataDetail", {
          no_doc: this.No_Doc,
          ofc_cod: this.NewInvent.unidad,
          sub_ofc_cod: this.NewInvent.subUnidades,
        })
        .then((data) => {
          console.log("datos guardados en la base de datos detalle doc act");
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onCancelDialog() {
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    onCancelDialogSuperior() {
      this.selectSuperior = null;
      this.showDialogSuperior = false;
    },
    onConfirmDialog() {
      if (!this.selectEncargado) {
        this.$message({
          message: "NO selecciono ningun encargado",
          type: "warning",
          showClose: true,
          duaration: 5000,
        });
        return;
      }
      let addEncargado = this.searchEncargados.filter((e) => {
        return e.nro_dip === this.selectEncargado;
      })[0];
      this.NewInvent.encargados.push(addEncargado.nro_dip);
      this.encargados.push(addEncargado);
      this.selectEncargado = null;
      this.showDialogEncargado = false;
    },
    onConfirmDialogSuperior() {
      if (!this.selectSuperior) {
        this.$message({
          message: "NO selecciono ningun encargado",
          type: "warning",
          showClose: true,
          duaration: 5000,
        });
        return;
      }
      let addSuperior = this.searchSuperior.filter((e) => {
        return e.nro_dip === this.selectSuperior;
      })[0];
      this.NewInvent.sup.push(addSuperior.nro_dip);
      this.superior.push(addSuperior);
      this.selectSuperior = null;
      this.showDialogSuperior = false;
    },
    returnPage() {
      this.$notify.info({
        title: "Creación cancelada",
        message: "No se creo inventario nuevo",
        duration: 3000,
      });
      this.$router.push({
        name: "newinventory",
      });
    },
  },
};
</script>
<style scoped>
.el-row {
  margin-bottom: 20px;
}
.el-col {
  border-radius: 4px;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  padding: 15px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>