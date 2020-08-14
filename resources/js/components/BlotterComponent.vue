
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Blotter List</h3>

                      <div class="card-tools">
                        <router-link to="/blotter/create" class="btn btn-success"><i class="fas fa-plus"></i> Add New</router-link>
                      </div>
                    </div>

                    <div class="card-body p-0 table-responsive" style="display: block;" v-if="!loadingData">
                      <table class="table table-striped projects">
                          <thead>
                              <tr>
                                  <th>
                                      Complainant/Victim
                                  </th>
                                  <th>
                                      Blotter Type
                                  </th>
                                  <th>
                                      Crime Type
                                  </th>
                                  <th>
                                      Date/Time of Incident
                                  </th>
                                  <th>
                                      Date/Time Reported
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Actions
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="blotter in blotters" :key="blotter.id">
                                  <td>
                                    <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                            {{blotter.name}}
                                          </li>
                                      </ul>
                                  </td>
                                  <td>
                                    {{ blotter.type }}
                                  </td>
                                  <td>
                                    {{ blotter.crime_type }}
                                  </td>
                                  <td>
                                    {{ blotter.date_of_incident | moment("MMMM D, YYYY") }}
                                  </td>
                                  <td>
                                      {{ blotter.time_reported | moment("MMMM D, YYYY") }}
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
                editMode : false,
                loadingData : true,
                blotters: {},
              form: new Form({
               id: '',
               name: '',
              })
            }
          },
          methods: {
            populateBlotter(){
               axios.get("/api/blotter")
                 .then((response) => {
                  console.log("aaa");
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
                            this.populateBlotter();
                             toast.fire({
                                  icon: 'success',
                                  title: 'Crime Type has been Deleted'
                                })
                            })
                          }
                })
            }
          },
        created() {
            this.populateBlotter();
        }
    }
  
</script>
