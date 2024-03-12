<template>
    <div>
      <el-card class="box-card">  
        <div slot="header" class="clearfix">
        <span>Diario de ingreso de productos de la granja universitaria</span>
        <el-button style="text-align: right; float: right" size="small" type="primary" icon="el-icon-plus"
          @click="initAddFarmSaleDay">Nuevo Ingreso</el-button>
        </div>

        <div>
        <el-table v-loading="loading" :data="dataSaleDays" style="width: 100%" size="medium">
          <el-table-column prop="fec_tra" label="fecha" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="info">{{ scope.row.fec_tra }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="id_dia" label="dia" :min-width="100">
            <template slot-scope="scope">
              <el-tag type="primary">{{ scope.row.id_dia }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column prop="glosa" label="glosa" :min-width="450"></el-table-column>
          <el-table-column align="right" prop="com_fin" label="operaciones" :min-width="200"></el-table-column>
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
  