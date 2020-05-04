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

                                      <button class="btn btn-success" alt="Print" title="Print Data">
                                          <i class="fas fa-print"></i>
                                          </i>
                                      </button>
                                      <button class="btn btn-primary" alt="Edit" title="Edit Data" @click="editClearance(clearance)">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" alt="Delete" title="Delete Data">
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
            editClearance(clearance){
                this.form.reset();
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
          },

        created() {
            this.populateClearance();
            this.fetchResidents();
        }
    }
</script>
