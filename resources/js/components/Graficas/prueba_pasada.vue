<template>
    <div id="app">

        <v-app>


            <v-card>
                <v-system-bar color="blue darken-2"></v-system-bar>
                <v-app-bar dark color="blue">
                    <v-toolbar-title>Gráfica general del despacho</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                </v-app-bar>
            </v-card>

            <v-card>
                <v-row>
                    <v-col cols="4">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-app-bar dark color="blue">
                                <v-toolbar-title>Información:</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                            </v-app-bar>
                            <v-container class="pa-2" fluid>
                                <v-list flat>
                                    <v-list-item-group color="primary">
                                        <v-list-item>

                                            <v-btn color="#673AB7" dark>
                                                <i class="material-icons">star_border</i>
                                            </v-btn>

                                            <v-list-item-content>
                                                <v-list-item-title>Meta: {{meta}}</v-list-item-title>
                                            </v-list-item-content>

                                        </v-list-item>
                                        <v-list-item>
                                            <v-btn color="#8BC34A" dark>
                                                <i class="material-icons">trending_up</i>
                                            </v-btn>
                                            <v-list-item-content>
                                                <v-list-item-title>Real: {{acumulado}}</v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-item-group>
                                </v-list>
                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col cols="4">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-app-bar dark color="blue">
                                <v-toolbar-title>Falta:</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                            </v-app-bar>
                            <v-container class="pa-2" fluid>
                                <apexchart type=radialBar height=280 width=315 :options="chartOptions2"
                                           :series="series3"/>
                            </v-container>
                        </v-card>
                    </v-col>
                    <v-col cols="4">
                        <v-card>
                            <v-system-bar color="blue darken-2"></v-system-bar>
                            <v-app-bar dark color="blue">
                                <v-toolbar-title>Se tiene:</v-toolbar-title>
                                <div class="flex-grow-1"></div>
                            </v-app-bar>
                            <v-container class="pa-2" fluid>
                                <apexchart type=radialBar height=280 width=315 :options="chartOptions2"
                                           :series="series2"/>
                            </v-container>
                        </v-card>
                    </v-col>
                </v-row>

                <v-col>
                    <v-card>
                        <v-system-bar color="blue darken-2"></v-system-bar>
                        <v-app-bar dark color="blue">
                            <v-toolbar-title></v-toolbar-title>
                            <div class="flex-grow-1"></div>
                        </v-app-bar>
                        <v-container class="pa-2" fluid>
                            <apexchart type=bar height=400 :options="chartOptions" :series="series"/>
                        </v-container>
                    </v-card>
                </v-col>
            </v-card>


        </v-app>
    </div>
</template>

<script>
    export default {
        props: {
            meta: 0,
            acumulado: 0,
        },
        data: function () {
            return {
                meta1: 0,
                acumulado1: 0,
                series3: [],
                series2: [],
                chartOptions2: {
                    colors: ['#2196F3'],
                    chart: {
                        offsetY: -10,
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 135,
                            dataLabels: {
                                value: {
                                    offsetY: 0,
                                    fontSize: '40px',
                                    color: '#2196F3',
                                    formatter: function (val) {
                                        return val + "%";
                                    }
                                }
                            }
                        }
                    },

                    stroke: {
                        dashArray: 2
                    },
                    labels: ['']
                },


                recargar: 0,
                porcentaje: 0,
                series: [],
                chartOptions: {
                    colors: ['#A9A9A9', '#A9A9A9'],
                    title: {
                        text: 'Gráfica general del despacho',
                        align: 'left',
                        margin: 10,
                        offsetX: 0,
                        offsetY: 0,
                        floating: false,
                        style: {
                            fontSize: '15px',
                            color: '#263238'
                        },
                    },
                    plotOptions: {
                        bar: {
                            endingShape: 'flat',
                            barHeight: '100%',
                            horizontal: true,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    stroke: {
                        width: 7,
                        curve: 'smooth'
                    },
                    xaxis: {
                        categories: ['Meta', 'Real']
                    },
                }

            }
        },

        mounted: function () {
            this.$nextTick(function () {
                this.acumulado1 = this.acumulado;
                this.meta1 = this.meta;
                this.hola();
                this.series =
                    [
                        {name: 'Meta', data: [this.meta1]},
                        {name: 'Real', data: [this.acumulado1]}
                    ];
            })
        },
        methods: {
            hola() {
                this.porcentaje = [(this.acumulado1 * 100) / this.meta1];
                this.series2 = [parseFloat(this.porcentaje).toFixed()];
                this.series3 = [100 - (parseFloat(this.porcentaje).toFixed())];
                if (this.porcentaje <= 65) {
                    this.chartOptions = {
                        colors: ['#673AB7', '#F44336']
                    }
                } else if (this.porcentaje > 65 && this.porcentaje <= 80) {
                    this.chartOptions = {
                        colors: ['#673AB7', '#FF9800']
                    }
                } else if (this.porcentaje > 80 && this.porcentaje <= 100) {
                    this.chartOptions = {
                        colors: ['#673AB7', '#8BC34A']
                    }
                } else if (this.porcentaje > 100) {
                    this.chartOptions = {
                        colors: ['#673AB7', '#03A9F4']
                    }
                }
            },
        }
    };
</script>

<style>
    #app {
        font-family: "Avenir", Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;

        margin-top: 60px;
    }

    .chart {
        width: 95%;
    }
</style>



