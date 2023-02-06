<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="loading-container" v-if="!loadingData">
                  <div class="row">
                    <div class="col-12">
                      <h4>
                        Barangay Officials List
                        <div class="float-right"> <button type="button" class="btn btn-success btn-sm mb-2" @click="openAddModal">
                              <i class="fas fa-plus"></i> Add New</button></div>
                      </h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 table-responsive">
                      <table class="table table-striped">
                        <thead>
                        <tr>
                          <th>Name</th>
                          <th>Position</th>
                          <th>Barangay</th>
                          <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="official in officials.data" :key="official.id">
                            <td class="custom-table">{{official.name}} </td>
                            <td class="custom-table">{{official.position}} </td>
                            <td class="custom-table">{{official.barangay_name}}</td>
                            <td class="project-actions text-center custom-table" style="text-align: center; justify-content: center; align-items: center; min-width: 170px !important;">
                                  <button class="btn btn-primary" @click="editBarangay(official)" alt="Edit" title="Edit Data">
                                      <i class="fas fa-pencil-alt">
                                      </i>
                                  </button>
                                  <button class="btn btn-danger" @click="deleteBarangay(official.id)" alt="Delete" title="Delete Data">
                                      <i class="fas fa-trash">
                                      </i>
                                  </button>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                        <pagination :data="officials" @pagination-change-page="getResults" class="float-right"></pagination>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <div class="text-center">
                    <div class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!--- Modal --->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <form @submit.prevent="editMode ? updateBarangay() : createBarangayOfficial()">
              <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="form.id" type="hidden" class="form-control" name="official_id" id="official_id">
                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" id="name" placeholder="Barangay Official Name" autocomplete="off">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select class="form-control" :class="{ 'is-invalid': form.errors.has('position') }" name="position" v-model="form.position">
                            <option value="" selected disabled>Select Barangay Official Position</option>
                            <option value="barangay captain">Barangay Captain</option>
                            <option value="treasurer">Treasurer</option>
                            <option value="secretary">Secretary</option>
                            <option value="kagawad">Kagawad</option>
                        </select>
                        <has-error :form="form" field="position"></has-error>
                        <span v-if="this.position_check" class="invalid-position">{{position_check_text}}</span>
                    </div>
                    <div class="form-group" v-if="this.user_id == 'admin'">
                        <label for="name">Barangay</label>
                         <v-select :options="barangay_id" v-if="this.barangay_id == 0" placeholder="No Barangay found" label="barangay_name" :clearable="false" @input="setSelected"></v-select>
                          <v-select :options="barangay_id" v-model='bar.id' v-else placeholder="Select Barangay" label="barangay_name" :clearable="false" @input="setSelected"></v-select>
                        <input class="form-control m-input" type="text" hidden v-model="form.barangay_id" :class="{ 'is-invalid': form.errors.has('barangay_id') }">
                        <has-error :form="form" field="barangay_id"></has-error>
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
    .invalid-position {
        width: 100%;
        margin-top: 0.25rem;
        font-size: 80%;
        color: #e3342f;
    }
</style>
<script>
    import EditBlotter from './EditBlotterComponent';
    export default {
        data () {
            return {
              loadingData : true,
              editMode: false,
              officials: {},
              barangay_id: [],
              barangay_select: '',
              user_id: '',
              position_check: false,
              position_check_text: '',
              bar:{
                    id: '',
                },
              form: new Form({
                position: '',
                official_id: '',
                barangay_id: '',
                name: '',
                id: '',
              })
            }
          },
          methods: {
            getResults(page = 1) {
              axios.get('api/officials?page=' + page)
                .then(response => {
                  this.officials = response.data;
                });
            },
            setSelected(value) {
                this.form.barangay_id = value.id
            },
            populateOfficial(){
                axios.get("api/officials")
                .then(({ data }) => (this.officials = data));
                this.loadingData = false;
            },
            openAddModal(){
                this.form.reset();
                this.editMode = false;
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New Barangay Official');
                $('#addModal').modal('show');
            },
            editBarangay(official){
                this.form.reset();
                this.editMode = true;
                $('.btnSubmit').text('Update');
                $('.btnSubmit').removeClass('btn-primary');
                $('.btnSubmit').css('font-weight','bold');
                $('.btnSubmit').addClass('btn-secondary');
                $('#addModal').modal('show');
                $('.modal-title').text('Edit Barangay Official');
                axios.get('/api/fetch/barangaySelected',{
                    params: {
                        barangay_id: official.barangay_id
                    }
                  }).then(function(response){
                    // console.log(response.data[0]);
                    this.bar.id = response.data[0].barangay_name;
                    this.form.barangay_id = response.data[0].id;
                }.bind(this));
                this.form.fill(official);
            },
            updateBarangay(){
                this.form.put('/api/officials/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Barangay Official Information updated!'
                        })
                        $("#modalclose").trigger("click");
                        this.form.barangay_id = '';
                        this.position_check = false;
                        this.populateOfficial();
                    })
                    .catch(error => {
                        if(error.response.data.errors.constructor == Object)
                        {
                        }else{
                            this.position_check = true;
                            this.position_check_text = error.response.data.errors;
                        }
                    });

            },
            deleteBarangay(id){
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
                            this.form.delete('/api/officials/'+id).then(()=> {
                            this.populateOfficial();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Barangay Official has been Deleted'
                                })
                            })
                          }
                })
            },
            fetchBarangay() {
                   axios.get('/api/fetch/barangay').then(function(response){
                    this.barangay_id = response.data;
                }.bind(this));
            },
            createBarangayOfficial() {
                this.form.post('/api/officials')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'New Barangay Official successfully Added'
                    })
                    $("#modalclose").trigger("click");
                    this.bar.id = '';
                    this.form.barangay_id = '';
                    this.position_check = false;
                    this.populateOfficial();
                })
                .catch(error => {
                    if(error.response.data.errors.constructor == Object)
                    {
                    }else{
                        this.position_check = true;
                        this.position_check_text = error.response.data.errors;
                    }
                   
                });
            },
            fetchUsers() {
                axios.get('/api/fetch/user_id').then(function(response){
                    this.user_id = response.data.type
                }.bind(this));
            },
          },
        created() {
           Fire.$on('searching',() => {
                let query = this.$parent.searhall;
              axios.get('api/search/officials?search=' + query)
                .then((data) => {
                    this.officials = data.data
                })
                .catch(() => {
                })
            })
            this.fetchUsers();
            this.fetchBarangay();
            this.populateOfficial();
            $('.modal').modal('hide');
            console.clear();
        }
    }
</script>
