<template>
    <div id="app">

        <v-dialog
            v-model="dialog"
            max-width="30%">
            <v-form @submit.prevent="update" v-model="valid">
                <v-card>
                    <v-card-title class="headline">Editar teléfono del aval</v-card-title>

                    <v-col>
                        <div v-for="(item,index) in telefono">
                            <v-text-field
                                label="Teléfono"
                                v-model="item.numero_tel"
                                :rules="reglaTelefono"
                                :counter="10"
                                outlined
                            ></v-text-field>
                        </div>

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
                            color="orange">
                            Editar teléfono del aval
                        </v-btn>
                    </v-card-actions>
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
                telefono: '',

                valid: true,
                dialog: false,

                reglaTelefono: [v => v.length > 6 || 'Como minimo son 7 números',v => /^[0-9]+$/.test(v) || 'Solo puede ingresar números'],
            }
        },
        created() {
            EventBus.$on('telefono', data => {
                this.telefono = data;
            });
            EventBus.$on('dialogTelefono', data => {
                this.dialog = data;
            });
            EventBus.$on('id_cliente', data => {
                this.id_cliente = data;
            });
        },
        methods: {
            update() {
                axios.put(`/editarTelefono`, this.telefono[0])
                    .then(res => {
                        console.log(res.data);
                    });
                swal({
                    title: "¡Editado!",
                    text: "Teléfono del aval editado",
                    icon: "success",
                    button: "Esta bien",
                });
            },
        },

    }
</script>

<style scoped>

</style>
