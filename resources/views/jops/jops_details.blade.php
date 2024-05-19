@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrumb">
    <h1>{{ __('translate.Employee_Details') }}</h1>
    <ul>
        <li><a href="/employees">{{ __('translate.Employee_List') }}</a></li>
        <li>{{ __('translate.Employee_Details') }}</li>
    </ul>
</div>
<div class="separator-breadcrumb border-top"></div>

<!-- content goes here -->

<section class="ul-product-detail__tab" id="section_details_employee">
    <div class="row">
        <div class="col-lg-12 col-md-12 mt-4">
            <div class="card mt-2 mb-4 ">
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active show" id="nav-basic-tab" data-toggle="tab"
                                href="#nav-basic" role="tab" aria-controls="nav-home"
                                aria-selected="true">{{ __('translate.Basic_Information') }}</a>

                        </div>
                    </nav>
                    <div class="tab-content ul-tab__content p-3" id="nav-tabContent">
                        {{-- Basic Information --}}
                        <div class="tab-pane fade active show" id="nav-basic" role="tabpanel"
                            aria-labelledby="nav-basic-tab">

                            <div class="row">
                                <!--begin::form-->
                                <form @submit.prevent="Update_Employee_Basic()">
                                    <div class="form-row ">
                                        <div class="form-group col-md-6">
                                            <label for="job_title" class="ul-form__label">{{ __('translate.job_title') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="text" class="form-control" id="job_title"
                                                placeholder="{{ __('translate.Enter_job_title') }}" v-model="jop.job_title">
                                            <span class="error" v-if="errors && errors.job_title">
                                                @{{ errors.job_title[0] }}
                                            </span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="required_candidates"
                                                class="ul-form__label">{{ __('translate.required_candidates') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="number" class="form-control" id="required_candidates"
                                                placeholder="{{ __('translate.Enter_required_candidates') }}"
                                                v-model="jop.required_candidates">
                                            <span class="error" v-if="errors && errors.required_candidates">
                                                @{{ errors.required_candidates[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.work_area') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_work_area') }}" v-model="jop.work_area"
                                                :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'الوسطى', value: 'Central' },
                                                    { label: 'الشمالية', value: 'Northern' },
                                                    { label: 'الجنوبية', value: 'Southern' },
                                                    { label: 'الشرقية', value: 'Eastern' },
                                                    { label: 'الغربية', value: 'Western' },

                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.work_area">
                                                @{{ errors.work_area[0] }}
                                            </span>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city" class="ul-form__label">{{ __('translate.city') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="text" class="form-control" id="city"
                                                placeholder="{{ __('translate.Enter_city') }}" v-model="jop.city">
                                            <span class="error" v-if="errors && errors.city">
                                                @{{ errors.city[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.academic_qualification') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_academic_qualification') }}"
                                                v-model="jop.academic_qualification" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'ابتداثي', value: 'Primary' },
                                                    { label: 'Intermediate', value: 'Intermediate' },
                                                    { label: 'Diploma', value: 'Diploma' },
                                                    { label: 'Bachelor', value: 'Bachelor' },
                                                    { label: 'Master', value: 'Master' },
                                                    { label: 'Ph.D', value: 'Ph.D' },
                                                    { label: 'Student', value: 'Student' }
                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.academic_qualification">
                                                @{{ errors.academic_qualification[0] }}
                                            </span>
                                            <input type="text" class="form-control" id="specialization"
                                                placeholder="{{ __('translate.Enter_specialization') }}"
                                                v-model="jop.specialization">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.language_level') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_language_level') }}"
                                                v-model="jop.language_level" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'Beginner', value: 'Beginner' },
                                                    { label: 'Intermediate', value: 'Intermediate' },
                                                    { label: 'Advanced', value: 'Advanced' }
                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.academic_qualification">
                                                @{{ errors.language_level[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.nationality') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_nationality') }}"
                                                v-model="jop.nationality" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'Beginner', value: 'Beginner' },
                                                    { label: 'Intermediate', value: 'Intermediate' },
                                                    { label: 'Advanced', value: 'Advanced' }
                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.academic_qualification">
                                                @{{ errors.nationality[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.disabilities_allowed') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_disabilities_allowed') }}"
                                                v-model="jop.disabilities_allowed" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'نعم', value: 'Yes' },
                                                    { label: 'لا', value: 'No' },

                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.disabilities_allowed">
                                                @{{ errors.disabilities_allowed[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="disability_type" class="ul-form__label">{{ __('translate.disability_type') }}
                                                <span class="field_required">*</span></label>
                                            <input type="string" class="form-control" id="disability_type"
                                                placeholder="{{ __('translate.Enter_disability_type') }}"
                                                v-model="jop.disability_type">
                                            <span class="error" v-if="errors && errors.disability_type">
                                                @{{ errors.disability_type[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="required_age" class="ul-form__label">{{ __('translate.required_age') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="number" class="form-control" id="required_age"
                                                placeholder="{{ __('translate.Enter_required_age') }}"
                                                v-model="jop.required_age">
                                            <span class="error" v-if="errors && errors.required_age">
                                                @{{ errors.required_age[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.work_type') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_work_type') }}"
                                                v-model="jop.work_type" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'عن بعد', value: 'remotly' },
                                                    { label: 'حضوري', value: 'onSite' },
                                                    { label: 'كلاهما', value: 'hybrid' },

                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.work_type">
                                                @{{ errors.work_type[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.Gender') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select @input="Selected_Gender" placeholder="{{ __('translate.Choose_Gender') }}"
                                                v-model="jop.gender" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'ذكر', value: 'male' },
                                                    { label: 'أنثى', value: 'female' },
                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.gender">
                                                @{{ errors.gender[0] }}
                                            </span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="ul-form__label">{{ __('translate.working_hours') }} <span
                                                    class="field_required">*</span></label>
                                            <v-select placeholder="{{ __('translate.Choose_working_hours') }}"
                                                v-model="jop.working_hours" :reduce="(option) => option.value"
                                                :options="[
                                                    { label: 'كامل', value: 'Full' },
                                                    { label: 'جزئي', value: 'Part' },
                                                ]">
                                            </v-select>

                                            <span class="error" v-if="errors && errors.working_hours">
                                                @{{ errors.working_hours[0] }}
                                            </span>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="basic_salary" class="ul-form__label">{{ __('translate.basic_salary') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="number" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.basic_salary') }}" v-model="jop.basic_salary">
                                            <span class="error" v-if="errors && errors.basic_salary">
                                                @{{ errors.basic_salary[0] }}
                                            </span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="housing_allowance"
                                                class="ul-form__label">{{ __('translate.housing_allowance') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="string" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.housing_allowance') }}"
                                                v-model="jop.housing_allowance">
                                            <span class="error" v-if="errors && errors.housing_allowance">
                                                @{{ errors.housing_allowance[0] }}
                                            </span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="transportation_allowance"
                                                class="ul-form__label">{{ __('translate.transportation_allowance') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="string" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.transportation_allowance') }}"
                                                v-model="jop.transportation_allowance">
                                            <span class="error" v-if="errors && errors.transportation_allowance">
                                                @{{ errors.transportation_allowance[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="other_allowances"
                                                class="ul-form__label">{{ __('translate.other_allowances') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="string" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.other_allowances') }}"
                                                v-model="jop.other_allowances">
                                            <span class="error" v-if="errors && errors.other_allowances">
                                                @{{ errors.other_allowances[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="monthly_attendance_days"
                                                class="ul-form__label">{{ __('translate.monthly_attendance_days') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="number" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.monthly_attendance_days') }}"
                                                v-model="jop.monthly_attendance_days">
                                            <span class="error" v-if="errors && errors.monthly_attendance_days">
                                                @{{ errors.monthly_attendance_days[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="weekly_rest_days"
                                                class="ul-form__label">{{ __('translate.weekly_rest_days') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="number" class="form-control" id="inputtext4"
                                                placeholder="{{ __('translate.weekly_rest_days') }}"
                                                v-model="jop.weekly_rest_days">
                                            <span class="error" v-if="errors && errors.weekly_rest_days">
                                                @{{ errors.weekly_rest_days[0] }}
                                            </span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="job_description" class="ul-form__label">{{ __('translate.job_description') }}
                                                <span class="field_required">*</span></label>
                                            <input type="text" class="form-control" id="job_description"
                                                placeholder="{{ __('translate.Enter_job_description') }}"
                                                v-model="jop.job_description">
                                            <span class="error" v-if="errors && errors.job_description">
                                                @{{ errors.job_description[0] }}
                                            </span>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="job_requirements"
                                                class="ul-form__label">{{ __('translate.job_requirements') }} <span
                                                    class="field_required">*</span></label>
                                            <input type="text" class="form-control" id="job_requirements"
                                                placeholder="{{ __('translate.Enter_job_requirements') }}"
                                                v-model="jop.job_requirements">
                                            <span class="error" v-if="errors && errors.job_requirements">
                                                @{{ errors.job_requirements[0] }}
                                            </span>
                                        </div>

                                    </div>


                                </form>
                                <!-- end::form -->
                            </div>
                        </div>






                    </div>
                </div>
            </div>
        </div>
</section>


@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect)
    var app = new Vue({
    el: '#section_details_employee',
    components: {
        vuejsDatepicker
    },
    data: {
        data: new FormData(),
        SubmitProcessing:false,
        editmode: false,
        errors:[],

        Submit_Processing_Bank:false,
        edit_mode_account:false,
        errors_bank:[],

        Submit_Processing_Experience:false,
        edit_mode_experience:false,
        errors_experience:[],

        Submit_Processing_social:false,
        errors_social:[],

        Submit_Processing_document:false,
        edit_mode_document: false,
        errors_document:[],

        Submit_Processing_status_task:false,
        errors_task:[],
        jop : @json($jop),

        experience: {
                title: "",
                company_name:"",
                employment_type:"",
                location:"",
                start_date:"",
                end_date:"",
                description:"",
            },

        task: {
            status: "",
        },

        account_bank: {
            bank_name: "",
            bank_branch:"",
            account_no:"",
            note:"",
        },

        document: {
            title: "",
            description:"",
            attachment:"",
        },
    },


    methods: {


        Selected_Status(value) {
            if (value === null) {
                this.task.status = "";
            }
        },

        Change_status_task(task) {
            this.task = task;
            $('#task_status_Modal').modal('show');
        },

          //------------------------ Update Task Status---------------------------\\
          Update_Task_status() {
            var self = this;
            self.Submit_Processing_status_task = true;
            axios.put("/update_task_status/" + self.task.id, {
                status: self.task.status,
            }).then(response => {
                    self.Submit_Processing_status_task = false;
                    window.location.href = '/employees/'+ self.employee.id;
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    self.errors_task = {};
            })
            .catch(error => {
                self.Submit_Processing_status_task = false;
                if (error.response.status == 422) {
                    self.errors_task = error.response.data.errors;
                }
                toastr.error('{{ __('translate.There_was_something_wronge') }}');
            });
        },



        //------------------------ Basic Information ---------------------------------------------------------------------------------------------\\

        formatDate(d){
            var m1 = d.getMonth()+1;
            var m2 = m1 < 10 ? '0' + m1 : m1;
            var d1 = d.getDate();
            var d2 = d1 < 10 ? '0' + d1 : d1;
            return [d.getFullYear(), m2, d2].join('-');
        },

        Selected_Company(value) {
            if (value === null) {
                this.employee.company_id = "";
                this.employee.department_id = "";
                this.employee.designation_id = "";
                this.employee.office_shift_id = "";
            }
            this.departments = [];
            this.designations = [];
            this.employee.department_id = "";
            this.employee.designation_id = "";
            this.employee.office_shift_id = "";
            this.Get_departments_by_company(value);
            this.Get_office_shift_by_company(value);
        },

        Selected_Department(value) {
            if (value === null) {
                this.employee.department_id = "";
                this.employee.designation_id = "";
            }
            this.designations = [];
            this.employee.designation_id = "";
            this.Get_designations_by_department(value);
        },


        Selected_Designation(value) {
            if (value === null) {
                this.employee.designation_id = "";
            }
        },

        Selected_Role(value) {
                if (value === null) {
                    this.employee.role_users_id = "";
                }
            },

        Selected_Gender(value) {
            if (value === null) {
                this.employee.gender = "";
            }
        },

        Selected_Employment_type_Employee(value) {
            if (value === null) {
                this.employee.employment_type = "";
            }
        },

        Selected_Family_status(value) {
            if (value === null) {
                this.employee.marital_status = "";
            }
        },


        Selected_Office_shift(value) {
            if (value === null) {
                this.employee.office_shift_id = "";
            }
        },



        //---------------------- Get_departments_by_company ------------------------------\\
        Get_departments_by_company(value) {
        axios
            .get("/core/Get_departments_by_company?id=" + value)
            .then(({ data }) => (this.departments = data));
        },

        //---------------------- Get designations by department ------------------------------\\
        Get_designations_by_department(value) {
        axios
            .get("/core/get_designations_by_department?id=" + value)
            .then(({ data }) => (this.designations = data));
        },

         //---------------------- Get_office_shift_by_company ------------------------------\\
         Get_office_shift_by_company(value) {
        axios
            .get("/Get_office_shift_by_company?id=" + value)
            .then(({ data }) => (this.office_shifts = data));
        },

        //------------------------ Update Employee ---------------------------\\
        Update_Employee_Basic() {
            var self = this;
            self.SubmitProcessing = true;
            axios.put("/employees/" + self.employee.id, {
                firstname: self.employee.firstname,
                lastname: self.employee.lastname,
                country: self.employee.country,
                email: self.employee.email,
                gender: self.employee.gender,
                phone: self.employee.phone,
                birth_date: self.employee.birth_date,
                company_id: self.employee.company_id,
                department_id: self.employee.department_id,
                designation_id: self.employee.designation_id,
                office_shift_id: self.employee.office_shift_id,
                joining_date: self.employee.joining_date,
                leaving_date: self.employee.leaving_date,
                marital_status: self.employee.marital_status,
                employment_type: self.employee.employment_type,
                city: self.employee.city,
                province: self.employee.province,
                address: self.employee.address,
                zipcode: self.employee.zipcode,
                hourly_rate: self.employee.hourly_rate,
                basic_salary: self.employee.basic_salary,
                role_users_id: self.employee.role_users_id,
                total_leave: self.employee.total_leave,
            }).then(response => {
                    self.SubmitProcessing = false;
                    window.location.href = '/employees';
                    toastr.success('Employee Updated in successfully');
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


        //------------------------ Nav Document ---------------------------------------------------------------------------------------------\\

            New_Document() {
                this.reset_Form_Document();
                this.edit_mode_document = false;
                $('#document_Modal').modal('show');
            },

            //------------------------------ Show Modal (Edit document) -------------------------------\\
            Edit_Document(document) {
                this.edit_mode_document = true;
                this.reset_Form_Document();
                this.document = document;
                $('#document_Modal').modal('show');
            },

              //----------------------------- reset_Form_Document---------------------------\\
              reset_Form_Document() {
                this.document = {
                    id: "",
                    title: "",
                    attachment:"",
                    description:"",
                };
                this.errors_document = {};
            },



            change_Document(e){
                let file = e.target.files[0];
                this.document.attachment = file;
            },

              //----------------------- Update document---------------------------\\
              Create_document() {
                var self = this;
                self.Submit_Processing_document = true;

                if (self.document.attachment) {
                    self.data.append("attachment", self.document.attachment);
                }
                self.data.append("employee_id", self.employee.id);
                self.data.append("title", self.document.title);
                self.data.append("description", self.document.description);

                axios
                    .post("/employee_document", self.data)
                    .then(response => {
                        self.Submit_Processing_document = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors_document = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_document = false;
                        if (error.response.status == 422) {
                            self.errors_document = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

              //----------------------- Update document---------------------------\\
              Update_document(id) {
                var self = this;
                self.Submit_Processing_document = true;

                if (self.document.attachment) {
                    self.data.append("attachment", self.document.attachment);
                }
                self.data.append("employee_id", self.employee.id);
                self.data.append("title", self.document.title);
                self.data.append("description", self.document.description);
                self.data.append("_method", "put");

                axios
                    .post("/employee_document/" + self.document.id, self.data)
                    .then(response => {
                        self.Submit_Processing_document = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_document = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_document = false;
                        if (error.response.status == 422) {
                            self.errors_document = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },


               //--------------------------------- Remove_Document ---------------------------\\
               Remove_Document(id) {

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
                            .delete("/employee_document/" + id)
                            .then(() => {
                                location.reload();
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
                },

        //------------------------ Work Experience ---------------------------------------------------------------------------------------------\\


            //------------------------------ Show Modal (Create Experience) -------------------------------\\
            New_Experience() {
                this.reset_Form_experience();
                this.edit_mode_experience = false;
                $('#Experience_Modal').modal('show');
            },

            //------------------------------ Show Modal (Edit Experience) -------------------------------\\
            Edit_Experience(experience) {
                this.edit_mode_experience = true;
                this.reset_Form_experience();
                this.experience = experience;
                $('#Experience_Modal').modal('show');
            },

            Selected_Employment_type (value) {
                if (value === null) {
                    this.experience.employment_type = "";
                }
            },

              //----------------------------- Reset_Form_experience---------------------------\\
              reset_Form_experience() {
                this.experience = {
                    id: "",
                    title: "",
                    company_name:"",
                    employment_type:"",
                    location:"",
                    start_date:"",
                    end_date:"",
                    description:"",
                };
                this.errors_experience = {};
            },

            //------------------------ Create Experience ---------------------------\\
            Create_Experience() {
                var self = this;
                self.Submit_Processing_Experience = true;
                axios.post("/work_experience", {
                    title: self.experience.title,
                    company_name: self.experience.company_name,
                    employee_id: self.employee.id,
                    location: self.experience.location,
                    employment_type: self.experience.employment_type,
                    start_date: self.experience.start_date,
                    end_date: self.experience.end_date,
                    description: self.experience.description,
                }).then(response => {
                        self.Submit_Processing_Experience = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors_experience = {};
                })
                .catch(error => {
                    self.Submit_Processing_Experience = false;
                    if (error.response.status == 422) {
                        self.errors_experience = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },

           //----------------------- Update Experience ---------------------------\\
            Update_Experience() {
                var self = this;
                self.Submit_Processing_Experience = true;
                axios.put("/work_experience/" + self.experience.id, {
                    title: self.experience.title,
                    company_name: self.experience.company_name,
                    employee_id: self.employee.id,
                    location: self.experience.location,
                    employment_type: self.experience.employment_type,
                    start_date: self.experience.start_date,
                    end_date: self.experience.end_date,
                    description: self.experience.description,
                }).then(response => {
                        self.Submit_Processing_Experience = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_experience = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_Experience = false;
                        if (error.response.status == 422) {
                            self.errors_experience = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

             //--------------------------------- Remove Experience ---------------------------\\
            Remove_Experience(id) {

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
                            .delete("/work_experience/" + id)
                            .then(() => {
                                location.reload();
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
                },


//------------------------ ---------------------Social Profile ----------------------------------------------\\

            //------------------------ Update Social Profile ---------------------------\\
            Update_Employee_social() {
                var self = this;
                self.Submit_Processing_social = true;
                axios.put("/update_social_profile/" + self.employee.id, {
                    facebook: self.employee.facebook,
                    skype: self.employee.skype,
                    whatsapp: self.employee.whatsapp,
                    twitter: self.employee.twitter,
                    linkedin: self.employee.linkedin,

                }).then(response => {
                        self.Submit_Processing_social = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_social = {};
                })
                .catch(error => {
                    self.Submit_Processing_social = false;
                    if (error.response.status == 422) {
                        self.errors_social = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },



//--------------------------------------------- Bank Account -----------------------------------------------------------\\


            //------------------------------ Show Modal (Create Bank Account) -------------------------------\\
            New_Account() {
                this.reset_Form_bank_account();
                this.edit_mode_account = false;
                $('#Account_Modal').modal('show');
            },

            //------------------------------ Show Modal (Edit Bank Account) -------------------------------\\
            Edit_Account(account_bank) {
                this.edit_mode_account = true;
                this.reset_Form_bank_account();
                this.account_bank = account_bank;
                $('#Account_Modal').modal('show');
            },


              //----------------------------- Reset_Form_Bank Account---------------------------\\
              reset_Form_bank_account() {
                this.account_bank = {
                    id: "",
                    bank_name: "",
                    bank_branch:"",
                    account_no:"",
                    note:"",
                };
                this.errors_bank = {};
            },

            //------------------------ Create Bank Account ---------------------------\\
            Create_Account() {
                var self = this;
                self.Submit_Processing_Bank = true;
                axios.post("/employee_account", {
                    employee_id: self.employee.id,
                    bank_name: self.account_bank.bank_name,
                    bank_branch: self.account_bank.bank_branch,
                    account_no: self.account_bank.account_no,
                    note: self.account_bank.note,

                }).then(response => {
                        self.Submit_Processing_Bank = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors_bank = {};
                })
                .catch(error => {
                    self.Submit_Processing_Bank = false;
                    if (error.response.status == 422) {
                        self.errors_bank = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },

           //----------------------- Update Bank Account ---------------------------\\
            Update_Account() {
                var self = this;
                self.Submit_Processing_Bank = true;
                axios.put("/employee_account/" + self.account_bank.id, {
                    employee_id: self.employee.id,
                    bank_name: self.account_bank.bank_name,
                    bank_branch: self.account_bank.bank_branch,
                    account_no: self.account_bank.account_no,
                    note: self.account_bank.note,

                }).then(response => {
                        self.Submit_Processing_Bank = false;
                        window.location.href = '/employees/'+ self.employee.id;
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_bank = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_Bank = false;
                        if (error.response.status == 422) {
                            self.errors_bank = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

             //--------------------------------- Remove Bank Account ---------------------------\\
             Remove_Account(id) {

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
                        axios.delete("/employee_account/" + id)
                            .then(() => {
                                toastr.success('Deleted in successfully');
                                location.reload();

                            })
                            .catch(() => {
                                toastr.danger('There was something wronge');
                            });
                    });
                },



    },
    //-----------------------------Autoload function-------------------
    created () {

    },

})

</script>

<script type="text/javascript">
    $(function () {
      "use strict";

        $('.data_datatable').DataTable( {
            "processing": true, // for show progress bar
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
