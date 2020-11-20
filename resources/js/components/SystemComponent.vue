<template>
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Settings</h1>
                     </div>
                </div>
            </div>
    </section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">System</a></li>
                            <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Certificate</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Activity Log <span class="badge badge-success">Soon</span></a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                            <form @submit.prevent="updateBarangayInfo()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label>Barangay Name</label>
                                            <input class="form-control m-input" type="text" readonly v-model="form.barangay_id">
                                            <has-error :form="form" field="barangay_id"></has-error>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label>Barangay Contact No.</label>
                                           <input type="text" name="barangay_contact" v-model="form.barangay_contact" class="form-control" placeholder="09XXXXXXXXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label>City</label>
                                           <input type="text" name="barangay_city" v-model="form.barangay_city" class="form-control" placeholder="ex. City of Manila">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label>Zone/Building</label>
                                           <input type="text" name="barangay_zone" v-model="form.barnagay_zone" class="form-control" placeholder="(Optional)" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <label>Barangay Address</label>
                                           <textarea class="form-control" name="barangay_address" v-model="form.barangay_address"></textarea>
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                           <label>Software Name</label>
                                           <input type="text" name="software_name" v-model="form.software_name" class="form-control" placeholder="e.g BRIS">
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center" v-if="!loadingDescription">
                                            <div class="spinner-border" role="status">
                                              <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div class="form-group" v-else>
                                           <label>Software Description</label>
                                           <textarea id="description" name="software_description" v-model="form.software_description" v-html="this.form.software_description"></textarea>
                                        </div>
                                    </div>
                                </div>
                               <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="tab-pane" id="timeline" style="margin:30px;">
                                <form @submit.prevent="updateBarangayLogo()">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group text-center" style="width: 250px; height: 250px; margin: 0 auto;">
                                            <img src="/images/certificate/default_logo1.png" :src="getBrgyLogo1()" @click="triggerLogo1" class="img-fluid rounded-circle" style="border:1px solid; height: 100%; width: 100%; object-fit:cover;">
                                            <div class="img-css" style="max-width: 250px;">
                                                <input type="file" name="" class="form-control" @change="onBarangayLogo1Change" ref="brgyLogo1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group text-center" style="width: 250px; height: 250px; margin: 0 auto;">
                                            <img src="/images/certificate/default_logo1.png" :src="getBrgyLogo2()" @click="triggerLogo2" class="img-fluid rounded-circle" style="border:1px solid; height: 100%; width: 100%; object-fit:cover;">
                                            <div class="img-css" style="max-width: 250px;">
                                                <input type="file" name="" class="form-control" @change="onBarangayLogo2Change" ref="brgyLogo2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style type="text/css">
    .note-toolbar .note-dropdown-menu.note-check a i {
            color: black;
            visibility: unset;
        }
        .note-icon-menu-check::before {
            content: "";
        }
        .dropdown-item
        {
            padding: 0px 5px;
        }
        .note-editor.note-frame .note-status-output
        {
            height: unset;
        }
        .note-placeholder{
            color: gray;
            display:inline-block;
            font-weight: bold;
            font-size:20px;
            border-left: solid 2px #55c4e8;
            margin: 30px;
            line-height: 30px;
        }
        .img-css {
            max-width: 250px;
            justify-content: center;
            align-items: center;
            display: flex;
            margin: 15px auto;
        }
