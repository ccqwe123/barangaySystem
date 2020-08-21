
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Resident List</h3>

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
                                      Full Name
                                  </th>
                                  <th>
                                      Age
                                  </th>
                                  <th>
                                      Gender
                                  </th>
                                  <th>
                                      Address
                                  </th>
                                  <th>
                                      Barangay
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="resident in residents" :key="resident.id">
                                  <td>
                                    <li class="list-inline-item text-capitalize">
                                      {{resident.first_name}} {{resident.last_name}}
                                    </li>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                        {{resident.age}} 
                                      </ul>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                        {{resident.gender}} 
                                      </ul>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                        {{resident.address}} 
                                      </ul>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                        {{resident.barangay_name}}
                                      </ul>
                                  </td>
                                  <td class="project-actions text-center" style="text-align: center; justify-content: center; align-items: center; min-width: 170px !important;">

                                      <!-- <button class="btn btn-success" alt="Edit" title="View Data">
                                          <i class="fas fa-eye">
                                          </i>
                                      </button> -->
                                      <button class="btn btn-primary" @click="editResident(resident)" alt="Edit" title="Edit Data">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" @click="deleteResident(resident.id)" alt="Delete" title="Delete Data">
                                          <i class="fas fa-trash">
                                          </i>
                                      </button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                    <div class="card-body p-0" style="display: block;" v-else>
                      <div class="text-center">
                        <div class="spinner-border" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModalresident" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <form @submit.prevent="editMode ? updateResident() : createResident()">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">First Name</label>
                        <input v-model="form.id" type="hidden" class="form-control" name="resident_id" id="resident_id">
                        <input v-model="form.first_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }" id="name" placeholder="First Name" autocomplete="off">
                        <has-error :form="form" field="first_name"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input v-model="form.middle_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('middle_name') }" id="middle_name" placeholder="Middle Name" autocomplete="off">
                        <has-error :form="form" field="middle_name"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input v-model="form.last_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }" id="last_name" placeholder="Last Name" autocomplete="off">
                        <has-error :form="form" field="last_name"></has-error>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="birthdate">Birth Date</label>
                        <input v-model="form.birthdate" @input="getAge(form.birthdate)" type="date" class="form-control" :class="{ 'is-invalid': form.errors.has('birthdate') }" id="birthdate" autocomplete="off">
                        <has-error :form="form" field="birthdate"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="birthplace">Birthplace</label>
                        <input v-model="form.birthplace" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('birthplace') }" id="birthplace" placeholder="Birthplace" autocomplete="off">
                        <has-error :form="form" field="birthplace"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input v-model="form.age" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('age') }" id="age" placeholder="0" autocomplete="off">
                        <has-error :form="form" field="age"></has-error>
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }" v-model="form.gender" id="gender">
                            <option value="" selected disabled>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        <has-error :form="form" field="gender"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="civil_status">Civil Status</label>
                        <select  placeholder="Select Civil Status" name="civil_status" v-bind:class="{'form-control': true, 'is-invalid': form.errors.has('civil_status') }" v-model="form.civil_status">
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                        <has-error :form="form" field="civil_status"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="mobile_no">Contact Number</label>
                        <input v-model="form.mobile_no" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('mobile_no') }" id="mobile_no" autocomplete="off">
                        <has-error :form="form" field="mobile_no"></has-error>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="citizenship">Citizenship</label>
                        <select class="form-control" name="citizenship" v-model="form.citizenship">
                            <option selected value="filipino">Filipino</option>
                            <optgroup label="Dual Citizenship By:">
                            <option value="birth">Birth</option>
                            <option value="naturalization">Naturalization</option>
                            </optgroup>
                        </select>
                        <has-error :form="form" field="citizenship"></has-error>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="employment_status">Employment Status</label>
                        <select class="form-control" :class="{ 'is-invalid': form.errors.has('employment_status') }" name="employment_status" v-model="form.employment_status" @change="employment(form.employment_status)">
                            <option value="" selected disabled="">Select Status</option>
                            <option value="Employed">Employed</option>
                            <option value="Unemployed">Unemployed</option>
                            <option value="Self-employed">Self-employed</option>
                        </select>
                        <has-error :form="form" field="employment_status"></has-error>
                  </div>
                  <div class="col-md-4">
                      <label for="occupation">Occupation</label>
                      <input type="text" name="occupation" v-model="form.occupation" class="form-control" :class="{ 'is-invalid': form.errors.has('occupation') }" :disabled="disabledText">
                      <has-error :form="form" field="occupation"></has-error>
                  </div>
                </div>
                <div class="row">
                  
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input v-model="form.address" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" id="address" autocomplete="off">
                        <has-error :form="form" field="first_name"></has-error>
                    </div>
                  </div>
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="barangay_id">Barangay</label>
                        <v-select :options="bbarangay_id" placeholder="select" @input="setSelected"  v-model='bar.id' label="barangay_name"  :clearable="false"></v-select>
                        <span class="invalid-feedback" v-if="form.errors.has('bbarangay_id')">The Barangay Field is required!</span>
                        <input type="text" hidden name="barangay_id" v-model="form.barangay_id">
                    </div>
                  </div> -->
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
                barangay_captain_set: 0,
                editMode : false,
                disabledText : true,
                loadingData : true,
                residents: {},
               bbarangay_id: [],
               bar:{
                    id: '',
                },
              form: new Form({
                id: '',
                first_name: '',
                middle_name: '',
                last_name: '',
                birthdate: '',
                age: '',
                gender: '',
                civil_status: 'single',
                mobile_no: '',
                citizenship: 'filipino',
                address: '',
                birthplace: '',
                employment_status: '',
                occupation: '',
                barangay_id: '',
               bbarangay_id: [],
              })
            }
          },
          // mixins: [
          //   Barangay
          // ],
          methods: {
            employment(status)
            {
                if(status !== 'Unemployed')
                {
                    this.disabledText = false;
                }else if(status == 'Unemployed')
                {
                    this.disabledText = true;
                }
            },
            getAge(dateString) {
              // console.log(dateString);
                var today = new Date();
                var birthDate = new Date(dateString);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                this.form.age = age;
                // return age;
            },
            setSelected(value) {
                this.form.barangay_id = value.id;
              },
            fetchBarangay() {
                   axios.get('/api/fetch/barangay').then(function(response){
                    this.bbarangay_id = response.data;
                }.bind(this));
            },
            updateResident(){
                this.form.put('/api/residents/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Resident Information updated!'
                        })
                        $("#modalclose").trigger("click");
                        this.populateResidents();
                    })
                    .catch(error => {
                    });

            },
            editResident(resident){
                this.form.reset();
                this.editMode = true;
                $('.btnSubmit').text('Update');
                $('.btnSubmit').removeClass('btn-primary');
                $('.btnSubmit').css('font-weight','bold');
                $('.btnSubmit').addClass('btn-secondary');
                $('.modal-title').text('Edit Resident Record');
                $('#addModalresident').modal('show');
            axios.all([
                  axios.get('/api/getBarangay',{
                    params: {
                        barangay_id: resident.id
                    }
                  }),
                  axios.get('/api/fetch/barangaySelected',{
                     params: {
                        barangay_id: resident.brgy_id
                    }
                  })
                ])
                .then(axios.spread((response, dep2) => {
                    this.bbarangay_id = response.data;
                    this.form.barangay_id = resident.barangay_id;
                    if(dep2.data[0].barangay_name != undefined){
                        this.bar.id = dep2.data[0].barangay_name;
                        this.form.barangay_id = dep2.data[0].id;
                    }else{
                        // this.bar.id = ''; 
                        // this.bar_id = ''; 
                    }
                })).catch(error => {  
                    // this.bar.id = ''; 
                    // this.bar_id = ''; 
                });

                this.form.fill(resident);
            },
            openAddModal(){
                this.form.reset();
                this.editMode = false;
                this.disabledText = true
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New Resident');
                $('#addModalresident').modal('show');
            },
            populateResidents(){
                axios.get("api/residents")
                .then(({ data }) => (this.residents = data.data));
                this.loadingData = false;
            },
            deleteResident(id){
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
                            this.form.delete('/api/residents/'+id).then(()=> {
                            this.populateResidents();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Resident has been Deleted'
                                })
                            })
                          }
                })
            },
            createResident(){
                this.form.post('/api/residents')
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'New Resident successfully Added'
                        })
                        $("#modalclose").trigger("click");
                        this.populateResidents();
                    })
                    .catch(error => {
                    });
            }
          },

        created() {
            this.populateResidents();
            this.fetchBarangay();
            $('.modal').modal('hide');
            console.clear();
        }
    }
</script>
