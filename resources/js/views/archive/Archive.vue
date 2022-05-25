
<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>archivos</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddArchive"
          >nuevo archivo</el-button
        >
      </div>
      <div style="margin-top: 15px">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select"
        >
          <el-button
            slot="append"
            icon="el-icon-search"
            @click="getArchives(1)"
          ></el-button>
        </el-input>
      </div>
      <br />
      <div>
        <el-table v-loading="loading" :data="data" style="width: 100%">
          <el-table-column width="75" label="No.">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.no_doc }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="glosa"
            width="450"
            label="descripcion del documento"
          ></el-table-column>
          <el-table-column width="150" label="Estado">
            <template slot-scope="scope">
              <div slot="reference" class="name-wrapper">
                <el-tag size="medium">{{ scope.row.estado }}</el-tag>
              </div>
            </template>
          </el-table-column>
          <el-table-column align="right-center" width="250" label="Operaciones">
            <template slot-scope="scope">
              <el-button
                @click="getDocumentsbyArchive(scope.row.no_doc)"
                type="success"
                plain
                size="mini"
                >Ver documentos
              </el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-pagination
          :page-size="pagination.per_page"
          layout="prev, pager, next"
          :current-page="pagination.current_page"
          :total="pagination.total"
          @current-change="getArchives"
        ></el-pagination>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      user: this.$store.state.user,
      data: [],
      pagination: {
        page: 1,
      },
      writtenTextParameter: "",
    };
  },
  mounted() {
    this.getArchives();
  },
  methods: {
    //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
    getArchives(page) {
      this.loading = true;
      let app = this;
      axios
        .post("/api/archive", {
          year: app.user.gestion,
          description: app.writtenTextParameter.toUpperCase(),
          page: page,
        })
        .then((response) => {
          app.loading = false;
          app.data = Object.values(response.data.data);
          console.log(app.data);
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

    getDocumentsbyArchive(id_archive) {
      this.$router.push({
        name: "archivedetails",
        params: {
          id: id_archive,
        },
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
    initAddArchive() {
      this.$router.push({
        name: "addarchive",
        params: {
          type: "Documento",
        },
      });
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped></style>