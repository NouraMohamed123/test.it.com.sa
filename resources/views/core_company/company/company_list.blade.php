@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Company_List') }}</h1>
    <ul>
        <li><a href="/core/company">{{ __('translate.Company') }}</a></li>
        <li>{{ __('translate.Title') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Company_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('company_add')
                <a class="btn btn-primary btn-md m-1" @click="New_Company"><i class="i-Add text-white mr-2"></i>
                    {{ __('translate.Create') }}</a>
                @endcan
                @can('company_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="company_list_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Company_Name') }}</th>
                                <th>{{ __('translate.Company_Email') }}</th>
                                <th>{{ __('translate.Company_Phone') }}</th>
                                <th>{{ __('translate.Status') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td @click="selected_row( {{ $company->id}})"></td>
                                <td>{{$company->name}}</td>
                                <td>{{$company->email}}</td>
                                <td>{{$company->phone}}</td>
                                <td>{{$company->status == 'active' ? 'نشط' : 'غير نشط'}}</td>
                                <td>
                                    @can('company_edit')
                                    <a @click="Edit_Company( {{ $company}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan
                                    @can('company_delete')
                                    <a @click="Remove_Company( {{ $company->id}})"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Delete">
                                        <i class="i-Close-Window"></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add & Edit company -->
        <div class="modal fade" id="Company_Modal" tabindex="-1" role="dialog" aria-labelledby="Company_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="editmode?Update_Company():Create_Company()">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name" class="ul-form__label">{{ __('translate.Company_Name') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="company.name" class="form-control" name="name" id="name"
                                        placeholder="{{ __('translate.Enter_Company_Name') }}">
                                    <span class="error" v-if="errors && errors.name">
                                        @{{ errors.name[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputEmail4"
                                        class="ul-form__label">{{ __('translate.Company_Email') }}</label>
                                    <input type="email" class="form-control" id="inputtext4"
                                        placeholder="{{ __('translate.Enter_email_address') }}" v-model="company.email">
                                    <span class="error" v-if="errors && errors.email">
                                        @{{ errors.email[0] }}
                                    </span>
                                </div>



                                <div class="col-md-6">
                                    <label for="phone"
                                        class="ul-form__label">{{ __('translate.Company_Phone') }}</label>
                                    <input type="text" class="form-control" id="phone"
                                        placeholder="{{ __('translate.Enter_Company_Phone') }}" v-model="company.phone">
                                    <span class="error" v-if="errors && errors.phone">
                                        @{{ errors.phone[0] }}
                                    </span>
                                </div>
                                
                                
                                <div class="col-md-6">
                                    <label for="tax_number"
                                        class="ul-form__label">{{ __('translate.Tax_number') }}</label>
                                    <input type="number" class="form-control" id="tax_number"
                                        placeholder="{{ __('translate.Enter_Tax_number') }}" v-model="company.tax_number">
                                    <span class="error" v-if="errors && errors.tax_number">
                                        @{{ errors.tax_number[0] }}
                                    </span>
                                </div>
                                
                                
                                <div class="col-md-6">
                                    <label for="Logo" class="ul-form__label">{{ __('translate.Logo') }}</label>
                                    <input name="Logo" @change="changeLogo" type="file" class="form-control"
                                        id="Logo">
                                    <span class="error" v-if="errors && errors.logo">
                                        @{{ errors.logo[0] }}
                                    </span>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="Tax_number_photo" class="ul-form__label">{{ __('translate.Tax_number_photo') }}</label>
                                    <input name="Tax_number_photo" @change="changeTaxNumberPhoto" type="file" class="form-control"
                                        id="Tax_number_photo">
                                    <span class="error" v-if="errors && errors.tax_number_photo">
                                        @{{ errors.tax_number_photo[0] }}
                                    </span>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="Job_classification" class="ul-form__label">{{ __('translate.Job_classification') }}</label>
                                    <input name="Job_classification" @change="changeJobClassification" type="file" class="form-control"
                                        id="Job_classification">
                                    <span class="error" v-if="errors && errors.job_classification">
                                        @{{ errors.job_classification[0] }}
                                    </span>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="Trade_register" class="ul-form__label">{{ __('translate.Trade_register') }}</label>
                                    <input name="Trade_register" @change="changeTradeRegister" type="file" class="form-control"
                                        id="Trade_register">
                                    <span class="error" v-if="errors && errors.trade_register">
                                        @{{ errors.trade_register[0] }}
                                    </span>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="ul-form__label">{{ __('translate.Status') }}</label>
                                    <div style="display: flex; gap: 20px">
                                    <div style="display: flex; align-items: center; gap: 2px;">
                                        <input type="radio" v-model="company.status" value="active" />
                                        <p class="mb-0">نشط</p>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 2px;">
                                        <input type="radio" v-model="company.status" value="inactive" />
                                        <p class="mb-0">غير نشط</p>
                                    </div>
                                    </div>
                                    <span class="error" v-if="errors && errors.phone">
                                        @{{ errors.phone[0] }}
                                    </span>
                                </div>
                                
                            </div>

                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
                                        {{ __('translate.Submit') }}
                                    </button>
                                    <div v-once class="typo__p" v-if="SubmitProcessing">
                                        <div class="spinner spinner-primary mt-3"></div>
                                    </div>
                                </div>
                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>


<script>
    var app = new Vue({
        el: '#section_Company_list',
        data: {
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            companies:[],
            company: {
                name: "",
                email:"",
                country:"",
                phone:"",
            }, 
        },
       
        methods: {
            changeLogo(e) {
                let file = e.target.files[0];
                this.company.logo = file;

        },
            changeTaxNumberPhoto(e) {
                let file = e.target.files[0];
                this.company.Tax_number_photo = file;

        },
            changeJobClassification(e) {
                let file = e.target.files[0];
                this.company.Job_classification = file;

        },
            changeTradeRegister(e) {
                let file = e.target.files[0];
                this.company.Trade_register = file;

        },
            Selected_Status(value) {
                if (value === null) {
                    this.user.status = "1";
                }
            },

              //---- Event selected_row
              selected_row(id) {
                //in here you can check what ever condition  before append to array.
                if(this.selectedIds.includes(id)){
                    const index = this.selectedIds.indexOf(id);
                    this.selectedIds.splice(index, 1);
                }else{
                    this.selectedIds.push(id)
                }
            },

            //------------------------------ Show Modal (Create Company) -------------------------------\\
            New_Company() {
                this.reset_Form();
                this.editmode = false;
                $('#Company_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update Company) -------------------------------\\
            Edit_Company(company) {
                this.editmode = true;
                this.reset_Form();
                this.company = company;
                $('#Company_Modal').modal('show');
            },


            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.company = {
                    id: "",
                    name: "",
                    email:"",
                    country:"",
                    phone:"",
                };
                this.errors = {};
            },
            
            //------------------------ Create company ---------------------------\\
            Create_Company() {
                var self = this;
                self.SubmitProcessing = true;
                
                                
                if (!self.data || !(self.data instanceof FormData)) {
                    self.data = new FormData();
                }
                
                
                self.data.append("name", self.company.name);
                self.data.append("logo", self.company.logo);
                self.data.append("status", self.company.status);
                self.data.append("tax_number", self.company.tax_number);
                self.data.append("tax_number_photo", self.company.tax_number_photo);
                self.data.append("job_classification", self.company.job_classification);
                self.data.append("trade_register", self.company.trade_register);
                self.data.append("email", self.company.email);
                self.data.append("phone", self.company.phone);
                
                axios.post("/core/company", self.data).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/company'; 
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors = {};
                })
                .catch(error => {
                    self.SubmitProcessing = false;
                    if (error.response.status == 422) {
                        self.errors = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },

           //----------------------- Update Company ---------------------------\\
            Update_Company() {
                var self = this;
                self.SubmitProcessing = true;
                
                axios.put("/core/company/" + self.company.id, {
                    name: self.company.name,
                    logo: self.company.logo,
                    status:  self.company.status,
                    tax_number: self.company.tax_number,
                    tax_number_photo: self.company.tax_number_photo,
                    job_classification: self.company.job_classification,
                    trade_register: self.company.trade_register,
                    email: self.company.email,
                    phone: self.company.phone,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/company'; 
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response.status == 422) {
                            self.errors = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

             //--------------------------------- Remove Company ---------------------------\\
            Remove_Company(id) {

                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                            .delete("/core/company/" + id)
                            .then(() => {
                                window.location.href = '/core/company'; 
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
                },

            //--------------------------------- delete_selected ---------------------------\\
            delete_selected() {
                var self = this;
                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                        .post("/core/company/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/core/company'; 
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
            },





           
        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    })

</script>

<script type="text/javascript">
    $(function () {
      "use strict";

        $('#company_list_table').DataTable( {
            "processing": true, // for show progress bar
            select: {
                style: 'multi',
                selector: '.select-checkbox',
                items: 'row',
            },
           
            columnDefs: [
                
                {
                    targets: 0,
                    className: 'select-checkbox'
                },
                {
                    targets: 0,
                    orderable: false
                }
            ],
        
            dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
            oLanguage:
                { 
                sLengthMenu: "_MENU_", 
                sSearch: '',
                sSearchPlaceholder: "Search..."
            },
            buttons: [
                {
                    extend: 'collection',
                    text: 'EXPORT',
                    buttons: [
                        'csv','excel', 'pdf', 'print'
                    ]
                }]
        });

    });
</script>
@endsection