<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">User List</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-success" @click="openAddModal">
                          <i class="fas fa-plus"></i> Add New</button>
                      </div>
                    </div>

                    <div class="card-body p-0" style="display: block;">
                      <table class="table table-striped projects">
                          <thead>
                              <tr>
                                  <th style="width: 20%">
                                    Name
                                </th>
                                  <th style="width: 20%">
                                      Name/Email
                                  </th>
                                  <th style="width: 10%">
                                      User Type
                                  </th>
                                  <th style="width: 15%">
                                      Registered At
                                  </th>
                                  <th style="text-align: center; justify-content: center; align-items: center;" class="text-center">
                                      Barangay
                                  </th>
                                  <th style="width: 20%">
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="user in users" :key="user.id">
                                  <td>
                                    {{user.name}}
                                  </td>
                                  <td>
                                      <a>
                                          {{user.username}}
                                      </a>
                                      <br>
                                      <small>
                                          {{user.email}}
                                      </small>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item text-capitalize">
                                              {{user.type}}
                                          </li>
                                      </ul>
                                  </td>
                                  <td>
                                      <ul class="list-inline">
                                          <li class="list-inline-item">
                                            {{ user.created_at | moment("MMMM D, YYYY") }}
                                          </li>
                                      </ul>
                                  </td>
                                  <td class="project-state" style="text-align: center; justify-content: center; align-items: center;">
                                       {{user.barangay_name}}
                                  </td>
                                  <td class="project-actions text-right">
                                      <button class="btn btn-success">
                                          <i class="fas fa-eye">
                                          </i>
                                      </button>
                                      <button class="btn btn-primary" @click="editUser(user)">
                                          <i class="fas fa-pencil-alt">
                                          </i>
                                      </button>
                                      <button class="btn btn-danger" @click="deleteUser(user.id)">
                                          <i class="fas fa-trash">
                                          </i>
                                      </button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
        <!--- add modal --->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <form @submit.prevent="editMode ? updateUser() : createUser()">
              <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input v-model="form.id" type="hidden" class="form-control" name="user_id" id="user_id">
                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" id="name" placeholder="Account Name">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="name">Userame</label>
                        <input v-model="form.username" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('username') }" id="name" placeholder="Username">
                        <has-error :form="form" field="username"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input v-model="form.email" type="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" id="email" placeholder="name@example.com">
                        <has-error :form="form" field="email"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="type">User Type</label>
                        <select v-model="form.type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" name="type" id="type">
                            <option selected disabled>Select Type</option>
                            <option value="User">User</option>
                            <option value="Admin" v-if="this.user_id == 'admin'">Admin</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" id="password" placeholder="Password">
                        <has-error :form="form" field="password"></has-error>
                    </div>
                    <div class="form-group" v-if="this.user_id == 'admin'">
                        <label for="name">Barangay</label>
                         <v-select :options="barangay_id" v-if="this.barangay_id == 0" placeholder="No Barangay found" label="barangay_name" :clearable="false" @input="setSelected"></v-select>
                          <v-select :options="barangay_id" v-else placeholder="Select Barangay" label="barangay_name" :clearable="false" @input="setSelected"></v-select>
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

<script>
    export default {
        data () {
            return {
                editMode : false,
                users: {},
                barangay_id: [],
                user_id: '',
              form: new Form({
                id: '',
                name: '',
                username: '',
                email: '',
                password: '',
                type: '',
                barangay_id: '',
                remember: false,
              })
            }
          },
          methods: {
            fetchBarangay() {
                   axios.get('/api/fetch/barangay').then(function(response){
                    this.barangay_id = response.data;
                }.bind(this));
            },
            setSelected(value) {
                this.form.barangay_id = value.id
            },
            updateUser(){
                this.form.put('/api/users/'+this.form.id)
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'User Info updated'
                        })
                        $("#modalclose").trigger("click");
                          $('body').css('padding-right', '');
                        this.populateUser();
                    })
                    .catch(error => {
                    });
                // $('#addModal').hide();
               
            },
            editUser(user){
                this.form.reset();
                this.editMode = true;
                $('.modal-title').text('Edit User');
                $('.btnSubmit').text('Update');
                $('.btnSubmit').removeClass('btn-primary');
                $('.btnSubmit').css('font-weight','bold');
                $('.btnSubmit').addClass('btn-secondary');
                $('#addModal').modal('show');
                this.form.fill(user);
            },
            openAddModal(){
                this.form.reset();
                this.editMode = false;
                $('.btnSubmit').text('Create');
                $('.btnSubmit').addClass('btn-primary');
                $('.btnSubmit').removeClass('btn-secondary');
                $('.modal-title').text('Add New User');
                $('#addModal').modal('show');
            },
            populateUser(){
                axios.get("api/users").then(({ data }) => (this.users = data.data));
            },
            deleteUser(id){
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
                            this.form.delete('/api/users/'+id).then(()=> {
                            this.populateUser();
                             toast.fire({
                                  icon: 'success',
                                  title: 'User has been Deleted'
                                })
                            })
                          }
                })
            },
            createUser(){
                this.form.post('/api/users')
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'New User successfully Added'
                        })
                        $("#modalclose").trigger("click");
                          $('body').css('padding-right', '');
                        this.populateUser();
                    })
                    .catch(error => {
                    });
                // $('#addModal').hide();

            },
             fetchUsers() {
                axios.get('/api/fetch/user_id').then(function(response){
                    this.user_id = response.data.type
                }.bind(this));
            }
          },
        created() {
            this.fetchUsers();
            this.populateUser();
            this.fetchBarangay();
        }
    }
</script>
