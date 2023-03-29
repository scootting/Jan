<template>
    <div>
        <el-card class="box-card">
            <div slot="header" class="clearfix">
                <span>editar tipo de archivo</span>
                <el-button style="float: right; padding: 3px 0" type="text" @click="test">ayuda</el-button>
            </div>
            <div>
                <el-form ref="form" :model="archive" label-width="120px" size="small">
                    <el-form-item label="Tipo">
                        <el-radio-group v-model="archive.idt" disabled>
                            <el-radio-button label="A">Archivo</el-radio-button>
                            <el-radio-button label="C">Contenedor</el-radio-button>
                            <el-radio-button label="U">Ubicacion</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="Codificacion">
                        <el-input v-model="archive.idc" disabled></el-input>
                    </el-form-item> <el-form-item label="Descripcion">
                        <el-input type="textarea" v-model="archive.descr"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onStoreTypeArchive">Guardar</el-button>
                        <el-button>Cancelar</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-card>
    </div>
</template>
    
<script>
export default {
    data() {
        return {
            archive: {
            }
        };
    },
    async mounted() {
        let app = this;
        let id = app.$route.params.id;
        console.log(id);
        try {
            let response = await axios.get("/api/typeArchive/" + id);
            console.log(response);
            app.archive = response.data[0];
            console.log(app.archive);
        } catch (error) {
            this.error = error.response.data;
            app.$alert(this.error.message, "Gestor de errores", {
                dangerouslyUseHTMLString: true,
            });
        }

    },
    methods: {
        test() {
            alert("bienvenido al modulo");
        },
        //  * A10. Guardar un nuevo tipo de archivo 
        async onStoreTypeArchive() {
            let app = this;
            try {
                let response = await axios.post("/api/onStoreTypeArchive", {
                    archive: app.archive,
                    marker: 'editar'
                });
                alert("el archivo se guardo correctamente");
            } catch (error) {
                this.error = error.response.data;
                app.$alert(this.error.message, "Gestor de errores", {
                    dangerouslyUseHTMLString: true,
                });
            }
        },
    },
};
</script>
    
    <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped></style>
    