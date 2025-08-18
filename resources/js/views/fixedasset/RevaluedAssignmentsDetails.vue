<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span><strong>indicadores para el revaluo del activos fijos</strong></span>
        <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
      </div>
      <div>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataFixedAsset" label-width="180px" size="small">
                <p>datos generales del activo</p>
                <el-form-item label="fecha de adquisicion" prop="fecha">
                  {{ dataFixedAsset.fecha_adquisicion }}
                </el-form-item>
                <el-form-item label="codigo" prop="fecha">
                  {{ dataFixedAsset.codigo }}
                </el-form-item>
                <el-form-item label="descripcion" prop="fecha">
                  {{ dataFixedAsset.descripcion }}
                </el-form-item>
                <el-form-item label="tipo de adquisicion">
                  {{ dataFixedAsset.tipo_adq }}
                </el-form-item>
                <el-form-item label="valor neto">
                  {{ dataFixedAsset.valor_neto }}
                </el-form-item>
              </el-form>
            </div>
          </el-col>

          <el-col :span="12">
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataTechnicals" label-width="350px" size="small">
                <p>factores tecnicos</p>
                <p>tabla de factores y coeficientes</p>
                <el-form-item label="perdida natural de unidad de mercado">
                  <el-input-number v-model="dataTechnicals.perdida" :min="0" :step="0.1" :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="funcionalidad">
                  <el-input-number v-model="dataTechnicals.funcionalidad" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="obsolescencia tecnologica">
                  <el-input-number v-model="dataTechnicals.obsolescencia" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="nivel de conservacion">
                  <el-input-number v-model="dataTechnicals.conservacion" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="frecuencia de uso">
                  <el-input-number v-model="dataTechnicals.uso" :min="0" :step="0.1" :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="mantenimiento">
                  <el-input-number v-model="dataTechnicals.mantenimiento" :min="0" :step="0.1"
                    :max="1"></el-input-number>
                </el-form-item>
                <el-form-item label="coeficiente conforme a factores aplicados">
                  <el-input placeholder="Please input" v-model="dataTechnicals.resultado"></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <div class="grid-content bg-purple">
              <p>determinacion segun revaluo</p>
              <p>cotizaciones</p>
              <el-table :data="dataQuotes" style="width: 100%" size="small">
                <el-table-column prop="id" label="no." width="90"></el-table-column>
                <el-table-column prop="cantidad" label="cantidad" width="220"></el-table-column>
                <el-table-column align="right" width="200">
                  <template slot-scope="scope">
                    <el-button @click="initEditDataQuotes(scope.$index, scope.row)" type="primary"
                      size="mini">Editar</el-button>
                    <el-button @click="initRemoveDataQuotes(scope.$index, scope.row)" type="primary"
                      size="mini">Quitar</el-button>
                  </template>
                </el-table-column>
              </el-table>
              <p></p>
              <el-button @click="initAddDataQuote" type="primary" size="small">agregar
              </el-button>
            </div>
          </el-col>
          <el-col :span="12">
            estado: '',
            vida: 0,
            valor_residual: 0,
            valor_revaluo: 0,
            valor_saldo: 0,
            <div class="grid-content bg-purple">
              <el-form ref="form" :model="dataResult" label-width="350px" size="small">
                <p>determinacion segun revaluo</p>
                <p>resultados</p>
                <el-form-item label="estado tecnico">
                  <el-input placeholder="Please input" v-model="dataResult.estado"></el-input>
                </el-form-item>
                <el-form-item label="años de vida util">
                  <el-input placeholder="Please input" v-model="dataResult.vida"></el-input>
                </el-form-item>
                <el-form-item label="valor residual">
                  <el-input placeholder="Please input" v-model="dataResult.valor_residual"></el-input>
                </el-form-item>
                <el-form-item label="valor segun revaluo">
                  <el-input placeholder="Please input" v-model="dataResult.valor_revaluo"></el-input>
                </el-form-item>
                <el-form-item label="saldo por revalorizar">
                  <el-input placeholder="Please input" v-model="dataResult.valor_saldo"></el-input>
                </el-form-item>
              </el-form>
            </div>
          </el-col>
        </el-row>
        <br>
        <el-button @click="initStoreDataRevaluedDetails" type="primary" size="small">calcular datos del revaluo
        </el-button>
        <p></p>
      </div>
    </el-card>

    <!-- Form Add Document to Archive-->
    <el-dialog title="cotizaciones" :visible.sync="dialogFormVisible">
      <el-form :model="dataQuote" label-width="220px" size="small">
        <el-form-item label="cantidad">
          <el-input v-model="dataQuote.cantidad" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" size="small" plain @click="initStoreDataQuotes">agregar</el-button>
        <el-button type="danger" size="small" plain @click="dialogFormVisible = false">Cancelar</el-button>
      </span>
    </el-dialog>
    <!-- Form Add Document to Archive-->

  </div>
</template>

<script>
import { forEach } from "lodash";
import information from "../components/Information.vue";

export default {
  components: {
    information,
  },
  data() {
    return {
      user: this.$store.state.user,
      id: this.$route.params.id,
      dialogFormVisible: false,
      dataQuotes: [],
      dataTechnicals: {
        perdida: 0,
        funcionalidad: 0,
        obsolescencia: 0,
        conservacion: 0,
        uso: 0,
        mantenimiento: 0,
        resultado: 0,
      },
      dataResult: {
        estado: '',
        vida: 0,
        valor_residual: 0,
        valor_revaluo: 0,
        valor_saldo: 0,
      },
      dataFixedAsset: {
        id: 0,
        idx: 0,
        descripcion: '',
        medida: '',
        cantidad: 1,
        importe: 0,
        fecha_adquisicion: '',
        id_contable: '',
        des_contable: '',
        id_presupuesto: '',
        des_presupuesto: '',
        estado: '',
        cod_prg: '',
        des_prg: '',
        ci_resp: '',
        id_asignaciones: '',
        adicional: [],
      },
      dataQuoteState: 'registrar',
      dataQuote: {
        id: 0,
        cantidad: 0,
      },
    };
  },
  mounted() {
    console.log(this.user);
    this.getDataRevaluedDetails();
  },

  methods: {


    /* cotizaciones */
    initAddDataQuote() {
      this.dialogFormVisible = true;
      this.dataQuoteState = 'registrar';
    },

    initStoreDataQuotes() {
      if (this.dataQuoteState == 'registrar') {
        let variable = this.dataQuote;
        this.dataQuotes.push(variable);
        let idn = 1;
        this.dataQuotes.forEach(element => {
          element.id = idn;
          idn = idn + 1;
        });
      } else {

      }
      console.log(this.dataQuotes);
      this.dialogFormVisible = false;
      this.dataQuote = {
        id: 0,
        cantidad: 0,
      };
    },

    initRemoveDataQuotes(idx, row) {
      this.dataQuotes.splice(idx, 1);
      let idn = 1;
      this.dataQuotes.forEach(element => {
        element.id = idn;
        idn = idn + 1;
      });
      console.log(this.dataQuotes);
    },

    initEditDataQuotes(idx, row) {
      this.dataQuote = row;
      this.dataQuoteState = 'editar';
      this.dialogFormVisible = true;
    },

    initStoreDataRevaluedDetails() {

    },
  },
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
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

.el-form .el-select {
  width: 100%;
}
</style>