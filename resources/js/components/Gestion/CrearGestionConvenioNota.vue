<template>
    <div id="app">
        <v-app id="inspire">
            <v-content>
                <v-container>
                    <v-card dark color="blue">
                        <v-row>
                            <v-col>{{ id_cliente }}</v-col>
                            <v-col> {{ cliente.nombre }}</v-col>
                            <v-col>$ {{ formatNumber(pago.total) }}</v-col>
                        </v-row>
                    </v-card>
                    <v-form @submit.prevent="store" v-model="valid">

                        <br>
                        <v-card outlined>
                            <v-card-text>
                                <v-card dark color="light-blue accent-2">
                                    Gestion
                                </v-card>

                                <v-row>
                                    <v-col>
                                        <v-card color="light-blue accent-2">
                                            <v-container class="pa-2">
                                                <v-card>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr>
                                                            <td><b>Nombre del titular</b></td>
                                                            <td>{{ cliente.nombre }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Clasificación del titular</b></td>
                                                            <td>{{ pago.clasificacion }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Atraso máximo</b></td>
                                                            <td>$ {{ formatNumber(pago.atraso_max) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Saldo</b></td>
                                                            <td>$ {{ formatNumber(pago.saldo) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Moratorios</b></td>
                                                            <td>$ {{formatNumber(pago.moratorios)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Día de pago</b></td>
                                                            <td>{{ pago.dia_de_pago }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Saldo total</b></td>
                                                            <td>$ {{formatNumber(pago.total) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Fecha de último pago</b></td>
                                                            <td>{{ pago.fecha_pago_ultimo }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Importe de último pago</b></td>
                                                            <td>$ {{ formatNumber(pago.importe_pago_ultimo) }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </v-card>
                                            </v-container>
                                        </v-card>
                                    </v-col>
                                    <v-col>
                                        <br><br><br><br>

                                        <v-select
                                            class="pr-2"
                                            color="light-blue accent-2"
                                            :items="items"
                                            v-model="tit_aval"
                                            label="¿Titular o Aval?"
                                        ></v-select>

                                        <v-select
                                            class="pr-2"
                                            color="light-blue accent-2"
                                            :items="tipoGestion"
                                            v-model="id_tipo_gestion"
                                            label="Tipo de gestión"
                                        ></v-select>
                                        <br><br><br><br>
                                        <v-select
                                            class="pr-2"
                                            color="light-blue accent-2"
                                            :items="tipoGestionScl"
                                            v-model="id_tipo_gestion_ssl"
                                            label="Tipo de gestión scl"
                                        ></v-select>
                                        <v-select v-if="0===tit_aval"
                                                  class="pr-2"
                                                  color="light-blue accent-2"
                                                  :items="gestionado"
                                                  v-model="id_gestionado"
                                                  label="Seleccionar gestionado"
                                        ></v-select>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                        <br>
                        <template v-if="tieneConvenio !== null">
                            <template
                                v-if="tieneConvenio.status ==='CONVENIO ACTIVO' || tieneConvenio.status ==='CONVENIO PENDIENTE'">
                                <v-card>
                                    <v-container fluid>
                                        <v-row justify="center">
                                            <p>No se puede crear un convenio, el cliente cuenta con un convenio o pago
                                                pendiente</p>
                                        </v-row>
                                    </v-container>
                                </v-card>
                            </template>
                            <template v-else>
                                <v-card>
                                    <v-container fluid>
                                        <v-row justify="center">
                                            <v-switch inset color="blue" v-model="convenio"
                                                      label="¿Se llegó a convenio?"
                                            ></v-switch>
                                        </v-row>
                                    </v-container>
                                </v-card>
                                <br>
                                <v-card v-if="convenio">
                                    <v-card-text>
                                        <v-card dark color="light blue accent-2">
                                            Convenio
                                        </v-card>
                                        <v-row>
                                            <v-col>
                                                <br><br><br><br>
                                                <div class="title">Saldo del plan</div>
                                                <v-text-field
                                                    class="my-input"
                                                    v-model="deudaTotal"
                                                    :rules="pagoReglas"
                                                    hint="Por ejemplo, 150"
                                                    label="Ingrese el saldo del plan"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <br><br><br><br>
                                                <div class="title">Opciones de pago</div>
                                                <v-select
                                                    class="pr-2"
                                                    :items="itemOpcionPago"
                                                    v-model="opcionPago"

                                                    label="Opciones de pago"
                                                ></v-select>
                                            </v-col>

                                            <v-row>
                                                <v-col>
                                                    <br><br><br><br><br><br>
                                                    <div class="title">Pago inicial</div>
                                                    <v-text-field
                                                        class="my-input"
                                                        v-model="pagoInicial"
                                                        :rules="rules"
                                                        hint="Por ejemplo, 150"
                                                        label="Ingrese el pago inicial"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col>
                                                    <div class="title">Fecha de pago inicial</div>
                                                    <v-date-picker v-model="fechaInicial"
                                                                   :min="limiteFecha"
                                                                   color="light blue accent-2"
                                                                   :landscape="$vuetify.breakpoint.smAndUp"
                                                                   required
                                                                   full-width
                                                                   year-icon="calendar_today"
                                                                   prev-icon="skip_previous"
                                                                   next-icon="skip_next"
                                                                   locale="es"
                                                    ></v-date-picker>
                                                </v-col>
                                            </v-row>

                                        </v-row>
                                    </v-card-text>
                                </v-card>
                            </template>
                        </template>
                        <template v-else>
                            <v-card>
                                <v-container fluid>
                                    <v-row justify="center">
                                        <v-switch inset color="blue" v-model="convenio" label="¿Se llegó a convenio?"
                                        ></v-switch>
                                    </v-row>
                                </v-container>
                            </v-card>
                            <br>
                            <v-card v-if="convenio">
                                <v-card-text>
                                    <v-card dark color="light blue accent-2">
                                        Convenio
                                    </v-card>
                                    <v-row>
                                        <v-col>
                                            <br><br><br><br>
                                            <div class="title">Saldo del plan</div>
                                            <v-text-field
                                                class="my-input"
                                                v-model="deudaTotal"
                                                :rules="pagoReglas"
                                                hint="Por ejemplo, 150"
                                                label="Ingrese el saldo del plan"
                                                required
                                            ></v-text-field>
                                        </v-col>
                                        <v-col>
                                            <br><br><br><br>
                                            <div class="title">Opciones de pago</div>
                                            <v-select
                                                class="pr-2"
                                                :items="itemOpcionPago"
                                                v-model="opcionPago"

                                                label="Opciones de pago"
                                            ></v-select>
                                        </v-col>

                                        <v-row>
                                            <v-col>
                                                <br><br><br><br><br><br>
                                                <div class="title">Pago inicial</div>
                                                <v-text-field
                                                    class="my-input"
                                                    v-model="pagoInicial"
                                                    :rules="rules"
                                                    hint="Por ejemplo, 150"
                                                    label="Ingrese el pago inicial"
                                                    required
                                                ></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <div class="title">Fecha de pago inicial</div>
                                                <v-date-picker v-model="fechaInicial"
                                                               :min="limiteFecha"
                                                               color="light blue accent-2"
                                                               :landscape="$vuetify.breakpoint.smAndUp"
                                                               required
                                                               full-width
                                                               year-icon="calendar_today"
                                                               prev-icon="skip_previous"
                                                               next-icon="skip_next"
                                                               locale="es"
                                                ></v-date-picker>
                                            </v-col>
                                        </v-row>

                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </template>

                        <br>
                        <v-card outlined>
                            <v-card-text>
                                <v-card
                                    color="indigo"
                                    dark>
                                    Volver a contactar
                                </v-card>
                                <v-row>

                                    <v-col>
                                        <div class="title">Fecha</div>
                                        <v-date-picker v-model="fechaContactar"
                                                       color="indigo"
                                                       :min="limiteFecha"
                                                       :landscape="$vuetify.breakpoint.smAndUp"
                                                       required
                                                       full-width
                                                       year-icon="calendar_today"
                                                       prev-icon="skip_previous"
                                                       next-icon="skip_next"
                                                       locale="es"
                                        ></v-date-picker>
                                    </v-col>
                                    <v-col>
                                        <div class="title">Hora</div>
                                        <br>
                                        <v-time-picker
                                            v-model="horaContactar"
                                            :landscape="$vuetify.breakpoint.smAndUp"
                                            ampm-in-title
                                            required
                                            color="indigo"
                                            min="7:00"
                                            max="22:00"
                                        ></v-time-picker>
                                    </v-col>
                                </v-row>
                                <v-textarea
                                    color="indigo"
                                    outlined
                                    name="input-7-4"
                                    label="Comentario"
                                    v-model="comentario"
                                ></v-textarea>
                            </v-card-text>
                        </v-card>
                        <br>
                        <v-card>
                            <v-card v-if="valid" style="padding: 6px;"
                                    class="mx-auto text-center text-white"
                                    color="blue ">

                                <v-btn
                                    type="submit"
                                    :disabled="!valid"
                                    class="mx-auto text-center text-white"
                                    color="blue">
                                    Guardar
                                </v-btn>


                            </v-card>
                        </v-card>
                    </v-form>
                </v-container>
            </v-content>
        </v-app>
    </div>
</template>


<script>
    import swal from "sweetalert";
    import axios from "axios";

    export default {

        props: {
            id_c: String,

        },
        data: () => ({
            valid: true,

            tieneConvenio: '',

            id_tipo_gestion: 0,
            id_tipo_gestion_ssl: 0,
            id_gestionado: 0,

            id_cliente: '',
            cliente: '',
            pago: '',

            items: [
                {text: 'Titular', value: 1},
                {text: 'Aval', value: 0},
            ],
            extra: [],
            tipoGestion: [],
            tipoGestionScl: [],
            gestionado: [],
            tit_aval: 1,

            convenio: false,
            limiteFecha: null,
            fechaInicial: new Date().toISOString().substr(0, 10),
            pagoInicial: '',
            opcionPago: 0,
            deudaTotal: '',
            itemOpcionPago: [],

            fechaContactar: new Date().toISOString().substr(0, 10),
            horaContactar: new Date().toString().substr(16, 5),
            comentario: '',


            pagoReglas: [
                v => !!v || 'Necesita ingresar el pago del cliente',
                v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números',
            ],

            bandera_guardar: true,
        }),
        created() {
            axios.get(`/gestionConvenio/${this.id_c}`)
                .then(res => {
                    this.id_cliente = this.id_c;
                    this.cliente = res.data.cliente;
                    this.pago = res.data.pago;
                    this.tipoGestion = res.data.tipoGestion;
                    this.tipoGestionScl = res.data.tipoGestionSsl;
                    this.gestionado = res.data.gestionado;
                    this.itemOpcionPago = res.data.itemOpcionPago;
                    console.log(res.data);
                });

            axios.get(`/tieneConvenioActivo/${this.id_c}`)
                .then(res => {
                    this.tieneConvenio = res.data.convenio;
                });

            var d = new Date();
            var mm = d.getMonth() + 1;
            var dd = d.getDate();
            if (dd < 10) {
                dd = '0' + dd;
            }

            if (mm < 10) {
                mm = '0' + mm;
            }

            var yy = d.getFullYear();
            this.limiteFecha = yy + '-' + mm + '-' + dd;

        },
        computed: {
            rules() {
                const rules = [];
                const rule3 = v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números';
                const rule2 = v => !!v || 'Necesita ingresar el pago del cliente';

                rules.push(rule3);
                rules.push(rule2);
                return rules;
            }
        },
        methods: {
            reglaSelector() {
                if (parseInt(this.deudaTotal) > parseInt(this.pago.total)) {
                    swal({
                        title: 'Corregir el pago',
                        text: "El saldo del plan no puede ser mayor al saldo total del cliente",
                        icon: "info",
                        button: "Entendido",
                    });
                    this.valid = false;
                }
                if (this.opcionPago !== 0) {
                    if (parseInt(this.pagoInicial) >= parseInt(this.deudaTotal)) {
                        swal({
                            title: 'Corregir el pago',
                            text: "El pago inicial debe ser menor al saldo del plan, dado que la opción de pagos es a parcialidades",
                            icon: "info",
                            button: "Entendido",
                        });
                        this.valid = false;
                    }
                } else {

                    if (parseInt(this.pagoInicial) !== parseInt(this.deudaTotal)) {

                        swal({
                            title: 'Corregir el pago',
                            text: "El pago inicial debe ser igual al saldo del plan, dado que es una liquidación",
                            icon: "info",
                            button: "Entendido",
                        });
                        this.valid = false;
                    }
                }
            },

            formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            },
            store() {
                if (this.convenio) {
                    this.reglaSelector();
                } else {
                    this.valid = true;
                }

                if (this.horaContactar < "07:00" || this.horaContactar > "22:00") {
                    swal({
                        title: 'Corregir la hora',
                        text: "La hora permitida es de 07:00 a 22:00",
                        icon: "info",
                        button: "Entendido",
                    });
                    this.valid = false;
                }

                if (this.valid) {

                    if (this.bandera_guardar) {
                        axios.post('/gestion', {
                            id_cliente: this.id_cliente,
                            tit_aval: this.tit_aval,
                            id_gestionado: this.id_gestionado,
                            id_tipo_gestion_ssl: this.id_tipo_gestion_ssl,
                            id_tipo_gestion: this.id_tipo_gestion,
                            fechaContactar: this.fechaContactar,
                            convenio: this.convenio,
                            pagoInicial: this.pagoInicial,
                            fechaInicial: this.fechaInicial,
                            horaContactar: this.horaContactar,
                            deudaTotal: this.deudaTotal,
                            opcionPago: this.opcionPago,
                            comentario: this.comentario
                        }).then(res => {

                            swal({
                                title: 'Guardado',
                                text: "Se guardaron los datos de manera correcta",
                                icon: "success",
                                allowOutsideClick: false,
                                closeOnClickOutside: false,
                                buttons: {
                                    confirm: 'Entendido',
                                },

                            })
                                .then((loading) => {
                                    if (loading) {
                                        window.location.href = '/gestion/' + this.id_cliente;
                                    }
                                });

                            console.log(res.data);

                        });

                        this.bandera_guardar = false
                    }
                }
                this.valid = true;
            }
        }
    }
</script>

<style scoped>

</style>
