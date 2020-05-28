<template>
    <div id="app">
        <v-app style="width: 20rem; height: 45rem;">
            <v-content>
                <v-container>
                    <div class="text-center">
                        <v-dialog v-model="ventanaOpciones" width="800">
                            <template v-slot:activator="{ on }">

                                <v-card class="mx-auto">
                                    <v-system-bar color="light-green lighten-3"></v-system-bar>
                                    <v-app-bar dark color="light-green darken-1">
                                        <v-app-bar-nav-icon>
                                            <v-icon>insert_drive_file</v-icon>
                                        </v-app-bar-nav-icon>
                                        <v-toolbar-title>Convenio y liquidación</v-toolbar-title>
                                    </v-app-bar>
                                    <v-container class="pa-3">
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
                                                            <v-list-item-content>• Convenio/liquidación nuevo
                                                            </v-list-item-content>
                                                            <v-list-item-content>• Convenio/liquidación recurrentes
                                                            </v-list-item-content>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </v-card-text>
                                            </v-card>
                                        </v-col>

                                        <v-col>
                                            <v-card color="light-green darken-1" dark>
                                                <v-card-text class="white--text">
                                                    <div>Filtrar reporte por</div>
                                                    <v-list-item>
                                                        <v-list-item-content>
                                                            <v-list-item-content>• Día/Semana/Mes</v-list-item-content>
                                                            <v-list-item-content>• Gestor/Encargado/Todos
                                                            </v-list-item-content>
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
                                    <v-toolbar-title>Generar reporte de convenio y liquidación</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-btn icon dark @click="ventanaOpciones = false; ventanaCargar = false">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </v-toolbar>
                                <v-col class="d-flex">
                                    <v-card-text>
                                        <v-select
                                            label="Filtrar por"
                                            :items="itemsfiltrarOpcion"
                                            v-model="filtrarOpcion"
                                            color="light-green darken-1"
                                            outlined
                                        ></v-select>
                                    </v-card-text>
                                    <v-card-text>
                                        <v-select
                                            label="Filtrar por"
                                            :items="itemsfiltrarOpcion2"
                                            v-model="filtrarOpcion2"
                                            color="light-green darken-1"
                                            outlined
                                        ></v-select>
                                    </v-card-text>
                                </v-col>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col>
                                                <v-col v-if="filtrarOpcion=='dia'">
                                                    <v-card-text>
                                                        <v-date-picker v-model="fecha"
                                                                       full-width
                                                                       year-icon="calendar_today"
                                                                       prev-icon="skip_previous"
                                                                       next-icon="skip_next"
                                                                       locale="es"
                                                                       color="light-green darken-1"
                                                        ></v-date-picker>
                                                    </v-card-text>
                                                </v-col>
                                                <v-col v-if="filtrarOpcion=='semana'">
                                                    <v-card-text>
                                                        Por favor selecione un año, mes y dia de la semana en el
                                                        calendario, para
                                                        calcular el número de semana
                                                    </v-card-text>
                                                    <v-card-text>
                                                        <p class="display-1 text--primary">
                                                            Semana:{{semana}}
                                                        </p>
                                                    </v-card-text>
                                                    <v-card-text>
                                                        <v-date-picker v-model="fecha"
                                                                       :events="arrayEvents"
                                                                       event-color="blue"
                                                                       no-title
                                                                       year-icon="calendar_today"
                                                                       prev-icon="skip_previous"
                                                                       next-icon="skip_next"
                                                                       locale="es"
                                                                       @input="calcular_semana"
                                                                       color="light-green darken-1"
                                                        ></v-date-picker>
                                                    </v-card-text>
                                                </v-col>
                                                <v-col v-if="filtrarOpcion=='mes'">
                                                    <v-card-text>
                                                        <v-date-picker v-model="fecha" type="month"
                                                                       full-width
                                                                       year-icon="calendar_today"
                                                                       prev-icon="skip_previous"
                                                                       next-icon="skip_next"
                                                                       locale="es"
                                                                       color="light-green darken-1"
                                                        ></v-date-picker>
                                                    </v-card-text>
                                                </v-col>
                                            </v-col>
                                            <v-col v-if="filtrarOpcion2=='gestor'">
                                                <v-card>
                                                    <v-list dense>
                                                        <v-list-item-group color="primary">
                                                            <v-list-item v-for="(item, i) in items" :key="i"
                                                                         @click="idUsuario = item.id">
                                                                <v-list-item-icon>
                                                                    <v-icon color="light-green darken-1">
                                                                        account_circle
                                                                    </v-icon>
                                                                    <v-list-item-title
                                                                        v-text="item.iniciales"></v-list-item-title>
                                                                </v-list-item-icon>

                                                                <v-list-item-content>
                                                                    <v-list-item-subtitle class="text--primary"
                                                                                          v-text="item.tipo"></v-list-item-subtitle>
                                                                    <v-list-item-subtitle class="text--primary"
                                                                                          v-text="item.nombre"></v-list-item-subtitle>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </v-list-item-group>
                                                    </v-list>
                                                </v-card>
                                            </v-col>
                                            <v-col v-if="filtrarOpcion2=='encargado'">
                                                <v-card>
                                                    <v-list dense>
                                                        <v-list-item-group color="primary">
                                                            <v-list-item v-for="(item, i) in encargados" :key="i"
                                                                         @click="encargado = item.encargado">
                                                                <v-list-item-icon>
                                                                    <v-icon color="light-green darken-1">
                                                                        account_circle
                                                                    </v-icon>

                                                                </v-list-item-icon>

                                                                <v-list-item-content>
                                                                    <v-list-item-subtitle class="text--primary"
                                                                                          v-text="item.encargado"></v-list-item-subtitle>
                                                                </v-list-item-content>
                                                            </v-list-item>
                                                        </v-list-item-group>
                                                    </v-list>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-divider></v-divider>
                                <v-card-actions>
                                    <div class="flex-grow-1"></div>


                                    <v-btn color="light-green darken-1" dark
                                           @click="funcion('/reporteSoloConvenios'); ventanaCargar = true;">
                                        • Convenio
                                    </v-btn>

                                    <v-btn color="light-green darken-1" dark
                                           @click="funcion('/reporteSoloPagoLiquidaciones'); ventanaCargar = true;">
                                        • Liquidación
                                    </v-btn>

                                    <v-btn color="light-green darken-1" dark
                                           @click="funcion('/reporteSoloPagoIntencion'); ventanaCargar = true; ">
                                        • Pago intención
                                    </v-btn>


                                    <v-btn color="light-green darken-1" dark
                                           @click="funcion('/reporteConvenioNuevo'); ventanaCargar = true;">
                                        • Todos
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

        name: "ConvenioLiquidación",
        data() {
            return {
                opcionReporte: String,
                itemsfiltrarOpcion: [
                    {text: 'Día', value: 'dia'},
                    {text: 'Semana', value: 'semana'},
                    {text: 'Mes', value: 'mes'}
                ],
                filtrarOpcion: '',
                itemsfiltrarOpcion2: [
                    {text: 'Gestor', value: 'gestor'},
                    {text: 'Encargado', value: 'encargado'},
                    {text: 'Todos', value: 'todos'}
                ],
                idUsuario: '',
                items: [],
                encargados: '',
                encargado: '',
                filtrarOpcion2: '',
                ventanaOpciones: false,
                ventanaCargar: false,
                barraCarga: false,
                arrayEvents: null,
                fecha: new Date().toISOString().substr(0, 10),
                semana: '',
                menu: '',
            }
        },
        created() {

            axios.get('/obtenerUsuarios')
                .then(res => {
                    this.items = res.data;
                    console.log(res.data);
                });

            axios.get('/obtenerEncargados')
                .then(res => {
                    this.encargados = res.data;
                    console.log(res.data);
                });
        },
        methods: {
            calcular_semana() {
                let now = new Date(this.fecha);
                let onejan = new Date(now.getFullYear(), 0, 1);
                this.semana = Math.ceil((((now - onejan) / 86400000) + onejan.getDay() + 1) / 7);

                let auxArrayEvents = [];
                let auxFecha = new Date(this.fecha);

                let diaSemana = auxFecha.toISOString().substr(8, 10);
                let diaSemanaInt = parseInt(diaSemana, 10);
                let numSemana = auxFecha.getDay();
                if (numSemana == 6) {
                    for (var i = 0; i < 6; i++) {
                        auxFecha.setDate(diaSemanaInt + (i));
                        auxArrayEvents[i] = auxFecha.toISOString().substr(0, 10);
                        console.log(auxFecha)
                    }
                    this.arrayEvents = auxArrayEvents;
                } else {
                    let izquierda = numSemana + 1;
                    console.log(izquierda);
                    for (var i = izquierda + 1; i > 0; i--) {
                        auxFecha.setDate(diaSemanaInt - (i));
                        auxArrayEvents[i] = auxFecha.toISOString().substr(0, 10);
                    }

                    if (numSemana !== 5) {
                        let derecha = (numSemana * -1) + 6;
                        console.log(derecha);
                        let tamArray = auxArrayEvents.length;
                        for (var i = 0; i < derecha - 1; i++) {
                            auxFecha.setDate(diaSemanaInt + (i));
                            auxArrayEvents[tamArray] = auxFecha.toISOString().substr(0, 10);
                            tamArray = tamArray + 1;
                        }
                    }
                    this.arrayEvents = auxArrayEvents;
                }

            },
            validarSelect() {
                if (this.filtrarOpcion === '') {
                    swal({
                        title: "Por favor seleccione una opción",
                        text: "Día, semana o mes",
                        icon: "info",
                        button: "Entendido",
                    });
                    this.ventanaOpciones = true;
                    return false;
                } else if (this.filtrarOpcion === 'semana') {
                    if (this.semana === '') {
                        swal({
                            title: "Por favor seleccione una semana del calendario",
                            icon: "info",
                            button: "Entendido",
                        });
                        this.ventanaOpciones = true;
                        return false;
                    }
                }
                if (this.filtrarOpcion2 === '') {
                    swal({
                        title: "Por favor seleccione una opción",
                        text: "Gestor, encargado o todos",
                        icon: "info",
                        button: "Entendido",
                    });
                    this.ventanaOpciones = true;
                    return false;
                } else if (this.filtrarOpcion2 === 'gestor') {
                    if (this.idUsuario === '') {
                        swal({
                            title: "Por favor seleccione un gestor",
                            icon: "info",
                            button: "Entendido",
                        });
                        this.ventanaOpciones = true;
                        return false;
                    }
                }
            },
            funcion(ruta) {
                if (this.validarSelect() === false) return;
                if (this.fecha !== '') {

                    this.ventanaOpciones = false;
                    this.barraCarga = true;
                    this.ventanaCargar = true;

                    const params = {
                        fechaBuscar: this.fecha,
                        filtrado: this.filtrarOpcion,
                        filtrado2: this.filtrarOpcion2,
                        idUsuario: this.idUsuario,
                        encargado: this.encargado
                    };


                    axios.post(ruta, params)
                        .then(res => {
                            this.barraCarga = false;
                            this.ventanaCargar = false;

                            if (ruta === '/reporteSoloConvenios') {
                                download(res.data, "reporte_convenios.csv", 'csv');

                            } else if (ruta === '/reporteSoloPagoIntencion') {
                                download(res.data, "reporte_intencion.csv", 'csv');

                            } else if (ruta === '/reporteSoloPagoLiquidaciones') {
                                download(res.data, "reporte_liquidacion.csv", 'csv');
                            }else{
                                download(res.data, "reporte_todos.csv", 'csv');
                            }

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