</style>
<script>
    export default {
        data () {
            return {
                loadingDescription : true,
                barangay_id: [],
                bar:{
                    id: '',
                },
              form: new Form({
                barangay_id: '',
                // barangay_name:'',
                barangay_contact:'',
                barangay_city:'',
                barnagay_zone: '',
                barangay_address:'',
                software_name: '',
                software_description: null,
                barangay_logo1: '',
                barangay_logo2: '',
              })
            }
          },
          methods: {
            triggerLogo1() {
                this.$refs.brgyLogo1.click()
            },
            triggerLogo2() {
                this.$refs.brgyLogo2.click()
            },
            getBrgyLogo1() {
                let photo1 = (this.form.barangay_logo1.length > 200) ? this.form.barangay_logo1 : "images/certificate/"+ this.form.barangay_logo1 ;
                return photo1;
            },
            getBrgyLogo2() {
                let photo2 = (this.form.barangay_logo2.length > 200) ? this.form.barangay_logo2 : "images/certificate/"+ this.form.barangay_logo2 ;
                return photo2;
            },
            //for barangay logo
            onBarangayLogo1Change(e)
             {
              let file = e.target.files[0];
              let reader = new FileReader();
              reader.onloadend = (file) => {
                this.form.barangay_logo1 = reader.result;
              }
              reader.readAsDataURL(file);
             },
            onBarangayLogo2Change(e)
             {
              let file = e.target.files[0];
              let reader = new FileReader();
              reader.onloadend = (file) => {
                this.form.barangay_logo2 = reader.result;
              }
              reader.readAsDataURL(file);
             },
            populateBrgyLogo() {
                let vm = this.form;
                axios.get('/api/system/getlogo').then(function(response){
                    vm.barangay_logo1 = response.data[0].barangay_logo1;
                    vm.barangay_logo2 = response.data[0].barangay_logo2;
                    // console.log(response.data[0].barangay_logo1);
                }.bind(this));
            },
            populateData() {
                let vm = this.form;
                let rampage = null;
                axios.get('/api/system-info').then(function(response){
                    vm.barangay_id = response.data.user_barangay.barangay_name;
                    for (var i = 0; i < response.data.varpath.length; i++) {
                        if(response.data.varpath[i].name == 'barangay_address') {
                            vm.barangay_address = response.data.varpath[i].value;
                        } else if(response.data.varpath[i].name == 'barangay_city') {
                            vm.barangay_city = response.data.varpath[i].value;
                        } else if(response.data.varpath[i].name == 'barnagay_zone') {
                            vm.barnagay_zone = response.data.varpath[i].value;
                        } else if(response.data.varpath[i].name == 'software_name') {
                            vm.software_name = response.data.varpath[i].value;
                        }  else if(response.data.varpath[i].name == 'barangay_contact') {
                            vm.barangay_contact = response.data.varpath[i].value;
                        } 
                    }
                }.bind(this));
            },
            setSelected(value) {
                this.form.barangay_id = value.barangay_name
            },
            updateBarangayLogo() {
                this.form.post('/api/system-info/logo')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'Barangay Information successfully Updated!'
                    })
                })
                .catch(error => {
                    
                });
            },
            updateBarangayInfo() {
                this.form.post('/api/system-info')
                .then((response) => {
                     toast.fire({
                      icon: 'success',
                      title: 'Barangay Information successfully Updated!'
                    })
                    this.bar.id = '';
                    this.form.barangay_id = '';
                    this.populateData();
                })
                .catch(error => {
                    
                });
            }
        },
        created() {
            this.populateBrgyLogo();
            this.populateData();
            $('.modal').modal('hide');
            console.clear();
        },
        mounted() {
            var vs = this.form;
            $(document).ready(function() {
              $('#description').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['picture']],
                    ['view', ['codeview', 'help']],
                  ],
                  tabDisable: true,
                  placeholder: 'System Description (optional)',
                  height: 150,
                  callbacks: {
                    onChange: function(contents, $editable) {
                        vs.software_description = contents
                        console.log(contents);
                    },
                    onInit: function() {
                      vs.software_description = "<h1>test</h1>";
                    }
                  }
                  // callbacks: {
                  //   onChange: function(contents, $editable) {
                  //       contents = contents.replace(/<\/?[^>]+(>|$)/g, "");
                  //       vs.form.software_description = contents
                  //       var regex = /\B{(\w*)}$/;
                  //     if (regex.test(contents)) {
                  //       console.log(contents);
                  //     }

                  //   }
                  // }
              });
            });
        }
    }
</script>
