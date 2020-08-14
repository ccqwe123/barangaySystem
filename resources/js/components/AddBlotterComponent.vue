
<template>
    <div class="container-fluid">
        <section class="content my-3">
        <form @submit.prevent="createBlotter()">
          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Blotter Informationas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="inputStatus">Desposition</label>
                                <select class="form-control custom-select" name="desposition" v-model="form.desposition" :class="{ 'is-invalid': form.errors.has('desposition') }">
                                  <option selected disabled value="">Select one</option>
                                  <option value="complaints">Complaints</option>
                                  <option value="for_record">For Record</option>
                                  <option value="for_follow_up">For Record & Followed By</option>
                                </select>
                                <has-error :form="form" field="desposition"></has-error>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Assigned to</label>
                                <select class="form-control" name="lupon" v-model="form.lupon" :class="{ 'is-invalid': form.errors.has('lupon') }" id="lupon" >
                                    <option value="chairman">Chairman</option>
                                    <option value="secretary">Secretary</option>
                                    <option value="lupon">Lupon Tagapamayapa</option>
                                </select>
                                <has-error :form="form" field="lupon"></has-error>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date & Time Of Incident</label>
                                            <input type="datetime-local" name="date_incident" v-model="form.date_incident" :class="{ 'is-invalid': form.errors.has('date_incident') }" class="form-control">
                                            <has-error :form="form" field="date_incident"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date & Time Reported</label>
                                            <input type="datetime-local" name="date_reported" v-model="form.date_reported" :class="{ 'is-invalid': form.errors.has('date_reported') }" class="form-control">
                                            <has-error :form="form" field="date_reported"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Incident Address</label>
                                            <input type="text" name="incident_address" v-model="form.incident_address" :class="{ 'is-invalid': form.errors.has('incident_address') }" class="form-control">
                                            <has-error :form="form" field="incident_address"></has-error>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6" style="">
              <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title">Complainant/Victim Information</h3>
                </div>
                <div class="card-body complainant-table">
                    <table class="table table-bordered">
                      <thead>                  
                        <tr>
                          <th>Name</th>
                          <th>Address</th>
                          <th>Telephone No.</th>
                          <th>Age</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(content, index) in form.complainants">
                          <td><input type="text" readonly name="complainant_name[]" class="form-control tbl-border-0" v-model="content.complainant_name"></td>
                          <td><input type="text" readonly name="address[]" class="form-control tbl-border-0" v-model="content.address"></td>
                          <td><input type="text" readonly name="telephone[]" class="form-control tbl-border-0" v-model="content.telephone"></td>
                          <td><input type="text" readonly name="age[]" class="form-control tbl-border-0" v-model="content.age"></td>
                          <td>
                              <a href="#" class="btn-danger btn-sm" @click.prevent="deleteComplainantRow(index)">Delete</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add Complainant/Victim</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="complainant_name" v-model="form.complainant_names" class="form-control" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="complainant_address" class="form-control" v-model="form.complainant_address" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label>Contact Number</label>
                                <input type="number" name="complainant_telephone" class="form-control" v-model="form.complainant_telephone" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="complainant_age" class="form-control" v-model="form.complainant_age" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    Total Complainant: {{complainant_checker}}
                    <a href="#" class="btn btn-primary float-right px-5" @click.prevent="AddComplainant">Add</a>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title">Respondent/Suspect Information</h3>
                </div>
                <div class="card-body complainant-table">
                    <table class="table table-bordered">
                      <thead>                  
                        <tr>
                          <th>Name</th>
                          <th>Address</th>
                          <th>Telephone No.</th>
                          <th>Age</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(content, index) in form.respondents">
                          <td><input type="text" readonly name="respondent_name[]" class="form-control tbl-border-0" v-model="content.respondent_name"></td>
                          <td><input type="text" readonly name="respondent_address[]" class="form-control tbl-border-0" v-model="content.respondent_address"></td>
                          <td><input type="text" readonly name="respondent_telephone[]" class="form-control tbl-border-0" v-model="content.respondent_telephone"></td>
                          <td><input type="text" readonly name="respondent_age[]" class="form-control tbl-border-0" v-model="content.respondent_age"></td>
                          <td>
                              <a href="#" class="btn-danger btn-sm" @click.prevent="deleteRespondentRow(index)">Delete</a>
                              <!-- <button class="btn-danger btn-sm" @click="deleteRespondentRow(index)">Delete</button> -->
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add Respondent/Suspect</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="respondent_name" class="form-control" v-model="form.respondent_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="respondent_address" class="form-control" v-model="form.respondent_address" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label>Contact Number</label>
                                <input type="number" name="respondent_telephone" class="form-control" v-model="form.respondent_telephone" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="respondent_age" class="form-control" v-model="form.respondent_age" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary float-right px-5" @click="AddRespondent">Add</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-secondary">
                <div class="card-header">
                  <h3 class="card-title">Witness Information</h3>
                </div>
                <div class="card-body complainant-table">
                    <table class="table table-bordered">
                      <thead>                  
                        <tr>
                          <th>Name</th>
                          <th>Address</th>
                          <th>Telephone No.</th>
                          <th>Age</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                         <tr v-for="(content, index) in form.witnesses">
                          <td><input type="text" readonly name="witness_name[]" class="form-control tbl-border-0" v-model="content.witness_name"></td>
                          <td><input type="text" readonly name="witness_address[]" class="form-control tbl-border-0" v-model="content.witness_address"></td>
                          <td><input type="text" readonly name="witness_telephone[]" class="form-control tbl-border-0" v-model="content.witness_telephone"></td>
                          <td><input type="text" readonly name="witness_age[]" class="form-control tbl-border-0" v-model="content.witness_age"></td>
                          <td>
                              <a href="#" type="button" class="btn-danger btn-sm" @click.prevent="deleteWitnessRow(index)">Delete</a>
                              <!-- <button type="button" class="btn-danger btn-sm" @click="deleteWitnessRow(index)">Delete</button> -->
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add Witness</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="witness_name" class="form-control" v-model="form.witness_name" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="witness_address" class="form-control" v-model="form.witness_address" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group">
                                <label>Contact Number</label>
                                <input type="number" name="witness_telephone" class="form-control" v-model="form.witness_telephone" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="witness_age" class="form-control" v-model="form.witness_age" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary float-right px-5" @click="AddWitness">Add</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Submit Blotter</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type of Crime</label>
                                    <v-select :options="crime_type" placeholder="select" v-model="res.id" label="name" :clearable="false" @input="setSelected"></v-select>
                                    <input class="form-control" hidden :class="{ 'is-invalid': form.errors.has('crime_type') }" type="text" v-model="form.crime_type_id">
                                    <has-error :form="form" field="crime_type"></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Attachment/Photos</label>
                                    <input type="file" name="blotter_attachment" class="form-control" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                 <div class="form-group">
                                    <label>Case Summary</label>
                                    <textarea class="form-control" rows="10" v-model="form.case_summary" :class="{ 'is-invalid': form.errors.has('case_summary') }"></textarea>
                                    <has-error :form="form" field="case_summary"></has-error>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <a href="#" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-success float-right" :disabled='submitform'>Submit Blotter</button>
            </div>
          </div>
        </form>
        </section>
    </div>
