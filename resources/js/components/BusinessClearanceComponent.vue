<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Business Clearance List</h3>

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
                                      Name
                                  </th>
                                  <th>
                                      Business or Trade Activity
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="clearance in clearances" :key="clearance.id">
                                  <td>
                                    {{ clearance.created_at | moment("MMMM D, YYYY") }}
                                  </td>
                                  <td>
                                    {{ clearance.name }}
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                            {{ clearance.business_name }}
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
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
                                    <label for="name">Business or Trade Activity</label>
                                    <input v-model="form.id" type="hidden" class="form-control" name="ba_id" id="barangay_id">
                                    <input v-model="form.business_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('business_name') }" id="name" placeholder="Business Name" autocomplete="off">
                                    <has-error :form="form" field="business_name"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Location</label>
                                    <input v-model="form.location" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('location') }" id="name" placeholder="Location" autocomplete="off">
                                    <has-error :form="form" field="location"></has-error>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <label for="name">Operator/Manager</label>
                                <v-select :options="residents" placeholder="select" v-model="res.id" label="full_name" :clearable="false" @input="setSelected"></v-select>
                                <input class="form-control" hidden :class="{ 'is-invalid': form.errors.has('requestor_resident_id') }" type="text" v-model="form.requestor_resident_id">
                                <has-error :form="form" field="requestor_resident_id"></has-error>
                            </div>
                        </div>  
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="name">Address</label>
                                <input type="text" name="address" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" v-model="form.address">
                                <has-error :form="form" field="address"></has-error>
                            </div>
                        </div>  
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="name">Remarks</label>
                                <textarea name="remarks" v-model="form.remarks" class="form-control" :class="{ 'is-invalid': form.errors.has('remarks') }" style="height: 150px;"></textarea>
                                <has-error :form="form" field="remarks"></has-error>
                            </div>
                        </div> 
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="name">Status</label>
                                <select class="form-control" :class="{ 'is-invalid': form.errors.has('status') }" name="status" v-model="form.status">
                                    <option selected value="New Business">New Business</option>
                                    <option value="Renewal">Renewal</option>
                                </select>
                                <has-error :form="form" field="status"></has-error>
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
                <img src="images/certificate/default_logo1.png" alt="Manila Logo" class="barangay_logo" />
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
                <img src="images/certificate/default_logo2.png" alt="Manila Logo" class="manila_logo" />
            </div>
        </div>

        <div class="cus_header_line_1"></div>
        <div class="cus_header_line_2"></div>

        <br />
        
        <!-- Date and Control No. -->
        <div class="date_and_control_number_container">
            <div class="date_container" style="display: flex; text-align: center;">
                Date: <div style="border-bottom: 1px solid black; width: 100px; margin: auto;">{{this.date_created | moment("MMMM D, YYYY")}}</div>
            </div>
            <div class="control_number_container" style="display: flex; text-align: center;">
                Control No: <div style="border-bottom: 1px solid black; width: 100px; margin: auto;">&nbsp;</div>
            </div>
        </div>

        <br />
        
        <!-- Business Clearance Content -->
        <div class="business_clearance_content">
            <div class="barangay_business_clearance_text">
                <b>BARANGAY BUSINESS CLEARANCE</b>
            </div>

            <div class="to_whom_it_may_concern_text">
                <b>TO WHOM IT MAY CONCERN:</b>
            </div>

            <div style="font-size: 1.5em;">
                This is to certify that the business or trade activity described below.
            </div>
            
            <br />
            <br />

            <div style="font-size: 1.5em;">
                <div style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.business_name}}</div>
                (Business or Trade Activity)
            </div>

            <br />

            <div style="font-size: 1.5em;">
            <div style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.location}}</div>
                (Location)
            </div>

            <br />

            <div style="font-size: 1.5em;">
                <div style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.name}}</div>
                (Operator/Manager)
            </div>

            <br />

            <div style="font-size: 1.5em;">
                <div style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.address}}</div>
                (Address)
            </div>

            <br />

            <div class="paragraph_container" >
                <div class="paragraph" style="text-align: justify;">
                    being applied for the <i><b>NEW / RENEWAL</b></i> of the corresponding Mayor's Permit has been found to be:
                    <br />
                    ___________/ Complying with the provisions of existing Barangay ordinances, rules and regulations being enforced in this Barangay:
                    <br />
                    ___________/ Partially complying with the provisions of existing Barangay ordinances, rules and regulations being enforced in this Barangay:
                </div>

                <br />

                <div class="paragraph" style="text-align: justify;">
                    In view of the foregoing, this Barangay thru the undersigned - - -
                    <br />
                    ___________/ Interposes <b><i>NO objection</i></b> for the issuance of the corresponding
                    <br />
                    Mayor's Permit being applied for;
                    <br />
                    ___________/ Recommended only the issuance of a <b><i>"TEMPORARY PERMIT"</i></b> for not more than (3) months and within that period the requirements under existing Barangay ordinances, rules and regulations on that matter should be totally compiled with, otherwise this Barangay would take the necessary actions, within legal bounds, to stop its continued operation.
                </div>
            </div>
        </div>

        <br />
        <br />

        <!-- Seal and Signature -->
        <div class="seal_and_signature">
            <div class="seal">
                <!-- <h6 style="margin: 0px;">
                    Attested:
                    <br />
                    <br />
                    ______________________________
                    <br />
                    <center>Kagawad</center>
                    <br />
                    <b>(Not valid without Barangay Seal)</b>
                </h6> -->
            </div>

            <div class="signature">
                <b>{{this.brgy_captain}}</b>
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
                business_name: '',
                location: '',
                name: '',
                address: '',
                remarks: '',
                requestor_resident_id: '',
                status: 'New Business',
                busy: false,
              })
            }
          },
          methods: {
            openAddModal(){
                this.form.reset();
                this.res.id = '';
                this.form.requestor_resident_id = '';
                this.editMode = false;
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New Business Clearance');
                $('#addModal').modal('show');
            },
            createClearance() {
            this.form.post('/api/business_clearance')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'New Business Clearance successfully Added'
                    })
                    $("#modalclose").trigger("click");
                    this.populateClearance();
                })
                .catch(error => {
                });
            },
            printClearance(clearance){
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
                    console.log(dep2.data.kagawad[1].name)
                    if(dep2.data.kagawad[0].name != undefined)
                    {
                        this.kagawad1 = dep2.data.kagawad[0].name;
                        console.log("kagawad 1: "+ this.kagawad1);
                    } 
                    if(dep2.data.kagawad[1].name != undefined)
                    {
                        this.kagawad2 = dep2.data.kagawad[1].name;
                        console.log("kagawad 2: "+ this.kagawad2);
                    }
                    if(dep2.data.kagawad[2].name != undefined)
                    {
                        this.kagawad3 = dep2.data.kagawad[2].name;
                        console.log("kagawad 3: "+ this.kagawad3);
                    }
                    if(dep2.data.kagawad[3].name != undefined)
                    {
                        this.kagawad4 = dep2.data.kagawad[3].name;
                        console.log("kagawad 4: "+ this.kagawad4);
                    }
                    if(dep2.data.kagawad[4].name != undefined)
                    {
                        this.kagawad5 = dep2.data.kagawad[4].name;
                        console.log("kagawad 5: "+ this.kagawad5);
                    }
                    if(dep2.data.kagawad[5].name != undefined)
                    {
                        this.kagawad6 = dep2.data.kagawad[5].name;
                        console.log("kagawad 6: "+ this.kagawad6);
                    }
                    if(dep2.data.kagawad[6].name != undefined)
                    {
                        this.kagawad7 = dep2.data.kagawad[6].name;
                        console.log("kagawad 7: "+ this.kagawad7);
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
                this.form.put('/api/business_clearance/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Business Clearance updated!'
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
                axios.get("api/business_clearance")
                 .then((response) => {
                        this.clearances = response.data.data
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
                            this.form.delete('/api/business_clearance/'+id).then(()=> {
                            this.populateClearance();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Business Clearance has been Deleted'
                                })
                            })
                          }
                })
            },
          },

        created() {
            this.populateClearance();
            
            this.fetchResidents();
        }
    }
</script>
