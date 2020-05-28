<template>
    <div id="app">
        <v-app style="height: 15rem;">
            <v-content>
                <v-container>
                    <v-file-input
                        v-model="file"
                        label="Seleccione el PDF a subir"
                        prepend-icon="attach_file"
                        accept="application/pdf"
                    >

                    </v-file-input>

                </v-container>
            </v-content>

        </v-app>
    </div>
</template>

<script>
    export default {
        vuetify: new Vuetify(),
        name: "pdf",
        props: {
            id_c: String
        },
        data: () => ({
            file: '',
        }),
    }
</script>

<style scoped>

</style>
