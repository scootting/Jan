<template>
  <div>
    <el-card class="box-card">

      <el-tabs type="border-card">
        <el-tab-pane label="descripcion">
          <el-row :gutter="20" class="features">
            <div class="description">
              <h1>Jacobitus - FIDO!</h1>
              <p>Para verificar si existe algún token conectado pulse sobre "Verificar Token".</p>
              <p>Para listar los certificados pulse sobre "Listar Certificados".</p>
            </div>
            <!-- Tokens -->
            <el-col :span="8">
              <h3>Tokens</h3>
              <el-button type="primary" @click="listar">Verificar</el-button>
              <p>{{ tokens }}</p>
            </el-col>
          </el-row>
        </el-tab-pane>
        <el-tab-pane label="certificados">
          <!-- Certificados -->
          <el-row :gutter="20" class="features">
            <el-col :span="8">
              <h3>Certificados</h3>

              <el-form :model="certForm" label-position="top">
                <el-form-item label="PIN">
                  <el-input v-model="certForm.pin" show-password></el-input>
                </el-form-item>
                <el-form-item label="Slot">
                  <el-input v-model="certForm.slot"></el-input>
                </el-form-item>
                <el-button type="primary" @click="certificados">Listar</el-button>
              </el-form>
              <p>{{ certificadosList }}</p>
            </el-col>
          </el-row>
        </el-tab-pane>
        <el-tab-pane label="Firma">
          <!-- Firma -->
          <el-row :gutter="20" class="features">
            <el-col :span="8">
              <h3>Firma</h3>
              <el-form :model="signForm" label-position="top">
                <el-form-item label="PIN">
                  <el-input v-model="signForm.pin" show-password></el-input>
                </el-form-item>
                <el-form-item label="Alias">
                  <el-input v-model="signForm.alias"></el-input>
                </el-form-item>
                <el-form-item label="Slot">
                  <el-input v-model="signForm.slot"></el-input>
                </el-form-item>
                <el-form-item label="Archivo">
                  <input type="file" @change="handleFileSelect" />
                </el-form-item>
                <el-button type="primary" @click="firmar">Firmar</el-button>
              </el-form>
            </el-col>
          </el-row>

        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tokens: '',
      certificadosList: [],
      certForm: {
        pin: '',
        slot: ''
      },
      signForm: {
        pin: '',
        alias: '',
        slot: '',
        file: null
      }
    };
  },
  methods: {
    listar() {
      let app = this;
      // Lógica para listar tokens
      axios.get('/api/estadoToken')
        .then(response => {
          console.log(response);
          if (response.data.finalizado) {
            this.tokens = response.data.datos.tokens;
            alert(this.tokens);
          }
        })
        .catch(e => {
          alert(e);
        });
    },
    async certificados() {
      // Lógica para listar certificados
            let app = this;
            try {
                let response = await axios.post("/api/certificadosToken", {
                    pin: app.certForm.pin,
                    slot: app.certForm.slot,
                });
                app.certificadosList = response.data;
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
                app.loading = false;
            }
    },
    
    firmar() {
      // Lógica para firmar con token
      const { pin, alias, slot, file } = this.signForm;
      if (file) {
        console.log("Firmando archivo", file.name);
      }
    },
    handleFileSelect(event) {
      this.signForm.file = event.target.files[0];
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.el-card {
  background: #ffffff;
}

.description {
  margin-bottom: 20px;
}

.features {
  margin-top: 20px;
}

h3 {
  margin-bottom: 10px;
}
</style>