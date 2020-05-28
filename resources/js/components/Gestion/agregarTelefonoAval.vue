<template>
    <div id="app">

        <v-dialog
            v-model="dialog"
            max-width="30%">
            <v-form @submit.prevent="update" v-model="valid">
                <v-card>

                    <v-toolbar color="primary" dark>
                        <v-toolbar-title>Agregar teléfono al aval</v-toolbar-title>
                        <div class="flex-grow-1"></div>
                        <v-icon>contact_phone</v-icon>
                    </v-toolbar>


                    <v-container>
                        <v-col>
                            <v-text-field
                                label="Teléfono"
                                v-model="telefono"
                                :rules="reglaTelefono"
                                :counter="10"
                                outlined
                            ></v-text-field>
                        </v-col>

                        <v-card-actions>
                            <div class="flex-grow-1"></div>

                            <v-btn
                                color="red"
                                class="mx-auto text-center text-white"
                                @click="dialog = false"
                            >
                                Cerrar
                            </v-btn>

                            <v-btn
                                @click="dialog = false"
                                type="submit"
                                :disabled="!valid"
                                class="mx-auto text-center text-white"
                                color="blue">
                                Agregar teléfono al aval
                            </v-btn>
                        </v-card-actions>
                    </v-container>
                </v-card>
            </v-form>
        </v-dialog>
    </div>

</template>

<script>
    import EventBus from '../../EventBus';

    export default {

        data() {
            return {
                id_cliente: '',
                aval: '',
                telefono: '',
                valid: true,
                dialog: false,

                reglaTelefono: [v => v.length > 6 || 'Como minimo son 7 números', v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
            }
        },
        created() {
            EventBus.$on('aval', data => {
                this.aval = data;
                console.log(this.aval);
            });
            EventBus.$on('dialogAgregarTelefono', data => {
                this.dialog = data;
            });
            EventBus.$on('id_cliente', data => {
                this.id_cliente = data;
            });
        },
        methods: {
            update() {
                axios.post('/agregarTelefono', {
                    id_aval: this.aval.id_aval,
                    numero_tel: this.telefono,
                })
                    .then(res => {
                        console.log(res.data)
                    });
                swal({
                    title: "¡Guardado!",
                    text: "Teléfono del aval agregado",
                    icon: "success",
                    button: "Entendido",
                });
                location.reload();
            },
        },

    }
</script>

<style scoped>

</style>
