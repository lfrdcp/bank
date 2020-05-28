<template>
    <div id="app">
        <v-app style="width: 15rem; height: 45rem;">
            <v-content>
                <v-container>
                    <div class="text-center">
                        <v-dialog v-model="ventanaOpciones" width="500">
                            <template v-slot:activator="{ on }">
                                <v-card class="mx-auto">
                                    <v-system-bar color="light-green lighten-3"></v-system-bar>
                                    <v-app-bar dark color="light-green darken-1">
                                        <v-app-bar-nav-icon>
                                                <v-icon>insert_drive_file</v-icon>
                                        </v-app-bar-nav-icon>
                                        <v-toolbar-title>BATCH</v-toolbar-title>
                                    </v-app-bar>

                                    <v-container class="pa-2" fluid>
                                        <v-divider class="mx-4"></v-divider>
                                        <v-col>
                                            <v-card color="light-green darken-1" dark>
                                                <div class="my-4 subtitle-1 white--text">
                                                    Permite:
                                                </div>
                                            </v-card>
                                        </v-col>
                                            <v-col>
                                                <v-card color="light-green darken-1" dark>
                                                    <v-card-text class="white--text">
                                                        <div>Generar reporte para</div>
                                                        <v-list-item>
                                                            <v-list-item-content>
                                                                <v-list-item-content>• SCL</v-list-item-content>
                                                                <v-list-item-content>• CYBER</v-list-item-content>
                                                            </v-list-item-content>
                                                        </v-list-item>
                                                    </v-card-text>
                                                </v-card>
                                            </v-col>
                                        <v-divider class="mx-4"></v-divider>
                                            <v-col>
                                                <v-btn color="light-green darken-3" dark v-on="on">
                                                    Clic para generar
                                                </v-btn>
                                            </v-col>

                                    </v-container>
                                </v-card>
                            </template>

                            <v-card>
                                <v-system-bar color="light-green lighten-3"></v-system-bar>
                                <v-toolbar color="light-green darken-1" dark>
                                    <v-icon>insert_drive_file</v-icon>
                                    <v-toolbar-title>Generar reporte Batch</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-btn icon dark @click="ventanaOpciones = false">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </v-toolbar>

                                <v-col>
                                    <v-card-text>
                                        <v-date-picker v-model="fecha"
                                                       full-width
                                                       color="light-green darken-1"
                                                       year-icon="calendar_today"
                                                       prev-icon="skip_previous"
                                                       next-icon="skip_next"
                                                       locale="es"
                                        ></v-date-picker>
                                    </v-card-text>
                                </v-col>
                                <br>


                                <v-divider></v-divider>

                                <v-card-actions>
                                    <div class="flex-grow-1"></div>
                                    <v-btn color="light-green darken-1" dark
                                           @click="scl()">
                                        • SCL
                                    </v-btn>
                                    <v-btn color="light-green darken-1" dark @click="cyber(); ventanaCargar = true">
                                        • CYBER
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                        <v-dialog
                            v-model="ventanaCargar"
                            hide-overlay
                            persistent
                            width="300"
                        >

                            <v-card v-model="barraCarga" v-if="barraCarga" color="primary" dark>
                                <v-card-text>
                                    ¡Generando reporte, por favor espere!
                                    <v-progress-linear
                                        indeterminate
                                        color="white"
                                        class="mb-0"
                                    ></v-progress-linear>
                                </v-card-text>
                            </v-card>

                        </v-dialog>

                    </div>
                </v-container>
            </v-content>
        </v-app>
    </div>
</template>

<script>
    import download from 'downloadjs'

    export default {

        name: "Batch",
        data() {
            return {
                ventanaOpciones: false,
                ventanaCargar: false,
                barraCarga: false,
                fecha: '',
                menu: '',
                idDespacho: '',
            }
        },
        created() {
            axios.get('/obtenerIdDespacho')
                .then(res => {
                    this.idDespacho = res.data;
                    console.log(res.data);
                });
        },
        methods: {
            scl() {
                if (this.fecha !== '') {
                    this.ventanaOpciones = false;
                    this.barraCarga = true;
                    this.ventanaCargar = true;
                    const params = {
                        fechaBuscar: this.fecha
                    };

                    axios.post('/reporteSclCsv', params)
                        .then(res => {
                            this.barraCarga = false;
                            this.ventanaCargar = false;

                            var d = new Date();
                            var mm = d.getMonth() + 1;
                            var dd = d.getDate();
                            if (dd < 10) {
                                dd = '0' + dd;
                            }
                            var yy = d.getFullYear();
                            var miFecha = yy + '' + mm + '' + dd;
                            var nombreArchivo = 'SCL_GESTIONES_' + this.idDespacho + '_' + miFecha + '.csv';
                            download(res.data, nombreArchivo, 'csv');
                        });

                    new Noty({
                        type: 'success',
                        layout: 'bottomRight',
                        text: 'Se descargo reporte BATCH/SCL',
                        timeout: 8000,
                        progressBar: true,
                    }).show();
                } else {
                    swal("Por favor seleccione una fecha", {
                        icon: "info",
                        button: "Entendido",
                    });
                }
            },

            cyber() {
                if (this.fecha !== '') {

                    this.ventanaOpciones = false;
                    this.barraCarga = true;
                    this.ventanaCargar = true;


                    axios.get('/reporteCyberCsv',
                        {
                            params: {
                                fechaBuscar: this.fecha
                            }
                        }
                    ).then(res => {
                        this.barraCarga = false;
                        this.ventanaCargar = false;

                        var d = new Date();
                        var mm = d.getMonth() + 1;
                        var dd = d.getDate();
                        if (dd < 10) {
                            dd = '0' + dd;
                        }
                        var yy = d.getFullYear();
                        var miFecha = dd + '' + mm + '' + yy;

                        var nombreArchivo = 'gestiones_' + this.idDespacho + '_' + miFecha + '.csv';
                        download(res.data, nombreArchivo, 'csv');

                    });

                    new Noty({
                        type: 'success',
                        layout: 'bottomRight',
                        text: 'Se descargo reporte BATCH/CYBER',
                        timeout: 8000,
                        progressBar: true,
                    }).show();

                } else {
                    swal("Por favor seleecione una fecha", {
                        icon: "info",
                        button: "Entendido",
                    });
                }

            },
        },
    }
</script>

<style>
    .zoom:hover {
        transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }

    a:hover, a:visited, a:link, a:active {
        text-decoration: none;
    }

    .container {
        max-width: 1140px
    }
</style>
