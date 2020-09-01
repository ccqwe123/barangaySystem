
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Barangay List</h3>

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
                                      Barangay Name
                                  </th>
                                  <th>
                                      Barangay Captain
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="barangay in barangays.data" :key="barangay.id">
                                  <td>
                                     {{barangay.barangay_name}}
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                            {{barangay.full_name}}
                                          </li>
                                      </ul>
                                  </td>
                                  <td class="project-actions text-center" style="text-align: center; justify-content: center; align-items: center; min-width: 170px !important;">
<!-- 
                                      <button class="btn btn-success" alt="Edit" title="View Data">
                                          <i class="fas fa-eye">
                                          </i>
                                      </button> -->
                                      <button class="btn btn-primary" @click="editBarangay(barangay)" alt="Edit" title="Edit Data">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" @click="deleteBarangay(barangay.id)" alt="Delete" title="Delete Data">
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
                    <div class="card-footer">
                      <pagination :data="barangays" @pagination-change-page="getResults" class="float-right"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addModalbrgy" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <form @submit.prevent="editMode ? updateBarangay() : createBarangay()">
              <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Barangay Name</label>
                        <input v-model="form.id" type="hidden" class="form-control" name="barangay_id" id="barangay_id">
                        <input v-model="form.barangay_name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('barangay_name') }" id="name" placeholder="Barangay Name" autocomplete="off">
                        <has-error :form="form" field="barangay_name"></has-error>
                    </div>
                    <div class="form-group" v-if="editMode">
                        <label for="name">Barangay Captain</label> <a href="" class="removeDept float-right" v-if="barangay_captain_set === 1" v-on:click.stop.prevent="removeDeptHead"><label><i class="fas fa-user-times"></i> [Remove Barangay Captain]</label></a>
                         <v-select :options="barangay_captain_id" placeholder="select" v-model='bar.id' label="full_name" @input="setSelected" :clearable="false"></v-select>

                        <input class="form-control m-input" hidden type="text" v-model="form.bar_id">
                        <has-error :form="form" field="bar_id"></has-error>
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
                fullPage: false,
                barangay_captain_set: 0,
                editMode : false,
                loadingData : true,
                barangays: {},
                barangay_captain_id: [],
                bar:{
                    id: '',
                },
              form: new Form({
               id: '',
               barangay_name: '',
               barangay_captain_id: '',
               bar_id: '',
              })
            }
          },
          methods: {
            getResults(page = 1) {
              axios.get('api/barangay?page=' + page)
                .then(response => {
                  this.barangays = response.data;
                });
            },
            // openAddDepartmentModal(){
            //     this.form.reset();
            //     this.editMode = false;
            //     $('.btnSubmit').text('Create');
            //     $('.btnSubmit').addClass('btn-primary');
            //     $('.btnSubmit').removeClass('btn-secondary');
            //     $('.modal-title').text('Add New Department');
            //     $('#addDepartmentModal').modal('show');
            // },
            updateBarangay(){
                this.form.put('/api/barangay/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'Barangay Information updated!'
                        })
                        $("#modalclose").trigger("click");
                        this.populateBarangay();
                    })
                    .catch(error => {
                    });

            },
            removeDeptHead(value) {
                this.barangay_captain_set = 0;
                this.form.bar_id = '';
                this.bar.id = '';
             },
            setSelected(value) {
                this.form.bar_id = value.id;
                this.barangay_captain_set = 1;
              },
            editBarangay(barangay){
                this.form.reset();
                this.editMode = true;
                $('.btnSubmit').text('Update');
                $('.btnSubmit').removeClass('btn-primary');
                $('.btnSubmit').css('font-weight','bold');
                $('.btnSubmit').addClass('btn-secondary');
                $('#addModalbrgy').modal('show');
              axios.all([
                  axios.get('/api/getStates',{
                    params: {
                        barangay_id: barangay.id
                    }
                  }),
                  axios.get('/api/fetch/barangay_head',{
                     params: {
                        barangay_id: barangay.id
                    }
                  })
                ])
                .then(axios.spread((response, dep2) => {
                    this.barangay_captain_id = response.data;
                    if(dep2.data[0].full_name != undefined){
                        this.bar.id = dep2.data[0].full_name;
                        this.form.bar_id = dep2.data[0].resident_id;
                        this.barangay_captain_set = 1;
                    }else{
                        this.bar.id = ''; 
                        this.bar_id = ''; 
                        this.barangay_captain_set = 0;
                    }
                })).catch(error => {  
                    this.bar.id = ''; 
                    this.bar_id = ''; 
                    this.barangay_captain_set = 0;
                });
                this.form.fill(barangay);
            },
            openAddModal(){
                this.form.reset();
                this.editMode = false;
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New Barangay');
                $('#addModalbrgy').modal('show');
            },
            populateBarangay(){
                axios.get("api/barangay")
                .then(({ data }) => (this.barangays = data));
                this.loadingData = false;
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
                            this.form.delete('/api/barangay/'+id).then(()=> {
                            this.populateBarangay();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Barangay has been Deleted'
                                })
                            })
                          }
                })
            },
            createBarangay(){
                this.form.post('/api/barangay')
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'New Barangay successfully Added'
                        })
                        $("#modalclose").trigger("click");
                        this.populateBarangay();
                    })
                    .catch(error => {
                    });
            }
          },

        created() {
           Fire.$on('searching',() => {
                let query = this.$parent.searhall;
              axios.get('api/search/barangay?search=' + query)
                .then((data) => {
                    this.barangays = data.data
                })
                .catch(() => {
                })
            })
            this.populateBarangay();
            $('.modal').modal('hide');
            console.clear();
        }
    }
</script>
