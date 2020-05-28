<template>
    <div id="app">
        <spinner-component v-if="loading"></spinner-component>
        <v-app>
            <v-card style="padding: 6px; margin: 6px;" class="text-white" color="blue darken-2">
                Puedes dar clic a los encabezados para acender o descender el orden
            </v-card>

            <v-data-table
                :headers="headers"
                :items="gestiones"
                locale="es"
                :footer-props="flechas"
            >

            </v-data-table>
        </v-app>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {

        props: {
            id_c: String
        },
        data() {
            return {
                loading: true,
                gestiones: [],
                headers: [
                    {text: 'Folio', value: 'folioGen'},
                    {text: 'Comentario', value: 'comentario'},
                    {text: 'Tipo de gestión SCL', value: 'id_tipo_gestion_ssl'}
                ],
                flechas: {
                    showFirstLastPage: true,
                    prevIcon: 'undo',
                    nextIcon: 'redo'
                },
            }
        },
        created() {
            axios.get(`/gestiones/${this.id_c}`)
                .then(res => {
                    this.loading = false;
                    this.gestiones = res.data;
                })
        },
    }
</script>
