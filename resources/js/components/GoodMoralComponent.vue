<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Good Moral Record List</h3>

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
                                      Address
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
                                            {{ clearance.address }}
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
                            <div class="col-md-12">
                                <label for="name">Name</label>
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
                                <label for="name">Purpose</label>
                                <textarea name="remarks" v-model="form.purpose" class="form-control" :class="{ 'is-invalid': form.errors.has('purpose') }" style="height: 150px;"></textarea>
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
            <div class="header mt-5">
            <div class="barangay_logo_container">
                <img src="images/certificate/default_logo1.png" :src="getBrgyLogo1()" alt="Manila Logo" class="manila_logo" />
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

        
        
        <!-- Business Clearance Content -->
        <div class="business_clearance_content">
            <div class="barangay_business_clearance_text mt-5">
                <h1 style="color:#FF0000">CERTIFICATION</h1>
            </div>

            <div class="to_whom_it_may_concern_text">
                <b>TO WHOM IT MAY CONCERN:</b>
            </div>

            <br />

            <div class="paragraph_container">
                <div class="paragraph" style=" text-align: justify;  text-justify: inter-word;">
                    This is to certify that <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.name}}</span> whose specimen signature appears below, is a bonafide resident of this barangay with 
                    postal address at <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.address}}</span>
                </div>

                <br />

                <div class="paragraph" style=" text-align: justify;  text-justify: inter-word;">
                    I hereby certify further, that the same is known to me to be a person of good moral
                    character and has no derogatory record whatsoever filed againts him/her before this office.

                </div>

                <br />

                <div class="paragraph" style=" text-align: justify;  text-justify: inter-word;">
                    This Certification is being issued upon the request of the interested party for <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.form.purpose}}</span>.
                </div>

                <br />

                <div class="paragraph" style=" text-align: justify;  text-justify: inter-word;">
                    Given this <span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.date_created | moment("DD")}} </span><span style="border-bottom: 1px solid black;"> day of </span><span style="border-bottom: 1px solid black; width: 300px; margin: auto;">{{this.date_created | moment("MMMM, YYYY")}}</span>
                </div>
            </div>
        </div>

        <br />
        <br />
        <br />
        <br />

        <!-- Seal and Signature -->
        <div class="seal_and_signature">
            <div class="seal">
                <h6 style="margin: 0px;">
                    Specimen Signature:
                    <br />
                    ___________________________
                    <br />
                    Res. Cert. No._______________
                    <br />
                    Issued At __________________
                    <br />
                    On ________________________
                    <br />
                    <br />
                    <strong>ATTESTED BY:</strong>
                    <br />
                    ___________________________
                    <br />
                           PUROK KAGAWAD
                </h6>
            </div>

            <div class="signature" style="margin-top:0px">
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
              brgy_captain: '',
              date_created: '',
              res:{
                    id: '',
                },
              form: new Form({
                id: '',
                barangay_logo1: '',
                barangay_logo2: '',
                name: '',
                address: '',
                purpose: '',
                requestor_resident_id: '',
                busy: false,
              })
            }
          },
          methods: {
            populateBrgyLogo() {
                let vm = this.form;
                axios.get('/api/system/getlogo').then(function(response){
                    vm.barangay_logo1 = response.data[0].barangay_logo1;
                    vm.barangay_logo2 = response.data[0].barangay_logo2;
                    // console.log(response.data[0].barangay_logo1);
                }.bind(this));
            },
             getBrgyLogo1() {
                let photo1 = (this.form.barangay_logo1.length > 200) ? this.form.barangay_logo1 : "images/certificate/"+ this.form.barangay_logo1 ;
                return photo1;
            },
            getBrgyLogo2() {
                let photo2 = (this.form.barangay_logo2.length > 200) ? this.form.barangay_logo2 : "images/certificate/"+ this.form.barangay_logo2 ;
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
                $('.modal-title').text('Add New Good Moral Certificate');
                $('#addModal').modal('show');
            },
            createClearance() {
            this.form.post('/api/good_moral')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'New Good Moral successfully Added'
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
                  axios.get('/api/fetch/barangay/officials'),
                  axios.get('/api/fetch/good_moral/data',{
                    params: {
                        id: clearance.id
                    }
                  }),
                ])
                .then(axios.spread((response, dep2, goodmoral) => {
                    this.variables = response.data;
                    this.date_created = goodmoral.data.created_at;
                   this.brgy_captain = dep2.data.captain.name;

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
                $('.modal-title').text('Edit Good Moral Certificate');
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
                this.form.put('/api/good_moral/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Good Moral updated!'
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
                axios.get("api/good_moral")
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
                            this.form.delete('/api/good_moral/'+id).then(()=> {
                            this.populateClearance();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Good Moral has been Deleted'
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
