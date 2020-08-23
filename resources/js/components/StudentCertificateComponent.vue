<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Student Certificate List</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-success" @click="openAddModal">
                          <i class="fas fa-plus"></i> Add New</button>
                      </div>
                    </div>

                    <div class="card-body p-0 table-responsive" style="display: block;" v-if="!loadingData">
                      <table class="table table-striped projects">
                          <thead>
                              <tr>
                                  <th>
                                      Date
                                  </th>
                                  <th>
                                      Student Name
                                  </th>
                                  <th>
                                      School
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="clearance in clearances.data" :key="clearance.id">
                                  <td>
                                    {{ clearance.created_at | moment("MMMM D, YYYY") }}
                                  </td>
                                  <td>
                                    {{ clearance.student_name }}
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                            {{ clearance.school_name }}
                                          </li>
                                      </ul>
                                  </td>
                                  <td class="project-actions text-center" style="text-align: center; justify-content: center; align-items: center; min-width: 170px !important;">

                                      <button class="btn btn-success" alt="Print" title="Print Data" @click="printClearance(clearance)">
                                          <i class="fas fa-print"></i>
                                          </i>
                                      </button>
                                      <button class="btn btn-primary" alt="Edit" title="Edit Data" @click="editClearance(clearance)">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" alt="Delete" title="Delete Data" @click="deleteClearance(clearance.id)">
                                          <i class="fas fa-trash">
                                          </i>
                                      </button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                    <div class="card-body p-3" style="display: block;" v-else>
                      <div class="text-center">
                        <div class="spinner-border" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <pagination :data="clearances" @pagination-change-page="getResults" class="float-right"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <form @submit.prevent="editMode ? updateClearance() : createClearance()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">School Name</label>
                                    <input v-model="form.id" type="hidden" class="form-control" name="ba_id" id="barangay_id">
                                    <input v-model="form.school_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('school_name') }" id="name" placeholder="School Name" autocomplete="off">
                                    <has-error :form="form" field="school_name"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input v-model="form.date" type="date" class="form-control" :class="{ 'is-invalid': form.errors.has('date') }" id="date" placeholder="Location" autocomplete="off">
                                    <has-error :form="form" field="date"></has-error>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <label for="name">Student Name</label>
                                <v-select :options="residents" placeholder="select" v-model="res.id" label="full_name" :clearable="false" @input="setSelected"></v-select>
                                <input class="form-control" hidden :class="{ 'is-invalid': form.errors.has('requestor_resident_id') }" type="text" v-model="form.requestor_resident_id">
                                <has-error :form="form" field="requestor_resident_id"></has-error>
                            </div>
                        </div>  
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="name">Purpose</label>
                                <input type="text" name="purpose" class="form-control" :class="{ 'is-invalid': form.errors.has('purpose') }" v-model="form.purpose">
                                <has-error :form="form" field="purpose"></has-error>
                            </div>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="modalclose" data-dismiss="modal">Close</button>
                        <button :disabled="form.busy" type="submit" class="btn btn-primary btnSubmit">Create</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!--- Print Data ---->
        <div id="printMe" hidden>
            <div class="header">
            <div class="barangay_logo_container">
                <img src="images/certificate/default_logo1.png" :src="getBrgyLogo1()" alt="Manila Logo" class="barangay_logo" />
            </div>
            <div class="header_text">
                Republic of the Philippines
                <br />
                <span v-for="variable in variables">
                    <span v-if="variable.name == 'barangay_name' && variable.value != ''"><b>{{variable.value}}</b></span>
                     <span v-if="variable.name == 'barangay_city' && variable.value != ''"><br>{{variable.value}}</span>
                     <span v-if="variable.name == 'barnagay_zone' && variable.value != ''"><br>{{variable.value}}</span>
                     <span v-if="variable.name == 'barangay_address' && variable.value != ''"><br>{{variable.value}}</span>
                     <span v-if="variable.name == 'barangay_contact' && variable.value != ''"><br>Telephone No: {{variable.value}}</span>
                </span>
                <br />
                <h2 class="office_of_the_punong_barangay_header">
                    <b>OFFICE OF THE PUNONG BARANGAY</b>
                </h2>
            </div>
            <div class="manila_logo_container">
                <img src="images/certificate/default_logo2.png" :src="getBrgyLogo2()" alt="Manila Logo" class="manila_logo" />
            </div>
        </div>

        <div class="cus_header_line_1"></div>
        <div class="cus_header_line_2"></div>


      <br/>
      _________________________<br/>
      _________________________<br/>
      _________________________<br/>
    

      <div class="to_whom_it_may_concern_text">

        <b>Dear Sir/Madam :</b>
      </div>

      <br />

        <br />
        
        <!-- Business Clearance Content -->
        <div class="business_clearance_content">
            <div class="paragraph_container">
              <div class="paragraph">
                This is to certify that the following students enrolled at <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{form.student_name}}</span> have conducted  <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{form.purpose}}</span> in our Barangay on  <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{form.date}}</span>
              </div>

              <br />

              <div class="paragraph" style="white-space: pre-line;margin-left: 150px;">
                

              </div>

              <br />

              <div class="paragraph">
                This Certification is being issued upon the request of the above named students in compliance of their school requirement/s
              </div>

              <br />

              <div class="paragraph" style=" text-align: justify;  text-justify: inter-word;">
                    Issued this <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.date_created | moment("Do")}} </span><span style="border-bottom: 1px solid black;"> day of </span><span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.date_created | moment("MMMM, YYYY")}}</span>
                </div>
            </div>

        </div>

        <br />
        <br />
        <br />

        <!-- Seal and Signature -->
        <div class="seal_and_signature">
          <div class="seal">
            <h6 style="margin: 0px;">
              Attested:
              <br />
              <br />
              ______________________________
              <br />
              <center>Kagawad</center>
              <br />
              <!-- <b>(Not valid without Barangay Seal)</b> -->
            </h6>
          </div>

          <div class="signature">
            <b style="text-transform:uppercase">{{this.brgy_captain}}</b>
            <br />
            PUNONG BARANGAY
          </div>
        </div>
        
        <br />
        <br />

        <!-- Footer -->
        <div class="cus_header_line_1"></div>

        <div class="footer">
            <div class="footer_punong_barangay">
                <b style="text-transform:uppercase">{{this.brgy_captain}}</b>
                <br />
                PUNONG BARANGAY
            </div>

            <div class="kagawad_text">KAGAWAD</div>
            
            <div class="kagawad_container_1">
                <div v-if="this.kagawad1 != ''">
                    <b>{{this.kagawad1}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.kagawad2 != ''">
                    <b>{{this.kagawad2}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.kagawad3 != ''">
                    <b>{{this.kagawad3}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.kagawad4 != ''">
                    <b>{{this.kagawad4}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.kagawad5 != ''">
                    <b>{{this.kagawad5}}</b>
                    <br />
                    Brgy. Kagawad
                </div>
            </div>

            <div class="kagawad_container_2">
                <div v-if="this.kagawad6 != ''">
                    <b>{{this.kagawad6}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.kagawad7 != ''">
                    <b>{{this.kagawad7}}</b>
                    <br />
                    Brgy. Kagawad
                </div>

                <div v-if="this.brgy_treasurer != ''">
                    <b>{{this.brgy_treasurer}}</b>
                    <br />
                    TREASURER
                </div>

                <div v-if="this.brgy_secretary != ''">
                    <b>{{this.brgy_secretary}}</b>
                    <br />
                    SECRETARY
                </div>
            </div>
        </div>
        </div>
    </div>
</template>
<style type="text/css">
    .removeDept, a label
    {
        text-decoration: none;
        color:red;
        margin-left:10px;
        cursor: pointer !important;
    }
    .removeDept:hover
    {
        color:darkred;
    }
    
</style>
<script>
    export default {
        data () {
            return {
              loadingData : true, //true
              editMode : false,
              clearances: {},
              residents: [],
              variables: [],
              kagawads: [],
              brgy_captain: '',
              brgy_secretary: '',
              brgy_treasurer: '',
              kagawad1: '',
              kagawad2: '',
              kagawad3: '',
              kagawad4: '',
              kagawad5: '',
              kagawad6: '',
              kagawad7: '',
              date_created: '',
              res:{
                    id: '',
                },
              form: new Form({
                id: '',
                school_name: '',
                student_name: '',
                barangay_logo1: '',
                barangay_logo2: '',
                date: '',
                name: '',
                purpose: '',
                requestor_resident_id: '',
                busy: false,
              })
            }
          },
          methods: {
            getResults(page = 1) {
              axios.get('api/student_certificate?page=' + page)
                .then(response => {
                  this.clearances = response.data;
                });
            },
            populateBrgyLogo() {
                let vm = this.form;
                axios.get('/api/system/getlogo').then(function(response){
                    vm.barangay_logo1 = response.data[0].barangay_logo1;
                    vm.barangay_logo2 = response.data[0].barangay_logo2;
                    // console.log(response.data[0].barangay_logo1);
                }.bind(this));
            },
             getBrgyLogo1() {
                let photo1 = (this.form.barangay_logo1 > 1200) ? this.form.barangay_logo1 : "images/certificate/"+ this.form.barangay_logo1 ;
                return photo1;
            },
            getBrgyLogo2() {
                let photo2 = (this.form.barangay_logo2 > 1200) ? this.form.barangay_logo2 : "images/certificate/"+ this.form.barangay_logo2 ;
                return photo2;
            },
            openAddModal(){
                this.form.reset();
                this.res.id = '';
                this.form.requestor_resident_id = '';
                this.editMode = false;
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New Student Certificate');
                $('#addModal').modal('show');
            },
            createClearance() {
            this.form.post('/api/student_certificate')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'New Student Certificate successfully Added'
                    })
                    $("#modalclose").trigger("click");
                    this.populateClearance();
                })
                .catch(error => {
                });
            },
            printClearance(clearance){
              this.populateBrgyLogo();
                this.res.id = '';
                this.form.requestor_resident_id = '';
                
                this.form.reset();
                this.form.fill(clearance);
              axios.all([
                  axios.get('/api/fetch/variable/path'),
                  axios.get('/api/fetch/barangay/officials')
                ])
                .then(axios.spread((response, dep2) => {
                    this.variables = response.data;
                    this.brgy_captain = dep2.data.captain.name;
                    this.brgy_secretary = dep2.data.secretary.name;
                    this.brgy_treasurer = dep2.data.treasurer.name;
                    if(dep2.data.kagawad[0].name != undefined)
                    {
                        this.kagawad1 = dep2.data.kagawad[0].name;
                    } 
                    if(dep2.data.kagawad[1].name != undefined)
                    {
                        this.kagawad2 = dep2.data.kagawad[1].name;
                    }
                    if(dep2.data.kagawad[2].name != undefined)
                    {
                        this.kagawad3 = dep2.data.kagawad[2].name;
                    }
                    if(dep2.data.kagawad[3].name != undefined)
                    {
                        this.kagawad4 = dep2.data.kagawad[3].name;
                    }
                    if(dep2.data.kagawad[4].name != undefined)
                    {
                        this.kagawad5 = dep2.data.kagawad[4].name;
                    }
                    if(dep2.data.kagawad[5].name != undefined)
                    {
                        this.kagawad6 = dep2.data.kagawad[5].name;
                    }
                    if(dep2.data.kagawad[6].name != undefined)
                    {
                        this.kagawad7 = dep2.data.kagawad[6].name;
                    }else{
                        this.kagawad1 = '';
                        this.kagawad2 = '';
                        this.kagawad3 = '';
                        this.kagawad4 = '';
                        this.kagawad5 = '';
                        this.kagawad6 = '';
                        this.kagawad7 = '';
                    }
                    // this.officials = dep2.data;
                    // for(var i = 0; i < dep2.data.length; i ++)
                    // {
                    //     if(dep2.data[i].position == 'barangay captain')
                    //     {
                    //         this.brgy_captain = dep2.data[i].name;
                    //     }
                    // }
                    if(response.data[0].created_at != undefined){
                        this.date_created = response.data[0].created_at;
                    }
                })).catch(error => {  
                });
                let vm = this;
                var timesRun = 0;
                var interval = setInterval(function(){
                timesRun += 1;
                if(timesRun === 1)
                {
                    clearInterval(interval);
                }
                vm.$htmlToPaper('printMe');
                }, 1000); 

            },
            editClearance(clearance){
                this.form.reset();
                this.res.id = '';
                this.form.requestor_resident_id = '';
                this.editMode = true;
                $('.btnSubmit').text('Update');
                $('.btnSubmit').removeClass('btn-primary');
                $('.btnSubmit').css('font-weight','bold');
                $('.btnSubmit').addClass('btn-secondary');
                $('#addModal').modal('show');
              axios.all([
                  axios.get('/api/fetch/barangay/residents'),
                  axios.get('/api/fetch/barangay/residents',{
                     params: {
                        clearance_id: clearance.requestor_resident_id
                    }
                  })
                ])
                .then(axios.spread((response, dep2) => {
                    this.residents = response.data;
                    if(dep2.data[0].full_name != undefined){
                        this.res.id = dep2.data[0].full_name;
                        this.form.requestor_resident_id = dep2.data[0].id;
                    }else{
                        this.res.id = ''; 
                        this.form.requestor_resident_id = '';
                    }
                })).catch(error => {  
                    this.res.id = ''; 
                    this.form.requestor_resident_id = '';
                });
                this.form.fill(clearance);
            },
            updateClearance()
            {
                this.form.put('/api/student_certificate/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Student Certificate updated!'
                        })
                        $("#modalclose").trigger("click");
                        this.populateClearance();
                    })
                    .catch(error => {
                    });
            },
            setSelected(value){
                  this.form.name = value.full_name;
                  this.form.requestor_resident_id = value.id;
            },
            populateClearance(){
                axios.get("api/student_certificate")
                 .then((response) => {
                    console.log(response.data)
                        this.clearances = response.data
                        this.loadingData = false;
                    })
                    .catch(error => {
                    });
            },
            fetchResidents(){
                axios.get('/api/fetch/barangay/residents')
                .then(function(response){
                    console.log(response);
                    this.residents = response.data;
                }.bind(this));
            },
            deleteClearance(id){
                swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                        if (result.value) {
                            this.form.delete('/api/student_certificate/'+id).then(()=> {
                            this.populateClearance();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Student Certificate has been Deleted'
                                })
                            })
                          }
                })
            },
          },

        created() {
            this.populateClearance();
            $('.modal').modal('hide');
            console.clear();
            this.fetchResidents();
        }
    }
</script>
