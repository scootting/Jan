<template>
    <div>
      <el-card class="box-card"> 
        <div slot="header" class="clearfix">
                <span>Ingreso de productos</span>
                <el-button style="float: right; padding: 3px 0" type="text">ayuda</el-button>
        </div>

        <div>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <p>Prodcuto</p>

                            <el-form ref="form" :model="farmSaleDays" label-width="120px" size="small">
                                
                              
                               <el-form-item label="fecha" prop="fecha">
                                    <el-date-picker type="date" placeholder="seleccione una fecha"
                                        ></el-date-picker>
                                </el-form-item>
                              <el-form-item>
                                    <el-button size="small" type="success" @click.prevent="initStoreFarmSaleDays"
                                        plain>Agregar producto</el-button>
                                    <el-button size="small" type="warning" @click.prevent="initStoreFarmSaleDays"
                                        plain>Guardar</el-button>    
                              </el-form-item>
                            </el-form>


                        </div>
                        <p></p>
                    </el-col>
                </el-row>
            </div>

            <div>
        <el-table v-loading="loading" :data="dataSaleDays" style="width: 100%" size="medium">
          <el-table-column prop="id_codigo" label="codigo" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="id_producto" label="producto" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          
          <el-table-column align="right" prop="com_fin" label="cantidad" :min-width="200"></el-table-column>
          <el-table-column align="right" prop="com_fin" label="precio unitario" :min-width="200"></el-table-column>
          <el-table-column align="right" prop="com_fin" label="importe" :min-width="200"></el-table-column>
          <el-table-column align="right" :min-width="320">
            <template slot-scope="scope">
              <el-button :disabled="dataSaleDays[scope.$index].estado == 'V'"
                @click="initCustomerSaleDetail(scope.$index, scope.row)" type="warning" size="small" plain>realizar
                venta</el-button>
              <el-button @click="initSaleDetailReport(scope.$index, scope.row)" type="primary" size="small" plain>detalle
                de la venta</el-button>
            </template>
          </el-table-column>
        </el-table>      
        </div>     



      </el-card>
    </div>
  </template>
  
  <script>
  
  export default {
    data() {
      return {
        mensaje: '',
        data: {},
      };
    },
    mounted() { },
    methods: {
      test() {
        alert("bienvenido al modulo");
      },
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped>
  
  </style>
  