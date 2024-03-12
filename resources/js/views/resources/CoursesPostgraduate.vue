<template>
    <div>
      <el-card class="box-card">  
        <div slot="header" class="clearfix">
        <span>LISTAR CURSO</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddPerson"
        >Agregar Curso</el-button>
      </div>
      <div>
        <el-table v-loading="loading" :data="Table" style="width: 100%">
          <el-table-column width="190" label="CODIGO">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_prg }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column width="190" label="NOMBRE">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.cod_val }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="monto" width="350" label="MONTO"></el-table-column>
          <el-table-column prop="tipo" width="150" label="TIPO"></el-table-column>
          
        </el-table>
        
        <el-pagination :page-size="pagination.per_page" layout="prev, pager, next" :current-page="pagination.current_page"
          :total="pagination.total" @current-change="getCourses"></el-pagination>

      </div>
      </el-card>
    </div>
  </template>
  
  <script>
import { Table } from 'element-ui';

  
  export default {
    data() {
      return {
        Table: [],
        loading: false,
        pagination: {
        page: 1,
        },
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
  