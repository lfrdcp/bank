<template>
    <div id="app">
        <spinner-component v-if="loading"></spinner-component>
        <v-app>
            <v-card style="padding: 6px; margin: 6px;" class="text-white" color="blue darken-2">
                Puedes dar clic a los encabezados para acender o descender el orden
            </v-card>

            <v-data-table
                :headers="headers"
                :items="convenios"
                :items-per-page="15"
                :footer-props="flechas"
                class="elevation-1"
            >
                <template v-slot:item.estado="{ item }">
                    <v-chip :color="getColor(item.estado)" dark>{{ item.estado }}</v-chip>
                </template>
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
                convenios: [],
                headers: [
                    {text: 'Folio', value: 'folioGen'},
                    {text: 'Estado', value: 'estado'},
                    {text: 'Número de pagos', value: 'numero_pagos'},
                    {text: 'Pago inicial', value: 'primer_pago_cantidad'},
                    {text: 'Saldo del plan', value: 'deuda_total_original'},
                    {text: 'Por pagar', value: 'deuda_total'},
                ],
                flechas: {
                    showFirstLastPage: true,
                    prevIcon: 'undo',
                    nextIcon: 'redo'
                },
            }
        },
        created() {
            axios.get(`/gestion-convenio/${this.id_c}`)
                .then(res => {
                    this.loading = false;
                    this.convenios = res.data;
                })
        },
        methods: {
            getColor(estado) {
                if (estado === "Cancelado") {
                    return 'red darken-1';
                } else if (estado === "Pendiente") {
                    return 'orange lighten-2';
                } else {
                    return 'green';
                }
            },

        },
    }
</script>
