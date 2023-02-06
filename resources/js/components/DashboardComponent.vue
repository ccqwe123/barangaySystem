<template>
    <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Residents</span>
                <span class="info-box-number">
                  {{total_res}}
                </span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Barangay</span>
                <span class="info-box-number">{{total_bar}}</span>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Blotter</span>
                <span class="info-box-number">0</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number">{{total_users}}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <v-select :options="bbarangay_id" placeholder="select" v-model='barangay_id' label="barangay_name" @input="setSelected"></v-select>
                    </div>
                  <div class="card-body">
                    <canvas id="myChart"></canvas>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                  <div class="card-body py-5">
                    <canvas id="barangayChart"></canvas>
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style type="text/css">
    .bg-info {
  background-color: #17a2b8!important;
}
.bg-warning {
    background-color: #ffc107!important;
}
</style>
<script>
    export default {
        data () {
            return {
                bbarangay_id: [],
                storevalue: [],
                brgyName: [],
                charData: [],
                barangay_id: [],
                set_brgy_chart: [],
                total_res: '',
                total_bar: '',
                total_users: '',
            }

        },
        methods: {
            getBarnagayChart() {

                     axios.get('/api/fetch/getbarangayinfo').then(function(response){
                        for (var i = 0; i < response.data.array_count.length; ++i) {
                            // this.storevalue.push([response.data.array_count[i][0]]);
                            if(response.data.array_count[i][0] === response.data.array_count[0][0])
                            {
                                this.storevalue += response.data.array_count[i][0];
                            }else{
                                this.storevalue += ","+response.data.array_count[i][0];
                            }
                        }
                        for (var i = 0; i < response.data.array_name.length; ++i) {
                            // this.storevalue.push([response.data.array_count[i][0]]);
                            var resp = response.data.array_name[i];
                            this.brgyName.push(
                                response.data.array_name[i]
                            );
                        }
                        // console.log(JSON.stringify(this.brgyName));
                        var ctxx = document.getElementById('barangayChart').getContext('2d');
                            var myPieChart = new Chart(ctxx, {
                                type: 'doughnut',
                                data: {
                                    datasets: [{
                                    data: this.storevalue,        
                                     backgroundColor:  [
                                     "rgba(0, 255, 0, 0.2)",
                                     "rgba(255, 159, 64, 0.2)",
                                     "rgba(0, 0, 255, 0.2)",
                                     "rgba(255, 0, 0, 0.2)",
                                     "rgba(54, 162, 235, 0.2)",
                                     "rgba(153, 102, 255, 0.2)",
                                     "rgba(201, 203, 207, 0.2)"],
                                    borderColor:  [
                                    "rgba(0, 255, 0, 1)",
                                    "rgba(255, 159, 64, 1)",
                                    "rgba(0, 0, 255, 1)",
                                    "rgba(255, 0, 0, 1)",
                                    "rgba(54, 162, 235, 1)",
                                    "rgba(153, 102, 255, 1)",
                                    "rgba(201, 203, 207, 1)"]
                                }],
                                labels: this.brgyName,   
                                // labels: ['Male Literate','Female Literate','Male Illiterate','Female Illiterate' ],   
                               
                                },
                                options: {
                                    tooltips: {
                                        mode: 'index',
                                        intersect: false
                                    },
                                    legend: {
                                        position: 'bottom'
                                    },
                                }

                            });
                    }.bind(this));
                     // console.log(this.charData);
                     
            },
            setSelected(value)
            {
                try {
                    axios.get('/api/dashboard', {
                    params: {
                        barangay_id: this.barangay_id.id
                    }
                    }).then(function(response){            
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['0-20','21-40','41-60','61-80','80-100','100+'],
                                datasets: [
                                {
                                    data:[response.data.populationMale20,response.data.populationMale40,response.data.populationMale60,response.data.populationMale80,response.data.populationMale100,response.data.populationMale120],
                                    label:'male',
                                    backgroundColor:"rgba(0,128,255, 1)",
                                },
                                {
                                    data:[response.data.populationFemale20,response.data.populationFemale40,response.data.populationFemale60,response.data.populationFemale80,response.data.populationFemale100,response.data.populationFemale120],
                                    label:'female',         
                                    backgroundColor:"rgba(255, 99, 132, 1)",
                                }
                                
                                ],

                            },

                            options: {
                                plugins: {
                                  datalabels: {
                                     display: true,
                                     align: 'center',
                                     anchor: 'center'
                                  }
                               },
                               scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                },

                                tooltips: {
                                    mode: 'index',
                                    intersect: false
                                },
                                legend: {
                                    position: 'bottom'
                                },
                                showTooltips: false,
                            }

                        });
                    
                }.bind(this));
            } catch (error) {
                axios.get('/api/dashboard').then(function(response){            
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['0-20','21-40','41-60','61-80','80-100','100+'],
                                datasets: [
                                {
                                    data:[response.data.populationMale20,response.data.populationMale40,response.data.populationMale60,response.data.populationMale80,response.data.populationMale100,response.data.populationMale120],
                                    label:'male',
                                    backgroundColor:"rgba(0,128,255, 1)",
                                },
                                {
                                    data:[response.data.populationFemale20,response.data.populationFemale40,response.data.populationFemale60,response.data.populationFemale80,response.data.populationFemale100,response.data.populationFemale120],
                                    label:'female',         
                                    backgroundColor:"rgba(255, 99, 132, 1)",
                                }
                                
                                ],

                            },

                            options: {
                                plugins: {
                                  datalabels: {
                                     display: true,
                                     align: 'center',
                                     anchor: 'center'
                                  }
                               },
                               scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                },

                                tooltips: {
                                    mode: 'index',
                                    intersect: false
                                },
                                legend: {
                                    position: 'bottom'
                                },
                                showTooltips: false,
                            }

                        });
                    
                }.bind(this));
            }
               
            },
            fetchBarangay() {
                   axios.get('/api/fetch/barangay').then(function(response){
                    this.bbarangay_id = response.data;
                }.bind(this));
            },
            fetcResientCount() {
                axios.get('/api/dashboard').then(function(response){
                    this.total_res = response.data.total_res;
                    this.total_bar = response.data.total_bar;
                    this.total_users = response.data.total_users;
                }.bind(this));
            },
            
        },
        mounted() {
            this.setSelected();
            this.getBarnagayChart();
        },
        created(){
            this.fetcResientCount();
            this.fetchBarangay();
            $('.modal').modal('hide');
            console.clear();
        }
    }
</script>
