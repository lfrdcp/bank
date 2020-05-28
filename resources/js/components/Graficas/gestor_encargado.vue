<template>
    <div id="app">
        <v-app>
            <v-card>
                <v-system-bar color="blue darken-2"></v-system-bar>
                <v-app-bar dark color="blue">
                    <v-toolbar-title>Gráfica de avance gestor por encargado</v-toolbar-title>
                    <div class="flex-grow-1"></div>
                </v-app-bar>
            </v-card>


                    <v-card>
                        <v-system-bar color="blue darken-2"></v-system-bar>
                        <v-container class="pa-2" fluid>

                            <apexchart :type=grafica :options="chartOptions" :series="series"/>

                        </v-container>
                    </v-card>



        </v-app>
    </div>
</template>

<script>
    export default {

        data: function () {
            return {
                grafica: 'bar',
                recargar: 0,
                series: [],
                chartOptions: {
                    stroke: {
                        width: 1,
                        colors: ['#fff']
                    },
                    legend: {
                        position: 'top'
                    },
                    colors: ['#673AB7', '#2196F3'],
                    chart: {
                        stacked: true,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                        },

                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {
                        categories: [],
                    },
                    title: {
                        text: 'Gráfica de avance por encargado'
                    },
                    yaxis: {
                        title: {
                            text: '$ (Pesos)'
                        }
                    },
                    fill: {
                        opacity: .8
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "$ " + val + " Pesos"
                            }
                        }
                    },
                },

            }
        },

        mounted: function () {
            this.$nextTick(function () {
                axios.get(`/grafica_encargado_index`).then(res => {
                    this.series = res.data[0];
                    this.chartOptions = {
                        xaxis: res.data[1]
                    }
                });
                this.updateChart();
            })
        },
        methods: {
            updateChart() {
                setInterval(function () {
                    axios.get(`/grafica_encargado_index`).then(res => {
                        this.series = res.data[0];
                        this.chartOptions = {
                            xaxis: res.data[1]
                        }
                    });
                    this.recargar++;
                    console.log(this.recargar);
                }.bind(this), 60000);
            },
        }
    };
</script>
