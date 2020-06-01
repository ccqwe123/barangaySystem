
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Blotter List</h3>

                      <div class="card-tools">
                        <router-link to="/barangay-officials">test</router-link>
                        <button type="button" class="btn btn-success">
                          <i class="fas fa-plus"></i> Add New</button>
                      </div>
                    </div>

                    <div class="card-body p-0 table-responsive" style="display: block;" v-if="!loadingData">
                      <table class="table table-striped projects">
                          <thead>
                              <tr>
                                  <th>
                                      Complainant
                                  </th>
                                  <th>
                                      Crime Type
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="blotter in blotters" :key="blotter.id">
                                  <td>
                                    {{ blotter.created_at | moment("MMMM D, YYYY") }}
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                            {{blotter.name}}
                                          </li>
                                      </ul>
                                  </td>
                                  <td class="project-actions text-center" style="text-align: center; justify-content: center; align-items: center; min-width: 170px !important;">
                                      <button class="btn btn-primary" @click="editBarangay(blotter)" alt="Edit" title="Edit Data">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" @click="deleteBarangay(blotter.id)" alt="Delete" title="Delete Data">
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
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
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
                        <label for="name">Crime Name</label>
                        <input v-model="form.id" type="hidden" class="form-control" name="barangay_id" id="barangay_id">
                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" id="name" placeholder="Crime Type" autocomplete="off">
                        <has-error :form="form" field="name"></has-error>
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
                loadingData : true,
                blotters: {},
                barangay_captain_id: [],
                bar:{
                    id: '',
                },
              form: new Form({
               id: '',
               name: '',
              })
            }
          },
          methods: {
            populateCrimeType(){
               axios.get("api/blotter")
                 .then((response) => {
                  console.log(response.data);
                        this.blotters = response.data.data
                        this.loadingData = false;
                    })
                    .catch(error => {
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
                            this.form.delete('/api/crime_type/'+id).then(()=> {
                            this.populateCrimeType();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Crime Type has been Deleted'
                                })
                            })
                          }
                })
            },
        created() {
            this.populateCrimeType();
        }
    }
  }
</script>