</template>
<style type="text/css" scoped>
    .card-header
    {
        padding: .25rem 1.25rem;
    }
    .card-title
    {
        font-size: 1rem;
        margin: 0.4em 0rem;
    }
    .well 
    {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }
    .complainant-table
    {
        height: 327px;
        overflow-y: scroll;
    }
    .form-control[readonly] {
    background-color: #ffffff;
    }
    .form-control.tbl-border-0
    {
        border: 1px solid #ffffff00 !important;
    }
</style>
<script>
    export default {
        data () {
            return {
                loadingData : true,
                crimes: {},
                crime_type: [],
                res:{
                    id: '',
                },
               complainant_checker: 0,
               submitform: true,
              form: new Form({
               id: '',
               respondents: [],
               complainants: [],
               witnesses: [],
               crime_type_id: '',
               date_incident: '',
               date_reported: '',
               incident_address: '',
               desposition: '',
               lupon: '',
               case_summary: '',
              })
            }
          },
          methods: {
            AddWitness() {
                 if(this.form.witness_name == '')
                {
                     toast.fire({
                          icon: 'warning',
                          title: 'Witness Name is Required!'
                        })
                }else if(this.form.witness_address == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Witness Address is Required!'
                        })
                }else if(this.form.witness_telephone == ''){
                    toast.fire({
                          icon: 'warning',
                          title: 'Witness Contact Number is Required!'
                        })
                }else if(this.form.witness_age == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Witness Age is Required!'
                        })
                }else{
                    this.form.witnesses.push({
                        witness_name: this.form.witness_name,
                        witness_address: this.form.witness_address,
                        witness_telephone: this.form.witness_telephone,
                        witness_age: this.form.witness_age,
                    });
                        this.form.witness_name = '';
                        this.form.witness_address = '';
                        this.form.witness_telephone = '';
                        this.form.witness_age = '';
                }
            },
            deleteWitnessRow(index)
            {
                this.form.witnesses.splice(index,1)
            },
            AddRespondent() {
                 if(this.form.respondent_name == '')
                {
                     toast.fire({
                          icon: 'warning',
                          title: 'Respondent Name is Required!'
                        })
                }else if(this.form.respondent_address == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Respondent Address is Required!'
                        })
                }else if(this.form.respondent_telephone == ''){
                    toast.fire({
                          icon: 'warning',
                          title: 'Respondent Contact Number is Required!'
                        })
                }else if(this.form.respondent_age == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Respondent Age is Required!'
                        })
                }else{
                    this.form.respondents.push({
                        respondent_name: this.form.respondent_name,
                        respondent_address: this.form.respondent_address,
                        respondent_telephone: this.form.respondent_telephone,
                        respondent_age: this.form.respondent_age,
                    });
                        this.form.respondent_name = '';
                        this.form.respondent_address = '';
                        this.form.respondent_telephone = '';
                        this.form.respondent_age = '';
                }
            },
            deleteRespondentRow(index)
            {
                this.form.respondents.splice(index,1)
            },
            AddComplainant() {
                if(this.form.complainant_names == '')
                {
                     toast.fire({
                          icon: 'warning',
                          title: 'Complainant Name is Required!'
                        })
                }else if(this.form.complainant_address == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Complainant Address is Required!'
                        })
                }else if(this.form.complainant_telephone == ''){
                    toast.fire({
                          icon: 'warning',
                          title: 'Complainant Contact Number is Required!'
                        })
                }else if(this.form.complainant_age == '')
                {
                    toast.fire({
                          icon: 'warning',
                          title: 'Complainant Age is Required!'
                        })
                }else{
                    this.form.complainants.push({
                        complainant_name: this.form.complainant_names,
                        address: this.form.complainant_address,
                        telephone: this.form.complainant_telephone,
                        age: this.form.complainant_age,
                    });
                        this.form.complainant_names = '';
                        this.form.complainant_address = '';
                        this.form.complainant_telephone = '';
                        this.form.complainant_age = '';
                    this.complainant_checker +=1;
                    this.submitform = false;
                }
            },
            deleteComplainantRow(index)
            {
                this.form.complainants.splice(index,1)
                this.complainant_checker -=1;
                if(this.complainant_checker>0)
                {
                  this.submitform = false;
                }else{
                  this.submitform = true;
                }
            },
            createBlotter(){
                this.form.post('/api/blotter')
                    .then((response) => {
                         toast.fire({
                          icon: 'success',
                          title: 'New Blotter successfully Added'
                        })
                         this.$router.push('/blotter')
                    })
                    .catch(error => {
                    });
            },
            fetchCrimeType(){
                axios.get('/api/fetch/crime_type')
                .then(function(response){
                    this.crime_type = response.data;
                }.bind(this));
            },
            setSelected(value){
                this.form.crime_type_id = value.id;
            },
          },

        created() {
            this.fetchCrimeType();
        }
    }
</script>
