<template>
    <v-app>
        <div id="app">
            <spinner-component v-if="loading"></spinner-component>


            <v-dialog v-model="dialog" max-width="45%">
                <v-card>
                    <v-form @submit.prevent="store" v-model="valid">
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title>Editar Pago</v-toolbar-title>
                            <div class="flex-grow-1"></div>
                            <v-icon>attach_money</v-icon>
                        </v-toolbar>

                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-card-text>
                                        <v-date-picker v-model="fecha_pago_realizada"
                                                       required
                                                       year-icon="calendar_today"
                                                       prev-icon="skip_previous"
                                                       next-icon="skip_next"
                                                       locale="es"
                                        ></v-date-picker>
                                    </v-card-text>

                                    <v-card-text>
                                        <v-text-field
                                            class="my-input"
                                            v-model="pago_realizado"
                                            :rules="rules"
                                            hint="Por ejemplo, 150"
                                            label="Ingrese monto"
                                        ></v-text-field>
                                    </v-card-text>


                                    <v-card-text>
                                        <v-text-field
                                            class="my-input"
                                            v-model="folio_ingresado"
                                            hint="Por ejemplo, A-001"
                                            label="Ingrese el folio"
                                            required
                                        ></v-text-field>
                                    </v-card-text>
                                </v-col>
                            </v-row>
                        </v-container>


                        <v-card-actions>
                            <div class="flex-grow-1"></div>


                            <v-card style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="red ">
                                <v-btn
                                    class="mx-auto text-center text-white"
                                    color="red" @click="dialog = false">
                                    Cancelar
                                </v-btn>

                            </v-card>

                            <v-card style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="blue ">
                                <v-btn
                                    type="submit"
                                    :disabled="!valid"
                                    class="mx-auto text-center text-white"
                                    color="blue" @click="dialog = false">
                                    Editar pago
                                </v-btn>

                            </v-card>


                        </v-card-actions>
                    </v-form>
                </v-card>

            </v-dialog>


            <table class="table table-bordered table-striped" responsive>
                <thead>
                <tr>
                    <th class="text-center"><h5>Saldo del plan</h5></th>
                    <th class="text-center"><h5>Avance del plan</h5></th>
                    <th class="text-center"><h5>Por pagar</h5></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <v-card style="padding: 6px;" class="mx-auto text-white text-center"
                                color="light-blue darken-3"> {{totales.deudaOriginal}}
                        </v-card>
                    </td>
                    <td>
                        <v-card style="padding: 6px;" class="mx-auto text-white text-center"
                                color="light-green darken-1">
                            {{totales.deudaPagada}}
                        </v-card>
                    </td>
                    <td>
                        <v-card style="padding: 6px;" class="mx-auto text-white text-center"
                                color="red"> {{totales.deudaTotal}}
                        </v-card>
                    </td>
                </tr>
                </tbody>
            </table>
            <br><br>
            <template v-if="tipo==='Administrador'" style="padding: 6px; margin: 6px;">
                <v-btn color="red" dark @click="cancelarConvenio">
                    Cancelar convenio
                </v-btn>
            </template>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Número de pago</th>
                        <th scope="col">Fecha esperada</th>
                        <th scope="col">Monto</th>
                        <th scope="col">¿Cuándo pagó?</th>
                        <th scope="col">¿Cuánto pagó?</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in datosPago">

                        <template v-if="index === 0">

                            <td v-if="index===0">
                                <div v-if="convenio.numero_pagos===0">
                                    Un solo pago(Liquidación)
                                </div>
                                <div v-else>
                                    Primer Pago
                                </div>
                            </td>
                            <td v-else>

                                {{index}}

                            </td>
                            <td>
                                {{item.fecha_pago_esperada}}
                            </td>
                            <td>
                                $ {{formatNumber(item.pago_esperado)}}
                            </td>
                            <td v-if="item.fecha_pago_realizada===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <div v-else>
                                <td>
                                    {{item.fecha_pago_realizada}}
                                </td>
                            </div>
                            <td v-if="item.pago_realizado===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <td v-else>
                                $ {{formatNumber(item.pago_realizado)}}
                            </td>
                            <td v-if="item.folio_ingresado===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <td v-else>
                                {{item.folio_ingresado}}
                            </td>
                            <td v-if="item.pagado===null">
                                <div v-if="totales.deudaTotal==='0'">
                                    - - -
                                </div>
                                <div v-else>
                                    <v-btn color="primary" dark @click.stop="dialog = true"
                                           @click="asignarValor(item.id_calendario)">
                                        Realizar pago
                                    </v-btn>
                                </div>
                            </td>
                            <td v-else>
                                <v-card
                                    style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="light-green darken-1">
                                    <v-btn
                                        type="submit"
                                        disabled="true"
                                        class="mx-auto text-center text-white"
                                        color="light-green darken-1">
                                        Pagado
                                    </v-btn>

                                </v-card>

                            </td>
                        </template>

                        <template v-else-if="datosPago[0].pagado==='Si'">
                            <td>
                                {{index}}
                            </td>
                            <td>
                                {{item.fecha_pago_esperada}}
                            </td>
                            <td>
                                $ {{formatNumber(item.pago_esperado)}}
                            </td>
                            <td v-if="item.fecha_pago_realizada===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <div v-else>
                                <td>
                                    {{item.fecha_pago_realizada}}
                                </td>
                            </div>
                            <td v-if="item.pago_realizado===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <td v-else>
                                $ {{formatNumber(item.pago_realizado)}}
                            </td>
                            <td v-if="item.folio_ingresado===null">
                                <div v-if="totales.deudaTotal===0">
                                    - - -
                                </div>
                                <div v-else>
                                    Pendiente
                                </div>
                            </td>
                            <td v-else>
                                {{item.folio_ingresado}}
                            </td>
                            <td v-if="item.pagado===null">
                                <div v-if="totales.deudaTotal==='0'">
                                    - - -
                                </div>
                                <div v-else>
                                    <v-btn color="primary" dark @click.stop="dialog = true"
                                           @click="asignarValor(item.id_calendario)">
                                        Realizar pago
                                    </v-btn>
                                </div>
                            </td>
                            <td v-else>
                                <v-card
                                    style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="light-green darken-1">
                                    <v-btn
                                        type="submit"
                                        disabled="true"
                                        class="mx-auto text-center text-white"
                                        color="light-green darken-1">
                                        Pagado
                                    </v-btn>

                                </v-card>

                            </td>
                        </template>


                    </tr>


                    </tbody>
                </table>
            </div>

        </div>
    </v-app>
