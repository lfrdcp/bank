<template>
    <div id="app">

        <spinner-component v-if="loading"></spinner-component>

        <v-app id="inspire">
            <v-content>
                <v-container>

                    <div class="text-center">

                        <v-dialog v-if="tipoUsuario!=='Gestor'" v-model="dialogFiltrar" max-width="45%">
                            <template v-slot:activator="{ on }">
                                <v-btn :color="selectedEvent.color" dark v-on="on">
                                    <i class="material-icons">filter_list</i>
                                    Filtrar el calendario
                                </v-btn>
                            </template>
                            <v-card>
                                <v-toolbar :color="selectedEvent.color" dark>
                                    <v-icon>filter_list</v-icon>
                                    <v-toolbar-title>Filtrar el calendario</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-btn icon dark @click="dialogFiltrar = false">
                                        <v-icon>close</v-icon>
                                    </v-btn>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container>

                                        <v-row>
                                            <v-col>
                                                <v-card>
                                                    <v-card-title>Ver por usuario</v-card-title>
                                                    <v-card-text>Da clic a un usuario para ver su calendario
                                                    </v-card-text>

                                                    <v-list dense>
                                                        <v-list-item-group color="primary">
                                                            <v-list-item v-for="(item, i) in usuarios" :key="i"
                                                                         @click="idBuscar = item.id; dialogFiltrar = false; buscarPorId(item.tipo)">
                                                                <v-list-item-icon>
                                                                    <v-icon color="primary">account_circle</v-icon>
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

                                            <v-form @submit.prevent="buscarPorId" v-model="valid">
                                                <v-col>
                                                    <v-card>
                                                        <v-card-title>Ver todo</v-card-title>
                                                        <v-card-text>Ver la información de todos los
                                                            gestores
                                                        </v-card-text>


                                                        <v-btn v-on:click="idBuscar=''" type="submit"
                                                               color="primary"
                                                               @click="dialogFiltrar = false">Ver
                                                        </v-btn>
                                                        <br><br><br>
                                                    </v-card>
                                                </v-col>
                                            </v-form>
                                        </v-row>

                                    </v-container>
                                </v-card-text>
                            </v-card>
                        </v-dialog>

                        <v-dialog v-model="dialogColores" max-width="60%">
                            <template v-slot:activator="{ on }">
                                <v-btn :color="selectedEvent.color" dark v-on="on">
                                    <i class="material-icons">color_lens</i>
                                    Filtrar por tipo
                                </v-btn>
                            </template>
                            <v-card>
                                <v-toolbar :color="selectedEvent.color" dark>
                                    <v-icon>color_lens</v-icon>
                                    <v-toolbar-title> Filtrar por tipo</v-toolbar-title>
                                    <div class="flex-grow-1"></div>
                                    <v-btn icon dark @click="dialogColores = false">
                                        <v-icon>close</v-icon>
                                    </v-btn>

                                </v-toolbar>
                                <v-card-text>
                                    <v-simple-table>
                                        <thead>
                                        <tr>
                                            <th class="text-center"><h5>Convenio</h5></th>
                                            <th class="text-center"><h5></h5></th>

                                            <th class="text-center"><h5>Pago</h5></th>
                                            <th class="text-center"><h5></h5></th>

                                            <th class="text-center"><h5>Contacto</h5></th>
                                            <th class="text-center"><h5></h5></th>

                                            <th class="text-center"><h5>Todo</h5></th>
                                            <th class="text-center"><h5></h5></th>
                                        </tr>
                                        </thead>
                                        <tbody>


                                        <tr>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="blue accent-2"
                                                        @click="colorTipo='blue accent-2'">
                                                    Convenio activo
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--Convenio activo-->
                                                <v-icon color="green" @click="switch_con_act = false; filtrarColor()"
                                                        v-if="switch_con_act">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_con_act = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>

                                            </td>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto" color="yellow lighten-1"
                                                        @click="colorTipo='yellow lighten-1'">
                                                    Liquidación
                                                    pendiente
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--  Liquidación
                                                pendiente-->
                                                <v-icon color="green" @click="switch_liq_pen = false; filtrarColor()"
                                                        v-if="switch_liq_pen">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_liq_pen = true;  filtrarColor()" v-else >
                                                    check_box_outline_blank
                                                </v-icon>

                                            </td>


                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="deep-purple accent-2"
                                                        @click="colorTipo='deep-purple accent-2'">
                                                    Contactar
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--Contactar-->
                                                <v-icon color="green" @click="switch_contactar = false; filtrarColor()"
                                                        v-if="switch_contactar">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_contactar = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </td>


                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="black"
                                                        @click="colorTipo='black'">
                                                    Ver todo
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--Ver todo-->


                                                <v-icon color="green" @click="switch_todo = false; filtrarColor()"
                                                        v-if="switch_todo">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_todo = true; filtrarColor();cambiarFalso()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="blue darken-4"
                                                        @click="colorTipo='blue darken-4'">
                                                    Convenio
                                                    cancelado
                                                </v-card>
                                            </td>
                                            <td>
                                                <!-- Convenio
                                                    cancelado-->


                                                <v-icon color="green" @click="switch_con_can = false; filtrarColor()"
                                                        v-if="switch_con_can">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_con_can = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </td>
                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="lime darken-1"
                                                        @click="colorTipo='lime darken-1'">
                                                    Liquidación realizada
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--    Convenio
                                                       Pendiente-->
                                                <v-icon color="green" @click="switch_liq_rea = false; filtrarColor()"
                                                        v-if="switch_liq_rea">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_liq_rea = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>

                                            </td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        <tr>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="orange lighten-2"
                                                        @click="colorTipo='orange lighten-2';">
                                                    Convenio
                                                    Pendiente
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--    Convenio pendiente-->

                                                <v-icon color="green" @click="switch_con_pen = false; filtrarColor()"
                                                        v-if="switch_con_pen">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_con_pen = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>

                                            </td>


                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto" color="orange lighten-1"
                                                        @click="colorTipo='orange lighten-1';">
                                                    Pago
                                                    pendiente
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--    Pago
                                                    pendiente-->

                                                <v-icon color="green" @click="switch_pago_pen = false; filtrarColor()"
                                                        v-if="switch_pago_pen">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_pago_pen = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>


                                        <tr>
                                            <td></td>
                                            <td></td>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="light-green darken-1"
                                                        @click="colorTipo='light-green darken-1';">Pago
                                                    realizado
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--    Pago
                                                    realizado-->
                                                <v-icon color="green" @click="switch_pago_rea = false; filtrarColor()"
                                                        v-if="switch_pago_rea">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_pago_rea = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>


                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>


                                        <tr>
                                            <td></td>
                                            <td></td>

                                            <td>
                                                <v-card style="padding: 6px;" class="mx-auto text-white"
                                                        color="pink lighten-1"
                                                        @click="colorTipo='pink lighten-1';">
                                                    Pago
                                                    intención
                                                </v-card>
                                            </td>
                                            <td>
                                                <!--     Pago
                                                    intención-->
                                                <v-icon color="green" @click="switch_pago_int = false; filtrarColor()"
                                                        v-if="switch_pago_int">check_box
                                                </v-icon>

                                                <v-icon color="orange" @click="switch_pago_int = true; filtrarColor()" v-else>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        </tbody>
                                    </v-simple-table>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                    <br>
                    <v-dialog v-model="dialog" max-width="450px">
                        <v-card>

                            <v-card-title>{{this.nombre}}</v-card-title>

                            <form @submit.prevent="actualizar(nombre,start,color,hora,comentarioNuevo,id,fecha)">
                                <v-col v-if="this.tipo === 'Contactar'">
                                    <div class="title">Fecha</div>
                                    <v-date-picker v-model="fecha"
                                                   :min="limiteFecha"
                                                   :color="color"
                                                   :landscape="$vuetify.breakpoint.smAndUp"
                                                   required
                                                   full-width
                                                   year-icon="calendar_today"
                                                   prev-icon="skip_previous"
                                                   next-icon="skip_next"
                                                   locale="es"
                                    ></v-date-picker>
                                </v-col>

                                <v-card-text>
                                    <v-time-picker :color="color" v-model="hora" ampm-in-title></v-time-picker>
                                </v-card-text>


                                <v-card-text>
                                    <v-textarea
                                        :color="color"
                                        outlined
                                        name="input-7-4"
                                        label="Comentario"
                                        v-model="comentarioNuevo"
                                    ></v-textarea>
                                </v-card-text>
                                <v-card-actions>
                                    <div class="flex-grow-1"></div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            @click="dialog = false">Cerrar
                                    </button>
                                    <button class="btn btn-primary" type="submit" @click="dialog = false">Editar
                                    </button>
                                </v-card-actions>
                            </form>
                        </v-card>
                    </v-dialog>

                    <v-layout>
                        <v-flex>
                            <v-sheet>
                                <v-toolbar flat :color="selectedEvent.color" dark>
                                    <v-btn outlined class="mr-4" @click="setToday">
                                        Hoy
                                    </v-btn>
                                    <v-btn fab text small @click="prev">
                                        <i class="material-icons">chevron_left</i>
                                    </v-btn>
                                    <v-btn fab text small @click="next">
                                        <i class="material-icons">chevron_right</i>
                                    </v-btn>

                                    <v-toolbar-title>{{ title }}</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-menu bottom right>
                                        <template v-slot:activator="{ on }">
                                            <v-btn outlined v-on="on">
                                                <span>{{ typeToLabel[type] }}</span>
                                                <i class="material-icons">expand_more</i>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item @click="type = 'day'">
                                                <v-list-item-title>Día</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item @click="type = '4day'">
                                                <v-list-item-title>4 Dias</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item @click="type = 'week'">
                                                <v-list-item-title>Semana</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item @click="type = 'month'">
                                                <v-list-item-title>Mes</v-list-item-title>
                                            </v-list-item>

                                        </v-list>
                                    </v-menu>
                                </v-toolbar>
                            </v-sheet>
                            <v-sheet>
                                <v-calendar
                                    class="order-3 pa-2"
                                    ref="calendar"
                                    v-model="focus"
                                    color="primary"
                                    :events="eventsAux"
                                    :event-color="getEventColor"
                                    :event-margin-bottom="3"
                                    :now="today"
                                    :type="type"
                                    locale="es"
                                    :first-interval="7"
                                    :interval-minutes="60"
                                    :interval-count="16"
                                    event-overlap-threshold="1000"
                                    event-margin-bottom="10"
                                    interval-height="150"
                                    @click:event="showEvent"
                                    @click:more="viewDay"
                                    @click:date="viewDay"
                                    @change="updateRange"
                                ></v-calendar>
                                <v-menu v-model="selectedOpen" :close-on-content-click="false"
                                        :activator="selectedElement"
                                        offset-x>
                                    <v-card color="grey lighten-4" align="left">
                                        <v-toolbar :color="selectedEvent.color" dark>

                                            <v-btn
                                                v-if="selectedEvent.tipo!=='Gestion' && selectedEvent.tipo!=='Convenio-Activo' && selectedEvent.tipo!=='Convenio-Cancelado'"
                                                icon @click="[llenarDatos, dialog = true]">
                                                <i class="material-icons">mode_edit</i>
                                            </v-btn>
                                            <v-toolbar-title>{{this.selectedEvent.tipo}}</v-toolbar-title>
                                            <v-spacer></v-spacer>
                                            <v-btn icon @click="selectedOpen = false">
                                                <i class="material-icons">close</i>
                                            </v-btn>
                                        </v-toolbar>
                                        <v-card-text>
                                            <p v-if="selectedEvent.tipo!=='Contactar'"><b>ID:</b>{{selectedEvent.folioGen}}
                                            </p>
                                            <p><b>Nombre:</b>{{selectedEvent.name}}</p>
                                            <p><b>Fecha y hora:</b>{{selectedEvent.start}}</p>
                                            <p v-if="selectedEvent.tipo!=='Gestion' && selectedEvent.tipo!=='Convenio-Activo' && selectedEvent.tipo!=='Convenio-Cancelado'">
                                                <b>Comentario:</b>
                                                {{selectedEvent.details}}</p>

                                            <a v-bind:href="'/gestion/'+ selectedEvent.id_cliente" target="_blank">
                                                <v-btn :color="selectedEvent.color" dark block>
                                                    <v-icon dark>list</v-icon>
                                                    &emsp; Ver más
                                                </v-btn>
                                            </a>
                                        </v-card-text>
                                    </v-card>
                                </v-menu>
                            </v-sheet>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>

    </div>
