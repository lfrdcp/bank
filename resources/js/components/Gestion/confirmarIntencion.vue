<template>
    <v-app>
        <div id="app">
            <spinner-component v-if="loading"></spinner-component>

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

            <template v-if="tipo==='Administrador' && intencion.estado ==='0'" style="padding: 6px; margin: 6px;">
                <v-btn color="red" dark @click="cancelarConvenio">
                    Cancelar pago intención
                </v-btn>
            </template>

            <br><br>

            <v-card>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Número de pago</th>
                            <th scope="col">Fecha esperada</th>
                            <th scope="col">Monto</th>
                            <th scope="col">¿Cuando pago?</th>
                            <th scope="col">¿Cuanto pago?</th>
                            <th scope="col">Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                Un solo pago (Pago intención)
                            </td>
                            <td>
                                {{intencion.fecha}}
                            </td>
                            <td>
                                $ {{formatNumber(intencion.pago)}}
                            </td>

                            <template v-if="intencion.fecha_realizada===null">
                                <td>
                                    Pendiente
                                </td>
                            </template>
                            <template v-else>
                                <td>
                                    {{intencion.fecha_realizada}}
                                </td>
                            </template>

                            <template v-if="intencion.pago_realizado===null || intencion.pago_realizado===0">
                                <td>
                                    Pendiente
                                </td>
                            </template>
                            <template v-else>
                                <td>
                                    $ {{formatNumber(intencion.pago_realizado)}}
                                </td>
                            </template>

                            <template v-if="intencion.estado==='0'">
                                <td>
                                    <v-card
                                        style="padding: 6px;"
                                        class="mx-auto text-center text-white"
                                        color="amber lighten-2 darken-1">
                                        Pendiente
                                    </v-card>
                                </td>
                            </template>
                            <template v-else-if="intencion.estado==='2'">
                                <td>
                                    <v-card
                                        style="padding: 6px;"
                                        class="mx-auto text-center text-white"
                                        color="light-green darken-1">
                                        Pagado
                                    </v-card>
                                </td>
                            </template>
                            <template v-else-if="intencion.estado==='1'">
                                <td>
                                    <v-card
                                        style="padding: 6px;"
                                        class="mx-auto text-center text-white"
                                        color="red darken-1">
                                        Cancelado
                                    </v-card>
                                </td>
                            </template>


                        </tr>


                        </tbody>
                    </table>
                </div>
            </v-card>

            <br><br>

            <template v-if="intencion.estado ==='0'">
                <v-card class="mx-auto" max-width="400">
                    <v-form @submit.prevent="store" v-model="valid">
                        <v-toolbar color="primary" dark>
                            <v-toolbar-title>Realizar Pago</v-toolbar-title>
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
                                            label="Ingrese cuanto pago"
                                        ></v-text-field>
                                    </v-card-text>

                                </v-col>
                            </v-row>
                        </v-container>

                        <v-card-actions>
                            <div class="flex-grow-1"></div>
                            <v-card style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="blue ">
                                <v-btn
                                    type="submit"
                                    :disabled="!valid"
                                    class="mx-auto text-center text-white"
                                    color="blue" @click="dialog = false">
                                    Realizar pago
                                </v-btn>
                            </v-card>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </template>


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
            fecha_pago_realizada: new Date().toISOString().substr(0, 10),
            pago_realizado: '',
            intencion: '',
            totales: [],
            loading: true,
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
            axios.get(`/realizarPagoIntencion/${this.id_c}`)
                .then(res => {
                    this.intencion = res.data.intencion;
                    this.totales = res.data.totales;
                    this.id_cliente = this.id_c;
                    this.loading = false;
                });
        },
        methods: {
            formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            },
            store() {
                swal({
                    title: "¡Cargando!",
                    text: "Por favor espere",
                    icon: "info",
                    button: false,
                });
                axios.post('/guardarPagoIntencion',
                    {
                        id_intencion: this.intencion.id_intencion,
                        fecha_pago_realizada: this.fecha_pago_realizada,
                        pago_realizado: this.pago_realizado
                    }
                ).then(res => {

                    swal({
                        title: "¡Pagado!",
                        text: "La página recargara para actualizar los datos, por favor espere",
                        icon: "success",
                        button: "Entendido",
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 3500);


                });
            },
            cancelarConvenio() {
                swal({
                    title: "¿Estas seguro de que quieres cancelar el pago intención?",
                    text: "¡Una vez cancelado, no se puede recuperar!",
                    icon: "warning",
                    buttons: {
                        cancel: 'No',
                        confirm: 'Si, cancelar',
                    },

                })
                    .then((loading) => {
                        if (loading) {

                            axios.get(`/cancelarPagoIntencion/${this.intencion.id_intencion}`)
                                .then(res => {
                                    alert(res.data);
                                    if (res.data) {
                                        swal({
                                            title: "¡Pago intención cancelado!",
                                            text: "La página recargara para actualizar los datos, por favor espere",
                                            icon: "success",
                                            button: "Entendido",
                                        });
                                        setTimeout(function () {
                                            location.reload();
                                        }, 3500);
                                    } else {
                                        swal({
                                            title: "¡Hubo un error!",
                                            text: "Por favor toma captura y comunicate con el personal",
                                            icon: "error",
                                            button: "Entendido",
                                        });
                                    }
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
