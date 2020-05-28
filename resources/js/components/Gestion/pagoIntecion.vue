<template>

    <div id="app">
        <v-app id="inspire">
            <v-content>
                <v-container>

                    <v-layout>
                        <v-flex>
                            <v-sheet>
                                <v-form @submit.prevent="store" v-model="valid">
                                    <v-card dark color="blue">
                                        <v-row>
                                            <v-col>{{id_c}}</v-col>
                                            <v-col> {{nombre}}</v-col>
                                            <v-col>${{formatNumber(total)}}</v-col>
                                        </v-row>
                                    </v-card>
                                    <br>
                                    <v-card outlined>
                                        <v-card-text>
                                            <v-card dark color="pink lighten-1">
                                                Pago
                                            </v-card>
                                            <v-row>
                                                <v-col>
                                                    <div class="title">Fecha</div>
                                                    <v-date-picker v-model="fecha"
                                                                   :min="limiteFecha"
                                                                   color="pink lighten-1"
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
                                                    <br><br><br><br><br><br>
                                                    <div class="title">Cantidad</div>
                                                    <v-text-field
                                                        color="pink lighten-1"
                                                        class="my-input"
                                                        v-model="pago"
                                                        :rules="pagoReglas"
                                                        hint="Por ejemplo, 150"
                                                        label="Ingrese cantidad a pagar"
                                                        required
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>

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
                                                <v-col>
                                                    <div class="title">Fecha</div>
                                                    <v-date-picker v-model="fechaContactar"
                                                                   :min="limiteFecha"
                                                                   color="indigo"
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
                                            <v-textarea
                                                color="indigo"
                                                outlined
                                                name="input-7-4"
                                                label="comentario"
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
        props: {
            id_c: String,
        },
        data: () => ({
            menu: false,
            valid: true,

            nombre: '',
            total: '',
            limiteFecha: null,
            fecha: new Date().toISOString().substr(0, 10),
            pago: '',

            horaContactar: new Date().toString().substr(16, 5),
            fechaContactar: new Date().toISOString().substr(0, 10),
            comentario: '',

            pagoReglas: [
                v => !!v || 'Necesita ingresar el pago del cliente',
                v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números',
            ],
        }),
        created() {
            var d = new Date();
            var mm = d.getMonth() + 1;
            var dd = d.getDate();
            if (dd < 10) {
                dd = '0' + dd;
            }
            var yy = d.getFullYear();
            this.limiteFecha = yy + '-' + mm + '-' + dd;

            axios.get(`/gestionPagoIntencion/${this.id_c}`).then(res => {
                this.nombre = res.data.nombre;
                this.total = res.data.total;
            });
        },
        methods: {
            formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
            },
            reglaPago() {


                if (parseInt(this.pago) > parseInt(this.total)) {
                    swal({
                        title: 'Corregir el pago',
                        text: "El pago debe ser menor o igual al saldo total",
                        icon: "info",
                        button: "Entendido",
                    });
                    return false;
                }
                return true;
            },

            reglaHora() {
                if (this.horaContactar < "07:00" || this.horaContactar > "22:00") {
                    swal({
                        title: 'Corregir la hora',
                        text: "La hora permitida es de 07:00 a 22:00",
                        icon: "info",
                        button: "Entendido",
                    });
                    return false;
                }
                return true;
            },
            store() {
                if (this.reglaPago() && this.reglaHora()) {


                    axios.post(`/gestionPagoIntencion`, {

                        id_cliente: this.id_c,
                        fecha: this.fecha,
                        pago: this.pago,

                        horaContactar: this.horaContactar,
                        fechaContactar: this.fechaContactar,
                        comentario: this.comentario

                    }).then(res => {
                        console.log(res.data);
                        swal({
                            title: res.data,
                            text: "Se guardo el pago de manera exitosa",
                            icon: "success",
                            button: "Entendido",
                        });
                    });
                }

            }
        }
    }
</script>