</template>

<script>
    export default {

        data: () => ({
            valid: true,
            probando: [],
            switch_con_act: false,
            switch_con_can: false,
            switch_con_pen: false,
            switch_contactar: false,
            switch_liq_pen: false,
            switch_liq_rea: false,
            switch_pago_pen: false,
            switch_pago_rea: false,
            switch_pago_int: false,
            switch_todo: false,

            colorTipo: '',
            eventsAux: '',
            dialog: false,
            dialogColores: true,
            dialogFiltrar: '',
            tipoUsuario: '',
            usuarios: [],
            idBuscar: '',
            filtro: '',
            tipo: '',
            folioGen: '',
            id_cliente: '',
            id: '',
            nombre: '',
            comentarioNuevo: '',
            hora: '',
            fecha: '',
            color: 'primary',
            loading: true,
            today: null,
            focus: null,
            type: 'month',
            typeToLabel: {
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                '4day': '4 Dias',
            },
            start: null,
            end: null,
            selectedEvent: {
                color: 'primary',
            },
            selectedElement: null,
            selectedOpen: false,
            events: [],
            idReglas: [
                v => !!v || 'Necesita ingresar un ID de un usuario',
                v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números',
            ],
        }),

        created() {
            var d = new Date();
            var mm = d.getMonth() + 1;
            var dd = d.getDate();
            var yy = d.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            var myDateString = yy + '-' + mm + '-' + dd;

            this.today = myDateString;
            this.focus = myDateString;
            this.limiteFecha = yy + '-' + mm + '-' + dd;
            axios.get('/calendario/', {
                params: {
                    id: this.idBuscar
                }
            })
                .then(res => {
                    this.events = res.data.notas;
                    this.eventsAux = this.events;
                    this.filtro = res.data.filtro;
                    this.usuarios = res.data.usuarios;
                    this.tipoUsuario = res.data.tipoUsuario;
                    this.loading = false;
                    if (this.events.length === 0) {
                        swal("El calendario esta vacio", {
                            icon: "info",
                            button: "Entendido",
                        });
                    }
                    if (this.filtro === 1) {
                        this.dialogFiltrar = true;
                    }
                });
        },
        computed: {
            llenarDatos() {
                this.selectedOpen = false;
                this.id = this.selectedEvent.id;
                this.nombre = this.selectedEvent.name;
                this.comentarioNuevo = this.selectedEvent.details;
                this.hora = this.selectedEvent.hora;
                this.start = this.selectedEvent.start;
                this.color = this.selectedEvent.color;
                this.tipo = this.selectedEvent.tipo;
                this.id_cliente = this.selectedEvent.id_cliente;
                this.folioGen = this.selectedEvent.folioGen;
            },
            title() {
                const {start, end} = this
                if (!start || !end) {
                    return ''
                }

                const startMonth = this.monthFormatter(start)
                const endMonth = this.monthFormatter(end)
                const suffixMonth = startMonth === endMonth ? '' : endMonth

                const startYear = start.year
                const endYear = end.year
                const suffixYear = startYear === endYear ? '' : endYear

                const startDay = start.day + this.nth(start.day)
                const endDay = end.day + this.nth(end.day)

                switch (this.type) {
                    case 'month':
                        return `${startMonth} ${startYear}`
                    case 'week':
                    case '4day':
                        return `${startMonth} ${startDay} ${startYear} - ${suffixMonth} ${endDay} ${suffixYear}`
                    case 'day':
                        return `${startMonth} ${startDay} ${startYear}`
                }
                return ''
            },
            monthFormatter() {
                return this.$refs.calendar.getFormatter({
                    timeZone: 'UTC', month: 'long', locale: 'es'
                })
            },

        },
        methods: {
            viewDay({date}) {
                this.focus = date
                this.type = 'day'
            },
            getEventColor(event) {
                return event.color
            },
            setToday() {
                this.focus = this.today
            },
            prev() {
                this.$refs.calendar.prev()
            },
            next() {
                this.$refs.calendar.next()
            },
            showEvent({nativeEvent, event}) {

                const open = () => {
                    this.selectedEvent = event;
                    this.selectedElement = nativeEvent.target;
                    setTimeout(() => this.selectedOpen = true, 10)
                };
                if (this.selectedOpen) {
                    this.selectedOpen = false;
                    setTimeout(open, 10)
                } else {
                    open()
                }
                nativeEvent.stopPropagation()
            },
            updateRange({start, end}) {
                // You could load events from an outside source (like database) now that we have the start and end dates on the calendar
                this.start = start
                this.end = end
            },
            nth(d) {
                return d > 3 && d < 21
                    ? ' '
                    : [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '][d % 10]
            },
            actualizar(nombre, start, color, h, com, id, f) {
                swal({
                    title: "¿Estas seguro de que quieres actualizar?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((loading) => {
                        if (loading) {
                            const params = {
                                nombre,
                                start,
                                color,
                                h,
                                com,
                                id,
                                f,
                                tipo: this.tipo,
                                folioGen: this.folioGen,
                                id_cliente: this.id_cliente
                            };
                            axios.put(`/calendario/${id}`, params)
                                .then(res => {
                                    const index = this.events.findIndex(item => item.id === id && item.tipo === this.tipo);
                                    this.events.splice(index, 1);
                                    this.events.push(res.data);
                                    console.log(res.data);
                                });

                            swal("¡Listo! Actualizado con exito", {
                                icon: "success",
                            });
                        } else {

                            swal("¡No se actualizo!");
                        }
                    });
            },
            buscarPorId(type) {
                this.events = [];
                this.loading = true;

                axios.get('/calendario/',
                    {
                        params: {
                            id: this.idBuscar,
                            tipo: type,
                        }
                    })
                    .then(res => {
                        this.events = res.data.notas;
                        this.eventsAux = this.events;
                        this.filtro = res.data.filtro;
                        this.loading = false;
                        if (this.events.length === 0) {
                            swal("El calendario esta vacio", {
                                icon: "info",
                                button: "Entendido",
                            });
                        }
                    });
                $('#modalFiltrar').modal('hide');
            },
            cambiarFalso(){
                this.switch_con_act=false;
                this.switch_con_can=false;
                this.switch_con_pen=false;
                this.switch_contactar=false;
                this.switch_liq_pen=false;
                this.switch_liq_rea=false;
                this.switch_pago_pen=false;
                this.switch_pago_rea=false;
                this.switch_pago_int=false;
                this.switch_todo=true;
                this.eventsAux = this.events;
            },

            filtrarColor() {
                if(this.switch_con_act){
                    this.probando.push("blue accent-2");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='blue accent-2');
                }
                if(this.switch_con_can){
                    this.probando.push("blue darken-4");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='blue darken-4');
                }
                if(this.switch_con_pen){
                    this.probando.push("orange lighten-2");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='orange lighten-2');
                }
                if(this.switch_contactar){
                    this.probando.push("deep-purple accent-2");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='deep-purple accent-2');
                }
                if(this.switch_liq_pen){
                    this.probando.push("yellow lighten-1");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='yellow lighten-1');
                }
                if(this.switch_liq_rea){
                    this.probando.push("lime darken-1");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='lime darken-1');
                }
                if(this.switch_pago_pen){
                    this.probando.push("orange lighten-1");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='orange lighten-1');
                }
                if(this.switch_pago_rea){
                    this.probando.push("light-green darken-1");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='light-green darken-1');
                }
                if(this.switch_pago_int){
                    this.probando.push("pink lighten-1");
                    this.switch_todo=false;
                }else{
                    this.probando=this.probando.filter(e=> e!=='pink lighten-1');
                }
                if(this.switch_todo){
                    this.eventsAux = this.events;
                    this.switch_con_act=false;
                    this.switch_con_can=false;
                    this.switch_con_pen=false;
                    this.switch_contactar=false;
                    this.switch_liq_pen=false;
                    this.switch_liq_rea=false;
                    this.switch_pago_pen=false;
                    this.switch_pago_rea=false;
                    this.switch_pago_int=false;
                }else{
                    this.eventsAux = this.events.filter(e => this.probando.includes(e.color));
                }
                if (this.colorTipo === "black") {
                    this.eventsAux = this.events;
                } else {

                }
                /*this.dialogColores = false;*/
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