</template>

<script>
    export default {

        props: {
            id_c: String,
            tipo: String,
        },
        data: () => ({
            dialog: false,
            menu: false,
            valid: true,

            id_cliente: '',
            id_calendario: '',
            fecha_pago_realizada: new Date().toISOString().substr(0, 10),
            pago_realizado: '',
            folio_ingresado: '',

            loading: true,
            convenio: [],
            datosPago: [],
            probando: 10,
            totales: [],
            folio: [],
            pagoReglas: [],
        }),
        computed: {
            rules() {
                const rules = [];
                const rule1 = v => v <= parseInt(this.totales.deudaTotal) || 'El monto ingresado no puede ser mayor a la deuda';
                const rule2 = v => !!v || 'Necesita ingresar el pago del cliente';
                const rule3 = v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números';
                rules.push(rule3);
                rules.push(rule2);
                rules.push(rule1);
                return rules;
            }
        },
        created() {
            axios.get(`/gestionPago/${this.id_c}`)
                .then(res => {
                    this.id_cliente = this.id_c;
                    this.convenio = res.data.convenio;
                    this.datosPago = res.data.datosPago;
                    this.id_cliente = res.data.id_cliente;
                    this.totales = res.data.totales;
                    this.folio = res.data.folio;
                    this.loading = false;
                });
        },
        methods: {
            formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            },
            asignarValor(id_calendario) {
                this.id_calendario = id_calendario;
            },
            store() {
                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });
                axios.post('/gestionPago',
                    {
                        id_calendario: this.id_calendario,
                        id_cliente: this.id_cliente,
                        fecha_pago_realizada: this.fecha_pago_realizada,
                        pago_realizado: this.pago_realizado,
                        folio_ingresado: this.folio_ingresado
                    }
                ).then(res => {

                    this.convenio = res.data.convenio;
                    this.datosPago = res.data.datosPago;
                    this.id_cliente = res.data.id_cliente;
                    this.totales = res.data.totales;
                    this.folio = res.data.folio;
                    swal({
                        title: "¡Agregado!",
                        text: "La página recargara para actualizar los datos, por favor espere",
                        icon: "success",
                        button: "Entendido",
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 3500);
                    EventBus.$emit('agrego_pago');
                });
            },
            cancelarConvenio() {
                swal({
                    title: "¿Estás seguro de cancelar el convenio?",
                    text: "¡Una vez eliminado, no se puede recuperar!",
                    icon: "warning",
                    buttons: {
                        cancel: 'No',
                        confirm: 'Si, cancelar',
                    },

                })
                    .then((loading) => {
                        if (loading) {

                            axios.get(`/cancelarConvenio/${this.convenio.id_convenio}/${this.id_cliente}`)
                                .then(res => {
                                    console.log(res.data);
                                    swal({
                                        title: "¡Convenio cancelado!",
                                        text: "La página recargara para actualizar los datos, por favor espere",
                                        icon: "success",
                                        button: "Entendido",
                                    });
                                    setTimeout(function () {
                                        location.reload();
                                    }, 3500);
                                });

                        } else {
                            swal({
                                title: "¡Listo!",
                                text: "No se cancelo el convenio",
                                icon: "info",
                                button: false,
                            });
                        }
                    });
            },
        },
    }
</script>

<style scoped>

</style>
